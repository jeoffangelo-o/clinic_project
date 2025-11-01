<?php

namespace App\Models;

use CodeIgniter\Model;

class ConsultationModel extends Model
{
    protected $table            = 'consultations';
    protected $primaryKey       = 'consultation_id';
    
    protected $allowedFields    = ['appointment_id',
                                'patient_id',
                                'nurse_id',
                                'diagnosis',
                                'treatment',
                                'prescription',
                                'consultation_date',
                                'notes'
                                ];

}
