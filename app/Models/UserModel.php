<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object'; 
    protected $allowedFields    = ['unit_id', 'nama', 'username', 'password', 'role', 'is_active'];
    protected $useTimestamps    = true;

    // Fungsi canggih untuk mengambil pengguna dengan Filter Ganda
    public function getUsersWithUnit($unit_id = null, $role = null)
    {
        $builder = $this->select('users.*, units.nama_unit')
                        ->join('units', 'units.id = users.unit_id', 'left')
                        ->orderBy('users.role', 'ASC')
                        ->orderBy('users.nama', 'ASC');
        
        if ($unit_id) {
            $builder->where('users.unit_id', $unit_id);
        }
        
        if ($role) {
            $builder->where('users.role', $role);
        }

        return $builder->findAll();
    }
}