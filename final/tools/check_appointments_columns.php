<?php

$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("DB connection failed: " . mysqli_connect_error() . "\n");
}

echo "=== appointments Table Columns ===\n\n";

$result = mysqli_query($db, "DESCRIBE appointments");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

mysqli_close($db);

?>
