<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PatientModel;
use App\Models\UserModel;

class PatientController extends BaseController
{
    public function patient()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();

        $data['patient'] = $patient->findAll();

        return view('Patient/patient', $data);
    }

    public function add_patient()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        return view('Patient/add_patient');
    }

    public function store_patient()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();
        $user = new UserModel();

        $first_name =  request()->getPost('first_name');
        $middle_name =  request()->getPost('middle_name');
        $last_name =  request()->getPost('last_name');
        $user_id =  request()->getPost('user_id') ?: null;

        if($user_id){
            $userExists = $user->find($user_id);
            if(!$userExists){
                return redirect()->to('/patient/add')->with('message', 'Error: User ID does not exist');
            }
        }

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
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();

        $data['p'] = $patient->find($id);

        if(!$data['p']){
            return redirect()->to('/patient')->with('message', 'Error: Patient not found');
        }

        return view('/Patient/view_patient', $data);
    }

    public function edit_patient($id){
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();

        $data['p'] = $patient->find($id);

        if(!$data['p']){
            return redirect()->to('/patient')->with('message', 'Error: Patient not found');
        }

        return view('/Patient/edit_patient', $data);
    }

    public function update_patient($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();

        $exist = $patient->find($id);
        if(!$exist){
            return redirect()->to('/patient')->with('message', 'Error: Patient not found');
        }

        $data = [
            'user_id' => request()->getPost('user_id') ?: null,
            'first_name' => request()->getPost('first_name'),
            'middle_name' => request()->getPost('middle_name'),
            'last_name' => request()->getPost('last_name'),
            'gender' => request()->getPost('gender'),
            'birth_date' => request()->getPost('birth_date'),
            'contact_no' => request()->getPost('contact_no'),
            'address' => request()->getPost('address'),
            'blood_type' => request()->getPost('blood_type') ?: null,
            'allergies' => request()->getPost('allergies') ?: null,
            'medical_history' => request()->getPost('medical_history'),
            'emergency_contact' => request()->getPost('emergency_contact'),
        ];

        $patient->update($id, $data);

        return redirect()->to('/patient/edit/'.$id)->with('message', 'Patient Updated Successfully');
    }

    public function delete_patient($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $patient = new PatientModel();
        
        $exist = $patient->find($id);
        if(!$exist){
            return redirect()->to('/patient')->with('message', 'Error: Patient not found');
        }

        $patient->delete($id);

        return redirect()->to('/patient')->with('message', 'Patient # ' . $id . ' is Deleted Successfully');
    }
}
