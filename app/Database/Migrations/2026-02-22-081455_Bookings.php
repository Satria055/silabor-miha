<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bookings extends Migration
{
    public function up()
    {
        $this->db->disableForeignKeyChecks();

        $this->forge->addField([
            'id'                => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'           => ['type' => 'BIGINT', 'unsigned' => true],
            'lab_id'            => ['type' => 'BIGINT', 'unsigned' => true],
            
            'jenis_peminjaman'  => ['type' => 'ENUM', 'constraint' => ['KBM', 'Khusus']],
            'tanggal_mulai'     => ['type' => 'DATE'],
            'tanggal_selesai'   => ['type' => 'DATE', 'null' => true],
            'waktu_mulai'       => ['type' => 'TIME'],
            'waktu_selesai'     => ['type' => 'TIME'],
            
            'mata_pelajaran'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'kelas'             => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'guru_pengajar'     => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            
            'keperluan'         => ['type' => 'TEXT', 'null' => true],
            'penanggung_jawab'  => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'file_surat'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            'status'            => ['type' => 'ENUM', 'constraint' => ['pending', 'disetujui', 'ditolak'], 'default' => 'pending'],
            'catatan_admin'     => ['type' => 'TEXT', 'null' => true],
            
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lab_id', 'laboratories', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('bookings');

        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        $this->db->disableForeignKeyChecks();
        $this->forge->dropTable('bookings');
        $this->db->enableForeignKeyChecks();
    }
}