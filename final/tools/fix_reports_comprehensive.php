<?php
// Bulk-update blank reports to 'comprehensive' and save generated report_data.
$host = 'localhost';
$db   = 'cspc_clinic';
$user = 'root';
$pass = '';
$port = 3306;
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Find reports with blank or NULL type
$stmt = $pdo->query("SELECT report_id FROM reports WHERE report_type = '' OR report_type IS NULL");
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (empty($rows)) {
    echo "No blank reports found.\n";
    exit(0);
}

$updated = 0;
foreach ($rows as $report_id) {
    // Build comprehensive report data by selecting from each table
    $data = [];
    $tables = [
        'patients',
        'consultations',
        'appointments',
        'inventory',
        'announcements',
        'users',
        'medical_certificates'
    ];

    foreach ($tables as $t) {
        try {
            $s = $pdo->query("SELECT * FROM `$t`");
            $dataKey = $t;
            // normalize key names to match controller expectations (e.g., medical_certificates -> certificates)
            if ($t === 'medical_certificates') {
                $dataKey = 'certificates';
            }
            $data[$dataKey] = $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // table might not exist, continue with empty
            $dataKey = ($t === 'medical_certificates') ? 'certificates' : $t;
            $data[$dataKey] = [];
        }
    }

    // Additional computed lists
    // pending/approved/completed for appointments
    try {
        $s = $pdo->query("SELECT * FROM `appointments` WHERE `status` = 'pending'");
        $data['pending'] = $s->fetchAll(PDO::FETCH_ASSOC);
        $s = $pdo->query("SELECT * FROM `appointments` WHERE `status` = 'approved'");
        $data['approved'] = $s->fetchAll(PDO::FETCH_ASSOC);
        $s = $pdo->query("SELECT * FROM `appointments` WHERE `status` = 'completed'");
        $data['completed'] = $s->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $data['pending'] = [];
        $data['approved'] = [];
        $data['completed'] = [];
    }

    // low_stock
    try {
        $s = $pdo->query("SELECT * FROM `inventory` WHERE `quantity` < 5");
        $data['low_stock'] = $s->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $data['low_stock'] = [];
    }

    // totals & summary
    $data['total_patients'] = count($data['patients'] ?? []);
    $data['total_consultations'] = count($data['consultations'] ?? []);
    $data['total_appointments'] = count($data['appointments'] ?? []);
    $data['total_items'] = count($data['inventory'] ?? []);
    $data['total_announcements'] = count($data['announcements'] ?? []);

    $data['summary'] = [
        'total_patients' => $data['total_patients'],
        'total_consultations' => $data['total_consultations'],
        'total_inventory_items' => $data['total_items'],
        'total_announcements' => $data['total_announcements'],
        'total_appointments' => $data['total_appointments'],
        'total_users' => count($data['users'] ?? []),
        'total_certificates' => count($data['certificates'] ?? []),
    ];

    $json = json_encode($data);
    if ($json === false) {
        echo "JSON encode error for report $report_id\n";
        continue;
    }

    $upd = $pdo->prepare("UPDATE reports SET report_type = 'comprehensive', report_data = :json WHERE report_id = :id");
    $upd->execute([':json' => $json, ':id' => $report_id]);
    $updated++;
    echo "Updated report #$report_id -> comprehensive\n";
}

echo "Done. Total updated: $updated\n";
