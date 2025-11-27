<?php
/**
 * Create consumption tracking table and helper tables
 */

$db = mysqli_connect('localhost', 'root', '', 'cspc_clinic');
if (!$db) {
    die("DB connection failed: " . mysqli_connect_error() . "\n");
}

echo "Creating consultation_medicines table...\n";

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS consultation_medicines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity_used INT NOT NULL DEFAULT 1,
    unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(consultation_id) ON DELETE CASCADE,
    FOREIGN KEY (item_id) REFERENCES inventory(item_id) ON DELETE CASCADE,
    UNIQUE KEY unique_consultation_item (consultation_id, item_id)
)
SQL;

if (mysqli_query($db, $sql)) {
    echo "✓ consultation_medicines table created successfully\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

echo "\nCreating inventory_log table (audit trail)...\n";

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS inventory_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    quantity_change INT NOT NULL,
    reason ENUM('consultation', 'stock_in', 'adjustment', 'damage') DEFAULT 'adjustment',
    related_consultation_id INT,
    logged_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (item_id) REFERENCES inventory(item_id) ON DELETE CASCADE,
    FOREIGN KEY (related_consultation_id) REFERENCES consultations(consultation_id) ON DELETE SET NULL,
    FOREIGN KEY (logged_by) REFERENCES users(user_id) ON DELETE SET NULL
)
SQL;

if (mysqli_query($db, $sql)) {
    echo "✓ inventory_log table created successfully\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

mysqli_close($db);
echo "\nSetup complete!\n";
