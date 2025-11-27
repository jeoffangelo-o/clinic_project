<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CodeIgniter\Database\Config;

$database = Config::connect();
$builder = $database->table('consultation_medicines');

echo "=== Medicine Allocations ===\n";

$medicines = $builder->get()->getResultArray();

if(empty($medicines)) {
    echo "No medicines allocated yet.\n";
} else {
    foreach($medicines as $med) {
        $inventory = $database->table('inventory')->where('item_id', $med['item_id'])->get()->getRowArray();
        echo "\nConsultation ID: " . $med['consultation_id'] . "\n";
        echo "  Medicine: " . ($inventory ? $inventory['item_name'] : 'Unknown') . "\n";
        echo "  Quantity Used: " . $med['quantity_used'] . " " . $med['unit'] . "\n";
        echo "  Date: " . $med['created_at'] . "\n";
    }
}

echo "\n=== Inventory Log ===\n";

$logs = $database->table('inventory_log')->orderBy('created_at', 'DESC')->limit(10)->get()->getResultArray();

if(empty($logs)) {
    echo "No logs yet.\n";
} else {
    foreach($logs as $log) {
        echo "\nItem ID: " . $log['item_id'] . "\n";
        echo "  Change: " . ($log['quantity_change'] > 0 ? '+' : '') . $log['quantity_change'] . "\n";
        echo "  Reason: " . $log['reason'] . "\n";
        echo "  Related Consultation: " . ($log['related_consultation_id'] ?? 'N/A') . "\n";
    }
}

echo "\n✓ Test complete\n";
?>
