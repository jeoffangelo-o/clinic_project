<?php
// Fill missing report_data for reports that have report_type set but no snapshot
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

$stmt = $pdo->query("SELECT report_id, report_type FROM reports WHERE (report_data IS NULL OR report_data = '') AND (report_type IS NOT NULL AND report_type <> '')");
$rows = $stmt->fetchAll();
if (empty($rows)) {
    echo "No reports with missing snapshots found.\n";
    exit(0);
}

$updated = 0;
foreach ($rows as $r) {
    $id = $r['report_id'];
    $type = $r['report_type'];
    echo "Processing report #$id type=$type...\n";
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

    try {
        switch ($type) {
            case 'patient':
                $s = $pdo->query('SELECT * FROM patients');
                $data['patients'] = $s->fetchAll();
                $data['total_patients'] = count($data['patients']);
                break;
            case 'consultation':
                $s = $pdo->query('SELECT * FROM consultations');
                $data['consultations'] = $s->fetchAll();
                $data['total_consultations'] = count($data['consultations']);
                break;
            case 'appointment':
                $s = $pdo->query('SELECT * FROM appointments');
                $data['appointments'] = $s->fetchAll();
                $data['total_appointments'] = count($data['appointments']);
                $data['pending'] = $pdo->query("SELECT * FROM appointments WHERE status = 'pending'")->fetchAll();
                $data['approved'] = $pdo->query("SELECT * FROM appointments WHERE status = 'approved'")->fetchAll();
                $data['completed'] = $pdo->query("SELECT * FROM appointments WHERE status = 'completed'")->fetchAll();
                break;
            case 'inventory':
                $s = $pdo->query('SELECT * FROM inventory');
                $data['inventory'] = $s->fetchAll();
                $data['total_items'] = count($data['inventory']);
                $data['low_stock'] = $pdo->query('SELECT * FROM inventory WHERE quantity < 5')->fetchAll();
                break;
            case 'announcement':
                $s = $pdo->query('SELECT * FROM announcements');
                $data['announcements'] = $s->fetchAll();
                $data['total_announcements'] = count($data['announcements']);
                break;
            case 'comprehensive':
            default:
                $data['patients'] = $pdo->query('SELECT * FROM patients')->fetchAll();
                $data['consultations'] = $pdo->query('SELECT * FROM consultations')->fetchAll();
                $data['appointments'] = $pdo->query('SELECT * FROM appointments')->fetchAll();
                $data['inventory'] = $pdo->query('SELECT * FROM inventory')->fetchAll();
                $data['announcements'] = $pdo->query('SELECT * FROM announcements')->fetchAll();
                $data['users'] = $pdo->query('SELECT * FROM users')->fetchAll();
                // medical_certificates -> certificates
                try {
                    $data['certificates'] = $pdo->query('SELECT * FROM medical_certificates')->fetchAll();
                } catch (Exception $e) {
                    $data['certificates'] = [];
                }

                $data['pending'] = $pdo->query("SELECT * FROM appointments WHERE status = 'pending'")->fetchAll();
                $data['approved'] = $pdo->query("SELECT * FROM appointments WHERE status = 'approved'")->fetchAll();
                $data['completed'] = $pdo->query("SELECT * FROM appointments WHERE status = 'completed'")->fetchAll();
                $data['low_stock'] = $pdo->query('SELECT * FROM inventory WHERE quantity < 5')->fetchAll();

                $data['total_patients'] = count($data['patients']);
                $data['total_consultations'] = count($data['consultations']);
                $data['total_appointments'] = count($data['appointments']);
                $data['total_items'] = count($data['inventory']);
                $data['total_announcements'] = count($data['announcements']);
                break;
        }

        // summary
        $data['summary'] = [
            'total_patients' => $data['total_patients'] ?? count($data['patients'] ?? []),
            'total_consultations' => $data['total_consultations'] ?? count($data['consultations'] ?? []),
            'total_inventory_items' => $data['total_items'] ?? count($data['inventory'] ?? []),
            'total_announcements' => $data['total_announcements'] ?? count($data['announcements'] ?? []),
            'total_appointments' => $data['total_appointments'] ?? count($data['appointments'] ?? []),
            'total_users' => count($data['users'] ?? []),
            'total_certificates' => count($data['certificates'] ?? []),
        ];

        $json = json_encode($data);
        $upd = $pdo->prepare('UPDATE reports SET report_data = :json WHERE report_id = :id');
        $upd->execute([':json' => $json, ':id' => $id]);
        $updated++;
        echo "Updated snapshot for report #$id\n";
    } catch (Exception $e) {
        echo "Error processing report #$id: " . $e->getMessage() . "\n";
    }
}

echo "Done. Updated: $updated\n";
