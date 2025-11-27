<?php
/**
 * Direct DB script to create example reports for all types
 * Usage: php create_reports_direct.php
 */

// Load DB config directly
$config = [
    'database' => 'cspc_clinic',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbdriver' => 'MySQLi',
];

try {
    $db = mysqli_connect(
        $config['hostname'],
        $config['username'],
        $config['password'],
        $config['database']
    );

    if (!$db) {
        throw new Exception("DB connection failed: " . mysqli_connect_error());
    }

    mysqli_set_charset($db, "utf8mb4");

    // Helper: Get data from tables
    function getAllPatients($db) {
        $result = mysqli_query($db, "SELECT * FROM patients");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllConsultations($db) {
        $result = mysqli_query($db, "SELECT * FROM consultations");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllAppointments($db) {
        $result = mysqli_query($db, "SELECT * FROM appointments");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllInventory($db) {
        $result = mysqli_query($db, "SELECT * FROM inventory");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllAnnouncements($db) {
        $result = mysqli_query($db, "SELECT * FROM announcements");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllUsers($db) {
        $result = mysqli_query($db, "SELECT * FROM users");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getAllCertificates($db) {
        $result = mysqli_query($db, "SELECT * FROM certificates");
        if (!$result) {
            return []; // Table doesn't exist; return empty array
        }
        return mysqli_fetch_all($result, MYSQLI_ASSOC) ?: [];
    }

    function getPendingAppointments($db) {
        $result = mysqli_query($db, "SELECT * FROM appointments WHERE status = 'pending'");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getApprovedAppointments($db) {
        $result = mysqli_query($db, "SELECT * FROM appointments WHERE status = 'approved'");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getCompletedAppointments($db) {
        $result = mysqli_query($db, "SELECT * FROM appointments WHERE status = 'completed'");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    function getLowStockInventory($db) {
        $result = mysqli_query($db, "SELECT * FROM inventory WHERE quantity < 5");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Generate report data structure by type
    function generateReportData($type, $db) {
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
                $data['patients'] = getAllPatients($db);
                $data['total_patients'] = count($data['patients']);
                break;

            case 'consultation':
                $data['consultations'] = getAllConsultations($db);
                $data['total_consultations'] = count($data['consultations']);
                break;

            case 'appointment':
                $data['appointments'] = getAllAppointments($db);
                $data['total_appointments'] = count($data['appointments']);
                $data['pending'] = getPendingAppointments($db);
                $data['approved'] = getApprovedAppointments($db);
                $data['completed'] = getCompletedAppointments($db);
                break;

            case 'inventory':
                $data['inventory'] = getAllInventory($db);
                $data['total_items'] = count($data['inventory']);
                $data['low_stock'] = getLowStockInventory($db);
                break;

            case 'announcement':
                $data['announcements'] = getAllAnnouncements($db);
                $data['total_announcements'] = count($data['announcements']);
                break;

            case 'comprehensive':
            default:
                $data['patients'] = getAllPatients($db);
                $data['consultations'] = getAllConsultations($db);
                $data['inventory'] = getAllInventory($db);
                $data['announcements'] = getAllAnnouncements($db);
                $data['appointments'] = getAllAppointments($db);
                $data['users'] = getAllUsers($db);
                $data['certificates'] = []; // Certificates table doesn't exist, skip
                $data['summary'] = [
                    'total_patients' => count($data['patients']),
                    'total_consultations' => count($data['consultations']),
                    'total_inventory_items' => count($data['inventory']),
                    'total_announcements' => count($data['announcements']),
                    'total_appointments' => count($data['appointments']),
                    'total_users' => count($data['users']),
                    'total_certificates' => 0,
                ];
                break;
        }

        return $data;
    }

    // Delete old test reports (IDs 28-33)
    echo "Clearing old example reports...\n";
    mysqli_query($db, "DELETE FROM reports WHERE report_id IN (28, 29, 30, 31, 32, 33)");

    // Create fresh reports for each type
    $types = ['patient', 'consultation', 'appointment', 'inventory', 'announcement', 'comprehensive'];
    $createdIds = [];

    foreach ($types as $type) {
        $snapshot = generateReportData($type, $db);
        $snapshot_json = json_encode($snapshot);

        $sanitized_json = mysqli_real_escape_string($db, $snapshot_json);
        $sanitized_type = mysqli_real_escape_string($db, $type);
        $query = "INSERT INTO reports (generated_by, report_type, report_data) VALUES (1, '$sanitized_type', '$sanitized_json')";

        if (mysqli_query($db, $query)) {
            $newId = mysqli_insert_id($db);
            $createdIds[] = $newId;
            echo "Created $type report (ID: $newId)\n";
        } else {
            echo "Error creating $type report: " . mysqli_error($db) . "\n";
        }
    }

    mysqli_close($db);

    echo "\nDone. Created reports: " . implode(', ', $createdIds) . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
