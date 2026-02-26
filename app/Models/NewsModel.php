<?php

namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table            = 'news';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['user_id', 'judul', 'slug', 'konten', 'thumbnail', 'status', 'views'];

    // Perbaikan: Menggunakan $this agar bisa di-chaining dengan paginate() atau findAll()
    public function getNewsWithAuthor($status = null)
    {
        $this->select('news.*, users.nama as penulis')
             ->join('users', 'users.id = news.user_id')
             ->orderBy('news.created_at', 'DESC');
        
        if ($status) {
            $this->where('news.status', $status);
        }
        
        return $this; 
    }

    public function getNewsBySlug($slug)
    {
        return $this->select('news.*, users.nama as penulis')
                    ->join('users', 'users.id = news.user_id')
                    ->where('news.slug', $slug)
                    ->first();
    }
}