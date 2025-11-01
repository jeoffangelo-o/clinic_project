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

        

        if(session()->get('role') === 'student' || session()->get('role') === 'staff'){
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
        }
        else if(session()->get('role') === 'nurse' || session()->get('role') === 'admin' ){
            $stats = request()->getGet('status');

            if($stats === 'all'){
                $data['appoint'] = $appoint->orderBy('created_at', 'asc')->findAll();
            }
            else{
                $data['appoint'] = $appoint
                                    ->where('status', $stats)
                                    ->orderBy('created_at', 'asc')
                                    ->findAll();
            }

            session()->set('appointment_status', $stats);
            
        }

        
        
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

    public function edit_appointment($id)
    {
        $appoint = new AppointmentModel();

        $data['a'] = $appoint->find($id);

        return view('Student_Staff/edit_appointment', $data);
    }

    public function update_appointment($id){
        $appoint = new AppointmentModel();

        if(session()->get('activity') === 'save'){

            $data = [
                'status' => request()->getPost('status'),
                'remarks' => request()->getPost('remarks'),
            ];

            $appoint->update($id, $data);

            return redirect()->to('/appointment')->with('message', 'Appointment Saved Successfully');
        }

        $data = [
                'appointment_date' => request()->getPost('appointment_date'),
                'purpose' => request()->getPost('purpose'),
        ];

        $appoint->update($id, $data);

        return redirect()->to('/appointment/edit/'.$id)->with('message', 'Appointment Updated Successfully');
    }

    public function delete_appointment($id)
    {
        $appoint = new AppointmentModel();

        $appoint->delete($id);

        return redirect()->to('/appointment')->with('message', 'Appointment Deleted Successfully');
    }
}
