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

            // Professional HTML email template
            $message = '
            <!DOCTYPE html>
            <html style="margin: 0; padding: 0;">
            <head>
                <meta charset="utf-8">
                <style>
                    body { font-family: Segoe UI, Arial, sans-serif; margin: 0; padding: 0; background-color: #f5f5f5; }
                    .email-container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                    .header { background: linear-gradient(135deg, #206bc4 0%, #1a54a0 100%); padding: 30px; text-align: center; color: white; }
                    .header-logo { font-size: 28px; font-weight: bold; margin-bottom: 10px; }
                    .header-subtitle { font-size: 14px; opacity: 0.9; }
                    .content { padding: 30px; }
                    .greeting { font-size: 18px; color: #206bc4; font-weight: 600; margin-bottom: 20px; }
                    .announcement-box { background: #f8f9fa; border-left: 4px solid #206bc4; padding: 20px; margin: 20px 0; border-radius: 4px; }
                    .announcement-title { font-size: 20px; color: #206bc4; font-weight: 600; margin-bottom: 10px; }
                    .announcement-date { color: #999; font-size: 13px; margin-bottom: 15px; }
                    .announcement-image { text-align: center; margin: 20px 0; }
                    .announcement-image img { max-width: 100%; height: auto; border-radius: 6px; }
                    .announcement-content { line-height: 1.6; color: #333; font-size: 14px; }
                    .divider { border-top: 1px solid #e0e0e0; margin: 20px 0; }
                    .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #e0e0e0; }
                    .footer-logo { color: #206bc4; font-weight: 600; font-size: 14px; margin-bottom: 5px; }
                    .button { display: inline-block; background-color: #206bc4; color: white; padding: 12px 25px; text-decoration: none; border-radius: 4px; margin: 15px 0; font-weight: 600; }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <!-- Header -->
                    <div class="header">
                        <div class="header-logo">🏥 CSPC</div>
                        <div class="header-subtitle">Clinic Management System</div>
                    </div>

                    <!-- Content -->
                    <div class="content">
                        <div class="greeting">Hello ' . esc($u['username']) . ',</div>
                        
                        <p>We have an important announcement for you:</p>

                        <div class="announcement-box">
                            ' . (!empty($url) ? '<div class="announcement-image"><img src="' . esc($url) . '" alt="announcement" style="max-height: 200px;"></div>' : '') . '
                            <div class="announcement-title">' . esc($title) . '</div>
                            <div class="announcement-date">Posted on ' . date('F d, Y g:i A', strtotime($posted_at)) . '</div>
                            <div class="announcement-content">' . nl2br(esc($content)) . '</div>
                        </div>

                        <div class="divider"></div>

                        <p style="color: #666; font-size: 13px;">If you have any questions regarding this announcement, please contact our clinic staff.</p>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <div class="footer-logo">CSPC Clinic</div>
                        <p>Professional Medical Services Center</p>
                        <p style="margin-top: 10px; padding-top: 10px; border-top: 1px solid #e0e0e0;">
                            © ' . date('Y') . ' CSPC Clinic. All rights reserved.
                        </p>
                    </div>
                </div>
            </body>
            </html>
            ';

            $email->setTo($u['email']);
            $email->setFrom('jeoffgbanaria@gmail.com', 'CSPC Clinic');
            $email->setSubject('📢 New Announcement: ' . substr($title, 0, 40));
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

    public function edit_announcement($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $announce = new AnnouncementModel();
        $data['announce'] = $announce->find($id);

        if(empty($data['announce'])){
            return redirect()->to('/announcement')->with('message', 'Announcement not found');
        }

        return view('Announcement/edit_announcement', $data);
    }

    public function update_announcement($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $announce = new AnnouncementModel();
        
        $title = request()->getPost('title');
        $content = request()->getPost('content');
        $posted_until = request()->getPost('posted_until');
        $url = request()->getPost('url');

        if(empty($posted_until)){
            return redirect()->to('/announcement/edit/' . $id)->with('message', 'Error: posted_until date is required');
        }

        $announcement_data = [
            'title' => $title,
            'content' => $content,
            'posted_until' => $posted_until,
            'url' => $url
        ];

        $announce->update($id, $announcement_data);

        return redirect()->to('/announcement')->with('message', 'Announcement Updated Successfully');
    }

    public function delete_announcement($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        $announce = new AnnouncementModel();
        $announce->delete($id);

        return redirect()->to('/announcement')->with('message', 'Announcement Deleted Successfully');
    }

}
