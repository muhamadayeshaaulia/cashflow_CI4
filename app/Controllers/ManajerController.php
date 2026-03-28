<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel;

class ManajerController extends BaseController
{
    protected $kasKeluarModel;

    public function __construct()
    {
        $this->kasKeluarModel = new KasKeluarModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        $data = ['title' => 'Dashboard Manajer Keuangan'];
        return view('manajer/dashboard', $data);
    }

    // Menampilkan daftar pengajuan yang butuh ACC (status: pending)
    public function persetujuan()
    {
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        $data = [
            'title' => 'Verifikasi & Otorisasi Pengeluaran Kas',
            // ambil data yang statusnya 'pending'
            'pengajuan_pending' => $this->kasKeluarModel->where('status', 'pending')->orderBy('id', 'DESC')->findAll()
        ];
        
        return view('manajer/persetujuan', $data);
    }

    // Memproses tombol ACC atau Tolak
   public function updateStatus()
    {
        $id = $this->request->getPost('id_pengajuan');
        $status_baru = $this->request->getPost('status_aksi');

        // Update status dan rekam NIP Manajer yang ACC/Tolak
        $this->kasKeluarModel->update($id, [
            'status'      => $status_baru,
            'nip_manajer' => session()->get('nip') 
        ]);

        $pesan = ($status_baru == 'acc') ? 'Pengajuan berhasil di-ACC.' : 'Pengajuan telah ditolak.';
        session()->setFlashdata('pesan', $pesan);

        return redirect()->to('/manajer/persetujuan');
    }
}