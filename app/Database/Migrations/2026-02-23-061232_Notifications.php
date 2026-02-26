<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Notifications extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'user_id'    => ['type' => 'INT', 'unsigned' => true], // Penerima notifikasi
            'judul'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'pesan'      => ['type' => 'TEXT'],
            'link'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true], // Link saat diklik
            'is_read'    => ['type' => 'BOOLEAN', 'default' => false],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        $this->forge->dropTable('notifications');
    }
}