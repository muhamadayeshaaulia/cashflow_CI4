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
        // memastikan role sesuai dengan session kamu
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        $data = [
            'title' => 'Dashboard Manajer Keuangan',
            'total_pending' => $this->kasKeluarModel->where('status', 'pending')->countAllResults(),
            'total_acc'     => $this->kasKeluarModel->where('status', 'acc')->countAllResults(),
            'total_rupiah'  => $this->kasKeluarModel->where('status', 'pending')->selectSum('total_pengajuan')->get()->getRow()->total_pengajuan ?? 0
        ];
        return view('manajer/dashboard', $data);
    }

    public function persetujuan()
    {
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        $data = [
            'title' => 'Verifikasi & Otorisasi Pengeluaran Kas',
            // Ambil data pending, urutkan yang terbaru di atas
            'pengajuan_pending' => $this->kasKeluarModel->where('status', 'pending')->orderBy('id', 'DESC')->findAll()
        ];
        
        return view('manajer/persetujuan', $data);
    }

   public function updateStatus()
    {
        $id = $this->request->getPost('id_pengajuan');
        $status_baru = $this->request->getPost('status_aksi');

        // memastikan ID ada sebelum update
        if (!$id) {
            return redirect()->back()->with('error', 'ID Pengajuan tidak ditemukan.');
        }

        $this->kasKeluarModel->update($id, [
            'status'      => $status_baru,
            'nip_manajer' => session()->get('nip'),
            'tgl_disetujui' => date('Y-m-d H:i:s') // buat audit laporan nanti
        ]);

        $pesan = ($status_baru == 'acc') ? 'Pengajuan berhasil di-ACC.' : 'Pengajuan telah ditolak.';
        session()->setFlashdata('pesan', $pesan);

        return redirect()->to('/manajer/persetujuan');
    }
}