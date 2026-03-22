<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index()
    {
        // Jika user sudah login, arahkan langsung ke dashboard sesuai role-nya
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        // Tampilkan halaman login
        return view('auth/login');
    }

    public function process()
    {
        $userModel = new UserModel();
        
        // Ambil data dari form login
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user di database berdasarkan username
        $user = $userModel->where('username', $username)->first();

        // Jika user ditemukan
        if ($user) {
            // Verifikasi password yang diinput dengan password hash di database
            if (password_verify($password, $user['password'])) {
                
                // Jika cocok, simpan data ke dalam session
                $sessionData = [
                    'id'         => $user['id'],
                    'username'   => $user['username'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ];
                session()->set($sessionData);

                // Arahkan ke dashboard sesuai role
                return $this->redirectByRole($user['role']);
            } else {
                // Password salah
                session()->setFlashdata('error', 'Password salah!');
                return redirect()->to('/login');
            }
        } else {
            // Username tidak ditemukan
            session()->setFlashdata('error', 'Username tidak ditemukan!');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        // Hapus semua data session dan kembalikan ke halaman login
        session()->destroy();
        return redirect()->to('/login');
    }

    // Fungsi bantuan untuk mengarahkan halaman berdasarkan role
    private function redirectByRole($role)
    {
        if ($role === 'purchasing') {
            return redirect()->to('/purchasing');
        } elseif ($role === 'manajer') {
            return redirect()->to('/manajer');
        } elseif ($role === 'admin_keuangan') {
            return redirect()->to('/admin');
        }
    }
}
