<?php
// Create example reports for all types with proper snapshots

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/Database.php';

use App\Models\ReportModel;
use App\Models\PatientModel;
use App\Models\ConsultationModel;
use App\Models\InventoryModel;
use App\Models\AnnouncementModel;
use App\Models\AppointmentModel;
use App\Models\UserModel;
use App\Models\CertificateModel;

$reportModel = new ReportModel();
$patientModel = new PatientModel();
$consultModel = new ConsultationModel();
$inventoryModel = new InventoryModel();
$announcementModel = new AnnouncementModel();
$appointmentModel = new AppointmentModel();
$userModel = new UserModel();
$certModel = new CertificateModel();

// Helper: generate report data by type
function generateReportData($type) {
    $patientModel = new PatientModel();
    $consultModel = new ConsultationModel();
    $inventoryModel = new InventoryModel();
    $announcementModel = new AnnouncementModel();
    $appointmentModel = new AppointmentModel();
    $userModel = new UserModel();
    $certModel = new CertificateModel();

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

    switch ($type) {
        case 'patient':
            $data['patients'] = $patientModel->findAll();
            $data['total_patients'] = count($data['patients']);
            break;

        case 'consultation':
            $data['consultations'] = $consultModel->findAll();
            $data['total_consultations'] = count($data['consultations']);
            break;

        case 'appointment':
            $data['appointments'] = $appointmentModel->findAll();
            $data['total_appointments'] = count($data['appointments']);
            $data['pending'] = $appointmentModel->where('status', 'pending')->findAll();
            $data['approved'] = $appointmentModel->where('status', 'approved')->findAll();
            $data['completed'] = $appointmentModel->where('status', 'completed')->findAll();
            break;

        case 'inventory':
            $data['inventory'] = $inventoryModel->findAll();
            $data['total_items'] = count($data['inventory']);
            $data['low_stock'] = $inventoryModel->where('quantity <', 5)->findAll();
            break;

        case 'announcement':
            $data['announcements'] = $announcementModel->findAll();
            $data['total_announcements'] = count($data['announcements']);
            break;

        case 'comprehensive':
        default:
            $data['patients'] = $patientModel->findAll();
            $data['consultations'] = $consultModel->findAll();
            $data['inventory'] = $inventoryModel->findAll();
            $data['announcements'] = $announcementModel->findAll();
            $data['appointments'] = $appointmentModel->findAll();
            $data['users'] = $userModel->findAll();
            $data['certificates'] = $certModel->findAll();
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

// Delete old example reports to avoid clutter
echo "Clearing old example reports...\n";
$reportModel->whereIn('report_id', [28, 29, 30, 31, 32, 33])->delete();

// Create fresh reports for each type
$types = ['patient', 'consultation', 'appointment', 'inventory', 'announcement', 'comprehensive'];
$createdIds = [];

foreach ($types as $type) {
    $snapshot = generateReportData($type);
    
    $insertData = [
        'generated_by' => 1,
        'report_type' => $type,
        'report_data' => json_encode($snapshot),
        'file_path' => null,
    ];

    $reportModel->insert($insertData);
    $newId = $reportModel->getInsertID();
    $createdIds[] = $newId;
    echo "Created $type report (ID: $newId)\n";
}

echo "\nDone. Created reports: " . implode(', ', $createdIds) . "\n";
