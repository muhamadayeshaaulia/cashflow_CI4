<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KasKeluar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            
            // KUNCI RELASI: Menyambungkan ke ID di tabel 'pengajuan'
            'pengajuan_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            
            // Detail Pembayaran Vendor
            'nama_vendor'      => ['type' => 'VARCHAR', 'constraint' => 150],
            'bank_vendor'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'rekening_vendor'  => ['type' => 'VARCHAR', 'constraint' => 50],
            
            // Detail Keuangan (Tetap pakai DECIMAL agar akurat)
            'nominal_barang'   => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'pajak_ppn'        => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'biaya_ongkir'     => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'total_pengajuan'  => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            
            // Bukti & Admin yang bayar (Hanya diisi saat status 'dibayar')
            'bukti_pembayaran' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nip_admin'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);

        //Hubungkan ke tabel 'pengajuan' (Jika pengajuan dihapus, detail ini ikut terhapus)
        $this->forge->addForeignKey('pengajuan_id', 'pengajuan', 'id', 'CASCADE', 'CASCADE');
        
        //Hubungkan ke tabel 'pegawai' untuk Admin Keuangan
        $this->forge->addForeignKey('nip_admin', 'pegawai', 'nip', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('kas_keluar');
    }

    public function down()
    {
        $this->forge->dropTable('kas_keluar');
    }
}