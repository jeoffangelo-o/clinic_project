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

    // Insert announcement record
    $a->insert($data);

    // Collect data for email body
    $title     = request()->getPost('title');
    $url       = request()->getPost('url');
    $posted_at = date('Y-m-d H:i:s'); // use actual current time instead of request()->getPost('posted_at')
    $content   = request()->getPost('content');

    $email = \Config\Services::email();
    $allUser = $user->findAll();

    $sentCount = 0; // counter
    $failCount = 0; // counter

    foreach ($allUser as $u) {
    

            // Prepare message
            $message = '
                <img src="' . $url . '" alt="image" style="height: 100px; width: 80px;">
                <h3>' . $title . '</h3>
                <p>' . $posted_at . '</p><br>
                <h2>' . $content . '</h2>
            ';

            // Set up email
            $email->setTo($u['email']);
            $email->setFrom('jeoffgbanaria@gmail.com', 'CSPC Clinic');
            $email->setSubject('ANNOUNCEMENT!!!');
            $email->setMessage($message);

            // Try sending
            if ($email->send()) {
                $sentCount++;
            } else {
                $failCount++;
                log_message('error', 'Email failed to send to: ' . $u['email']);
                log_message('error', $email->printDebugger(['headers']));
            }

            // Clear recipients before next loop
            $email->clear(true);
        
    }

    // Feedback message
    $message = "✅ Announcement posted successfully!<br>"
             . "Emails sent: <strong>{$sentCount}</strong><br>"
             . "Failed: <strong>{$failCount}</strong>";

    return redirect()->to('/announcement/add')->with('message', $message);

}
}
