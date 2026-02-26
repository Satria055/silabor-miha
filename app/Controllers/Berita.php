<?php

namespace App\Controllers;

use App\Models\NewsModel;

class Berita extends BaseController
{
    protected $newsModel;

    public function __construct()
    {
        $this->newsModel = new NewsModel();
        helper(['text', 'url']); 
    }

    // ====================================================================
    // SISI PUBLIK 
    // ====================================================================

    public function index()
    {
        $data = [
            'berita' => $this->newsModel->getNewsWithAuthor('publish')->paginate(6, 'berita'),
            'pager'  => $this->newsModel->pager,
        ];
        return view('berita/index', $data);
    }

    public function detail($slug)
    {
        $berita = $this->newsModel->getNewsBySlug($slug);
        
        if (!$berita || ($berita->status == 'draft' && !session()->get('logged_in'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->newsModel->update($berita->id, ['views' => $berita->views + 1]);

        $data = ['berita' => $berita];
        return view('berita/detail', $data);
    }

    // ====================================================================
    // SISI ADMIN 
    // ====================================================================

    public function manage()
    {
        $role = session()->get('role');
        $userId = session()->get('id');

        $query = $this->newsModel->getNewsWithAuthor();
        
        if ($role != 'super_admin') {
            $query->where('news.user_id', $userId);
        }

        $data = ['berita' => $query->findAll()];
        return view('berita/manage', $data);
    }

    public function form($id = null)
    {
        $data = [
            'berita' => $id ? $this->newsModel->find($id) : null
        ];
        return view('berita/form', $data);
    }

    public function save()
    {
        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        
        $slug = url_title($judul, '-', true);
        $cekSlug = $this->newsModel->where('slug', $slug)->where('id !=', $id)->first();
        if ($cekSlug) {
            $slug .= '-' . time(); 
        }

        $saveData = [
            'judul'   => $judul,
            'slug'    => $slug,
            'konten'  => $this->request->getPost('konten'),
            'status'  => $this->request->getPost('status'),
            'user_id' => session()->get('id')
        ];

        $fileThumbnail = $this->request->getFile('thumbnail');
        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {
            $namaFile = $fileThumbnail->getRandomName();
            $fileThumbnail->move(FCPATH . 'uploads/berita', $namaFile);
            $saveData['thumbnail'] = $namaFile;
            
            if ($id) {
                $beritaLama = $this->newsModel->find($id);
                if ($beritaLama->thumbnail && file_exists(FCPATH . 'uploads/berita/' . $beritaLama->thumbnail)) {
                    unlink(FCPATH . 'uploads/berita/' . $beritaLama->thumbnail);
                }
            }
        }

        if ($id) {
            $this->newsModel->update($id, $saveData);
            session()->setFlashdata('success', 'Berita berhasil diperbarui.');
        } else {
            if (empty($saveData['thumbnail'])) {
                session()->setFlashdata('error', 'Gambar thumbnail wajib diunggah untuk berita baru.');
                return redirect()->back()->withInput();
            }
            $this->newsModel->insert($saveData);
            session()->setFlashdata('success', 'Berita berhasil disimpan.');
        }

        return redirect()->to('/berita/manage');
    }

    public function delete($id)
    {
        $berita = $this->newsModel->find($id);
        if ($berita && $berita->thumbnail && file_exists(FCPATH . 'uploads/berita/' . $berita->thumbnail)) {
            unlink(FCPATH . 'uploads/berita/' . $berita->thumbnail);
        }

        $this->newsModel->delete($id);
        session()->setFlashdata('success', 'Berita berhasil dihapus.');
        return redirect()->to('/berita/manage');
    }

    // Fungsi Hapus Massal (Bulk Delete)
    public function bulkDelete()
    {
        $ids = $this->request->getPost('ids');
        
        if (!empty($ids) && is_array($ids)) {
            foreach ($ids as $id) {
                $berita = $this->newsModel->find($id);
                // Hapus thumbnail fisik agar server tidak penuh
                if ($berita && $berita->thumbnail && file_exists(FCPATH . 'uploads/berita/' . $berita->thumbnail)) {
                    unlink(FCPATH . 'uploads/berita/' . $berita->thumbnail);
                }
            }
            $this->newsModel->delete($ids);
            session()->setFlashdata('success', count($ids) . ' artikel berita berhasil dihapus permanen.');
        } else {
            session()->setFlashdata('error', 'Tidak ada artikel yang dipilih untuk dihapus.');
        }
        
        return redirect()->to('/berita/manage');
    }

    public function uploadImage()
    {
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/berita/konten', $newName);
            
            return $this->response->setJSON([
                'url'      => base_url('uploads/berita/konten/' . $newName),
                'csrfName' => csrf_token(),
                'csrfHash' => csrf_hash()
            ]);
        }
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Gagal mengunggah gambar.']);
    }
}