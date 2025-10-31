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

        return view('Nurse/patient', $data);
    }

    public function add_patient()
    {
        return view('Nurse/add_patient');
    }

    public function store_patient()
    {
        $patient = new PatientModel();

        $first_name =  request()->getPost('first_name');
        $middle_name =  request()->getPost('middle_name');
        $last_name =  request()->getPost('last_name');
        $user_id =  request()->getPost('user_id') ?: null;

        $exist = $patient->groupStart()
                        ->where('first_name', $first_name)
                        ->where('middle_name', $middle_name)
                        ->where('last_name', $last_name)
                        ->groupEnd()
                        ->orWhere('user_id', $user_id)
                        ->first();

        if($exist){
            return redirect()->to('/patient/add')->with('message', 'Patient Already Exists');
        }

        $data = [
            'user_id' => $user_id,
            'first_name' => request()->getPost('first_name'),
            'middle_name' => request()->getPost('middle_name'),
            'last_name' => request()->getPost('last_name'),
            'gender' => request()->getPost('gender'),
            'birth_date' => request()->getPost('birth_date'),
            'contact_no' => request()->getPost('contact_no'),
            'address' => request()->getPost('address'),
            'blood_type' => request()->getPost('blood_type'),
            'allergies' => request()->getPost('allergies'),
            'medical_history' => request()->getPost('medical_history'),
            'emergency_contact' => request()->getPost('emergency_contact'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $patient->insert($data);

        return redirect()->to('/patient/add')->with('message', 'Patient Added Successfully');
    }

    public function view_patient($id){
        $patient = new PatientModel();

        $data['p'] = $patient->find($id);

        return view('/Nurse/view_patient', $data);
    }
}
