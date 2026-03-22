<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
   public function index()
    {
        if (session()->get('role') !== 'admin_keuangan') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard Admin Keuangan'
        ];
        
        return view('admin/dashboard', $data);
    }
}
