<?php
$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("Connection failed\n");
}

// Test insert with simple data
$test_types = ['consultation', 'appointment', 'announcement', 'comprehensive'];

foreach ($test_types as $type) {
    $sanitized_type = mysqli_real_escape_string($db, $type);
    $query = "INSERT INTO reports (generated_by, report_type, report_data) VALUES (1, '$sanitized_type', '{\"test\":\"data\"}')";
    
    echo "Query: $query\n";
    
    if (mysqli_query($db, $query)) {
        $newId = mysqli_insert_id($db);
        
        // Read it back
        $check = mysqli_query($db, "SELECT report_id, report_type FROM reports WHERE report_id = $newId");
        $row = mysqli_fetch_assoc($check);
        echo "  ✓ Inserted ID: $newId | Read back type: '" . $row['report_type'] . "'\n";
    } else {
        echo "  ✗ Error: " . mysqli_error($db) . "\n";
    }
}

mysqli_close($db);
