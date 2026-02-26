<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan user sudah login (lapis pertama)
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek apakah ada batasan role pada rute ini (lapis kedua)
        if ($arguments !== null) {
            $userRole = session()->get('role');
            
            // Jika role user saat ini TIDAK ADA di dalam array argument rute, tendang keluar!
            if (!in_array($userRole, $arguments)) {
                session()->setFlashdata('error', 'Akses Ditolak! Anda tidak memiliki otoritas untuk mengakses halaman tersebut.');
                return redirect()->to('/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah request
    }
}