<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class StrukturOrganisasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'jabatan'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'foto'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'wa'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'ig'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'fb'       => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'web'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'urutan'   => ['type' => 'INT', 'default' => 0], // Untuk mengurutkan dari Kepala Lab -> Staff
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('struktur_organisasi', true);
    }

    public function down() { $this->forge->dropTable('struktur_organisasi'); }
}