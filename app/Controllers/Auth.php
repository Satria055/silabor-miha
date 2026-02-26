<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung arahkan ke dashboard
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        return view('login');
    }

    public function process()
    {
        $users = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $users->where('username', $username)->first();

        if ($user) {
            // Verifikasi hash password
            if (password_verify($password, $user->password)) {
                
                // Jika sukses, simpan data ke session
                $sessionData = [
                    'id'        => $user->id,
                    'username'  => $user->username,
                    'nama'      => $user->nama,
                    'role'      => $user->role,
                    'unit_id'   => $user->unit_id,
                    'logged_in' => TRUE
                ];
                session()->set($sessionData);
                
                return redirect()->to('/dashboard');
            } else {
                session()->setFlashdata('error', 'Password yang Anda masukkan salah.');
                return redirect()->to('/login');
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan di sistem.');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        // Menghapus hanya data spesifik user, bukan keseluruhan session sistem
        session()->remove(['id', 'username', 'nama', 'role', 'unit_id', 'logged_in']);
        session()->setFlashdata('success', 'Anda telah berhasil keluar dari sistem.');
        return redirect()->to('/login');
    }
}