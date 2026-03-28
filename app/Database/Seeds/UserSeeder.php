<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 3 data pegawai beserta NIP-nya
        $karyawan = [
            [
                'nip'      => '198501012010011001',
                'username' => 'purchasing',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'nama'     => 'Budi Santoso',
                'email'    => 'budi.pur@sariling.com',
                'no_telp'  => '081122334455',
                'role'     => 'purchasing'
            ],
            [
                'nip'      => '197805122005011002',
                'username' => 'manajer',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'nama'     => 'Andi Wijaya',
                'email'    => 'andi.manajer@sariling.com',
                'no_telp'  => '082233445566',
                'role'     => 'manajer'
            ],
            [
                'nip'      => '199011232015032001',
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

            // Ambil ID User yang baru saja terbuat
            $userId = $this->db->insertID();

            // Masukkan biodata ke tabel 'pegawai' dengan menyertakan NIP
            $this->db->table('pegawai')->insert([
                'nip'          => $data['nip'], // NIP dimasukkan di sini
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