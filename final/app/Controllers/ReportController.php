<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReportModel;
use App\Models\PatientModel;
use App\Models\ConsultationModel;
use App\Models\InventoryModel;
use App\Models\AnnouncementModel;
use App\Models\AppointmentModel;
use App\Models\UserModel;
use App\Models\CertificateModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

class ReportController extends BaseController
{
    public function report()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $report = new ReportModel();
        $data['report'] = $report->findAll();

        return view('Report/report', $data);
    }

    public function store_report()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $reportModel = new ReportModel();
        $report_type = $this->request->getPost('report_type') ?: 'comprehensive';

        // Generate the snapshot for the selected type and persist it
        $report_data = $this->generateReportData($report_type);

        $data = [
            'generated_by' => session()->get('user_id') ?: null,
            'report_type' => $report_type,
            'report_data' => json_encode($report_data),
            'file_path' => $this->request->getPost('file_path') ?: null,
        ];

        $reportModel->insert($data);

        return redirect()->to('/report')->with('message', 'Report Generated Successfully');
    }

    public function view_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $reportModel = new ReportModel();
        $rep = $reportModel->find($id);
        if (!$rep) {
            return redirect()->to('/report')->with('message', 'Error: Report not found');
        }

        $data['rep'] = $rep;
        $storedType = strtolower(trim((string) ($rep['report_type'] ?? '')));

        if (empty($storedType)) {
            $data['report_data'] = [];
        } else {
            $decoded = json_decode($rep['report_data'] ?? '', true);
            if (empty($decoded) || !is_array($decoded)) {
                $generated = $this->generateReportData($storedType);
                $data['report_data'] = $generated;
                try {
                    $reportModel->update($id, ['report_data' => json_encode($generated)]);
                } catch (\Exception $e) {
                    log_message('error', 'Failed to persist generated report_data for report #' . $id . ': ' . $e->getMessage());
                }
            } else {
                $data['report_data'] = $decoded;
            }
        }

        return view('Report/view_report', $data);
    }

    public function set_report_type($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $reportModel = new ReportModel();
        $exist = $reportModel->find($id);
        if (!$exist) {
            return redirect()->to('/report')->with('message', 'Error: Report not found');
        }

        $allowed = ['patient', 'consultation', 'appointment', 'inventory', 'announcement', 'comprehensive'];
        $type = $this->request->getPost('report_type');
        if (empty($type) || !in_array($type, $allowed, true)) {
            return redirect()->to('/report/view/' . $id)->with('message', 'Invalid report type selected');
        }

        $reportModel->update($id, ['report_type' => $type]);

        return redirect()->to('/report/view/' . $id)->with('message', 'Report type updated');
    }

    public function delete_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $reportModel = new ReportModel();
        $exist = $reportModel->find($id);
        if (!$exist) {
            return redirect()->to('/report')->with('message', 'Error: Report not found');
        }

        $reportModel->delete($id);

        return redirect()->to('/report')->with('message', 'Report #' . $id . ' Deleted Successfully');
    }

    public function export_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $reportModel = new ReportModel();
        $rep = $reportModel->find($id);
        if (!$rep) {
            return redirect()->to('/report')->with('message', 'Report not found');
        }

        $format = strtolower($this->request->getGet('format') ?? 'csv');
        $report_data = json_decode($rep['report_data'] ?? '[]', true) ?: [];

        if ($format === 'csv') {
            $csv_content = $this->generateCSV($rep, $report_data);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="report_' . $rep['report_id'] . '_' . date('Y-m-d-H-i-s') . '.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');

            echo $csv_content;
            exit;
        }

        if ($format === 'xlsx') {
            // Fallback: convert CSV rows into an XLSX sheet
            $csv_content = $this->generateCSV($rep, $report_data);
            $lines = explode("\n", trim($csv_content));

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $rowNum = 1;
            foreach ($lines as $line) {
                // naive split on comma; report CSV may contain quoted commas but this is a pragmatic fallback
                $cols = str_getcsv($line);
                // Use fromArray to write the row starting at column A for simplicity
                $sheet->fromArray($cols, null, 'A' . $rowNum);
                $rowNum++;
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'report_' . $rep['report_id'] . '_' . date('Y-m-d-H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
        }

        if ($format === 'pdf') {
            $html = $this->generatePdfHtml($rep, $report_data);

            $mpdf = new Mpdf(['mode' => 'utf-8']);
            $mpdf->WriteHTML($html);
            $filename = 'report_' . $rep['report_id'] . '_' . date('Y-m-d-H-i-s') . '.pdf';
            $mpdf->Output($filename, 'D');
            exit;
        }

        return redirect()->to('/report/view/' . $id)->with('message', 'Unsupported export format');
    }

    private function generateReportData(string $report_type): array
    {
        $patient = new PatientModel();
        $consult = new ConsultationModel();
        $inventory = new InventoryModel();
        $announce = new AnnouncementModel();
        $appoint = new AppointmentModel();
        $user = new UserModel();
        $cert = new CertificateModel();

        $data = [
            'patients' => [],
            'consultations' => [],
            'appointments' => [],
            'inventory' => [],
            'announcements' => [],
            'users' => [],
            'certificates' => [],
            'pending' => [],
            'approved' => [],
            'completed' => [],
            'low_stock' => [],
            'summary' => [],
            'total_patients' => 0,
            'total_consultations' => 0,
            'total_appointments' => 0,
            'total_items' => 0,
            'total_announcements' => 0,
        ];

        switch ($report_type) {
            case 'patient':
                $data['patients'] = $patient->findAll();
                $data['total_patients'] = count($data['patients']);
                break;

            case 'consultation':
                $data['consultations'] = $consult->findAll();
                $data['total_consultations'] = count($data['consultations']);
                break;

            case 'inventory':
                $data['inventory'] = $inventory->findAll();
                $data['total_items'] = count($data['inventory']);
                $data['low_stock'] = $inventory->where('quantity <', 5)->findAll();
                break;

            case 'announcement':
                $data['announcements'] = $announce->findAll();
                $data['total_announcements'] = count($data['announcements']);
                break;

            case 'appointment':
                $data['appointments'] = $appoint->findAll();
                $data['total_appointments'] = count($data['appointments']);
                $data['pending'] = $appoint->where('status', 'pending')->findAll();
                $data['approved'] = $appoint->where('status', 'approved')->findAll();
                $data['completed'] = $appoint->where('status', 'completed')->findAll();
                break;

            case 'comprehensive':
            default:
                $data['patients'] = $patient->findAll();
                $data['consultations'] = $consult->findAll();
                $data['inventory'] = $inventory->findAll();
                $data['announcements'] = $announce->findAll();
                $data['appointments'] = $appoint->findAll();
                $data['users'] = $user->findAll();
                $data['certificates'] = $cert->findAll();
                $data['summary'] = [
                    'total_patients' => count($data['patients']),
                    'total_consultations' => count($data['consultations']),
                    'total_inventory_items' => count($data['inventory']),
                    'total_announcements' => count($data['announcements']),
                    'total_appointments' => count($data['appointments']),
                    'total_users' => count($data['users']),
                    'total_certificates' => count($data['certificates']),
                ];
                break;
        }

        return $data;
    }

    private function generateCSV(array $rep, array $report_data): string
    {
        $csv = "Report ID," . ($rep['report_id'] ?? '') . "\n";
        $csv .= "Report Type," . ($rep['report_type'] ?? '') . "\n";
        $csv .= "Generated By," . ($rep['generated_by'] ?? '') . "\n";
        $csv .= "Generated Date," . ($rep['generated_at'] ?? '') . "\n\n";

        switch (($rep['report_type'] ?? 'comprehensive')) {
            case 'patient':
                $csv .= "PATIENT REPORT\n";
                $csv .= "Total Patients," . ($report_data['total_patients'] ?? 0) . "\n\n";
                $csv .= "Patient ID,Full Name,Gender,Blood Type,Contact,Date Added\n";
                foreach ($report_data['patients'] ?? [] as $p) {
                    $csv .= ($p['patient_id'] ?? '') . "," . trim((($p['last_name'] ?? '') . ' ' . ($p['first_name'] ?? '') . ' ' . ($p['middle_name'] ?? ''))) . "," . ($p['gender'] ?? '') . "," . ($p['blood_type'] ?? 'N/A') . "," . ($p['contact_no'] ?? 'N/A') . "," . ($p['created_at'] ?? '') . "\n";
                }
                break;

            case 'consultation':
                $csv .= "CONSULTATION REPORT\n";
                $csv .= "Total Consultations," . ($report_data['total_consultations'] ?? 0) . "\n\n";
                $csv .= "Consultation ID,Patient ID,Diagnosis,Treatment,Date\n";
                foreach ($report_data['consultations'] ?? [] as $c) {
                    $csv .= ($c['consultation_id'] ?? '') . "," . ($c['patient_id'] ?? '') . ",\"" . str_replace('"', '""', ($c['diagnosis'] ?? '')) . "\",\"" . str_replace('"', '""', ($c['treatment'] ?? '')) . "\"," . ($c['consultation_date'] ?? '') . "\n";
                }
                break;

            case 'appointment':
                $csv .= "APPOINTMENT REPORT\n";
                $csv .= "Total Appointments," . ($report_data['total_appointments'] ?? 0) . "\n";
                $csv .= "Pending," . count($report_data['pending'] ?? []) . "\n";
                $csv .= "Approved," . count($report_data['approved'] ?? []) . "\n";
                $csv .= "Completed," . count($report_data['completed'] ?? []) . "\n\n";
                $csv .= "Appointment ID,Patient ID,Date,Purpose,Status\n";
                foreach ($report_data['appointments'] ?? [] as $a) {
                    $csv .= ($a['appointment_id'] ?? '') . "," . ($a['patient_id'] ?? '') . "," . ($a['appointment_date'] ?? '') . ",\"" . str_replace('"', '""', ($a['purpose'] ?? '')) . "\"," . ($a['status'] ?? '') . "\n";
                }
                break;

            case 'inventory':
                $csv .= "INVENTORY REPORT\n";
                $csv .= "Total Items," . ($report_data['total_items'] ?? 0) . "\n";
                $csv .= "Low Stock Items," . count($report_data['low_stock'] ?? []) . "\n\n";
                $csv .= "Item ID,Item Name,Category,Quantity,Unit,Expiry Date\n";
                foreach ($report_data['inventory'] ?? [] as $i) {
                    $csv .= ($i['item_id'] ?? '') . "," . str_replace('"', '""', ($i['item_name'] ?? '')) . "," . ($i['category'] ?? '') . "," . ($i['quantity'] ?? '') . "," . ($i['unit'] ?? 'N/A') . "," . ($i['expiry_date'] ?? 'N/A') . "\n";
                }
                break;

            case 'announcement':
                $csv .= "ANNOUNCEMENT REPORT\n";
                $csv .= "Total Announcements," . ($report_data['total_announcements'] ?? 0) . "\n\n";
                $csv .= "Announcement ID,Title,Posted By,Posted Date,Posted Until\n";
                foreach ($report_data['announcements'] ?? [] as $an) {
                    $csv .= ($an['announcement_id'] ?? '') . ",\"" . str_replace('"', '""', ($an['title'] ?? '')) . "\"," . ($an['posted_by'] ?? '') . "," . ($an['posted_at'] ?? '') . "," . ($an['posted_until'] ?? '') . "\n";
                }
                break;

            case 'comprehensive':
            default:
                $csv .= "COMPREHENSIVE REPORT\n";
                $csv .= "Total Patients," . ($report_data['summary']['total_patients'] ?? 0) . "\n";
                $csv .= "Total Consultations," . ($report_data['summary']['total_consultations'] ?? 0) . "\n";
                $csv .= "Total Appointments," . ($report_data['summary']['total_appointments'] ?? 0) . "\n";
                $csv .= "Total Inventory Items," . ($report_data['summary']['total_inventory_items'] ?? 0) . "\n";
                $csv .= "Total Announcements," . ($report_data['summary']['total_announcements'] ?? 0) . "\n";
                $csv .= "Total Users," . ($report_data['summary']['total_users'] ?? 0) . "\n";
                $csv .= "Total Certificates," . ($report_data['summary']['total_certificates'] ?? 0) . "\n\n";

                $csv .= "--- PATIENTS ---\n";
                $csv .= "Patient ID,Full Name,Gender,Contact,Created At\n";
                foreach ($report_data['patients'] ?? [] as $p) {
                    $csv .= ($p['patient_id'] ?? '') . ",\"" . str_replace('"', '""', trim((($p['last_name'] ?? '') . ' ' . ($p['first_name'] ?? '')))) . "\"," . ($p['gender'] ?? '') . "," . ($p['contact_no'] ?? 'N/A') . "," . ($p['created_at'] ?? '') . "\n";
                }

                $csv .= "\n--- CONSULTATIONS ---\n";
                $csv .= "Consultation ID,Appointment ID,Patient ID,Diagnosis,Treatment,Date\n";
                foreach ($report_data['consultations'] ?? [] as $c) {
                    $csv .= ($c['consultation_id'] ?? '') . "," . ($c['appointment_id'] ?? '') . "," . ($c['patient_id'] ?? '') . ",\"" . str_replace('"', '""', ($c['diagnosis'] ?? '')) . "\",\"" . str_replace('"', '""', ($c['treatment'] ?? '')) . "\"," . ($c['consultation_date'] ?? '') . "\n";
                }

                $csv .= "\n--- APPOINTMENTS ---\n";
                $csv .= "Appointment ID,Patient ID,Date,Purpose,Status,Remarks\n";
                foreach ($report_data['appointments'] ?? [] as $a) {
                    $csv .= ($a['appointment_id'] ?? '') . "," . ($a['patient_id'] ?? '') . "," . ($a['appointment_date'] ?? '') . ",\"" . str_replace('"', '""', ($a['purpose'] ?? '')) . "\"," . ($a['status'] ?? '') . ",\"" . str_replace('"', '""', ($a['remarks'] ?? '')) . "\"\n";
                }

                $csv .= "\n--- INVENTORY ---\n";
                $csv .= "Item ID,Item Name,Category,Quantity,Unit,Expiry Date,Description\n";
                foreach ($report_data['inventory'] ?? [] as $i) {
                    $csv .= ($i['item_id'] ?? '') . ",\"" . str_replace('"', '""', ($i['item_name'] ?? '')) . "\"," . ($i['category'] ?? '') . "," . ($i['quantity'] ?? '') . ",\"" . ($i['unit'] ?? 'N/A') . "\"," . ($i['expiry_date'] ?? 'N/A') . ",\"" . str_replace('"', '""', ($i['description'] ?? '')) . "\"\n";
                }

                $csv .= "\n--- ANNOUNCEMENTS ---\n";
                $csv .= "Announcement ID,Title,Posted By,Posted At,Posted Until,URL\n";
                foreach ($report_data['announcements'] ?? [] as $an) {
                    $csv .= ($an['announcement_id'] ?? '') . ",\"" . str_replace('"', '""', ($an['title'] ?? '')) . "\"," . ($an['posted_by'] ?? '') . "," . ($an['posted_at'] ?? '') . "," . ($an['posted_until'] ?? '') . ",\"" . ($an['url'] ?? '') . "\"\n";
                }

                $csv .= "\n--- USERS ---\n";
                $csv .= "User ID,Username,Email,Role,Created At\n";
                foreach ($report_data['users'] ?? [] as $u) {
                    $csv .= ($u['user_id'] ?? '') . ",\"" . str_replace('"', '""', ($u['username'] ?? '')) . "\",\"" . ($u['email'] ?? '') . "\"," . ($u['role'] ?? '') . "," . ($u['created_at'] ?? '') . "\n";
                }

                $csv .= "\n--- CERTIFICATES ---\n";
                $csv .= "Certificate ID,Consultation ID,Patient ID,Type,Issued By,Issued Date,Validity Start,Validity End,File Path\n";
                foreach ($report_data['certificates'] ?? [] as $cft) {
                    $csv .= ($cft['certificate_id'] ?? '') . "," . ($cft['consultation_id'] ?? '') . "," . ($cft['patient_id'] ?? '') . "," . ($cft['certificate_type'] ?? '') . "," . ($cft['issued_by'] ?? '') . "," . ($cft['issued_date'] ?? '') . "," . ($cft['validity_start'] ?? '') . "," . ($cft['validity_end'] ?? '') . ",\"" . ($cft['file_path'] ?? '') . "\"\n";
                }
                break;
        }

        return $csv;
    }

    private function generatePdfHtml(array $rep, array $report_data): string
    {
        $type = $rep['report_type'] ?? 'comprehensive';
        $timestamp = $rep['generated_at'] ?? date('Y-m-d H:i:s');
        
        $html = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        .header {
            border-bottom: 3px solid #0066cc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0066cc;
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #0066cc;
            color: white;
            padding: 8px 12px;
            margin: 15px 0 10px 0;
            font-size: 12px;
            font-weight: bold;
            border-radius: 3px;
        }
        .section-subtitle {
            color: #0066cc;
            margin: 12px 0 8px 0;
            font-weight: bold;
            font-size: 11px;
        }
        .summary {
            background-color: #f0f5ff;
            padding: 12px;
            margin: 10px 0;
            border-left: 4px solid #0066cc;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
        }
        th {
            background-color: #0066cc;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #004a99;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f0f5ff;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #999;
            text-align: right;
        }
    </style>
</head>
<body>
HTML;

        $html .= '<div class="header">';
        $html .= '<h1>📊 ' . htmlspecialchars(ucfirst($type)) . ' Report</h1>';
        $html .= '<p><strong>Report ID:</strong> ' . htmlspecialchars($rep['report_id']) . '</p>';
        $html .= '<p><strong>Generated:</strong> ' . htmlspecialchars($timestamp) . '</p>';
        $html .= '<p><strong>Generated By:</strong> User ID ' . htmlspecialchars($rep['generated_by'] ?? 'N/A') . '</p>';
        $html .= '</div>';

        // Render content based on report type
        switch ($type) {
            case 'patient':
                $html .= $this->renderPatientPdf($report_data);
                break;
            case 'consultation':
                $html .= $this->renderConsultationPdf($report_data);
                break;
            case 'appointment':
                $html .= $this->renderAppointmentPdf($report_data);
                break;
            case 'inventory':
                $html .= $this->renderInventoryPdf($report_data);
                break;
            case 'announcement':
                $html .= $this->renderAnnouncementPdf($report_data);
                break;
            case 'comprehensive':
            default:
                $html .= $this->renderComprehensivePdf($report_data);
                break;
        }

        $html .= '<div class="footer">';
        $html .= '<p>Generated on ' . date('Y-m-d H:i:s') . ' by Clinic Management System</p>';
        $html .= '</div>';
        $html .= '</body></html>';

        return $html;
    }

    private function renderPatientPdf(array $data): string
    {
        $html = '<div class="section">';
        $html .= '<div class="section-title">👥 Patient Summary</div>';
        $html .= '<div class="summary">Total Patients: <strong>' . ($data['total_patients'] ?? 0) . '</strong></div>';
        
        if (!empty($data['patients'])) {
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Full Name</th><th>Gender</th><th>Blood Type</th><th>Contact</th><th>Date Added</th></tr>';
            foreach ($data['patients'] as $p) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($p['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) . '</td>';
                $html .= '<td>' . htmlspecialchars($p['gender'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($p['blood_type'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($p['contact_no'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($p['created_at'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No patient data found.</p>';
        }
        $html .= '</div>';
        return $html;
    }

    private function renderConsultationPdf(array $data): string
    {
        $html = '<div class="section">';
        $html .= '<div class="section-title">🏥 Consultation Summary</div>';
        $html .= '<div class="summary">Total Consultations: <strong>' . ($data['total_consultations'] ?? 0) . '</strong></div>';
        
        if (!empty($data['consultations'])) {
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Patient ID</th><th>Diagnosis</th><th>Treatment</th><th>Date</th></tr>';
            foreach ($data['consultations'] as $c) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($c['consultation_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($c['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($c['diagnosis'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($c['treatment'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($c['consultation_date'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No consultation data found.</p>';
        }
        $html .= '</div>';
        return $html;
    }

    private function renderAppointmentPdf(array $data): string
    {
        $html = '<div class="section">';
        $html .= '<div class="section-title">📅 Appointment Summary</div>';
        $html .= '<div class="summary">';
        $html .= 'Total Appointments: <strong>' . ($data['total_appointments'] ?? 0) . '</strong> | ';
        $html .= 'Pending: <strong>' . count($data['pending'] ?? []) . '</strong> | ';
        $html .= 'Approved: <strong>' . count($data['approved'] ?? []) . '</strong> | ';
        $html .= 'Completed: <strong>' . count($data['completed'] ?? []) . '</strong>';
        $html .= '</div>';
        
        if (!empty($data['appointments'])) {
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Patient ID</th><th>Date</th><th>Purpose</th><th>Status</th></tr>';
            foreach ($data['appointments'] as $a) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($a['appointment_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['appointment_date'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($a['purpose'] ?? 'N/A') . '</td>';
                $html .= '<td><strong>' . htmlspecialchars(ucfirst($a['status'] ?? '')) . '</strong></td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No appointment data found.</p>';
        }
        $html .= '</div>';
        return $html;
    }

    private function renderInventoryPdf(array $data): string
    {
        $html = '<div class="section">';
        $html .= '<div class="section-title">📦 Inventory Summary</div>';
        $html .= '<div class="summary">';
        $html .= 'Total Items: <strong>' . ($data['total_items'] ?? 0) . '</strong> | ';
        $html .= 'Low Stock (&lt;5): <strong>' . count($data['low_stock'] ?? []) . '</strong>';
        $html .= '</div>';
        
        if (!empty($data['inventory'])) {
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Item Name</th><th>Category</th><th>Quantity</th><th>Unit</th><th>Expiry Date</th></tr>';
            foreach ($data['inventory'] as $i) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($i['item_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($i['item_name'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($i['category'] ?? '')) . '</td>';
                $html .= '<td>' . htmlspecialchars($i['quantity'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($i['unit'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($i['expiry_date'] ?? 'N/A') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No inventory data found.</p>';
        }
        $html .= '</div>';
        return $html;
    }

    private function renderAnnouncementPdf(array $data): string
    {
        $html = '<div class="section">';
        $html .= '<div class="section-title">📢 Announcement Summary</div>';
        $html .= '<div class="summary">Total Announcements: <strong>' . ($data['total_announcements'] ?? 0) . '</strong></div>';
        
        if (!empty($data['announcements'])) {
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Title</th><th>Posted By</th><th>Posted Date</th><th>Valid Until</th></tr>';
            foreach ($data['announcements'] as $an) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($an['announcement_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($an['title'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($an['posted_by'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($an['posted_at'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($an['posted_until'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        } else {
            $html .= '<p>No announcement data found.</p>';
        }
        $html .= '</div>';
        return $html;
    }

    private function renderComprehensivePdf(array $data): string
    {
        $html = '';
        
        // Summary section
        if (!empty($data['summary'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">📊 Overall Summary</div>';
            $html .= '<div class="summary">';
            $html .= 'Patients: <strong>' . ($data['summary']['total_patients'] ?? 0) . '</strong> | ';
            $html .= 'Consultations: <strong>' . ($data['summary']['total_consultations'] ?? 0) . '</strong> | ';
            $html .= 'Appointments: <strong>' . ($data['summary']['total_appointments'] ?? 0) . '</strong> | ';
            $html .= 'Inventory Items: <strong>' . ($data['summary']['total_inventory_items'] ?? 0) . '</strong> | ';
            $html .= 'Announcements: <strong>' . ($data['summary']['total_announcements'] ?? 0) . '</strong> | ';
            $html .= 'Users: <strong>' . ($data['summary']['total_users'] ?? 0) . '</strong>';
            $html .= '</div>';
            $html .= '</div>';
        }

        // Patients section
        if (!empty($data['patients'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">👥 Patients</div>';
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Full Name</th><th>Gender</th><th>Contact</th></tr>';
            foreach ($data['patients'] as $p) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($p['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars(($p['last_name'] ?? '') . ', ' . ($p['first_name'] ?? '')) . '</td>';
                $html .= '<td>' . htmlspecialchars($p['gender'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($p['contact_no'] ?? 'N/A') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }

        // Consultations section
        if (!empty($data['consultations'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">🏥 Consultations</div>';
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Patient ID</th><th>Diagnosis</th><th>Treatment</th><th>Date</th></tr>';
            foreach ($data['consultations'] as $c) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($c['consultation_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($c['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($c['diagnosis'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($c['treatment'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($c['consultation_date'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }

        // Appointments section
        if (!empty($data['appointments'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">📅 Appointments</div>';
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Patient ID</th><th>Date</th><th>Purpose</th><th>Status</th></tr>';
            foreach ($data['appointments'] as $a) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($a['appointment_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['patient_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($a['appointment_date'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($a['purpose'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($a['status'] ?? '')) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }

        // Inventory section
        if (!empty($data['inventory'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">📦 Inventory</div>';
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Item Name</th><th>Category</th><th>Quantity</th><th>Unit</th><th>Expiry Date</th></tr>';
            foreach ($data['inventory'] as $i) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($i['item_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($i['item_name'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars(ucfirst($i['category'] ?? '')) . '</td>';
                $html .= '<td>' . htmlspecialchars($i['quantity'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($i['unit'] ?? 'N/A') . '</td>';
                $html .= '<td>' . htmlspecialchars($i['expiry_date'] ?? 'N/A') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }

        // Announcements section
        if (!empty($data['announcements'])) {
            $html .= '<div class="section">';
            $html .= '<div class="section-title">📢 Announcements</div>';
            $html .= '<table>';
            $html .= '<tr><th>ID</th><th>Title</th><th>Posted By</th><th>Posted Date</th></tr>';
            foreach ($data['announcements'] as $an) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($an['announcement_id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($an['title'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($an['posted_by'] ?? '') . '</td>';
                $html .= '<td>' . htmlspecialchars($an['posted_at'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            $html .= '</div>';
        }

        return $html;
    }
}
