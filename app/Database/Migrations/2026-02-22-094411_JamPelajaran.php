<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class JamPelajaran extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->addField([
            'id'            => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'unit_id'       => ['type' => 'BIGINT', 'unsigned' => true],
            'nama_sesi'     => ['type' => 'VARCHAR', 'constraint' => 100],
            'waktu_mulai'   => ['type' => 'TIME'],
            'waktu_selesai' => ['type' => 'TIME'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('jam_pelajaran');
        $this->db->enableForeignKeyChecks();
    }

    public function down() { 
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('jam_pelajaran'); 
        $this->db->enableForeignKeyChecks();
    }
}