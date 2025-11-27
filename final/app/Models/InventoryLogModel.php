<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryLogModel extends Model
{
    protected $table = 'inventory_log';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['item_id', 'quantity_change', 'reason', 'related_consultation_id', 'logged_by', 'notes'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;
}
