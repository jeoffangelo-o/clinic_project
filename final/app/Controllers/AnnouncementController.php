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
        $announce = new AnnouncementModel();

        $data['announce'] = $announce->findAll();

        return view('Announcement/announcement' , $data);
    }

    public function add_announcement()
    {
        return view('Announcement/add_announcement');
    }
public function store_announcement()
{
    $a = new AnnouncementModel();
    $user = new UserModel();

    $data = [
        'title'        => request()->getPost('title'),
        'content'      => request()->getPost('content'),
        'posted_by'    => session()->get('user_id'),
        'posted_at'    => date('Y-m-d H:i:s'),
        'posted_until' => request()->getPost('posted_until'),
        'url'          => request()->getPost('url'),
    ];

 
    $a->insert($data);

    //email

    $title     = request()->getPost('title');
    $url       = request()->getPost('url');
    $posted_at = date('Y-m-d H:i:s');
    $content   = request()->getPost('content');

    $email = \Config\Services::email();
    $allUser = $user->findAll();

    $emailSent = 0;
    $emailFailed = 0;

    foreach ($allUser as $u) {
        if(empty($u['email'])){
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
