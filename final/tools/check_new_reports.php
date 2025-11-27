<?php
$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("Connection failed\n");
}

$result = mysqli_query($db, 'SELECT report_id, report_type, LENGTH(report_data) as data_len FROM reports WHERE report_id >= 43 ORDER BY report_id');

if (!$result) {
    die("Query failed: " . mysqli_error($db) . "\n");
}

echo "New reports (ID >= 43):\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo sprintf("ID: %d | Type: %s | Data length: %d bytes\n", $row['report_id'], $row['report_type'], $row['data_len']);
}

mysqli_close($db);
