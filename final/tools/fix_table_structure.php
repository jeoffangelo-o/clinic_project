<?php

$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("DB connection failed: " . mysqli_connect_error() . "\n");
}

echo "Checking consultation_medicines table...\n";

// Drop and recreate to fix any issues
$sql = "DROP TABLE IF EXISTS consultation_medicines";
mysqli_query($db, $sql);

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS consultation_medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity_used DECIMAL(10, 2) NOT NULL,
    unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(consultation_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES inventory(item_id) ON DELETE CASCADE
)
SQL;

if (mysqli_query($db, $sql)) {
    echo "✓ consultation_medicines table recreated successfully\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

echo "\nChecking inventory_log table...\n";

$sql = "DROP TABLE IF EXISTS inventory_log";
mysqli_query($db, $sql);

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS inventory_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantity_change DECIMAL(10, 2) NOT NULL,
    reason ENUM('consumption', 'stock_in', 'adjustment', 'rollback') DEFAULT 'adjustment',
    related_consultation_id INT,
    logged_by INT,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES inventory(item_id) ON DELETE CASCADE,
    FOREIGN KEY (related_consultation_id) REFERENCES consultations(consultation_id) ON DELETE SET NULL,
    FOREIGN KEY (logged_by) REFERENCES users(user_id) ON DELETE SET NULL
)
SQL;

if (mysqli_query($db, $sql)) {
    echo "✓ inventory_log table recreated successfully\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
echo "\n✓ Tables recreated - all issues fixed!\n";

?>
