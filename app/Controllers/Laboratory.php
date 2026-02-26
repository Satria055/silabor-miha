<?php

namespace App\Controllers;

use App\Models\LabModel;
use App\Models\UnitModel;

class Laboratory extends BaseController
{
    protected $labModel;
    protected $unitModel;

    public function __construct()
    {
        $this->labModel = new LabModel();
        $this->unitModel = new UnitModel();
    }

    // Menampilkan daftar laboratorium
    public function index()
    {
        $data = [
            'labs' => $this->labModel->getLabsWithUnits()
        ];
        return view('laboratories/index', $data);
    }

    // Menampilkan form tambah/edit lab
    public function form($id = null)
    {
        $data = [
            'units' => $this->unitModel->findAll(),
            'lab'   => $id ? $this->labModel->find($id) : null // Jika ada ID, berarti mode Edit
        ];
        return view('laboratories/form', $data);
    }

    // Menyimpan data (Baru atau Update)
    public function save()
    {
        $id = $this->request->getPost('id');
        $saveData = [
            'unit_id'   => $this->request->getPost('unit_id'),
            'nama_lab'  => $this->request->getPost('nama_lab'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'status'    => $this->request->getPost('status'),
        ];

        if ($id) {
            $this->labModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data laboratorium berhasil diperbarui.');
        } else {
            $this->labModel->insert($saveData);
            session()->setFlashdata('success', 'Laboratorium baru berhasil ditambahkan.');
        }

        return redirect()->to('/laboratories');
    }

    // Menghapus data
    public function delete($id)
    {
        $this->labModel->delete($id);
        session()->setFlashdata('success', 'Laboratorium berhasil dihapus permanen.');
        return redirect()->to('/laboratories');
    }
}