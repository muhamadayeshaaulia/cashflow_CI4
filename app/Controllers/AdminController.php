<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;
use App\Models\KasKeluarModel;
use App\Models\KasMasukModel;

class AdminController extends BaseController
{
    protected $pengajuanModel;
    protected $kasKeluarModel;
    protected $kasMasukModel;

    public function __construct()
    {
        $this->pengajuanModel = new PengajuanModel();
        $this->kasKeluarModel = new KasKeluarModel();
        $this->kasMasukModel  = new KasMasukModel();
    }

    // 1. DASHBOARD UTAMA ADMIN
public function index()
{
    if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

    // 1. Ambil baris data Kas Masuk (Gunakan nominal_netto sesuai Migration)
    $rowMasuk = $this->kasMasukModel->selectSum('nominal_netto')->get()->getRow();
    
    // 2. Ambil baris data Kas Keluar
    $rowKeluar = $this->kasKeluarModel->selectSum('total_pengajuan')->get()->getRow();

    $data = [
        'title'             => 'Dashboard Admin Keuangan',
        // PERBAIKAN: Gunakan properti ->nominal_netto (sesuai nama kolom di DB)
        'total_kas_masuk'   => $rowMasuk->nominal_netto ?? 0, 
        'total_kas_keluar'  => $rowKeluar->total_pengajuan ?? 0,
        'pengajuan_acc'     => $this->pengajuanModel->where('status', 'acc')->countAllResults(),
    ];
    
    return view('admin/dashboard', $data);
}

    // --- BAGIAN A: PEMBAYARAN VENDOR (KAS KELUAR) ---

    public function pembayaran()
{
    if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

    $data = [
        'title'      => 'Realisasi Pembayaran Vendor',
        'siap_bayar' => $this->pengajuanModel->select('pengajuan.*, kas_keluar.*')
                            ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                            ->where('pengajuan.status', 'acc')
                            ->findAll(),
        
        // PERBAIKAN DI SINI: Pastikan select('pengajuan.*, kas_keluar.*') ada
        'riwayat'    => $this->pengajuanModel->select('pengajuan.*, kas_keluar.*')
                            ->join('kas_keluar', 'kas_keluar.pengajuan_id = pengajuan.id')
                            ->where('pengajuan.status', 'dibayar')
                            ->orderBy('pengajuan.updated_at', 'DESC')
                            ->findAll()
    ];
    return view('admin/pembayaran', $data);
}

    public function prosesBayar()
{
    // Ambil filenya
    $fileBukti = $this->request->getFile('bukti_pembayaran');

    // JALANKAN VALIDASI (Wajib isi & Harus Gambar)
    $validationRule = [
        'bukti_pembayaran' => [
            'label' => 'Bukti Pembayaran',
            'rules' => 'uploaded[bukti_pembayaran]'
                     . '|is_image[bukti_pembayaran]'
                     . '|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png]'
                     . '|max_size[bukti_pembayaran,2048]', // Maksimal 2MB
            'errors' => [
                'uploaded' => 'Anda belum memilih file bukti transfer!',
                'is_image' => 'File yang Anda upload bukan gambar.',
                'mime_in'  => 'Format gambar harus JPG, JPEG, atau PNG.',
                'max_size' => 'Ukuran gambar terlalu besar (Maksimal 2MB).'
            ]
        ]
    ];

    if (!$this->validate($validationRule)) {
        return redirect()->back()->withInput()->with('error', $this->validator->getError('bukti_pembayaran'));
    }

    // JIKA LOLOS VALIDASI, BARU PROSES SIMPAN
    $id_pengajuan = $this->request->getPost('id_pengajuan');
    
    if ($fileBukti->isValid() && !$fileBukti->hasMoved()) {
        $namaBukti = $fileBukti->getRandomName();
        $fileBukti->move('uploads/bukti', $namaBukti);

        $db = \Config\Database::connect();
        $db->transStart();

        // Update Tabel Kas Keluar
        $this->kasKeluarModel->where('pengajuan_id', $id_pengajuan)->set([
            'bukti_pembayaran' => $namaBukti,
            'nip_admin'        => session()->get('nip')
        ])->update();

        // Update Tabel Pengajuan
        $this->pengajuanModel->update($id_pengajuan, ['status' => 'dibayar']);

        $db->transComplete();

        return redirect()->to('/admin/pembayaran')->with('pesan', 'Pembayaran berhasil dikonfirmasi!');
    }
}
    // --- BAGIAN B: PENERIMAAN PROYEK (KAS MASUK) ---

    public function kasMasuk()
    {
        if (session()->get('role') !== 'admin_keuangan') return redirect()->to('/login');

        $data = [
            'title'     => 'Penerimaan Proyek Dinas',
            'kas_masuk' => $this->kasMasukModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/kas_masuk', $data);
    }

    public function simpanKasMasuk()
    {
        // Bersihkan input nominal dari format Rp dan titik
        $brutoRaw = preg_replace('/[^0-9]/', '', $this->request->getPost('nominal_kontrak'));
        $pajakRaw = preg_replace('/[^0-9]/', '', $this->request->getPost('potongan_pajak'));

        $nominal_kontrak = (float) $brutoRaw;
        $potongan_pajak  = (float) ($pajakRaw ?: 0);
        $nominal_netto   = $nominal_kontrak - $potongan_pajak;

        $dataSimpan = [
            'tanggal_pembuatan' => $this->request->getPost('tanggal_pembuatan'),
            'nama_proyek'       => strtoupper($this->request->getPost('nama_proyek')),
            'no_bast'           => $this->request->getPost('no_bast'),
            'no_tagihan'        => $this->request->getPost('no_tagihan'),
            'nominal_kontrak'   => $nominal_kontrak,
            'potongan_pajak'    => $potongan_pajak,
            'nominal_netto'     => $nominal_netto,
            'status'            => 'proses_kirim', 
            'nip_admin'         => session()->get('nip'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        $this->kasMasukModel->insert($dataSimpan);
        return redirect()->to('/admin/kas-masuk')->with('pesan', 'Data penagihan proyek berhasil ditambahkan.');
    }

    public function updateKasMasuk()
    {
        $id   = $this->request->getPost('id_kas_masuk');
        $aksi = $this->request->getPost('aksi_status');
        $dataUpdate = ['updated_at' => date('Y-m-d H:i:s')];

        if ($aksi == 'tagihan_dikirim') {
            $dataUpdate['status'] = 'tagihan_dikirim';
        } 
        elseif ($aksi == 'sp2d_terbit') {
            $dataUpdate['status']  = 'sp2d_terbit';
            $dataUpdate['no_sp2d'] = $this->request->getPost('no_sp2d');
        } 
        elseif ($aksi == 'lunas') {
            $dataUpdate['status'] = 'lunas';
            
            $fileBukti = $this->request->getFile('bukti_transfer');
            if ($fileBukti && $fileBukti->isValid() && !$fileBukti->hasMoved()) {
                $namaBukti = $fileBukti->getRandomName();
                $fileBukti->move('uploads/bukti_masuk', $namaBukti); 
                $dataUpdate['bukti_transfer'] = $namaBukti;
            }
        }

        $this->kasMasukModel->update($id, $dataUpdate);
        return redirect()->to('/admin/kas-masuk')->with('pesan', 'Status Kas Masuk berhasil diperbarui.');
    }
}