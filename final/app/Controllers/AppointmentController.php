<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppointmentModel;
use App\Models\PatientModel;


class AppointmentController extends BaseController
{
    public function appointment()
    {
        $appoint = new AppointmentModel;
        $patient = new PatientModel;

        $user_id = session()->get('user_id');

        $exist = $patient->where('user_id', $user_id)->first();

        if($exist){
            session()->set([
                'hasPatient' => true
            ]);
        }
        else{
            session()->set([
                'hasPatient' => false
            ]);
        }

        $data['appoint'] = $appoint
                            ->where('patient_id', $exist['patient_id'])
                            ->findAll();
        
        return view('Student_Staff/appointment', $data);
    }

    public function add_appointment(){
        return view('Student_Staff/add_appointment');
    }

    public function store_appointment()
    {
        $appoint = new AppointmentModel();
        $patient = new PatientModel();

        $user_id = session()->get('user_id');

        $exist = $patient->where('user_id', $user_id)->first();

        $data = [
            'patient_id' => $exist['patient_id'],
            'appointment_date' => request()->getPost('appointment_date'),
            'purpose' => request()->getPost('purpose'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $appoint->insert($data);

        return redirect()->to('/appointment/add')->with('message', 'Appointment Created Successfully');
    }
}
