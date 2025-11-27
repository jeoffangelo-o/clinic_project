<?php
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

// Counts by type
$stmt = $pdo->query("SELECT COALESCE(NULLIF(report_type, ''), '<blank>') AS type, COUNT(*) AS cnt FROM reports GROUP BY type ORDER BY cnt DESC");
$rows = $stmt->fetchAll();
echo "Report type counts:\n";
foreach ($rows as $r) {
    echo str_pad($r['type'], 15) . ': ' . $r['cnt'] . PHP_EOL;
}

// Show a sample report for each non-blank type
$stmt = $pdo->query("SELECT DISTINCT COALESCE(NULLIF(report_type, ''), '<blank>') AS type FROM reports");
$types = $stmt->fetchAll(PDO::FETCH_COLUMN);
foreach ($types as $t) {
    echo "\nSample for type: $t\n";
    $q = ($t === '<blank>') ? "SELECT * FROM reports WHERE report_type = '' LIMIT 1" : "SELECT * FROM reports WHERE report_type = '".addslashes($t)."' LIMIT 1";
    $s = $pdo->query($q);
    $rep = $s->fetch(PDO::FETCH_ASSOC);
    if (!$rep) { echo "(no row)\n"; continue; }
    echo "ID: " . $rep['report_id'] . " | generated_by: " . $rep['generated_by'] . " | generated_at: " . $rep['generated_at'] . "\n";
    echo "report_type: '" . $rep['report_type'] . "'\n";
    echo "report_data present: " . (empty($rep['report_data']) ? 'NO' : 'YES') . "\n";
}
