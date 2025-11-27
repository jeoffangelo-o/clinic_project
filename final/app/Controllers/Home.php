<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        // Pass role-based data to view
        $data['role'] = session()->get('role');
        $data['username'] = session()->get('username');
        $data['isLoggedIn'] = session()->get('isLoggedIn');
        
        return view('dashboard', $data);
    }
}