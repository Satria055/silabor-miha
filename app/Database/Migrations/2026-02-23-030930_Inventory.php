<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Inventory extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'lab_id'        => ['type' => 'BIGINT', 'unsigned' => true],
            'kode_barang'   => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'nama_barang'   => ['type' => 'VARCHAR', 'constraint' => 255],
            'kategori'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'jumlah_total'  => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'kondisi_baik'  => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'kondisi_rusak' => ['type' => 'INT', 'unsigned' => true, 'default' => 0],
            'keterangan'    => ['type' => 'TEXT', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('lab_id', 'laboratories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inventories');

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('inventories');
        $this->db->enableForeignKeyChecks();
    }
}