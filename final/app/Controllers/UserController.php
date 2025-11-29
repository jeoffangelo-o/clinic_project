<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function register()
    {
        return view('Auth/register');
    }

    public function store_user(){
        $user = new UserModel();

        $username =  request()->getPost('username');

        $exist = $user->where('username', $username)->first();

        if($exist){
            return redirect()->to('/register')->with('message', 'Username Already Exists');
        }

        $email =  request()->getPost('email');
        $existingEmail = $user->where('email', $email)->first();

        if($existingEmail){
            return redirect()->to('/register')->with('message', 'Email Already Exists');
        }

        $password =  request()->getPost('password');
        $role =  request()->getPost('role');

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $data = [
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email,
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $user->insert($data);
        
        return redirect()->to('/register')->with('message', 'Registered Successfully');
    }

    public function login()
    {
        return view('/Auth/login');
    }

    public function auth()
    {
        $session = session();
        $user = new UserModel();

        $username =  request()->getPost('username');
        $password =  request()->getPost('password');

        $exist = $user->where('username', $username)->first();

        if(!$exist){
            return redirect()->to('/login')->with('message', 'User does not exist');
        }
        else{
            if(password_verify($password, $exist['password'])){
                $session->set([
                    'user_id' => $exist['user_id'],
                    'username' => $exist['username'],
                    'role' => $exist['role'],
                    'isLoggedIn' => true
                ]);

                return redirect()->to('/');
            }
            else{
                return redirect()->to('/login')->with('message', 'Incorrect Password');
            }
        }
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');

    }

    public function list_user()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('message', 'You do not have permission to access this page');
        }

        $user = new UserModel();
        $search = request()->getGet('search') ?? '';
        $sort = request()->getGet('sort') ?? 'asc';
        
        // Validate sort parameter
        if (!in_array($sort, ['asc', 'desc'])) {
            $sort = 'asc';
        }

        if ($search) {
            $data['user'] = $user
                ->groupStart()
                ->like('username', $search)
                ->orLike('email', $search)
                ->orLike('role', $search)
                ->groupEnd()
                ->orderBy('user_id', $sort)
                ->findAll();
        } else {
            $data['user'] = $user->orderBy('user_id', $sort)->findAll();
        }

        return view('/Admin/list_user', $data);
    }

    public function edit_user($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Allow users to edit their own info, or admins to edit anyone
        if (session()->get('user_id') != $id && session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('message', 'You can only edit your own information');
        }

        $user = new UserModel();

        $data['user'] = $user->find($id);

        if(!$data['user']){
            return redirect()->to('/')->with('message', 'Error: User not found');
        }

        return view('Admin/edit_user', $data);
    }

    public function update_user($id){
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        // Allow users to edit their own info, or admins to edit anyone
        if (session()->get('user_id') != $id && session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('message', 'You can only edit your own information');
        }

        $user = new UserModel();

        $password =  request()->getPost('password');

        $exist = $user->where('user_id', $id)->first();

        if(!$exist){
            return redirect()->to('/')->with('message', 'Error: User not found');
        }

        $verify = password_verify($password, $exist['password']);

        $username =  request()->getPost('username');
        $oldpassword =  request()->getPost('password');
        $newpassword =  request()->getPost('newpassword');
        $email =  request()->getPost('email');
        $role =  request()->getPost('role');

        $data = [
            'username' => $username,
            'email' => $email,
        ];

        // Only admins can change role, users can only edit their own
        if(session()->get('role') === 'admin'){
            $data['role'] = $role;
        }

        if(empty($oldpassword) && empty($newpassword)){
            // Both passwords empty - no password update
        }
        else if(!empty($oldpassword) && !empty($newpassword)){
            // Both passwords provided - verify old and update new
            if(!$verify){
                return redirect()->to('/edit_user/'.$id)->with('message', 'Old Password is Incorrect');
            }
                
            $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);
            $data['password'] = $hashedPassword;
        }
        else{
            // One password provided but not the other - invalid
            return redirect()->to('/edit_user/'.$id)->with('message', 'Put Input both old and new password or keep them both blank');
        }

      
        $user->update($id, $data);
        
        return redirect()->to('/edit_user/'.$id)->with('message', 'User Updated Successfully');
    }

    public function delete_user($id)
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('message', 'Please login to continue');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/')->with('message', 'You do not have permission to access this page');
        }

        $user = new UserModel();

        $user->delete($id);

        return redirect()->to('/list_user')->with('message', 'User Deleted Successfully');
    }
}
