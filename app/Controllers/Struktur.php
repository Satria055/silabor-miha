<?php

namespace App\Controllers;

use App\Models\StrukturModel;

class Struktur extends BaseController
{
    protected $strukturModel;

    public function __construct()
    {
        $this->strukturModel = new StrukturModel();
    }

    // --- SISI PUBLIK ---
    public function index()
    {
        // Menampilkan data untuk publik, diurutkan berdasarkan kolom 'urutan'
        $data = [
            'tim' => $this->strukturModel->orderBy('urutan', 'ASC')->findAll()
        ];
        return view('struktur/index', $data);
    }

    // --- SISI ADMIN ---
    public function manage()
    {
        if (session()->get('role') != 'super_admin') return redirect()->to('/dashboard');
        
        $data = ['tim' => $this->strukturModel->orderBy('urutan', 'ASC')->findAll()];
        return view('struktur/manage', $data);
    }

    public function form($id = null)
    {
        if (session()->get('role') != 'super_admin') return redirect()->to('/dashboard');

        $data = ['anggota' => $id ? $this->strukturModel->find($id) : null];
        return view('struktur/form', $data);
    }

    public function save()
    {
        if (session()->get('role') != 'super_admin') return redirect()->to('/dashboard');

        $id = $this->request->getPost('id');
        $saveData = [
            'nama'    => $this->request->getPost('nama'),
            'jabatan' => $this->request->getPost('jabatan'),
            'wa'      => $this->request->getPost('wa'),
            'ig'      => $this->request->getPost('ig'),
            'fb'      => $this->request->getPost('fb'),
            'web'     => $this->request->getPost('web'),
            'urutan'  => $this->request->getPost('urutan') ?: 0,
        ];

        // Proses Upload Foto
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move('uploads/tim', $namaFile);
            $saveData['foto'] = $namaFile;

            // Hapus foto lama jika sedang edit
            $existing = $id ? $this->strukturModel->find($id) : null;
            if ($existing && $existing->foto && file_exists('uploads/tim/' . $existing->foto)) {
                unlink('uploads/tim/' . $existing->foto);
            }
        }

        if ($id) {
            $this->strukturModel->update($id, $saveData);
            session()->setFlashdata('success', 'Profil tim berhasil diperbarui.');
        } else {
            $this->strukturModel->insert($saveData);
            session()->setFlashdata('success', 'Anggota tim baru berhasil ditambahkan.');
        }

        return redirect()->to('/struktur/manage');
    }

    public function delete($id)
    {
        if (session()->get('role') != 'super_admin') return redirect()->to('/dashboard');

        $anggota = $this->strukturModel->find($id);
        if ($anggota && $anggota->foto && file_exists('uploads/tim/' . $anggota->foto)) {
            unlink('uploads/tim/' . $anggota->foto);
        }

        $this->strukturModel->delete($id);
        session()->setFlashdata('success', 'Anggota tim berhasil dihapus.');
        return redirect()->to('/struktur/manage');
    }
}