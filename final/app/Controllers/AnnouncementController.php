<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnnouncementModel;
use App\Models\UserModel;

class AnnouncementController extends BaseController
{
    public function announcement()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $announce = new AnnouncementModel();

        $data['announce'] = $announce->findAll();

        return view('Announcement/announcement' , $data);
    }

    public function add_announcement()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        return view('Announcement/add_announcement');
    }

    public function store_announcement()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $a = new AnnouncementModel();
        $user = new UserModel();

        $title = request()->getPost('title');
        $content = request()->getPost('content');
        $posted_until = request()->getPost('posted_until');
        $url = request()->getPost('url');

        // Validate posted_until date
        if(empty($posted_until)){
            return redirect()->to('/announcement/add')->with('message', 'Error: posted_until date is required');
        }

        $posted_at = date('Y-m-d H:i:s');
        
        // Ensure posted_until is a valid date and is not in the past
        if(!strtotime($posted_until) || strtotime($posted_until) < time()){
            return redirect()->to('/announcement/add')->with('message', 'Error: posted_until date must be a future date');
        }

        $data = [
            'title'        => $title,
            'content'      => $content,
            'posted_by'    => session()->get('user_id'),
            'posted_at'    => $posted_at,
            'posted_until' => $posted_until,
            'url'          => $url,
        ];

        $a->insert($data);

        //email
        $email = \Config\Services::email();
        $allUser = $user->findAll();

        $emailSent = 0;
        $emailFailed = 0;

        foreach ($allUser as $u) {
            if(empty($u['email'])){
                $emailFailed++;
                continue;
            }

            // Validate email format before sending
            if(!filter_var($u['email'], FILTER_VALIDATE_EMAIL)){
                $emailFailed++;
                continue;
            }

            $message = '
                <img src="' . $url . '" alt="image" style="height: 100px; width: 80px;">
                <h3>' . $title . '</h3>
                <p>' . $posted_at . '</p><br>
                <h2> Good Day '. $u['username'] . '!!!</h2><br><br>
                <h2>' . $content . '</h2>
            ';

            $email->setTo($u['email']);
            $email->setFrom('jeoffgbanaria@gmail.com', 'CSPC Clinic');
            $email->setSubject('ANNOUNCEMENT!!!');
            $email->setMessage($message);

            if($email->send()){
                $emailSent++;
            }
            else{
                $emailFailed++;
            }
            
            $email->clear(true);
        
        }

   
        $message = 'Announcement Posted Successfully';
        if($emailFailed > 0){
            $message .= ' | Emails Sent: ' . $emailSent . ' | Failed: ' . $emailFailed;
        }
        return redirect()->to('/announcement/add')->with('message', $message);
    }

    public function edit_announcement()
    {
        $announce = new AnnouncementModel();
    }

}
