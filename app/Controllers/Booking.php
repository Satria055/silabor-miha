<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\LabModel;
use CodeIgniter\I18n\Time; 

class Booking extends BaseController
{
    protected $bookingModel;
    protected $labModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->labModel = new LabModel();
        
        // Load Helper Telegram
        helper('telegram'); 
    }

    private function getSystemSettings()
    {
        $db = \Config\Database::connect();
        $settings = ['jam_buka' => '07:00', 'jam_tutup' => '16:00', 'lead_time' => 1]; 
        if ($db->tableExists('settings')) {
            $query = $db->table('settings')->get()->getResult();
            foreach($query as $row) {
                $settings[$row->setting_key] = $row->setting_value;
            }
        }
        return (object) $settings;
    }

    public function index()
    {
        $role = session()->get('role');
        $user_id = session()->get('id');
        
        $lab_id = $this->request->getGet('lab_id');
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $bookings = ($role == 'super_admin' || $role == 'admin_lab') 
                    ? $this->bookingModel->getBookingsWithDetails(null, $lab_id, $start_date, $end_date) 
                    : $this->bookingModel->getBookingsWithDetails($user_id, $lab_id, $start_date, $end_date);

        $data = [
            'bookings'     => $bookings,
            'labs'         => $this->labModel->where('status', 'aktif')->findAll(),
            'selected_lab' => $lab_id,
            'start_date'   => $start_date,
            'end_date'     => $end_date
        ];
        
        return view('bookings/index', $data);
    }

    public function formKbm()
    {
        $db = \Config\Database::connect();
        $role = session()->get('role');
        $unit_id = session()->get('unit_id');
        
        $jamQuery = $db->table('jam_pelajaran')
                       ->select('jam_pelajaran.*, units.nama_unit')
                       ->join('units', 'units.id = jam_pelajaran.unit_id', 'left');
        
        if ($role != 'super_admin' && $role != 'admin_lab' && $unit_id) {
            $jamQuery->where('jam_pelajaran.unit_id', $unit_id);
        }

        $rawData = $jamQuery->orderBy('units.nama_unit', 'ASC')->orderBy('jam_pelajaran.waktu_mulai', 'ASC')->get()->getResult();

        $groupedJam = [];
        foreach ($rawData as $row) {
            $unitName = $row->nama_unit ?? 'Global';
            $groupedJam[$unitName][] = $row;
        }
        
        $data = [
            'labs'        => $this->labModel->where('status', 'aktif')->findAll(),
            'grouped_jam' => $groupedJam,
            'sys_setting' => $this->getSystemSettings(), 
            'user_role'   => $role
        ];
        
        return view('bookings/form_kbm', $data);
    }

    public function formKhusus()
    {
        $data = [
            'labs'        => $this->labModel->where('status', 'aktif')->findAll(),
            'sys_setting' => $this->getSystemSettings(), 
            'user_role'   => session()->get('role')
        ];
        return view('bookings/form_khusus', $data);
    }

    public function saveKbm()
    {
        $id = $this->request->getPost('id'); 
        $lab_id = $this->request->getPost('lab_id');
        $tanggal = $this->request->getPost('tanggal_mulai');
        $jam_id = $this->request->getPost('jam_pelajaran_id');
        
        $role = session()->get('role');
        $isAdmin = in_array($role, ['super_admin', 'admin_lab']);
        $sys = $this->getSystemSettings();

        // Validasi Jam Pengajuan & Lead Time
        if (!$isAdmin) {
            $currentTime = Time::now('Asia/Jakarta')->format('H:i');
            $jamBuka = substr($sys->jam_buka, 0, 5);
            $jamTutup = substr($sys->jam_tutup, 0, 5);
            
            if ($currentTime < $jamBuka || $currentTime > $jamTutup) {
                session()->setFlashdata('error', "Sistem ditolak! Anda mengajukan pada pukul {$currentTime}. Layanan peminjaman hanya dibuka pukul {$jamBuka} - {$jamTutup} WIB.");
                return redirect()->back()->withInput();
            }

            $minDateAllowed = date('Y-m-d', strtotime("+" . $sys->lead_time . " days"));
            if ($tanggal < $minDateAllowed) {
                session()->setFlashdata('error', "Pemesanan ditolak! Minimal pemesanan adalah H-{$sys->lead_time} sebelum pemakaian.");
                return redirect()->back()->withInput();
            }
        }

        $db = \Config\Database::connect();
        $jam = $db->table('jam_pelajaran')->where('id', $jam_id)->get()->getRow();

        if (!$jam) {
            session()->setFlashdata('error', 'Pilihan jam pelajaran tidak valid.');
            return redirect()->back()->withInput();
        }

        $waktu_mulai = $jam->waktu_mulai;
        $waktu_selesai = $jam->waktu_selesai;

        // Validasi Bentrok Backend
        if ($this->bookingModel->checkOverlap($lab_id, $tanggal, $tanggal, $waktu_mulai, $waktu_selesai, $id)) {
            session()->setFlashdata('error', 'Maaf, laboratorium tersebut sudah dipesan oleh orang lain pada jam tersebut.');
            return redirect()->back()->withInput();
        }

        $saveData = [
            'lab_id'           => $lab_id,
            'jenis_peminjaman' => 'KBM',
            'tanggal_mulai'    => $tanggal,
            'tanggal_selesai'  => $tanggal,
            'waktu_mulai'      => $waktu_mulai,
            'waktu_selesai'    => $waktu_selesai,
            'mata_pelajaran'   => $this->request->getPost('mata_pelajaran'),
            'kelas'            => $this->request->getPost('kelas'),
            'guru_pengajar'    => $this->request->getPost('guru_pengajar'),
        ];

        if ($id) {
            $this->bookingModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data peminjaman KBM berhasil diperbarui.');
        } else {
            $saveData['user_id'] = session()->get('id');
            $saveData['status']  = 'pending';
            $this->bookingModel->insert($saveData);
            
            // --- 1. NOTIFIKASI WEB SISTEM ---
            if ($db->tableExists('notifications')) {
                $admins = $db->table('users')->whereIn('role', ['super_admin', 'admin_lab'])->get()->getResult();
                $notifData = [];
                $namaPemohon = session()->get('nama');
                foreach($admins as $admin) {
                    $notifData[] = [
                        'user_id' => $admin->id, 'judul' => 'Pengajuan KBM Baru',
                        'pesan' => "{$namaPemohon} mengajukan peminjaman lab untuk KBM.",
                        'link' => '/bookings', 'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                if(!empty($notifData)) $db->table('notifications')->insertBatch($notifData);
            }

            // --- 2. NOTIFIKASI TELEGRAM ---
            $lab = $this->labModel->find($lab_id);
            $namaLab = $lab ? $lab->nama_lab : 'Lab';
            $tglFormat = date('d M Y', strtotime($tanggal));
            
            $teleMsg = "🔔 <b>PENGAJUAN KBM BARU</b>\n\n";
            $teleMsg .= "👤 <b>Pemohon:</b> " . session()->get('nama') . "\n";
            $teleMsg .= "🏫 <b>Lab:</b> {$namaLab}\n";
            $teleMsg .= "📅 <b>Tanggal:</b> {$tglFormat}\n";
            $teleMsg .= "⏰ <b>Waktu:</b> " . substr($waktu_mulai,0,5) . " - " . substr($waktu_selesai,0,5) . " WIB\n";
            $teleMsg .= "📚 <b>Mapel/Kelas:</b> {$saveData['mata_pelajaran']} ({$saveData['kelas']})\n\n";
            $teleMsg .= "Segera cek dashboard admin untuk proses persetujuan.";
            send_telegram_notif($teleMsg);

            session()->setFlashdata('success', 'Pengajuan peminjaman KBM berhasil dikirim.');
        }

        return redirect()->to('/bookings');
    }

    public function saveKhusus()
    {
        $id = $this->request->getPost('id');
        $lab_id = $this->request->getPost('lab_id');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai') ?: $tanggal_mulai; 
        $waktu_mulai = $this->request->getPost('waktu_mulai');
        $waktu_selesai = $this->request->getPost('waktu_selesai');

        $role = session()->get('role');
        $isAdmin = in_array($role, ['super_admin', 'admin_lab']);
        $sys = $this->getSystemSettings();

        // Validasi Jam & Lead Time
        if (!$isAdmin) {
            $currentTime = Time::now('Asia/Jakarta')->format('H:i');
            $jamBuka = substr($sys->jam_buka, 0, 5);
            $jamTutup = substr($sys->jam_tutup, 0, 5);
            
            if ($currentTime < $jamBuka || $currentTime > $jamTutup) {
                session()->setFlashdata('error', "Sistem ditolak! Anda mengajukan pada pukul {$currentTime}. Layanan peminjaman hanya dibuka pukul {$jamBuka} - {$jamTutup} WIB.");
                return redirect()->back()->withInput();
            }

            $minDateAllowed = date('Y-m-d', strtotime("+" . $sys->lead_time . " days"));
            if ($tanggal_mulai < $minDateAllowed) {
                session()->setFlashdata('error', "Pemesanan ditolak! Minimal pemesanan adalah H-{$sys->lead_time} sebelum pemakaian.");
                return redirect()->back()->withInput();
            }

            if ($waktu_mulai < $jamBuka || $waktu_selesai > $jamTutup) {
                session()->setFlashdata('error', "Pemakaian lab hanya diizinkan pada jam operasional ({$jamBuka} - {$jamTutup}).");
                return redirect()->back()->withInput();
            }
        }

        // Cek Bentrok
        if ($this->bookingModel->checkOverlap($lab_id, $tanggal_mulai, $tanggal_selesai, $waktu_mulai, $waktu_selesai, $id)) {
            session()->setFlashdata('error', 'Jadwal bentrok! Ruangan sudah dipakai oleh orang lain pada tanggal/jam tersebut.');
            return redirect()->back()->withInput();
        }

        $saveData = [
            'lab_id'           => $lab_id,
            'jenis_peminjaman' => 'Khusus',
            'tanggal_mulai'    => $tanggal_mulai,
            'tanggal_selesai'  => $tanggal_selesai,
            'waktu_mulai'      => $waktu_mulai,
            'waktu_selesai'    => $waktu_selesai,
            'keperluan'        => $this->request->getPost('keperluan'),
            'penanggung_jawab' => $this->request->getPost('penanggung_jawab'),
        ];

        $fileSurat = $this->request->getFile('file_surat');
        if ($fileSurat && $fileSurat->isValid() && !$fileSurat->hasMoved()) {
            $namaFile = $fileSurat->getRandomName();
            $fileSurat->move('uploads/surat_peminjaman', $namaFile);
            $saveData['file_surat'] = $namaFile;
            if ($existing && $existing->file_surat && file_exists('uploads/surat_peminjaman/' . $existing->file_surat)) {
                unlink('uploads/surat_peminjaman/' . $existing->file_surat);
            }
        }

        if ($id) {
            $this->bookingModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data Kegiatan Khusus berhasil diperbarui.');
        } else {
            $saveData['user_id'] = session()->get('id');
            $saveData['status']  = 'pending';
            $this->bookingModel->insert($saveData);
            
            $db = \Config\Database::connect();
            
            // --- 1. NOTIFIKASI WEB SISTEM ---
            if ($db->tableExists('notifications')) {
                $admins = $db->table('users')->whereIn('role', ['super_admin', 'admin_lab'])->get()->getResult();
                $notifData = [];
                $namaPemohon = session()->get('nama');
                foreach($admins as $admin) {
                    $notifData[] = [
                        'user_id' => $admin->id, 'judul' => 'Pengajuan Lab Khusus',
                        'pesan' => "{$namaPemohon} mengajukan peminjaman lab (Kegiatan Khusus).",
                        'link' => '/bookings', 'created_at' => date('Y-m-d H:i:s')
                    ];
                }
                if(!empty($notifData)) $db->table('notifications')->insertBatch($notifData);
            }

            // --- 2. NOTIFIKASI TELEGRAM ---
            $lab = $this->labModel->find($lab_id);
            $namaLab = $lab ? $lab->nama_lab : 'Lab';
            $tglFormat = date('d M Y', strtotime($tanggal_mulai));
            
            $teleMsg = "📣 <b>PENGAJUAN KEGIATAN KHUSUS</b>\n\n";
            $teleMsg .= "👤 <b>PJ:</b> {$saveData['penanggung_jawab']} (" . session()->get('nama') . ")\n";
            $teleMsg .= "🏫 <b>Lab:</b> {$namaLab}\n";
            $teleMsg .= "📅 <b>Tgl:</b> {$tglFormat}\n";
            $teleMsg .= "⏰ <b>Waktu:</b> " . substr($waktu_mulai,0,5) . " - " . substr($waktu_selesai,0,5) . " WIB\n";
            $teleMsg .= "📝 <b>Kegiatan:</b> {$saveData['keperluan']}\n\n";
            $teleMsg .= "Cek dashboard admin untuk surat pengajuan dan persetujuan.";
            send_telegram_notif($teleMsg);

            session()->setFlashdata('success', 'Pengajuan Kegiatan Khusus berhasil dikirim.');
        }

        return redirect()->to('/bookings');
    }

    public function edit($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) return redirect()->to('/bookings')->with('error', 'Data tidak ditemukan.');

        $db = \Config\Database::connect();
        $unit_id = session()->get('unit_id');
        $role = session()->get('role');

        $data = [
            'booking'     => $booking,
            'labs'        => $this->labModel->where('status', 'aktif')->findAll(),
            'sys_setting' => $this->getSystemSettings(),
            'user_role'   => $role
        ];

        if ($booking->jenis_peminjaman == 'KBM') {
            $jamQuery = $db->table('jam_pelajaran')
                           ->select('jam_pelajaran.*, units.nama_unit')
                           ->join('units', 'units.id = jam_pelajaran.unit_id', 'left');
            if ($role != 'super_admin' && $role != 'admin_lab' && $unit_id) {
                $jamQuery->where('jam_pelajaran.unit_id', $unit_id);
            }
            $rawData = $jamQuery->orderBy('units.nama_unit', 'ASC')->orderBy('jam_pelajaran.waktu_mulai', 'ASC')->get()->getResult();
            $groupedJam = [];
            foreach ($rawData as $row) {
                $unitName = $row->nama_unit ?? 'Global';
                $groupedJam[$unitName][] = $row;
            }
            $data['grouped_jam'] = $groupedJam;
            return view('bookings/form_kbm', $data);
        } else {
            return view('bookings/form_khusus', $data);
        }
    }

    public function approve($id)
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');
        $this->bookingModel->update($id, ['status' => 'disetujui']);
        
        $booking = $this->bookingModel->find($id);
        $db = \Config\Database::connect();
        if ($db->tableExists('notifications')) {
            $db->table('notifications')->insert([
                'user_id' => $booking->user_id, 'judul' => 'Peminjaman Disetujui',
                'pesan' => 'Pengajuan laboratorium Anda telah disetujui.', 'link' => '/bookings', 'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // --- NOTIFIKASI TELEGRAM LOG ADMIN ---
        $lab = $this->labModel->find($booking->lab_id);
        $namaLab = $lab ? $lab->nama_lab : 'Lab';
        $tglFormat = date('d M Y', strtotime($booking->tanggal_mulai));
        $adminName = session()->get('nama');
        
        $teleMsg = "✅ <b>DISETUJUI OLEH {$adminName}</b>\n\n";
        $teleMsg .= "Peminjaman <b>{$namaLab}</b> pada <b>{$tglFormat}</b> untuk kegiatan <b>{$booking->jenis_peminjaman}</b> telah disetujui dan masuk ke jadwal utama.";
        send_telegram_notif($teleMsg);

        session()->setFlashdata('success', 'Peminjaman berhasil DISETUJUI.');
        return redirect()->to('/bookings');
    }

    public function reject($id)
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');
        $this->bookingModel->update($id, ['status' => 'ditolak']);
        
        $booking = $this->bookingModel->find($id);
        $db = \Config\Database::connect();
        if ($db->tableExists('notifications')) {
            $db->table('notifications')->insert([
                'user_id' => $booking->user_id, 'judul' => 'Peminjaman Ditolak',
                'pesan' => 'Maaf, pengajuan laboratorium Anda ditolak.', 'link' => '/bookings', 'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        // --- NOTIFIKASI TELEGRAM LOG ADMIN ---
        $lab = $this->labModel->find($booking->lab_id);
        $namaLab = $lab ? $lab->nama_lab : 'Lab';
        $tglFormat = date('d M Y', strtotime($booking->tanggal_mulai));
        $adminName = session()->get('nama');
        
        $teleMsg = "❌ <b>DITOLAK OLEH {$adminName}</b>\n\n";
        $teleMsg .= "Peminjaman <b>{$namaLab}</b> pada <b>{$tglFormat}</b> untuk kegiatan <b>{$booking->jenis_peminjaman}</b> telah ditolak.";
        send_telegram_notif($teleMsg);

        session()->setFlashdata('success', 'Peminjaman telah DITOLAK.');
        return redirect()->to('/bookings');
    }

    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        if (!empty($ids) && is_array($ids)) {
            foreach ($ids as $id) {
                $booking = $this->bookingModel->find($id);
                if ($booking && $booking->file_surat && file_exists('uploads/surat_peminjaman/' . $booking->file_surat)) {
                    unlink('uploads/surat_peminjaman/' . $booking->file_surat);
                }
            }
            $this->bookingModel->delete($ids);
            session()->setFlashdata('success', count($ids) . ' data peminjaman berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Tidak ada data yang dipilih.');
        }
        return redirect()->to('/bookings');
    }

    public function checkConflictApi()
    {
        $jenis = $this->request->getPost('jenis');
        $lab_id = $this->request->getPost('lab_id');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai') ?: $tanggal_mulai;
        $booking_id = $this->request->getPost('booking_id');

        $waktu_mulai = $this->request->getPost('waktu_mulai');
        $waktu_selesai = $this->request->getPost('waktu_selesai');

        $db = \Config\Database::connect();

        if ($jenis == 'kbm') {
            $jam_id = $this->request->getPost('jam_pelajaran_id');
            $jam = $db->table('jam_pelajaran')->where('id', $jam_id)->get()->getRow();
            if ($jam) {
                $waktu_mulai = $jam->waktu_mulai;
                $waktu_selesai = $jam->waktu_selesai;
            } else {
                return $this->response->setJSON(['status' => 'incomplete', 'csrfHash' => csrf_hash()]);
            }
        }

        if (!$lab_id || !$tanggal_mulai || !$waktu_mulai || !$waktu_selesai) {
            return $this->response->setJSON(['status' => 'incomplete', 'csrfHash' => csrf_hash()]);
        }

        $builder = $db->table('bookings')
            ->where('lab_id', $lab_id)
            ->whereIn('status', ['pending', 'disetujui']) 
            ->where('tanggal_mulai <=', $tanggal_selesai)
            ->where('tanggal_selesai >=', $tanggal_mulai)
            ->groupStart()
                ->where('waktu_mulai <', $waktu_selesai)
                ->where('waktu_selesai >', $waktu_mulai)
            ->groupEnd();
            
        if ($booking_id) {
            $builder->where('id !=', $booking_id);
        }

        if ($builder->countAllResults() > 0) {
            return $this->response->setJSON([
                'status' => 'conflict', 
                'message' => 'Ruangan sudah dibooking oleh pihak lain pada jam & tanggal tersebut!', 
                'csrfHash' => csrf_hash()
            ]);
        }

        return $this->response->setJSON(['status' => 'available', 'csrfHash' => csrf_hash()]);
    }
}