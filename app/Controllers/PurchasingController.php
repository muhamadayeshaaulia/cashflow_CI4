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
        $data = [
            'title' => 'Dashboard Purchasing',
            // Data tambahan untuk dashboard agar tidak kosong
            'total_pengajuan' => $this->kasKeluarModel->where('nip_purchasing', $nip)->selectSum('total_pengajuan')->get()->getRow()->total_pengajuan ?? 0,
            'jumlah_pending'  => $this->kasKeluarModel->where(['nip_purchasing' => $nip, 'status' => 'pending'])->countAllResults()
        ];
        return view('purchasing/dashboard', $data);
    }

    public function pengajuan()
    {
        if (session()->get('role') !== 'purchasing') return redirect()->to('/login');

        $data = [
            'title' => 'Form Pengajuan Kas Keluar',
            'history_pengajuan' => $this->kasKeluarModel
                                        ->where('nip_purchasing', session()->get('nip'))
                                        ->orderBy('id', 'DESC')
                                        ->findAll()
        ];
        
        return view('purchasing/pengajuan', $data);
    }

    public function simpanPengajuan()
    {
        $rek_raw = $this->request->getPost('rekening_vendor');
        $rek_clean = preg_replace('/\D/', '', $rek_raw);
        // Ambil data nominal
        $nominal = $this->request->getPost('nominal_barang') ?? 0;
        $pajak   = $this->request->getPost('pajak_ppn') ?? 0;
        $ongkir  = $this->request->getPost('biaya_ongkir') ?? 0;
        $total   = $nominal + $pajak + $ongkir;

        // Simpan ke Database
        $this->kasKeluarModel->insert([
            'tanggal_pengajuan' => $this->request->getPost('tanggal_pengajuan'),
            'divisi_peminta'    => $this->request->getPost('divisi_peminta'),
            'deskripsi'         => $this->request->getPost('deskripsi'),
            'nama_vendor'       => $this->request->getPost('nama_vendor'),
            'bank_vendor'       => $this->request->getPost('bank_vendor'),
            'rekening_vendor'   => $rek_clean,
            'nominal_barang'    => $nominal,
            'pajak_ppn'         => $pajak,
            'biaya_ongkir'      => $ongkir,
            'total_pengajuan'   => $total,
            'status'            => 'pending',
            'nip_purchasing'    => session()->get('nip')
        ]);

        return redirect()->to('/purchasing/pengajuan')->with('pesan', 'Pengajuan Berhasil Dikirim!');
    }
}