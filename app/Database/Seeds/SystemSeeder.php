<?php
namespace App\Database\Seeds;
use CodeIgniter\Database\Seeder;

class SystemSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Jam Operasional Lab (Buka jam 07:00, Tutup jam 16:00)
        $settings = [
            ['setting_key' => 'jam_buka', 'setting_value' => '07:00:00'],
            ['setting_key' => 'jam_tutup', 'setting_value' => '16:00:00'],
        ];
        $this->db->table('settings')->insertBatch($settings);

        // 2. Setup Jam Pelajaran KBM (Diasumsikan Unit ID 1 adalah SMK)
        $jamPelajaran = [
            ['unit_id' => 1, 'nama_sesi' => 'Jam ke-1 & 2', 'waktu_mulai' => '07:00:00', 'waktu_selesai' => '08:30:00'],
            ['unit_id' => 1, 'nama_sesi' => 'Jam ke-3 & 4', 'waktu_mulai' => '08:30:00', 'waktu_selesai' => '10:00:00'],
            ['unit_id' => 1, 'nama_sesi' => 'Jam ke-5 & 6', 'waktu_mulai' => '10:30:00', 'waktu_selesai' => '12:00:00'],
        ];
        $this->db->table('jam_pelajaran')->insertBatch($jamPelajaran);
    }
}