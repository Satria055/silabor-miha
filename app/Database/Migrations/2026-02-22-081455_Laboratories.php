<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Laboratories extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'unit_id'    => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'nama_lab'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'kapasitas'  => ['type' => 'INT', 'constraint' => 5, 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['aktif', 'maintenance'], 'default' => 'aktif'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('laboratories');

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('laboratories');
        $this->db->enableForeignKeyChecks();
    }
}