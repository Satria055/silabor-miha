<?php

namespace App\Models;

use CodeIgniter\Model;

class JamPelajaranModel extends Model
{
    protected $table            = 'jam_pelajaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $allowedFields    = ['unit_id', 'nama_sesi', 'waktu_mulai', 'waktu_selesai'];

    // Fungsi untuk mengambil data jam beserta nama unitnya (dilengkapi fitur filter)
    public function getJamWithUnit($unit_id = null)
    {
        $builder = $this->select('jam_pelajaran.*, units.nama_unit')
                        ->join('units', 'units.id = jam_pelajaran.unit_id', 'left')
                        ->orderBy('units.nama_unit', 'ASC')
                        ->orderBy('jam_pelajaran.waktu_mulai', 'ASC');
        
        if ($unit_id) {
            $builder->where('jam_pelajaran.unit_id', $unit_id);
        }

        return $builder->findAll();
    }
}