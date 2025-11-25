<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table            = 'reports';
    protected $primaryKey       = 'report_id';

    protected $allowedFields    = [
        'generated_by',
        'report_type',
        'file_path'
    ];
}
