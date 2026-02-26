<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\LabModel;

class Inventory extends BaseController
{
    protected $inventoryModel;
    protected $labModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->labModel = new LabModel();
    }

    public function index()
    {
        // Menangkap 3 parameter filter dari URL
        $lab_id = $this->request->getGet('lab_id');
        $kategori = $this->request->getGet('kategori');
        $kondisi = $this->request->getGet('kondisi');
        
        $data = [
            'inventories' => $this->inventoryModel->getInventoryWithLab($lab_id, $kategori, $kondisi),
            'labs'        => $this->labModel->where('status', 'aktif')->findAll(),
            'lab_id'      => $lab_id,
            'kategori'    => $kategori,
            'kondisi'     => $kondisi
        ];
        
        return view('inventory/index', $data);
    }

    public function form($id = null)
    {
        $data = [
            'labs'      => $this->labModel->where('status', 'aktif')->findAll(),
            'inventory' => $id ? $this->inventoryModel->find($id) : null
        ];
        return view('inventory/form', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $kode_barang = $this->request->getPost('kode_barang');

        $cekKode = $this->inventoryModel->where('kode_barang', $kode_barang)->where('id !=', $id)->first();
        if ($cekKode) {
            session()->setFlashdata('error', 'Kode barang sudah digunakan. Silakan gunakan kode lain.');
            return redirect()->back()->withInput();
        }

        $saveData = [
            'lab_id'        => $this->request->getPost('lab_id'),
            'kode_barang'   => $kode_barang,
            'nama_barang'   => $this->request->getPost('nama_barang'),
            'kategori'      => $this->request->getPost('kategori'),
            'jumlah_total'  => $this->request->getPost('jumlah_total'),
            'kondisi_baik'  => $this->request->getPost('kondisi_baik'),
            'kondisi_rusak' => $this->request->getPost('kondisi_rusak'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ];

        if ($id) {
            $this->inventoryModel->update($id, $saveData);
            session()->setFlashdata('success', 'Data inventaris berhasil diperbarui.');
        } else {
            $this->inventoryModel->insert($saveData);
            session()->setFlashdata('success', 'Barang baru berhasil ditambahkan ke inventaris.');
        }

        return redirect()->to('/inventory');
    }

    public function delete($id)
    {
        $this->inventoryModel->delete($id);
        session()->setFlashdata('success', 'Data inventaris berhasil dihapus.');
        return redirect()->to('/inventory');
    }

    // Fungsi untuk Hapus Massal
    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (!empty($ids) && is_array($ids)) {
            $this->inventoryModel->delete($ids);
            session()->setFlashdata('success', count($ids) . ' data inventaris berhasil dihapus permanen.');
        } else {
            session()->setFlashdata('error', 'Tidak ada data yang dipilih.');
        }
        
        return redirect()->to('/inventory');
    }
}