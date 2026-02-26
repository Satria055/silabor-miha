<?php

namespace App\Controllers;

use App\Models\SettingModel;

class Settings extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        // Blokir akses jika bukan Super Admin atau Admin Lab
        $role = session()->get('role');
        if (!in_array($role, ['super_admin', 'admin_lab'])) {
            return redirect()->to('/dashboard');
        }

        // Ambil semua data setting dari database
        $settings = $this->settingModel->findAll();
        $sys_setting = [];
        
        foreach ($settings as $set) {
            $sys_setting[$set->setting_key] = $set->setting_value;
        }

        // Siapkan nilai default jika tabel masih kosong
        $data = [
            'jam_buka'  => $sys_setting['jam_buka'] ?? '07:00',
            'jam_tutup' => $sys_setting['jam_tutup'] ?? '16:00',
            'lead_time' => $sys_setting['lead_time'] ?? '1'
        ];

        return view('settings/index', $data);
    }

    public function save()
    {
        $role = session()->get('role');
        if (!in_array($role, ['super_admin', 'admin_lab'])) {
            return redirect()->to('/dashboard');
        }

        // Tangkap data dari form
        $postData = [
            'jam_buka'  => $this->request->getPost('jam_buka'),
            'jam_tutup' => $this->request->getPost('jam_tutup'),
            'lead_time' => $this->request->getPost('lead_time'),
        ];

        // Lakukan Upsert (Update jika ada, Insert jika belum ada)
        foreach ($postData as $key => $value) {
            $existing = $this->settingModel->where('setting_key', $key)->first();
            
            if ($existing) {
                $this->settingModel->update($existing->id, ['setting_value' => $value]);
            } else {
                $this->settingModel->insert([
                    'setting_key'   => $key,
                    'setting_value' => $value
                ]);
            }
        }

        session()->setFlashdata('success', 'Pengaturan sistem berhasil diperbarui. Semua form peminjaman sekarang mengikuti aturan baru ini.');
        return redirect()->to('/settings');
    }
}