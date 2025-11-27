<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsultationMedicineModel extends Model
{
    protected $table = 'consultation_medicines';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['consultation_id', 'item_id', 'quantity_used', 'unit'];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = null;
    protected $deletedField = null;
}
