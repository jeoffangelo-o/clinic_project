<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table            = 'medical_certificates';
    protected $primaryKey       = 'certificate_id';

    protected $allowedFields    = [
        'consultation_id',
        'patient_id',
        'issued_by',
        'certificate_type',
        'diagnosis_summary',
        'recommendation',
        'validity_start',
        'validity_end',
        'file_path'
    ];
}
