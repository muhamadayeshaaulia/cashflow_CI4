<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KasKeluarModel;
use App\Models\KasMasukModel;

class AdminController extends BaseController
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
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');
        return view('admin/dashboard', ['title' => 'Dashboard Admin Keuangan']);
    }

    // PEMBAYARAN VENDOR (KAS KELUAR)
    public function pembayaran()
    {
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

        $data = [
            'title'      => 'Realisasi Pembayaran Vendor',
            'siap_bayar' => $this->kasKeluarModel->where('status', 'acc')->findAll(),
            'riwayat'    => $this->kasKeluarModel->where('status', 'dibayar')->orderBy('updated_at', 'DESC')->findAll()
        ];
        return view('admin/pembayaran', $data);
    }

    public function prosesBayar()
    {
        $id = $this->request->getPost('id_pengajuan');
        $fileBukti = $this->request->getFile('bukti_pembayaran');
        $namaBukti = null;

        if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
            $namaBukti = $fileBukti->getRandomName();
            $fileBukti->move('uploads/bukti', $namaBukti);
        }

        // ambil dari session 'nip'
        $this->kasKeluarModel->update($id, [
            'status'           => 'dibayar',
            'nip_admin'        => session()->get('nip'), 
            'bukti_pembayaran' => $namaBukti
        ]);

        session()->setFlashdata('pesan', 'Pembayaran berhasil diproses.');
        return redirect()->to('/admin/pembayaran');
    }

    // PENERIMAAN DINAS (KAS MASUK)
    public function kasMasuk()
    {
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

        $data = [
            'title'     => 'Penerimaan Proyek Dinas',
            // Menampilkan semua data kas masuk, diurutkan dari yang terbaru
            'kas_masuk' => $this->kasMasukModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/kas_masuk', $data);
    }

    // Menyimpan penagihan baru ke Dinas
    public function simpanKasMasuk()
    {
        $dataSimpan = [
            'tanggal_pembuatan' => $this->request->getPost('tanggal_pembuatan'),
            'no_bast'           => $this->request->getPost('no_bast'),
            'no_tagihan'        => $this->request->getPost('no_tagihan'),
            'nominal'           => str_replace(['Rp', '.', ','], '', $this->request->getPost('nominal')),
            'status'            => 'proses_kirim', // Status awal saat dokumen BAST dibuat
            'nip_admin'         => session()->get('nip') 
        ];

        $this->kasMasukModel->insert($dataSimpan);
        session()->setFlashdata('pesan', 'Data penagihan proyek dinas berhasil ditambahkan.');
        return redirect()->to('/admin/kas-masuk');
    }

    // Mengupdate status pergerakan dokumen/uang
    public function updateKasMasuk()
    {
        $id   = $this->request->getPost('id_kas_masuk');
        $aksi = $this->request->getPost('aksi_status');

        $dataUpdate = [];

        // Logika pergerakan status
        if ($aksi == 'tagihan_dikirim') {
            $dataUpdate['status'] = 'tagihan_dikirim';
            session()->setFlashdata('pesan', 'Status: Tagihan telah dikirim ke dinas.');
        
        } elseif ($aksi == 'sp2d_terbit') {
            $dataUpdate['status']  = 'sp2d_terbit';
            $dataUpdate['no_sp2d'] = $this->request->getPost('no_sp2d');
            session()->setFlashdata('pesan', 'Status: SP2D berhasil diterbitkan.');
        
        } elseif ($aksi == 'lunas') {
            $dataUpdate['status'] = 'lunas';
            
            // Proses upload bukti transfer uang masuk dari dinas
            $fileBukti = $this->request->getFile('bukti_transfer');
            if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
                $namaBukti = $fileBukti->getRandomName();
                // Kita pisahkan foldernya ke bukti_masuk agar rapi
                $fileBukti->move('uploads/bukti_masuk', $namaBukti); 
                $dataUpdate['bukti_transfer'] = $namaBukti;
            }
            session()->setFlashdata('pesan', 'Hore! Pembayaran dari Dinas telah diterima (Lunas).');
        }

        $this->kasMasukModel->update($id, $dataUpdate);
        return redirect()->to('/admin/kas-masuk');
    }
}