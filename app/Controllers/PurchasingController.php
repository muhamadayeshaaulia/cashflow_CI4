<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class PurchasingController extends BaseController
{
   public function index()
    {
        // Pastikan hanya role 'purchasing' yang bisa akses
        if (session()->get('role') !== 'purchasing') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard Purchasing'
        ];
        
        return view('purchasing/dashboard', $data);
    }
}
