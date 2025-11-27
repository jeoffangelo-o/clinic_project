<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CodeIgniter\Database\Config;

$db = Config::connect();

echo "=== consultation_medicines Table Structure ===\n\n";

$fields = $db->getFieldData('consultation_medicines');

foreach($fields as $field) {
    echo "Field: " . $field->name . "\n";
    echo "  Type: " . $field->type . "\n";
    echo "  Null: " . ($field->nullable ? 'YES' : 'NO') . "\n";
    echo "  Key: " . ($field->key ? $field->key : 'NONE') . "\n";
    echo "  Default: " . ($field->default ? $field->default : 'NONE') . "\n";
    echo "\n";
}

echo "\n=== inventory_log Table Structure ===\n\n";

$fields = $db->getFieldData('inventory_log');

foreach($fields as $field) {
    echo "Field: " . $field->name . "\n";
    echo "  Type: " . $field->type . "\n";
    echo "  Null: " . ($field->nullable ? 'YES' : 'NO') . "\n";
    echo "  Key: " . ($field->key ? $field->key : 'NONE') . "\n";
    echo "  Default: " . ($field->default ? $field->default : 'NONE') . "\n";
    echo "\n";
}

?>
