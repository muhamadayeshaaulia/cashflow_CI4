<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 3 data pegawai sekaligus akunnya
        $karyawan = [
            [
                'username' => 'purchasing',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'nama'     => 'Budi Santoso',
                'email'    => 'budi.pur@sariling.com',
                'no_telp'  => '081122334455',
                'role'     => 'purchasing'
            ],
            [
                'username' => 'manajer',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'nama'     => 'Andi Wijaya',
                'email'    => 'andi.manajer@sariling.com',
                'no_telp'  => '082233445566',
                'role'     => 'manajer'
            ],
            [
                'username' => 'admin_keuangan',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'nama'     => 'Siti Aminah',
                'email'    => 'siti.keuangan@sariling.com',
                'no_telp'  => '083344556677',
                'role'     => 'admin_keuangan'
            ]
        ];

        foreach ($karyawan as $data) {
            // Masukkan data login ke tabel 'users'
            $this->db->table('users')->insert([
                'username'   => $data['username'],
                'password'   => $data['password'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Ambil ID User yang baru saja terbuat secara otomatis (Auto Increment)
            $userId = $this->db->insertID();

            // Masukkan biodata ke tabel 'pegawai' dan sambungkan dengan ID User tadi
            $this->db->table('pegawai')->insert([
                'user_id'      => $userId,
                'nama_lengkap' => $data['nama'],
                'email'        => $data['email'],
                'no_telp'      => $data['no_telp'],
                'role'         => $data['role'],
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]);
        }
    }
}