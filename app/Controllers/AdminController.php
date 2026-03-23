<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel;

class AdminController extends BaseController
{
    protected $kasKeluarModel;

    public function __construct()
    {
        // Panggil model kas keluar agar admin bisa baca data yang sudah di-ACC
        $this->kasKeluarModel = new KasKeluarModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

        $data = ['title' => 'Dashboard Admin Keuangan'];
        return view('admin/dashboard', $data);
    }

    // Fungsi untuk menampilkan halaman pembayaran
    public function pembayaran()
    {
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

        $data = [
            'title' => 'Realisasi Pembayaran Vendor',
            // Ambil data yang siap dibayar (status: acc)
            'siap_bayar' => $this->kasKeluarModel->where('status', 'acc')->findAll(),
            // Ambil data riwayat yang sudah selesai dibayar
            'riwayat' => $this->kasKeluarModel->where('status', 'dibayar')->orderBy('updated_at', 'DESC')->findAll()
        ];
        
        return view('admin/pembayaran', $data);
    }

    // Fungsi untuk memproses upload bukti transfer
    public function prosesBayar()
    {
        $id = $this->request->getPost('id_pengajuan');
        
        // Menangkap file yang diupload
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $namaBukti = null;

        // Cek apakah ada file yang diupload dan valid
        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            // Hasilkan nama file acak agar aman dan tidak menimpa file bernama sama
            $namaBukti = $fileBukti->getRandomName();
            
            // Pindahkan file ke folder public/uploads/bukti
            $fileBukti->move('uploads/bukti', $namaBukti);
        }

        // Update status di database menjadi 'dibayar' beserta nama file buktinya
        $this->kasKeluarModel->update($id, [
            'status'           => 'dibayar',
            'id_admin'         => session()->get('id'), // Catat siapa admin yang bayar
            'bukti_pembayaran' => $namaBukti
        ]);

        // CATATAN UNTUK NANTI: Di baris ini nanti kita akan tambahkan 
        // kode otomatis untuk memasukkan data ke tabel Jurnal Akuntansi.

        session()->setFlashdata('pesan', 'Pembayaran berhasil diproses dan bukti telah disimpan.');
        return redirect()->to('/admin/pembayaran');
    }
}