<?php

namespace App\Models;

use CodeIgniter\Model;

class AppointmentModel extends Model
{
    protected $table            = 'appointments';
    protected $primaryKey       = 'appointment_id';
   
    protected $allowedFields    = ['appointment_id',
                                    'patient_id',
                                    'nurse_id',
                                    'appointment_date',
                                    'purpose',
                                    'status',
                                    'remarks',
                                    'created_at'
                                    ];

}
