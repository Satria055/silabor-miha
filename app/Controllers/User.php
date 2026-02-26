<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\UnitModel;

class User extends BaseController
{
    protected $userModel;
    protected $unitModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->unitModel = new UnitModel();
    }

    // Cek Hak Akses (RBAC)
    private function checkAccess()
    {
        $role = session()->get('role');
        if ($role != 'super_admin' && $role != 'admin_unit') {
            session()->setFlashdata('error', 'Akses ditolak! Anda tidak memiliki izin mengelola pengguna.');
            header('Location: ' . base_url('/dashboard'));
            exit;
        }
    }

    public function index()
    {
        $this->checkAccess();
        
        // Menangkap parameter filter dari URL
        $unit_id = $this->request->getGet('unit_id');
        $role_filter = $this->request->getGet('role');
        
        $data = [
            'users'       => $this->userModel->getUsersWithUnit($unit_id, $role_filter),
            'units'       => $this->unitModel->findAll(),
            'unit_id'     => $unit_id,
            'role_filter' => $role_filter
        ];
        return view('users/index', $data);
    }

    public function form($id = null)
    {
        $this->checkAccess();

        $data = [
            'units' => $this->unitModel->findAll(),
            'user'  => $id ? $this->userModel->find($id) : null
        ];
        return view('users/form', $data);
    }

    public function save()
    {
        $this->checkAccess();
        $id = $this->request->getPost('id');
        
        $saveData = [
            'unit_id'   => $this->request->getPost('unit_id') ?: null,
            'nama'      => $this->request->getPost('nama'),
            'username'  => $this->request->getPost('username'),
            'role'      => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active'),
        ];

        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $saveData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($id) {
            $this->userModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data pengguna berhasil diperbarui.');
        } else {
            if (empty($password)) {
                return redirect()->back()->with('error', 'Password wajib diisi untuk pengguna baru.')->withInput();
            }
            $this->userModel->insert($saveData);
            session()->setFlashdata('success', 'Pengguna baru berhasil ditambahkan.');
        }

        return redirect()->to('/users');
    }

    public function delete($id)
    {
        $this->checkAccess();
        
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return redirect()->to('/users');
        }

        $this->userModel->delete($id);
        session()->setFlashdata('success', 'Data pengguna berhasil dihapus.');
        return redirect()->to('/users');
    }

    // Fungsi Hapus Massal (Bulk Delete) yang aman
    public function bulkDelete()
    {
        $this->checkAccess();
        $ids = $this->request->getPost('ids');
        $currentUserId = session()->get('id');
        
        if (!empty($ids) && is_array($ids)) {
            // Mencegah akun yang sedang login ikut terhapus secara massal
            if (($key = array_search($currentUserId, $ids)) !== false) {
                unset($ids[$key]); // Copot ID Admin dari antrean eksekusi
                session()->setFlashdata('error', 'Akun Anda dikecualikan dari penghapusan massal untuk keamanan.');
            }

            if (!empty($ids)) {
                $this->userModel->delete($ids);
                session()->setFlashdata('success', count($ids) . ' pengguna berhasil dihapus permanen.');
            }
        } else {
            session()->setFlashdata('error', 'Tidak ada pengguna yang dipilih untuk dihapus.');
        }
        
        return redirect()->to('/users');
    }
}