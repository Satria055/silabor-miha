<?php

namespace App\Controllers;

use App\Models\JamPelajaranModel;
use App\Models\UnitModel;

class JamPelajaran extends BaseController
{
    protected $jamModel;
    protected $unitModel;

    public function __construct()
    {
        $this->jamModel = new JamPelajaranModel();
        $this->unitModel = new UnitModel();
    }

    public function index()
    {
        // Tangkap parameter filter unit_id dari URL
        $unit_id = $this->request->getGet('unit_id');

        $data = [
            'jams'    => $this->jamModel->getJamWithUnit($unit_id),
            'units'   => $this->unitModel->findAll(),
            'unit_id' => $unit_id
        ];
        
        return view('jam_pelajaran/index', $data);
    }

    public function form($id = null)
    {
        $data = [
            'units' => $this->unitModel->findAll(),
            'jam'   => $id ? $this->jamModel->find($id) : null
        ];
        return view('jam_pelajaran/form', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $saveData = [
            'unit_id'       => $this->request->getPost('unit_id'),
            'nama_sesi'     => $this->request->getPost('nama_sesi'),
            'waktu_mulai'   => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
        ];

        if ($id) {
            $this->jamModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data jam pelajaran berhasil diperbarui.');
        } else {
            $this->jamModel->insert($saveData);
            session()->setFlashdata('success', 'Jam pelajaran baru berhasil ditambahkan.');
        }

        return redirect()->to('/jam-pelajaran');
    }

    public function delete($id)
    {
        $this->jamModel->delete($id);
        session()->setFlashdata('success', 'Data jam pelajaran berhasil dihapus.');
        return redirect()->to('/jam-pelajaran');
    }

    // Fungsi untuk Hapus Massal (Bulk Delete)
    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (!empty($ids) && is_array($ids)) {
            $this->jamModel->delete($ids);
            session()->setFlashdata('success', count($ids) . ' data jam pelajaran berhasil dihapus permanen.');
        } else {
            session()->setFlashdata('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }
        
        return redirect()->to('/jam-pelajaran');
    }
}