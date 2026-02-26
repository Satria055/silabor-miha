<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'user_id', 'lab_id', 'jenis_peminjaman', 'tanggal_mulai', 'tanggal_selesai',
        'waktu_mulai', 'waktu_selesai', 'mata_pelajaran', 'kelas', 'guru_pengajar',
        'keperluan', 'penanggung_jawab', 'file_surat', 'status', 'catatan_admin'
    ];
    protected $useTimestamps    = true;

    // Upgrade: Fungsi mengecek overlap hari DAN overlap jam sekaligus
    public function checkOverlap($lab_id, $tgl_mulai, $tgl_selesai, $wkt_mulai, $wkt_selesai, $exclude_id = null)
    {
        $builder = $this->where('lab_id', $lab_id)
                    ->whereIn('status', ['pending', 'disetujui'])
                    // 1. Cek rentang TANGGAL
                    ->groupStart()
                        ->where('tanggal_mulai <=', $tgl_selesai)
                        ->where('tanggal_selesai >=', $tgl_mulai)
                    ->groupEnd()
                    // 2. Cek rentang WAKTU
                    ->groupStart()
                        ->where('waktu_mulai <', $wkt_selesai)
                        ->where('waktu_selesai >', $wkt_mulai)
                    ->groupEnd();
        
        // Jika sedang edit, jangan anggap diri sendiri sebagai bentrok
        if ($exclude_id) {
            $builder->where('id !=', $exclude_id);
        }

        return $builder->countAllResults() > 0;
    }

    // Fungsi untuk mengambil data booking lengkap beserta nama lab dan user
    public function getBookingsWithDetails($user_id = null, $lab_id = null, $start_date = null, $end_date = null)
    {
        $builder = $this->select('bookings.*, laboratories.nama_lab, users.nama as peminjam')
                        ->join('laboratories', 'laboratories.id = bookings.lab_id')
                        ->join('users', 'users.id = bookings.user_id')
                        ->orderBy('bookings.created_at', 'DESC');
        
        if ($user_id) {
            $builder->where('bookings.user_id', $user_id);
        }
        if ($lab_id) {
            $builder->where('bookings.lab_id', $lab_id);
        }
        if ($start_date) {
            $builder->where('bookings.tanggal_mulai >=', $start_date);
        }
        if ($end_date) {
            $builder->where('bookings.tanggal_mulai <=', $end_date);
        }
        
        return $builder->get()->getResult();
    }
}