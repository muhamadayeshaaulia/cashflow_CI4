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
        $nominal = $this->request->getPost('nominal_barang') ?? 0;
        $pajak   = $this->request->getPost('pajak_ppn') ?? 0;
        $ongkir  = $this->request->getPost('biaya_ongkir') ?? 0;
        $total   = $nominal + $pajak + $ongkir; // Hitung Total Otomatis

        $this->kasKeluarModel->insert([
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'divisi_peminta'    => $this->request->getPost('divisi_peminta'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            'nama_vendor'       => $this->request->getPost('nama_vendor'),
            'bank_vendor'       => $this->request->getPost('bank_vendor'),
            'rekening_vendor'   => $this->request->getPost('rekening_vendor'),
            'nominal_barang'    => $nominal,
            'pajak_ppn'         => $pajak,
            'biaya_ongkir'      => $ongkir,
            'total_pengajuan'   => $total,
            'status'            => 'pending',
            'nip_purchasing'    => session()->get('nip')
        ]);

        return redirect()->to('/purchasing/pengajuan')->with('pesan', 'Pengajuan berhasil dikirim.');
    }
}