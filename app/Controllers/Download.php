<?php

namespace App\Controllers;

use App\Models\DownloadModel;

class Download extends BaseController
{
    protected $downloadModel;

    public function __construct()
    {
        $this->downloadModel = new DownloadModel();
    }

    // --- SISI PUBLIK ---
    public function index()
    {
        $data = [
            'downloads' => $this->downloadModel->orderBy('created_at', 'DESC')->findAll()
        ];
        return view('downloads/index', $data);
    }

    // --- SISI ADMIN ---
    public function manage()
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');
        
        $data = ['downloads' => $this->downloadModel->orderBy('created_at', 'DESC')->findAll()];
        return view('downloads/manage', $data);
    }

    public function form($id = null)
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $data = ['download' => $id ? $this->downloadModel->find($id) : null];
        return view('downloads/form', $data);
    }

    public function save()
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $id = $this->request->getPost('id');
        $saveData = [
            'judul'     => $this->request->getPost('judul'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        // Proses Upload File Dokumen
        $fileDokumen = $this->request->getFile('file_dokumen');
        if ($fileDokumen && $fileDokumen->isValid() && !$fileDokumen->hasMoved()) {
            $ext = $fileDokumen->getExtension();
            $namaFile = $fileDokumen->getRandomName();
            $fileDokumen->move('uploads/dokumen', $namaFile);
            
            $saveData['nama_file'] = $namaFile;
            $saveData['tipe_file'] = $ext;

            // Hapus file lama jika sedang edit
            $existing = $id ? $this->downloadModel->find($id) : null;
            if ($existing && $existing->nama_file && file_exists('uploads/dokumen/' . $existing->nama_file)) {
                unlink('uploads/dokumen/' . $existing->nama_file);
            }
        }

        if ($id) {
            $this->downloadModel->update($id, $saveData);
            session()->setFlashdata('success', 'Informasi dokumen berhasil diperbarui.');
        } else {
            $this->downloadModel->insert($saveData);
            session()->setFlashdata('success', 'Dokumen baru berhasil ditambahkan.');
        }

        return redirect()->to('/downloads/manage');
    }

    public function delete($id)
    {
        if (!in_array(session()->get('role'), ['super_admin', 'admin_lab'])) return redirect()->to('/dashboard');

        $dokumen = $this->downloadModel->find($id);
        if ($dokumen && $dokumen->nama_file && file_exists('uploads/dokumen/' . $dokumen->nama_file)) {
            unlink('uploads/dokumen/' . $dokumen->nama_file);
        }

        $this->downloadModel->delete($id);
        session()->setFlashdata('success', 'Dokumen berhasil dihapus.');
        return redirect()->to('/downloads/manage');
    }
}