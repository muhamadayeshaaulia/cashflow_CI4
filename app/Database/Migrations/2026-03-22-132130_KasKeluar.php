<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KasKeluar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tanggal_pengajuan' => ['type' => 'DATE'],
            'deskripsi'         => ['type' => 'TEXT'],
            'nominal'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'status'            => ['type' => 'ENUM', 'constraint' => ['pending', 'acc', 'ditolak', 'dibayar'], 'default' => 'pending'],
            'bukti_pembayaran'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            
            //NIP (VARCHAR)
            'nip_purchasing'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nip_manajer'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nip_admin'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
            'updated_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // RELASI KE TABEL PEGAWAI (Berdasarkan NIP)
        $this->forge->addForeignKey('nip_purchasing', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nip_manajer', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nip_admin', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('kas_keluar');
    }
    public function down()
    {
        $this->forge->dropTable('kas_keluar');
    }
}