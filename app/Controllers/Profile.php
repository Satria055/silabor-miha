<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $id = session()->get('id');
        
        // Ambil data user yang sedang login
        $data = [
            'user' => $userModel->find($id)
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $id = session()->get('id');
        $userModel = new UserModel();
        $user = $userModel->find($id);

        // 1. Validasi Input Dasar
        // Menggunakan kurung siku [] untuk parameter validasi
        $rules = [
            'nama'     => 'required|min_length[3]', 
            'username' => "required|alpha_numeric|is_unique[users.username,id,{$id}]",
        ];

        // 2. Validasi Password (Jika diisi)
        $passwordBaru = $this->request->getPost('password_baru');
        
        if (!empty($passwordBaru)) {
            $rules['password_lama']       = 'required';
            $rules['password_baru']       = 'min_length[5]'; // min_length[5]
            $rules['konfirmasi_password'] = 'matches[password_baru]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // 3. Cek Password Lama (Security Check)
        if (!empty($passwordBaru)) {
            $passwordLama = $this->request->getPost('password_lama');
            if (!password_verify($passwordLama, $user->password)) {
                return redirect()->back()->withInput()->with('error', 'Password lama yang Anda masukkan salah.');
            }
        }

        // 4. Siapkan Data Update
        $dataUpdate = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
        ];

        // Hanya update password jika diisi
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_DEFAULT);
        }

        // 5. Eksekusi Update
        $userModel->update($id, $dataUpdate);

        // Update Session nama jika berubah
        session()->set('nama', $dataUpdate['nama']);

        session()->setFlashdata('success', 'Profil berhasil diperbarui.');
        return redirect()->to('/profile');
    }
}