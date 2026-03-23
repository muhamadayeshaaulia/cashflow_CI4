<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel;
use App\Models\KasMasukModel;

class LaporanController extends BaseController
{
    protected $kasKeluarModel;
    protected $kasMasukModel;

    public function __construct()
    {
        $this->kasKeluarModel = new KasKeluarModel();
        $this->kasMasukModel  = new KasMasukModel();
    }

    public function index()
    {
        // Hanya Manajer dan Admin Keuangan yang boleh melihat laporan ini
        $role = session()->get('role');
        if ($role !== 'manajer' && $role !== 'admin_keuangan') {
            return redirect()->to('/login');
        }

        // Ambil data Kas Masuk (Hanya yang statusnya 'lunas')
        $dataMasuk = $this->kasMasukModel->where('status', 'lunas')->findAll();
        
        // Ambil data Kas Keluar (Hanya yang statusnya 'dibayar')
        $dataKeluar = $this->kasKeluarModel->where('status', 'dibayar')->findAll();

        $buku_besar = [];

        // Format Data Kas Masuk menjadi format Jurnal
        foreach ($dataMasuk as $masuk) {
            $buku_besar[] = [
                // Prioritaskan tanggal updated_at (tanggal saat dilunasi)
                'tanggal'    => date('Y-m-d', strtotime($masuk['updated_at'] ?? $masuk['tanggal_pembuatan'])),
                'keterangan' => 'Penerimaan Proyek - BAST: ' . $masuk['no_bast'] . ' (Tagihan: ' . $masuk['no_tagihan'] . ')',
                'kategori'   => 'masuk',
                'nominal'    => $masuk['nominal']
            ];
        }

        // Format Data Kas Keluar menjadi format Jurnal
        foreach ($dataKeluar as $keluar) {
            $buku_besar[] = [
                // Prioritaskan tanggal updated_at (tanggal saat dibayar admin)
                'tanggal'    => date('Y-m-d', strtotime($keluar['updated_at'] ?? $keluar['tanggal_pengajuan'])),
                'keterangan' => 'Pembayaran Vendor - ' . $keluar['deskripsi'],
                'kategori'   => 'keluar',
                'nominal'    => $keluar['nominal']
            ];
        }

        // Urutkan gabungan data berdasarkan Tanggal (dari yang terlama ke terbaru)
        // Ini fungsi bawaan PHP yang sangat berguna untuk menyusun kronologi buku besar
        usort($buku_besar, function($a, $b) {
            return strtotime($a['tanggal']) - strtotime($b['tanggal']);
        });

        $data = [
            'title'      => 'Laporan Cash Flow',
            'buku_besar' => $buku_besar
        ];

        return view('laporan/index', $data);
    }
}