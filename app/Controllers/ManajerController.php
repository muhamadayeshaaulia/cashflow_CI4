<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\KasKeluarModel;

class ManajerController extends BaseController
{
    protected $pengajuanModel;
    protected $kasKeluarModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->kasKeluarModel = new KasKeluarModel();
    }

    public function index()
    {
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        // Untuk Dashboard, kita hitung dari tabel pengajuan
        $data = [
            'title' => 'Dashboard Manajer Keuangan',
            'total_pending' => $this->pengajuanModel->where('status', 'pending')->countAllResults(),
            'total_acc'     => $this->pengajuanModel->where('status', 'acc')->countAllResults(),
            // Untuk total rupiah, kita harus join karena kolom uang ada di kas_keluar
            'total_rupiah'  => $this->pengajuanModel->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                                   ->where('pengajuan.status', 'pending')
                                                   ->selectSum('kas_keluar.total_pengajuan')
                                                   ->get()->getRow()->total_pengajuan ?? 0
        ];
        return view('manajer/dashboard', $data);
    }

    public function persetujuan()
    {
        if (session()->get('role') !== 'manajer') return redirect()->to('/login');

        $data = [
            'title' => 'Verifikasi & Otorisasi Pengeluaran Kas',
            // AMBIL DATA DARI DUA TABEL (JOIN)
            'pengajuan_pending' => $this->pengajuanModel->select('pengajuan.*, kas_keluar.*')
                                                       ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                                       ->where('pengajuan.status', 'pending')
                                                       ->orderBy('pengajuan.id', 'DESC')
                                                       ->findAll()
        ];
        
        return view('manajer/persetujuan', $data);
    }

    public function updateStatus()
    {
        $id = $this->request->getPost('id_pengajuan'); // Ini ID dari tabel pengajuan
        $status_baru = $this->request->getPost('status_aksi');

        if (!$id) {
            return redirect()->back()->with('error', 'ID Pengajuan tidak ditemukan.');
        }

        // UPDATE STATUS DI TABEL PENGAJUAN (Bukan KasKeluar)
        $this->pengajuanModel->update($id, [
            'status'      => $status_baru,
            'nip_manajer' => session()->get('nip'),
            'updated_at'  => date('Y-m-d H:i:s')
        ]);

        $pesan = ($status_baru == 'acc') ? 'Pengajuan berhasil di-ACC.' : 'Pengajuan telah ditolak.';
        session()->setFlashdata('pesan', $pesan);

        return redirect()->to('/manajer/persetujuan');
    }

    public function history()
{
    if (session()->get('role') !== 'manajer') return redirect()->to('/login');

    $data = [
        'title' => 'History Verifikasi Pengajuan',
        // Ambil data yang statusnya BUKAN pending (acc, ditolak, atau dibayar)
        'history_verifikasi' => $this->pengajuanModel->select('pengajuan.*, kas_keluar.*')
                                                    ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                                    ->whereIn('pengajuan.status', ['acc', 'ditolak', 'dibayar'])
                                                    ->orderBy('pengajuan.updated_at', 'DESC')
                                                    ->findAll()
    ];
    
    return view('manajer/history', $data);
}
}