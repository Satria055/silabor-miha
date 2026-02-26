<?php

namespace App\Controllers;

use App\Models\UnitModel;

class Unit extends BaseController
{
    protected $unitModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
    }

    public function index()
    {
        $data = [
            'units' => $this->unitModel->findAll()
        ];
        return view('units/index', $data);
    }

    public function form($id = null)
    {
        $data = [
            'unit' => $id ? $this->unitModel->find($id) : null
        ];
        return view('units/form', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $saveData = [
            'nama_unit' => $this->request->getPost('nama_unit')
        ];

        if ($id) {
            $this->unitModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data unit pendidikan berhasil diperbarui.');
        } else {
            $this->unitModel->insert($saveData);
            session()->setFlashdata('success', 'Unit pendidikan baru berhasil ditambahkan.');
        }

        return redirect()->to('/units');
    }

    public function delete($id)
    {
        $this->unitModel->delete($id);
        session()->setFlashdata('success', 'Unit pendidikan berhasil dihapus permanen.');
        return redirect()->to('/units');
    }

    // Fungsi Hapus Massal (Bulk Delete)
    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (!empty($ids) && is_array($ids)) {
            $this->unitModel->delete($ids);
            session()->setFlashdata('success', count($ids) . ' unit pendidikan berhasil dihapus permanen.');
        } else {
            session()->setFlashdata('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }
        
        return redirect()->to('/units');
    }
}