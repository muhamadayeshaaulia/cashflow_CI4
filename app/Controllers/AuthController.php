<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika sudah login, langsung tendang ke dashboard masing-masing
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');
            $url_dashboard = ($role == 'admin_keuangan') ? '/admin' : '/' . $role;
            return redirect()->to($url_dashboard);
        }

        return view('auth/login');
    }

    public function process()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $db = \Config\Database::connect();
        
        // Menggabungkan (JOIN) tabel users dan pegawai
        $user = $db->table('users')
                   ->select('users.id, users.username, users.password, pegawai.nip, pegawai.nama_lengkap, pegawai.role')
                   ->join('pegawai', 'pegawai.user_id = users.id')
                   ->where('users.username', $username)
                   ->get()
                   ->getRowArray();

        // Jika username ditemukan di database
        if ($user) {
            // Cek kecocokan password
            if (password_verify($password, $user['password'])) {
                
                // Simpan data ke memori (Session)
                $sessionData = [
                    'id'           => $user['id'],
                    'nip'          => $user['nip'],
                    'username'     => $user['username'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role'         => $user['role'],
                    'isLoggedIn'   => true
                ];
                session()->set($sessionData);

                // Arahkan ke dashboard sesuai role
                $url_dashboard = ($user['role'] == 'admin_keuangan') ? '/admin' : '/' . $user['role'];
                return redirect()->to($url_dashboard);
                
            } else {
                session()->setFlashdata('error', 'Password salah!');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->back();
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}