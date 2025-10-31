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
                            ->where('patient_id', $patient_id)
                            ->findAll();
        
        return view('Student_Staff/appointment', $data);
    }
}
