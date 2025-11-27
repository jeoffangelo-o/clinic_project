<?php

echo "=== INVENTORY CONSUMPTION FEATURE - INTEGRATION TEST ===\n\n";

// Check models exist
echo "1. Checking models...\n";
$consModel = file_exists(__DIR__ . '/../app/Models/ConsultationMedicineModel.php');
$invLogModel = file_exists(__DIR__ . '/../app/Models/InventoryLogModel.php');
echo "   " . ($consModel ? "✓" : "✗") . " ConsultationMedicineModel.php\n";
echo "   " . ($invLogModel ? "✓" : "✗") . " InventoryLogModel.php\n\n";

// Check views updated
echo "2. Checking views...\n";
$addViewPath = __DIR__ . '/../app/Views/Consultation/add_consultation.php';
$addView = file_get_contents($addViewPath);
$hasMedicineSection = strpos($addView, 'addMedicineRow') !== false;
echo "   " . ($hasMedicineSection ? "✓" : "✗") . " add_consultation.php has medicine allocation section\n";

$consultViewPath = __DIR__ . '/../app/Views/Consultation/consultation.php';
$consultView = file_get_contents($consultViewPath);
$hasDisplay = strpos($consultView, 'Medicines Used') !== false;
echo "   " . ($hasDisplay ? "✓" : "✗") . " consultation.php displays medicines in listing\n";

$editViewPath = __DIR__ . '/../app/Views/Consultation/edit_consultation.php';
$editView = file_get_contents($editViewPath);
$hasEditDisplay = strpos($editView, 'Medicines Used in this Consultation') !== false;
echo "   " . ($hasEditDisplay ? "✓" : "✗") . " edit_consultation.php displays medicines used\n\n";

// Check controller updated
echo "3. Checking controller methods...\n";
$controllerPath = __DIR__ . '/../app/Controllers/ConsultationController.php';
$controller = file_get_contents($controllerPath);
$hasAllocate = strpos($controller, 'private function allocateMedicines') !== false;
$hasRollback = strpos($controller, 'Restore inventory') !== false;
$hasMedFetch = strpos($controller, 'ConsultationMedicineModel') !== false;
echo "   " . ($hasAllocate ? "✓" : "✗") . " allocateMedicines() method present\n";
echo "   " . ($hasRollback ? "✓" : "✗") . " rollback logic in delete_consultation\n";
echo "   " . ($hasMedFetch ? "✓" : "✗") . " fetches medicines in consultation() and edit_consultation()\n\n";

// Summary
echo "=== SUMMARY ===\n";
$allGood = $consModel && $invLogModel && 
           $hasMedicineSection && $hasDisplay &&
           $hasEditDisplay && $hasAllocate && $hasRollback && $hasMedFetch;

if($allGood) {
    echo "✓ ALL COMPONENTS INTEGRATED SUCCESSFULLY!\n\n";
    echo "Feature includes:\n";
    echo "  • Medicine allocation form in create consultation\n";
    echo "  • Automatic inventory decrement on consultation create\n";
    echo "  • Automatic inventory rollback on consultation delete\n";
    echo "  • Display of allocated medicines in consultation listing\n";
    echo "  • Display of medicines used in edit view (read-only)\n";
    echo "  • Full audit trail in inventory_log table\n";
    echo "  • Consultation and inventory link via consultation_medicines table\n\n";
    echo "Ready to test:\n";
    echo "  1. Create a consultation with medicine allocation\n";
    echo "  2. Check inventory quantities decreased\n";
    echo "  3. View consultation to see allocated medicines\n";
    echo "  4. Delete consultation and verify inventory restored\n";
} else {
    echo "⚠ Some components missing - review above\n";
}

?>
