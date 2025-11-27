<?php
$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) die("Connection failed\n");

echo "=== CURRENT SCHEMA ===\n\n";

echo "Consultations table:\n";
$result = mysqli_query($db, "DESCRIBE consultations");
while ($row = mysqli_fetch_assoc($result)) {
    echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nInventory table:\n";
$result = mysqli_query($db, "DESCRIBE inventory");
while ($row = mysqli_fetch_assoc($result)) {
    echo "  - " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nChecking for existing consumption/junction table:\n";
$result = mysqli_query($db, "SHOW TABLES LIKE '%consult%med%' OR LIKE '%consumption%'");
$tables = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tables[] = $row['Tables_in_cspc_clinic'];
}
echo count($tables) > 0 ? "Found: " . implode(', ', $tables) : "  None found\n";

mysqli_close($db);
