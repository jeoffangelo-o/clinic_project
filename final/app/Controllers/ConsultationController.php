<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ConsultationModel;
use App\Models\AppointmentModel;
use App\Models\PatientModel;

class ConsultationController extends BaseController
{

    public function consultation()
    {
        $consult = new ConsultationModel();

        $data['consult'] = $consult->findAll();

        return view('Consultation/consultation', $data);
    }

    public function add_consultation()
    {
        $consult = new ConsultationModel();

        $service = request()->getGet('service');

        if($service === 'walkin'){
            session()->set('service', 'walkin');
        }
        else{
            session()->set('service', 'appoint');
        }

        return view('Consultation/add_consultation');
    }

    public function store_consultation()
    {
        $appoint = new AppointmentModel();
        $patient = new PatientModel();
        $consult = new ConsultationModel();

        $service = request()->getPost('service');
        
        if($service === 'walkin'){
            $patient_id = request()->getPost('patient_id');

            $patientExist = $patient->where('patient_id', $patient_id)->first;

            if(!$patientExist){
                return redirect()->to('/consultation/add')->with('message', 'Patient ID is not found.');
            }

            $data = [
                'patient_id' => $patient_id,
                'diagnosis' => request()->getPost('diagnosis'),
                'treatment' => request()->getPost('treatment'),
                'prescription' => request()->getPost('prescription'),
                'notes' => request()->getPost('notes'),
                'consultation_data' => date('Y-m-d H:i:s')
            ];
        }
        else{
            $appoint_id = request()->getPost('appointment_id');

            $appointExist = $appoint->where('appointment_id', $appoint_id)->first();

            if(!$appointExist){
                return redirect()->to('/consultation/add')->with('message', 'Appointment ID is not found.');
            }

            $patient_id = $appointExist['patient_id'];

            $data = [
                'patient_id' => $patient_id,
                'diagnosis' => request()->getPost('diagnosis'),
                'treatment' => request()->getPost('treatment'),
                'appointment_id' => request()->getPost('appointment_id'),
                'prescription' => request()->getPost('prescription'),
                'notes' => request()->getPost('notes'),
                'consultation_data' => date('Y-m-d H:i:s')
            ];

        }


    }

    
}
