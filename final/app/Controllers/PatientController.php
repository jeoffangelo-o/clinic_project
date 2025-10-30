<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PatientModel;

class PatientController extends BaseController
{
     public function patient()
    {
        $patient = new PatientModel();

        $data['patient'] = $patient->findAll();

        return view('Nurse/patient');
    }

    public function add_patient()
    {
        return view('Nurse/add_patient');
    }
}
