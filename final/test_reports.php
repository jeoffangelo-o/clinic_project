<?php
require 'vendor/autoload.php';

$config = new Config\Database();
$db = $config->initialize();

$results = [
    'patients' => $db->table('patients')->countAllResults(),
    'consultations' => $db->table('consultations')->countAllResults(),
    'appointments' => $db->table('appointments')->countAllResults(),
    'inventory' => $db->table('inventory')->countAllResults(),
    'announcements' => $db->table('announcements')->countAllResults(),
    'reports' => $db->table('reports')->countAllResults(),
];

echo json_encode($results, JSON_PRETTY_PRINT);
?>
