<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel; // Panggil modelnya

class PurchasingController extends BaseController
{
    protected $kasKeluarModel;

    public function __construct()
    {
        // Inisialisasi Model agar bisa dipakai di semua fungsi
        $this->kasKeluarModel = new KasKeluarModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'purchasing') return redirect()->to('/login');
        
        $data = ['title' => 'Dashboard Purchasing'];
        return view('purchasing/dashboard', $data);
    }

    // Fungsi untuk menampilkan halaman Form Pengajuan
    public function pengajuan()
    {
        if (session()->get('role') !== 'purchasing') return redirect()->to('/login');

        $data = [
            'title' => 'Form Pengajuan Kas Keluar',
            // ambil history pengajuan user ini untuk ditampilkan di tabel
            'history_pengajuan' => $this->kasKeluarModel
                                        ->where('nip_purchasing', session()->get('nip'))
                                        ->orderBy('id', 'DESC')
                                        ->findAll()
        ];
        
        return view('purchasing/pengajuan', $data);
    }

    // Fungsi untuk memproses data dari form dan menyimpan ke database
    public function simpanPengajuan()
    {
        $this->kasKeluarModel->insert([
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'divisi_peminta'    => $this->request->getPost('divisi_peminta'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            'nominal'           => str_replace(['Rp', '.', ','], '', $this->request->getPost('nominal')),
            'status'            => 'pending',
            //Menggunakan NIP dari session
            'nip_purchasing'    => session()->get('nip') 
        ]);

        session()->setFlashdata('pesan', 'Pengajuan berhasil dikirim ke Manajer.');
        return redirect()->to('/purchasing/pengajuan');
    }
}