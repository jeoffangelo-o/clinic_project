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
        $data['patient'] = [];
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'asc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        // Student and Staff can only see their own patient record
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $myPatient = $patient->where('user_id', $user_id)->first();
            
            if($myPatient){
                $data['patient'] = [$myPatient];
            }
        }
        // Admin and Nurse can see all patient records
        else if(session()->get('role') === 'admin' || session()->get('role') === 'nurse'){
            if ($search) {
                $data['patient'] = $patient->groupStart()
                    ->like('first_name', $search)
                    ->orLike('middle_name', $search)
                    ->orLike('last_name', $search)
                    ->orLike('patient_id', $search)
                    ->orLike('contact_no', $search)
                    ->groupEnd()
                    ->orderBy('patient_id', $sort)
                    ->findAll();
            } else {
                $data['patient'] = $patient->orderBy('patient_id', $sort)->findAll();
            }
        }

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

        // Student and Staff can only create patient record for themselves
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            $existingPatient = $patient->where('user_id', $user_id)->first();
            
            if($existingPatient){
                return redirect()->to('/patient/add')->with('message', 'Error: You already have a patient record');
            }
        }

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

        // Student and Staff can only edit their own patient record
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            if($exist['user_id'] != $user_id){
                return redirect()->to('/patient')->with('message', 'Error: You can only edit your own patient record');
            }
        }

        // Preserve existing user_id when the edit form does not include the field
        $userModel = new UserModel();
        $user_id_post = request()->getPost('user_id');
        if ($user_id_post !== null) {
            // Field was present in POST (even if empty) - interpret empty as null
            $user_id = $user_id_post ?: null;
            if ($user_id) {
                $userExists = $userModel->find($user_id);
                if (!$userExists) {
                    return redirect()->to('/patient/edit/'.$id)->with('message', 'Error: User ID does not exist');
                }
            }
        } else {
            // Field not provided in form - keep previous value
            $user_id = $exist['user_id'];
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

        // Student and Staff can only delete their own patient record
        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
            $user_id = session()->get('user_id');
            if($exist['user_id'] != $user_id){
                return redirect()->to('/patient')->with('message', 'Error: You can only delete your own patient record');
            }
        }

        $patient->delete($id);

        return redirect()->to('/patient')->with('message', 'Patient # ' . $id . ' is Deleted Successfully');
    }
}
