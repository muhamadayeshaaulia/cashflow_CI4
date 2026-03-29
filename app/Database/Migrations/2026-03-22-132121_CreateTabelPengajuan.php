<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTabelPengajuan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'no_pengajuan'     => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true], // Contoh: SRL-29032026-001
            'tanggal_pengajuan'=> ['type' => 'DATE'],
            'divisi_peminta'   => ['type' => 'VARCHAR', 'constraint' => 100], // Nama Proyek / Dinas
            'deskripsi'        => ['type' => 'TEXT'],
            'status'           => ['type' => 'ENUM', 'constraint' => ['pending', 'acc', 'ditolak', 'dibayar'], 'default' => 'pending'],
            
            // Relasi ke Pegawai (Purchasing yang buat & Manajer yang ACC)
            'nip_purchasing'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'nip_manajer'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        // Foreign Key ke tabel pegawai (Pastikan tabel pegawai sudah ada sebelumnya)
        $this->forge->addForeignKey('nip_purchasing', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('nip_manajer', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('pengajuan');
    }

    public function down()
    {
        $this->forge->dropTable('pengajuan');
    }
}