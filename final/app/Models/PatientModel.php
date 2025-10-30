<?php

namespace App\Models;

use CodeIgniter\Model;

class PatientModel extends Model
{
    protected $table            = 'patients';
    protected $primaryKey       = 'patients_id';


    protected $allowedFields    = ['user_id',
                                    'first_name',
                                    'middle_name',
                                    'last_name',
                                    'gender',
                                    'birth_date',
                                    'contact_no',
                                    'address',
                                    'blood_type',
                                    'allergies',
                                    'medical_history',
                                    'emergency_contact',
                                    'created_at'];

}
