<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ConsultationModel;
use App\Models\AppointmentModel;

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
    
}
