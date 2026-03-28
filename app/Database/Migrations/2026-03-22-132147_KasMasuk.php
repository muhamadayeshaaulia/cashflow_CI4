<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KasMasuk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal_pembuatan' => ['type' => 'DATE'],
            'no_bast'           => ['type' => 'VARCHAR', 'constraint' => 50],
            'no_tagihan'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'no_sp2d'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nominal'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'status'            => ['type' => 'ENUM', 'constraint' => ['proses_kirim', 'tagihan_dikirim', 'sp2d_terbit', 'lunas'], 'default' => 'proses_kirim'],
            'bukti_transfer'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            // NIP (VARCHAR)
            'nip_admin'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        
        // RELASI KE TABEL PEGAWAI
        $this->forge->addForeignKey('nip_admin', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');
        
        $this->forge->createTable('kas_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('kas_masuk');
    }
}