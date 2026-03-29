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
        
        // Perbaikan Query Dashboard: Ambil getRow() dengan cara yang benar
        $totalDanaRow = $this->kasKeluarModel->where('nip_purchasing', $nip)
                                            ->selectSum('total_pengajuan')
                                            ->get()->getRow();

        $data = [
            'title'           => 'Dashboard Purchasing',
            // kalau kosong hasilnya 0
            'total_pengajuan' => $totalDanaRow->total_pengajuan ?? 0,
            'jumlah_pending'  => $this->kasKeluarModel->where(['nip_purchasing' => $nip, 'status' => 'pending'])->countAllResults(),
            // Tampilkan 5 pengajuan terakhir
            'history_dashboard' => $this->kasKeluarModel->where('nip_purchasing', $nip)->orderBy('id', 'DESC')->limit(5)->findAll()
        ];
        return view('purchasing/dashboard', $data);
    }

    public function pengajuan()
    {
        if (session()->get('role') !== 'purchasing') return redirect()->to('/login');

        $data = [
            'title'             => 'Form Pengajuan Kas Keluar',
            'history_pengajuan' => $this->kasKeluarModel
                                        ->where('nip_purchasing', session()->get('nip'))
                                        ->orderBy('id', 'DESC')
                                        ->findAll()
        ];
        
        return view('purchasing/pengajuan', $data);
    }

   public function simpanPengajuan()
{
    $nominal = (float) $this->request->getPost('nominal_barang');
    $ongkir  = (float) $this->request->getPost('biaya_ongkir');
    
    // LOGIC BARU: Cek apakah input 'pakai_ppn' ada nilainya (apapun nilainya asal dicentang)
    $pajak = 0;
    if ($this->request->getPost('pakai_ppn')) {
        $pajak = round($nominal * 0.12);
    }

    $total = $nominal + $pajak + $ongkir;

    // Ambil Nama Bank
    $bank = $this->request->getPost('bank_vendor');
    if ($bank === 'LAINNYA') {
        $bank = $this->request->getPost('bank_manual');
    }

    $this->kasKeluarModel->insert([
        'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
        'divisi_peminta'    => $this->request->getPost('divisi_peminta'),
        'deskripsi'         => $this->request->getPost('deskripsi'),
        'nama_vendor'       => strtoupper($this->request->getPost('nama_vendor')),
        'bank_vendor'       => strtoupper($bank),
        'rekening_vendor'   => preg_replace('/\D/', '', $this->request->getPost('rekening_vendor')),
        'nominal_barang'    => $nominal,
        'pajak_ppn'         => $pajak,
        'biaya_ongkir'      => $ongkir,
        'total_pengajuan'   => $total,
        'status'            => 'pending',
        'nip_purchasing'    => session()->get('nip'),
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s'),
    ]);

    return redirect()->to('/purchasing/pengajuan')->with('pesan', 'Pengajuan Berhasil!');
}
}