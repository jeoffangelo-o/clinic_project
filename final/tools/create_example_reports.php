<?php
// Create example report rows for consultation, appointment, and announcement
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

$types = ['consultation', 'appointment', 'announcement'];
$created = [];

foreach ($types as $type) {
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
            case 'announcement':
                $s = $pdo->query('SELECT * FROM announcements');
                $data['announcements'] = $s->fetchAll();
                $data['total_announcements'] = count($data['announcements']);
                break;
        }

        // Provide sensible totals/summary
        $data['total_patients'] = count($pdo->query('SELECT * FROM patients')->fetchAll());
        $data['total_consultations'] = $data['total_consultations'] ?? count($data['consultations']);
        $data['total_appointments'] = $data['total_appointments'] ?? count($data['appointments']);
        $data['total_items'] = count($pdo->query('SELECT * FROM inventory')->fetchAll());
        $data['total_announcements'] = $data['total_announcements'] ?? count($data['announcements']);

        $data['summary'] = [
            'total_patients' => $data['total_patients'],
            'total_consultations' => $data['total_consultations'],
            'total_inventory_items' => $data['total_items'],
            'total_announcements' => $data['total_announcements'],
            'total_appointments' => $data['total_appointments'],
            'total_users' => count($pdo->query('SELECT * FROM users')->fetchAll()),
            'total_certificates' => count($pdo->query('SELECT * FROM medical_certificates')->fetchAll() ?: []),
        ];

        $json = json_encode($data);
        if ($json === false) {
            echo "JSON encode failed for $type\n";
            continue;
        }

        $stmt = $pdo->prepare('INSERT INTO reports (generated_by, report_type, report_data, file_path) VALUES (:by, :type, :data, NULL)');
        $stmt->execute([':by' => 1, ':type' => $type, ':data' => $json]);
        $id = $pdo->lastInsertId();
        $created[] = $id;
        echo "Created report #$id type=$type\n";
    } catch (Exception $e) {
        echo "Error creating $type: " . $e->getMessage() . "\n";
    }
}

if (!empty($created)) {
    echo "Done. Created reports: " . implode(', ', $created) . "\n";
} else {
    echo "No reports created.\n";
}
