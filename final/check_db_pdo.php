<?php
$host = '127.0.0.1';
$db   = 'cspc_clinic';
$user = 'root';
$pass = '';
$port = 3306;
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "DB connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

function safeCount($pdo, $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as c FROM `" . $table . "`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$row['c'];
    } catch (Exception $e) {
        return 'ERR';
    }
}

$tables = ['patients','consultations','appointments','inventory','announcements','reports'];
$counts = [];
foreach ($tables as $t) {
    $counts[$t] = safeCount($pdo, $t);
}

echo "Table counts:\n";
foreach ($counts as $k => $v) {
    echo "- $k: $v\n";
}

echo "\nReports table rows:\n";
try {
    $stmt = $pdo->query("SELECT report_id, report_type, generated_by, generated_at FROM reports ORDER BY generated_at DESC LIMIT 50");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "(no reports found)\n";
    } else {
        foreach ($rows as $r) {
            echo "ID: {$r['report_id']} | type: '" . trim($r['report_type']) . "' | by: {$r['generated_by']} | at: {$r['generated_at']}\n";
        }
    }
} catch (Exception $e) {
    echo "Error reading reports: " . $e->getMessage() . "\n";
}

?>
