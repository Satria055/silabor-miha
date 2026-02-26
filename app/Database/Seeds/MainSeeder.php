<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run()
    {
        // 1. Data Unit Pendidikan
        $unitData = [
            ['nama_unit' => 'SMK', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_unit' => 'SMA', 'created_at' => date('Y-m-d H:i:s')],
            ['nama_unit' => 'SMP', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('units')->insertBatch($unitData);

        // 2. Data 5 Ruang Laboratorium
        $labData = [
            ['unit_id' => 1, 'nama_lab' => 'Lab Komputer 1', 'kapasitas' => 36, 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s')],
            ['unit_id' => 1, 'nama_lab' => 'Lab Komputer 2', 'kapasitas' => 36, 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s')],
            ['unit_id' => 1, 'nama_lab' => 'Lab Multimedia', 'kapasitas' => 30, 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s')],
            ['unit_id' => 1, 'nama_lab' => 'Lab Desain', 'kapasitas' => 25, 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s')],
            ['unit_id' => 1, 'nama_lab' => 'Lab Jaringan', 'kapasitas' => 36, 'status' => 'aktif', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('laboratories')->insertBatch($labData);

        // 3. Data Akun Super Admin
        $userData = [
            'unit_id'    => null, // Super admin tidak terikat 1 unit
            'nama'       => 'Super Admin Yayasan',
            'username'   => 'superadmin',
            // Kita enkripsi password 'rahasia123' menggunakan standar keamanan PHP
            'password'   => password_hash('rahasia123', PASSWORD_DEFAULT),
            'role'       => 'super_admin',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->db->table('users')->insert($userData);
    }
}