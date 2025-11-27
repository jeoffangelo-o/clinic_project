<?php
// Quick DB table counts for debugging
$host = 'localhost';
$db   = 'cspc_clinic';
$user = 'root';
$pass = '';
$port = 3306;
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

$tables = [
    'patients',
    'consultations',
    'appointments',
    'inventory',
    'announcements',
    'reports',
    'users',
    'medical_certificates',
    'certificates'
];

foreach ($tables as $t) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM `$t`");
        $count = $stmt->fetchColumn();
        echo str_pad($t, 25) . ": " . $count . PHP_EOL;
    } catch (Exception $e) {
        echo str_pad($t, 25) . ": (missing or error) " . $e->getMessage() . PHP_EOL;
    }
}

// Show a few report rows to inspect report_type
try {
    $stmt = $pdo->query("SELECT report_id, report_type, generated_by, generated_at FROM reports ORDER BY report_id DESC LIMIT 10");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo PHP_EOL . "Recent reports:" . PHP_EOL;
    foreach ($rows as $r) {
        echo "#" . $r['report_id'] . " | type='" . $r['report_type'] . "' | by=" . $r['generated_by'] . " | at=" . $r['generated_at'] . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Could not fetch reports: " . $e->getMessage() . PHP_EOL;
}
