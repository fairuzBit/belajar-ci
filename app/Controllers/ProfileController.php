<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('form');
    }

    public function index(): string
    {
        $username = session()->get('username');
        $user = $this->userModel->where('username', $username)->first();

        return view('v_profile', [
            'user' => $user
        ]);
    }

    public function update()
    {
        $username = session()->get('username');
        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->to('profile')->with('error', 'User tidak ditemukan');
        }

        $rules = [
            'email' => 'required|valid_email'
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[7]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $data = [
            'email' => $this->request->getPost('email')
        ];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($user['id'], $data);

        return redirect()->to('profile')->with('success', 'Profile berhasil diperbarui');
    }
}
