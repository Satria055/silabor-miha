<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\LabModel;
use App\Models\InventoryModel;

class Reports extends BaseController
{
    protected $bookingModel;
    protected $labModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->labModel = new LabModel();
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $data = [
            'labs' => $this->labModel->where('status', 'aktif')->findAll()
        ];
        return view('reports/index', $data);
    }

    public function printBooking()
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $lab_id = $this->request->getGet('lab_id');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');
        $status = $this->request->getGet('status');

        // Query Builder untuk Laporan Booking (Join ke tabel laboratories)
        $builder = $this->bookingModel->builder();
        $builder->select('bookings.*, laboratories.nama_lab, users.nama as nama_peminjam');
        $builder->join('laboratories', 'laboratories.id = bookings.lab_id'); 
        $builder->join('users', 'users.id = bookings.user_id');

        if ($lab_id) {
            $builder->where('bookings.lab_id', $lab_id);
        }
        if ($start_date) {
            $builder->where('bookings.tanggal_mulai >=', $start_date);
        }
        if ($end_date) {
            $builder->where('bookings.tanggal_mulai <=', $end_date);
        }
        if ($status) {
            $builder->where('bookings.status', $status);
        } else {
            $builder->whereIn('bookings.status', ['disetujui', 'selesai']);
        }

        $data = [
            'bookings' => $builder->orderBy('tanggal_mulai', 'ASC')->get()->getResult(),
            'filter' => [
                'lab' => $lab_id ? $this->labModel->find($lab_id)->nama_lab : 'Semua Laboratorium',
                'start' => $start_date,
                'end' => $end_date
            ]
        ];

        return view('reports/print_booking', $data);
    }

    public function printInventory()
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $lab_id = $this->request->getGet('lab_id');
        $kondisiInput = $this->request->getGet('kondisi'); // input dari view (baik/rusak_ringan/rusak_berat)

        // Mapping filter view ke logika Model (baik/rusak)
        $filterKondisiModel = null;
        if ($kondisiInput == 'baik') {
            $filterKondisiModel = 'baik';
        } elseif (strpos($kondisiInput, 'rusak') !== false) {
            $filterKondisiModel = 'rusak';
        }

        // Menggunakan Method Canggih dari InventoryModel
        $items = $this->inventoryModel->getInventoryWithLab($lab_id, null, $filterKondisiModel);

        $data = [
            'items' => $items,
            'filter' => [
                'lab' => $lab_id ? $this->labModel->find($lab_id)->nama_lab : 'Semua Laboratorium',
                'kondisi' => $kondisiInput ?: 'Semua Kondisi'
            ]
        ];

        return view('reports/print_inventory', $data);
    }
}