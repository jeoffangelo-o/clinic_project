<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\AnnouncementModel;

class EmailController extends BaseController
{
    public function sendAnnouncement()
    {
        $user = new UserModel();
        $announce = new announce();

       
    }
}
