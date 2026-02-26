<?php

namespace App\Models;

use CodeIgniter\Model;

class StrukturModel extends Model
{
    protected $table            = 'struktur_organisasi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object';
    protected $useTimestamps    = false;
    protected $allowedFields    = ['nama', 'jabatan', 'foto', 'wa', 'ig', 'fb', 'web', 'urutan'];
}