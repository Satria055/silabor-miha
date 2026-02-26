<?php

namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table            = 'inventories';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'lab_id', 'kode_barang', 'nama_barang', 'kategori', 
        'jumlah_total', 'kondisi_baik', 'kondisi_rusak', 'keterangan'
    ];

    // Fungsi canggih untuk mengambil data inventaris dengan filter 3 Dimensi
    public function getInventoryWithLab($lab_id = null, $kategori = null, $kondisi = null)
    {
        $builder = $this->select('inventories.*, laboratories.nama_lab')
                        ->join('laboratories', 'laboratories.id = inventories.lab_id')
                        ->orderBy('laboratories.nama_lab', 'ASC')
                        ->orderBy('inventories.nama_barang', 'ASC');
        
        // Filter 1: Laboratorium
        if ($lab_id) {
            $builder->where('inventories.lab_id', $lab_id);
        }
        
        // Filter 2: Kategori
        if ($kategori) {
            $builder->where('inventories.kategori', $kategori);
        }

        // Filter 3: Kondisi Barang
        if ($kondisi == 'rusak') {
            $builder->where('inventories.kondisi_rusak >', 0); // Menampilkan barang yang memiliki minimal 1 kerusakan
        } elseif ($kondisi == 'baik') {
            $builder->where('inventories.kondisi_rusak', 0);   // Menampilkan barang yang 100% mulus
        }
        
        return $builder->findAll();
    }
}