<?php

namespace App\Models;

use CodeIgniter\Model;

class LabModel extends Model
{
    protected $table            = 'laboratories';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = true; // Mengaktifkan pengisian created_at & updated_at otomatis
    protected $allowedFields    = ['unit_id', 'nama_lab', 'kapasitas', 'status'];

    // Fungsi untuk mengambil data lab beserta nama unit yayasannya
    public function getLabsWithUnits()
    {
        return $this->select('laboratories.*, units.nama_unit')
                    ->join('units', 'units.id = laboratories.unit_id', 'left')
                    ->findAll();
    }
}