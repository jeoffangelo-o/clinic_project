<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventory';
    protected $primaryKey       = 'item_id';

    protected $allowedFields    = [
        'item_name',
        'category',
        'quantity',
        'unit',
        'expiry_date',
        'description',
        'added_by'
    ];
}
