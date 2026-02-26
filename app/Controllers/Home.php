<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\UnitModel;
use App\Models\BookingModel;
use CodeIgniter\I18n\Time;

class Home extends BaseController
{
    public function index()
    {
        $labModel = new LabModel();
        $unitModel = new UnitModel();
        $bookingModel = new BookingModel();

        $data = [
            'total_lab'  => $labModel->where('status', 'aktif')->countAllResults(),
            'total_unit' => $unitModel->countAllResults(),
            'total_kegiatan' => $bookingModel->where('status', 'disetujui')->countAllResults()
        ];

        return view('home/index', $data);
    }

    // Fungsi untuk menampilkan halaman kalender
    public function jadwal()
    {
        $labModel = new LabModel();
        $data = [
            // Ambil semua lab yang aktif untuk dimasukkan ke dropdown filter
            'labs' => $labModel->where('status', 'aktif')->findAll()
        ];
        return view('jadwal', $data);
    }

    // =========================================================================
    // FUNGSI API KALENDER (DIPERBAIKI DENGAN LOGIKA SPLIT HARIAN)
    // =========================================================================
    public function getEvents()
    {
        $db = \Config\Database::connect();
        
        // Tangkap parameter lab_id dari dropdown filter (jika ada)
        $lab_id = $this->request->getGet('lab_id');
        
        $builder = $db->table('bookings')
                      ->select('bookings.*, laboratories.nama_lab, users.nama as peminjam')
                      ->join('laboratories', 'laboratories.id = bookings.lab_id')
                      ->join('users', 'users.id = bookings.user_id')
                      ->where('bookings.status', 'disetujui');
                      
        // Jika pengguna memilih Lab spesifik di dropdown, filter datanya
        if (!empty($lab_id)) {
            $builder->where('bookings.lab_id', $lab_id);
        }

        $bookings = $builder->get()->getResult();
        $events = [];

        foreach ($bookings as $b) {
            // --- LOGIKA UTAMA: MEMECAH RENTANG TANGGAL ---
            // Mengubah rentang panjang (misal 2-7 Maret) menjadi kotak-kotak harian terpisah
            
            $begin = new \DateTime($b->tanggal_mulai);
            $end   = new \DateTime($b->tanggal_selesai ?: $b->tanggal_mulai);
            
            // Tambah 1 hari agar loop mencakup hari terakhir (karena DatePeriod bersifat eksklusif di akhir)
            $end = $end->modify('+1 day'); 

            $interval = new \DateInterval('P1D'); // Interval 1 Hari
            $period   = new \DatePeriod($begin, $interval, $end);

            // Tentukan Warna Event (Navy untuk KBM, Ungu untuk Kegiatan Khusus)
            $color = ($b->jenis_peminjaman == 'KBM') ? '#1e3a8a' : '#7e22ce';

            // Loop setiap hari dalam rentang booking
            foreach ($period as $dt) {
                $currentDate = $dt->format('Y-m-d');
                
                // Gabungkan Tanggal Loop + Jam Asli dari Database
                // Hasil: "2026-03-02T07:00:00" sampai "2026-03-02T14:05:00"
                $startDateTime = $currentDate . 'T' . $b->waktu_mulai;
                $endDateTime   = $currentDate . 'T' . $b->waktu_selesai;

                // Siapkan Judul Event
                $title = $b->nama_lab . ' - ' . ($b->jenis_peminjaman == 'KBM' ? $b->mata_pelajaran : $b->keperluan);

                $events[] = [
                    'id' => $b->id,
                    'title' => $title,
                    'start' => $startDateTime,
                    'end'   => $endDateTime,
                    'allDay' => false, // Agar TIDAK all-day (blokir seharian)
                    
                    // Styling Warna
                    'backgroundColor' => $color,
                    'borderColor' => $color,

                    // Data tambahan untuk Popup Detail (Swal)
                    'extendedProps' => [
                        'peminjam' => $b->peminjam,
                        'jenis'    => $b->jenis_peminjaman,
                        'waktu'    => substr($b->waktu_mulai, 0, 5) . ' - ' . substr($b->waktu_selesai, 0, 5)
                    ]
                ];
            }
        }

        return $this->response->setJSON($events);
    }
}