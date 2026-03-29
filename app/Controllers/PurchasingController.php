<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel;

class PurchasingController extends BaseController
{
    protected $kasKeluarModel;

    public function __construct()
    {
        $this->kasKeluarModel = new KasKeluarModel();
    }

    public function index()
{
    if (session()->get('role') !== 'purchasing') return redirect()->to('/login');
    
    $nip = session()->get('nip');
    $pengajuanModel = new \App\Models\PengajuanModel(); // Kita butuh ini buat cek status & NIP
    
    // Hitung Total Dana yang pernah diajukan (Harus Join karena nominal ada di kas_keluar)
    $totalDanaRow = $pengajuanModel->selectSum('kas_keluar.total_pengajuan')
                                   ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                   ->where('pengajuan.nip_purchasing', $nip)
                                   ->get()->getRow();

    $data = [
        'title'           => 'Dashboard Purchasing',
        'total_pengajuan' => $totalDanaRow->total_pengajuan ?? 0,
        
        // Hitung jumlah yang masih PENDING (Ambil dari tabel pengajuan)
        'jumlah_pending'  => $pengajuanModel->where([
                                    'nip_purchasing' => $nip, 
                                    'status'         => 'pending'
                                 ])->countAllResults(),
                                 
        // History 5 terakhir (Join biar dapet data lengkap)
        'history_dashboard' => $pengajuanModel->select('pengajuan.*, kas_keluar.total_pengajuan, kas_keluar.nama_vendor')
                                             ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                             ->where('pengajuan.nip_purchasing', $nip)
                                             ->orderBy('pengajuan.id', 'DESC')
                                             ->limit(5)
                                             ->findAll()
    ];

    return view('purchasing/dashboard', $data);
}
    public function pengajuan()
{
    if (session()->get('role') !== 'purchasing') return redirect()->to('/login');

    $pengajuanModel = new \App\Models\PengajuanModel();

    $data = [
        'title'             => 'Form Pengajuan Kas Keluar',
        // Kita Join supaya dapet data dari dua tabel sekaligus
        'history_pengajuan' => $pengajuanModel->select('pengajuan.*, kas_keluar.*')
                                    ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                                    ->where('nip_purchasing', session()->get('nip'))
                                    ->orderBy('pengajuan.id', 'DESC')
                                    ->findAll()
    ];
    
    return view('purchasing/pengajuan', $data);
}

   public function simpanPengajuan()
{
    // Load Model-nya dulu
    $pengajuanModel = new \App\Models\PengajuanModel();
    $kasKeluarModel = new \App\Models\KasKeluarModel();
    $db = \Config\Database::connect();

    // --- MULAI TRANSAKSI ---
    $db->transStart();

    // GENERATE NOMOR PENGAJUAN (SRL-TGL-URUT)
    $tgl = date('dmY');
    $prefix = "SRL-" . $tgl . "-";
    
    // Cari nomor terakhir hari ini di tabel pengajuan
    $lastEntry = $pengajuanModel->like('no_pengajuan', $prefix, 'after')
                                ->orderBy('id', 'DESC')
                                ->first();

    if ($lastEntry) {
        $lastNo = substr($lastEntry['no_pengajuan'], -3);
        $nextNo = str_pad((int)$lastNo + 1, 3, '0', STR_PAD_LEFT);
    } else {
        $nextNo = "001";
    }
    $no_pengajuan = $prefix . $nextNo;

    // SIMPAN KE TABEL INDUK (pengajuan)
    $pengajuanModel->insert([
        'no_pengajuan'      => $no_pengajuan,
        'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
        'divisi_peminta'    => $this->request->getPost('divisi_peminta'),
        'deskripsi'         => $this->request->getPost('deskripsi'),
        'status'            => 'pending',
        'nip_purchasing'    => session()->get('nip'),
    ]);

    // AMBIL ID barusan untuk relasi ke tabel detail
    $pengajuan_id = $pengajuanModel->getInsertID();

    // HITUNG NOMINAL
    $nominal = (float) $this->request->getPost('nominal_barang');
    $ongkir  = (float) $this->request->getPost('biaya_ongkir');
    $pajak   = $this->request->getPost('pakai_ppn') ? round($nominal * 0.12) : 0;
    $total   = $nominal + $pajak + $ongkir;

    // Cek Bank Manual
    $bank = $this->request->getPost('bank_vendor');
    if ($bank === 'LAINNYA') {
        $bank = $this->request->getPost('bank_manual');
    }

    // SIMPAN KE TABEL DETAIL (kas_keluar)
    $kasKeluarModel->insert([
        'pengajuan_id'     => $pengajuan_id,
        'nama_vendor'      => strtoupper($this->request->getPost('nama_vendor')),
        'bank_vendor'      => strtoupper($bank),
        'rekening_vendor'  => preg_replace('/\D/', '', $this->request->getPost('rekening_vendor')),
        'nominal_barang'   => $nominal,
        'pajak_ppn'        => $pajak,
        'biaya_ongkir'     => $ongkir,
        'total_pengajuan'  => $total,
    ]);

    // --- SELESAI TRANSAKSI ---
    $db->transComplete();

    if ($db->transStatus() === FALSE) {
        return redirect()->back()->with('error', 'Gagal menyimpan data pengajuan!');
    }

    return redirect()->to('/purchasing/pengajuan')->with('pesan', 'Pengajuan ' . $no_pengajuan . ' Berhasil Dikirim!');
}
}