<?php
define('APPPATH', __DIR__ . '/app/');
define('BASEPATH', __DIR__ . '/system/');
define('ROOTPATH', __DIR__ . '/');
define('ENVIRONMENT', 'development');

require_once ROOTPATH . 'vendor/autoload.php';
require_once APPPATH . 'Config/Paths.php';

$config = new \Config\Database();
$db = $config->initialize();

// Get all reports
$reports = $db->table('reports')->get()->getResultArray();

echo "Total Reports: " . count($reports) . "\n";
foreach ($reports as $r) {
    echo "ID: " . $r['report_id'] . " | Type: " . trim($r['report_type']) . " | Generated: " . $r['generated_at'] . "\n";
}

// Check table row counts
echo "\nTable Row Counts:\n";
echo "patients: " . $db->table('patients')->countAllResults() . "\n";
echo "consultations: " . $db->table('consultations')->countAllResults() . "\n";
echo "appointments: " . $db->table('appointments')->countAllResults() . "\n";
echo "inventory: " . $db->table('inventory')->countAllResults() . "\n";
echo "announcements: " . $db->table('announcements')->countAllResults() . "\n";
?>
