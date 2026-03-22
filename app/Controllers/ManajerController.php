<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ManajerController extends BaseController
{
   public function index()
    {
        if (session()->get('role') !== 'manajer') {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard Manajer Keuangan'
        ];
        
        return view('manajer/dashboard', $data);
    }
}
