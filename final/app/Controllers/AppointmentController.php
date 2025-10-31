<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AppointmentModel;
use App\Models\UserModel;

class AppointmentController extends BaseController
{
    public function appointment()
    {
        $appoint = new AppointmentModel;

        $user_id = session()->get('user_id');

        $data['appoint'] = $appoint
                            ->where('user_id', $user_id)
                            ->findAll();
        
        return view('Student_Staff/appointment', $data);
    }
}
