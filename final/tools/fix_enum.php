<?php
$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("Connection failed\n");
}

// Alter the enum to include all report types
$altQuery = "ALTER TABLE reports MODIFY COLUMN report_type ENUM('patient', 'consultation', 'appointment', 'inventory', 'announcement', 'comprehensive', 'daily', 'weekly', 'monthly')";

echo "Altering report_type enum...\n";
echo "Query: $altQuery\n\n";

if (mysqli_query($db, $altQuery)) {
    echo "✓ Successfully altered enum column\n";
    
    // Verify schema
    $result = mysqli_query($db, "DESCRIBE reports");
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['Field'] === 'report_type') {
            echo "New type: " . $row['Type'] . "\n";
        }
    }
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
