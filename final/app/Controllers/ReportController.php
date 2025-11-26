<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ReportModel;
use App\Models\PatientModel;
use App\Models\ConsultationModel;
use App\Models\InventoryModel;
use App\Models\AnnouncementModel;
use App\Models\AppointmentModel;

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

    public function generate_report()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        return view('Report/generate_report');
    }

    public function store_report()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $report = new ReportModel();
        $report_type = request()->getPost('report_type');

        if(empty($report_type)){
            return redirect()->to('/report/generate')->with('message', 'Error: Please select a report type');
        }

        // Aggregate data based on report type
        $report_data = $this->generateReportData($report_type);

        if(!session()->get('user_id')){
            return redirect()->to('/report/generate')->with('message', 'Error: User not logged in');
        }

        $data = [
            'generated_by' => session()->get('user_id'),
            'report_type' => $report_type,
            'file_path' => request()->getPost('file_path') ?: null,
        ];

        $report->insert($data);

        return redirect()->to('/report')->with('message', 'Report Generated Successfully');
    }

    private function generateReportData($report_type)
    {
        $patient = new PatientModel();
        $consult = new ConsultationModel();
        $inventory = new InventoryModel();
        $announce = new AnnouncementModel();
        $appoint = new AppointmentModel();

        $data = [];

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
                $data['patients'] = $patient->findAll();
                $data['consultations'] = $consult->findAll();
                $data['inventory'] = $inventory->findAll();
                $data['announcements'] = $announce->findAll();
                $data['appointments'] = $appoint->findAll();
                $data['summary'] = [
                    'total_patients' => count($data['patients']),
                    'total_consultations' => count($data['consultations']),
                    'total_inventory_items' => count($data['inventory']),
                    'total_announcements' => count($data['announcements']),
                    'total_appointments' => count($data['appointments']),
                ];
                break;

            default:
                // Default to all data if report_type is not recognized
                $data['patients'] = $patient->findAll();
                $data['consultations'] = $consult->findAll();
                $data['inventory'] = $inventory->findAll();
                $data['announcements'] = $announce->findAll();
                $data['appointments'] = $appoint->findAll();
                $data['total_patients'] = count($data['patients']);
                $data['total_consultations'] = count($data['consultations']);
                $data['total_items'] = count($data['inventory']);
                $data['total_announcements'] = count($data['announcements']);
                $data['total_appointments'] = count($data['appointments']);
                $data['pending'] = $appoint->where('status', 'pending')->findAll();
                $data['approved'] = $appoint->where('status', 'approved')->findAll();
                $data['completed'] = $appoint->where('status', 'completed')->findAll();
                $data['low_stock'] = $inventory->where('quantity <', 5)->findAll();
                break;
        }

        return $data;
    }

    public function view_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $report = new ReportModel();
        $rep = $report->find($id);

        if (!$rep) {
            return redirect()->to('/report')->with('message', 'Error: Report not found');
        }

        $data['rep'] = $rep;

        // Get the aggregated data based on report type
        $data['report_data'] = $this->generateReportData($rep['report_type']);

        return view('Report/view_report', $data);
    }

    public function delete_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $report = new ReportModel();
        
        $exist = $report->find($id);
        if(!$exist){
            return redirect()->to('/report')->with('message', 'Error: Report not found');
        }

        $report->delete($id);

        return redirect()->to('/report')->with('message', 'Report #' . $id . ' Deleted Successfully');
    }

    public function export_report($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $report = new ReportModel();
        $rep = $report->find($id);

        if (!$rep) {
            return redirect()->to('/report')->with('message', 'Report not found');
        }

        // Get report data
        $report_data = $this->generateReportData($rep['report_type']);

        // Generate CSV content
        $csv_content = $this->generateCSV($rep, $report_data);

        // Set headers for download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="report_' . $rep['report_id'] . '_' . date('Y-m-d-H-i-s') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Output CSV
        echo $csv_content;
        exit;
    }

    private function generateCSV($rep, $report_data)
    {
        $csv = "Report ID," . $rep['report_id'] . "\n";
        $csv .= "Report Type," . $rep['report_type'] . "\n";
        $csv .= "Generated By," . $rep['generated_by'] . "\n";
        $csv .= "Generated Date," . $rep['generated_at'] . "\n";
        $csv .= "\n\n";

        switch ($rep['report_type']) {
            case 'patient':
                $csv .= "PATIENT REPORT\n";
                $csv .= "Total Patients," . $report_data['total_patients'] . "\n\n";
                $csv .= "Patient ID,Full Name,Gender,Blood Type,Contact,Date Added\n";
                foreach ($report_data['patients'] as $p) {
                    $csv .= $p['patient_id'] . "," . $p['last_name'] . ' ' . $p['first_name'] . " " . $p['middle_name'] . "," . $p['gender'] . "," . ($p['blood_type'] ?: 'N/A') . "," . ($p['contact_no'] ?: 'N/A') . "," . $p['created_at'] . "\n";
                }
                break;

            case 'consultation':
                $csv .= "CONSULTATION REPORT\n";
                $csv .= "Total Consultations," . $report_data['total_consultations'] . "\n\n";
                $csv .= "Consultation ID,Patient ID,Diagnosis,Treatment,Date\n";
                foreach ($report_data['consultations'] as $c) {
                    $csv .= $c['consultation_id'] . "," . $c['patient_id'] . ",\"" . $c['diagnosis'] . "\",\"" . ($c['treatment'] ?: 'N/A') . "\"," . $c['consultation_date'] . "\n";
                }
                break;

            case 'appointment':
                $csv .= "APPOINTMENT REPORT\n";
                $csv .= "Total Appointments," . $report_data['total_appointments'] . "\n";
                $csv .= "Pending," . count($report_data['pending']) . "\n";
                $csv .= "Approved," . count($report_data['approved']) . "\n";
                $csv .= "Completed," . count($report_data['completed']) . "\n\n";
                $csv .= "Appointment ID,Patient ID,Date,Purpose,Status\n";
                foreach ($report_data['appointments'] as $a) {
                    $csv .= $a['appointment_id'] . "," . $a['patient_id'] . "," . $a['appointment_date'] . ",\"" . ($a['purpose'] ?: 'N/A') . "\"," . ucfirst($a['status']) . "\n";
                }
                break;

            case 'inventory':
                $csv .= "INVENTORY REPORT\n";
                $csv .= "Total Items," . $report_data['total_items'] . "\n";
                $csv .= "Low Stock Items," . count($report_data['low_stock']) . "\n\n";
                $csv .= "Item ID,Item Name,Category,Quantity,Unit,Expiry Date\n";
                foreach ($report_data['inventory'] as $i) {
                    $csv .= $i['item_id'] . "," . $i['item_name'] . "," . ucfirst($i['category']) . "," . $i['quantity'] . "," . ($i['unit'] ?: 'N/A') . "," . ($i['expiry_date'] ?: 'N/A') . "\n";
                }
                break;

            case 'announcement':
                $csv .= "ANNOUNCEMENT REPORT\n";
                $csv .= "Total Announcements," . $report_data['total_announcements'] . "\n\n";
                $csv .= "Announcement ID,Title,Posted By,Posted Date,Posted Until\n";
                foreach ($report_data['announcements'] as $an) {
                    $csv .= $an['announcement_id'] . ",\"" . $an['title'] . "\"," . $an['posted_by'] . "," . $an['posted_at'] . "," . $an['posted_until'] . "\n";
                }
                break;

            case 'comprehensive':
                $csv .= "COMPREHENSIVE REPORT\n";
                $csv .= "Total Patients," . $report_data['summary']['total_patients'] . "\n";
                $csv .= "Total Consultations," . $report_data['summary']['total_consultations'] . "\n";
                $csv .= "Total Appointments," . $report_data['summary']['total_appointments'] . "\n";
                $csv .= "Total Inventory Items," . $report_data['summary']['total_inventory_items'] . "\n";
                $csv .= "Total Announcements," . $report_data['summary']['total_announcements'] . "\n\n";

                $csv .= "--- PATIENTS ---\n";
                $csv .= "Patient ID,Full Name,Gender,Contact\n";
                foreach ($report_data['patients'] as $p) {
                    $csv .= $p['patient_id'] . "," . $p['last_name'] . ' ' . $p['first_name'] . "," . $p['gender'] . "," . ($p['contact_no'] ?: 'N/A') . "\n";
                }

                $csv .= "\n--- CONSULTATIONS (First 10) ---\n";
                $csv .= "Consultation ID,Patient ID,Diagnosis,Date\n";
                $count = 0;
                foreach ($report_data['consultations'] as $c) {
                    if ($count++ < 10) {
                        $csv .= $c['consultation_id'] . "," . $c['patient_id'] . ",\"" . substr($c['diagnosis'], 0, 100) . "\"," . $c['consultation_date'] . "\n";
                    }
                }

                $csv .= "\n--- APPOINTMENTS (First 10) ---\n";
                $csv .= "Appointment ID,Patient ID,Status,Date\n";
                $count = 0;
                foreach ($report_data['appointments'] as $a) {
                    if ($count++ < 10) {
                        $csv .= $a['appointment_id'] . "," . $a['patient_id'] . "," . ucfirst($a['status']) . "," . $a['appointment_date'] . "\n";
                    }
                }
                break;
        }

        return $csv;
    }
}
