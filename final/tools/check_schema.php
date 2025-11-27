<?php
$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("Connection failed\n");
}

$result = mysqli_query($db, "DESCRIBE reports");
if ($result) {
    echo "Table schema for 'reports':\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo sprintf("%-15s | %-20s | %s | %s | %s | %s\n", 
            $row['Field'], 
            $row['Type'], 
            $row['Null'] ?? 'N/A',
            $row['Key'] ?? 'N/A',
            $row['Default'] ?? 'N/A',
            $row['Extra'] ?? 'N/A'
        );
    }
} else {
    echo "Error: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
