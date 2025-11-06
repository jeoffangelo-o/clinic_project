<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AnnouncementModel;

class AnnouncementController extends BaseController
{
    public function announcement()
    {
        return view('Announcement/announcement');
    }

    public function add_announcement()
    {
        return view('Announcement/add_announcement');
    }

    public function store_announcement()
    {
        $announce = new AnnouncementModel();

        $data = [
            'title' => request()->getPost('title'),
            'content' => request()->getPost('content'),
            'posted_by' => session()->get('user_id'),
            'posted_at' => date('Y:m:d H-i-s'),
            'posted_until' => request()->getPost('posted_until'),
            'url' => request()->getPost('url'),
        ];

        $announce->insert($data);

        return redirect()->to('/announcement/add')->with('message', 'Announcement is Posted Successfully');
    }
}
