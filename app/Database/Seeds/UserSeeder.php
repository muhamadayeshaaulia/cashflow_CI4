<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'purchasing',
                // gunakan password_hash agar aman
                'password'   => password_hash('123456', PASSWORD_DEFAULT),
                'role'       => 'purchasing',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'manajer',
                'password'   => password_hash('123456', PASSWORD_DEFAULT),
                'role'       => 'manajer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'admin_keuangan',
                'password'   => password_hash('123456', PASSWORD_DEFAULT),
                'role'       => 'admin_keuangan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Masukkan data ke dalam tabel 'users'
        $this->db->table('users')->insertBatch($data);
    }
}