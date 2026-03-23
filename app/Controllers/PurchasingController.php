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
            // Kita juga sekalian ambil history pengajuan user ini untuk ditampilkan di tabel
            'history_pengajuan' => $this->kasKeluarModel
                                        ->where('id_purchasing', session()->get('id'))
                                        ->orderBy('id', 'DESC')
                                        ->findAll()
        ];
        
        return view('purchasing/pengajuan', $data);
    }

    // Fungsi untuk memproses data dari form dan menyimpan ke database
    public function simpanPengajuan()
    {
        // Ambil data dari form input
        $dataSimpan = [
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            // Kita hilangkan titik/koma ribuan sebelum masuk ke database
            'nominal'           => str_replace(['Rp', '.', ','], '', $this->request->getPost('nominal')),
            'status'            => 'pending', // Default saat baru diajukan
            'id_purchasing'     => session()->get('id') // Ambil ID user yang sedang login
        ];

        // Simpan ke database menggunakan Model
        $this->kasKeluarModel->insert($dataSimpan);

        // Buat pesan sukses sementara menggunakan Flashdata session
        session()->setFlashdata('pesan', 'Pengajuan kas berhasil dikirim ke Manajer Keuangan.');

        // Kembalikan ke halaman form
        return redirect()->to('/purchasing/pengajuan');
    }
}