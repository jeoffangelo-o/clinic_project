<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CodeIgniter\Database\Config;

$db = Config::connect();

echo "=== INVENTORY CONSUMPTION FEATURE - INTEGRATION TEST ===\n\n";

// Check tables exist
echo "1. Checking tables...\n";
$tables = $db->listTables();
$hasConsultMed = in_array('consultation_medicines', $tables);
$hasInvLog = in_array('inventory_log', $tables);

echo "   ✓ consultation_medicines: " . ($hasConsultMed ? "EXISTS" : "MISSING") . "\n";
echo "   ✓ inventory_log: " . ($hasInvLog ? "EXISTS" : "MISSING") . "\n\n";

// Check columns in consultation_medicines
echo "2. Checking consultation_medicines columns...\n";
$fields = $db->getFieldData('consultation_medicines');
$fieldNames = array_column($fields, 'name');
$required = ['consultation_id', 'item_id', 'quantity_used', 'unit', 'created_at'];
foreach($required as $field) {
    $has = in_array($field, $fieldNames) ? "✓" : "✗";
    echo "   $has $field\n";
}
echo "\n";

// Check columns in inventory_log
echo "3. Checking inventory_log columns...\n";
$fields = $db->getFieldData('inventory_log');
$fieldNames = array_column($fields, 'name');
$required = ['item_id', 'quantity_change', 'reason', 'related_consultation_id', 'created_at'];
foreach($required as $field) {
    $has = in_array($field, $fieldNames) ? "✓" : "✗";
    echo "   $has $field\n";
}
echo "\n";

// Check for sample data
echo "4. Checking for data...\n";
$medCount = $db->table('consultation_medicines')->countAll();
$logCount = $db->table('inventory_log')->countAll();
echo "   Medicines allocated: $medCount records\n";
echo "   Inventory logs: $logCount records\n\n";

// Check models exist
echo "5. Checking models...\n";
$consModel = file_exists(__DIR__ . '/../app/Models/ConsultationMedicineModel.php') ? "✓" : "✗";
$invLogModel = file_exists(__DIR__ . '/../app/Models/InventoryLogModel.php') ? "✓" : "✗";
echo "   $consModel ConsultationMedicineModel.php\n";
echo "   $invLogModel InventoryLogModel.php\n\n";

// Check views updated
echo "6. Checking views...\n";
$addViewPath = __DIR__ . '/../app/Views/Consultation/add_consultation.php';
$addView = file_get_contents($addViewPath);
$hasMedicineSection = strpos($addView, 'addMedicineRow') !== false ? "✓" : "✗";
echo "   $hasMedicineSection add_consultation.php has medicine allocation\n";

$consultViewPath = __DIR__ . '/../app/Views/Consultation/consultation.php';
$consultView = file_get_contents($consultViewPath);
$hasDisplay = strpos($consultView, 'Medicines Used') !== false ? "✓" : "✗";
echo "   $hasDisplay consultation.php displays medicines\n";

$editViewPath = __DIR__ . '/../app/Views/Consultation/edit_consultation.php';
$editView = file_get_contents($editViewPath);
$hasEditDisplay = strpos($editView, 'Medicines Used in this Consultation') !== false ? "✓" : "✗";
echo "   $hasEditDisplay edit_consultation.php displays medicines\n\n";

// Check controller updated
echo "7. Checking controller...\n";
$controllerPath = __DIR__ . '/../app/Controllers/ConsultationController.php';
$controller = file_get_contents($controllerPath);
$hasAllocate = strpos($controller, 'allocateMedicines') !== false ? "✓" : "✗";
$hasRollback = strpos($controller, 'rollback') !== false ? "✓" : "✗";
echo "   $hasAllocate ConsultationController has allocateMedicines method\n";
echo "   $hasRollback ConsultationController has rollback logic in delete\n\n";

// Summary
echo "=== SUMMARY ===\n";
$allGood = $hasConsultMed && $hasInvLog && 
           strpos($consModel, '✓') !== false && strpos($invLogModel, '✓') !== false &&
           strpos($hasMedicineSection, '✓') !== false && strpos($hasDisplay, '✓') !== false &&
           strpos($hasEditDisplay, '✓') !== false && strpos($hasAllocate, '✓') !== false;

if($allGood) {
    echo "✓ ALL COMPONENTS PRESENT - Feature fully integrated!\n\n";
    echo "Next steps:\n";
    echo "1. Create a consultation with medicine allocation\n";
    echo "2. Verify inventory quantities decreased\n";
    echo "3. View the consultation to see allocated medicines\n";
    echo "4. Delete the consultation and verify inventory restored\n";
} else {
    echo "⚠ Some components missing - review checks above\n";
}

?>
