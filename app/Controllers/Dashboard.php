<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\BookingModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $labModel = new LabModel();
        $bookingModel = new BookingModel();
        $userModel = new UserModel();

        // Mengambil 5 aktivitas peminjaman terbaru
        $recent_activities = $bookingModel->select('bookings.*, users.nama as peminjam, laboratories.nama_lab')
                                          ->join('users', 'users.id = bookings.user_id')
                                          ->join('laboratories', 'laboratories.id = bookings.lab_id')
                                          ->orderBy('bookings.updated_at', 'DESC')
                                          ->limit(5)
                                          ->get()->getResult();

        $data = [
            'title' => 'Dashboard - Silabor Miha',
            'nama'  => session()->get('nama'),
            'role'  => session()->get('role'),
            
            'total_lab'         => $labModel->countAllResults(),
            'peminjaman_aktif'  => $bookingModel->where('status', 'disetujui')->countAllResults(),
            'menunggu_validasi' => $bookingModel->where('status', 'pending')->countAllResults(),
            'total_pengguna'    => $userModel->countAllResults(),
            
            'recent_activities' => $recent_activities
        ];

        return view('dashboard/index', $data);
    }
}