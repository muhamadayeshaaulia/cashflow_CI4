<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* GEDEIN SEMUA INPUTAN BIAR MANTAP DI HP */
    .form-control, .form-select, .select2-container--default .select2-selection--single {
        height: 50px !important; 
        font-size: 1.05rem !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 48px !important;
        padding-left: 15px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 48px !important;
    }

    .label-gede {
        font-size: 0.9rem;
        font-weight: 700;
        color: #566a7f;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
    }

    @media (max-width: 991.98px) {
        .row-flex-mobile { display: flex; flex-direction: column; }
        .col-mobile-full { width: 100% !important; margin-bottom: 25px; }
        .col-sm-mobile { width: 100% !important; margin-bottom: 15px; }
    }
</style>

<div class="container-fluid p-0">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Operasional /</span> Pengajuan Dana Proyek</h4>

    <div class="row row-flex-mobile">
        <div class="col-md-7 col-mobile-full">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-pencil-square me-2"></i> Form Pengajuan</h5>
                </div>
                <div class="card-body pt-4">
                    <form action="<?= base_url('/purchasing/pengajuan/simpan') ?>" method="POST">
                        
                        <div class="row">
                            <div class="col-md-5 col-sm-mobile mb-3">
                                <label class="label-gede">Tanggal Pengajuan</label>
                                <input type="date" class="form-control border-primary" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required />
                            </div>
                            <div class="col-md-7 col-sm-mobile mb-3">
                                <label class="label-gede">Nama Proyek / Dinas</label>
                                <input type="text" class="form-control border-primary" name="divisi_peminta" placeholder="Contoh: Kantor desa Kab.tng" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="label-gede">Keperluan Barang / Jasa</label>
                            <textarea name="deskripsi" class="form-control border-primary" rows="3" placeholder="Sebutkan detail barang atau jasa..." required></textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="text-primary mb-3 fw-bold"><i class="bi bi-bank me-2"></i> INFORMASI REKENING TUJUAN</h6>
                        
                        <div class="mb-4">
                            <label class="label-gede">Pilih Bank / E-Wallet</label>
                            <select name="bank_vendor" id="bank_vendor" class="form-select select2-enable" required>
                                <option value="">-- Cari Nama Bank (Ketik di sini) --</option>
                                
                                <optgroup label="BANK NASIONAL (BUMN & SWASTA)">
                                    <option value="MANDIRI">BANK MANDIRI</option>
                                    <option value="BCA">BCA (BANK CENTRAL ASIA)</option>
                                    <option value="BNI">BNI (BANK NEGARA INDONESIA)</option>
                                    <option value="BRI">BRI (BANK RAKYAT INDONESIA)</option>
                                    <option value="BSI">BSI (BANK SYARIAH INDONESIA)</option>
                                    <option value="BTN">BTN (BANK TABUNGAN NEGARA)</option>
                                    <option value="DANAMON">BANK DANAMON</option>
                                    <option value="CIMB">CIMB NIAGA</option>
                                    <option value="PERMATA">BANK PERMATA</option>
                                    <option value="MAYBANK">MAYBANK INDONESIA</option>
                                    <option value="PANIN">BANK PANIN</option>
                                    <option value="OCBC">OCBC INDONESIA (NISP)</option>
                                    <option value="MEGA">BANK MEGA</option>
                                    <option value="SINARMAS">BANK SINARMAS</option>
                                    <option value="BUKOPIN">KB BUKOPIN</option>
                                    <option value="MUAMALAT">BANK MUAMALAT</option>
                                    <option value="MESTIKA">BANK MESTIKA</option>
                                </optgroup>

                                <optgroup label="BANK DIGITAL & E-WALLET">
                                    <option value="SEABANK">SEABANK (PT BANK SEABANK INDONESIA)</option>
                                    <option value="BANK JAGO">BANK JAGO</option>
                                    <option value="NEO COMMERCE">BANK NEO COMMERCE (BNC)</option>
                                    <option value="BLU">BLU BY BCA DIGITAL</option>
                                    <option value="JENIUS">JENIUS (BTPN)</option>
                                    <option value="ALLO BANK">ALLO BANK</option>
                                    <option value="LINE BANK">LINE BANK (KEB HANA)</option>
                                    <option value="DIGIBANK">DIGIBANK (DBS)</option>
                                    <option value="TMRW">TMRW (UOB)</option>
                                    <option value="RAYA">BANK RAYA (BRI GROUP)</option>
                                    <option value="ALADIN">BANK ALADIN SYARIAH</option>
                                    <option value="KROM">KROM BANK INDONESIA</option>
                                    <option value="SAQU">BANK SAQU</option>
                                    <option value="DANA">DANA (E-WALLET)</option>
                                    <option value="OVO">OVO (E-WALLET)</option>
                                    <option value="GOPAY">GOPAY / LINKAJA</option>
                                </optgroup>

                                <optgroup label="BANK PEMBANGUNAN DAERAH (BPD SELURUH INDONESIA)">
                                    <option value="ACEH">BANK ACEH SYARIAH</option>
                                    <option value="SUMUT">BANK SUMUT</option>
                                    <option value="NAGARI">BANK NAGARI (SUMATERA BARAT)</option>
                                    <option value="RIAU">BANK RIAU KEPRI SYARIAH</option>
                                    <option value="JAMBI">BANK JAMBI</option>
                                    <option value="BENGKULU">BANK BENGKULU</option>
                                    <option value="SUMSEL">BANK SUMSEL BABEL</option>
                                    <option value="LAMPUNG">BANK LAMPUNG</option>
                                    <option value="DKI">BANK DKI</option>
                                    <option value="BJB">BANK BJB (JAWA BARAT & BANTEN)</option>
                                    <option value="JATENG">BANK JATENG</option>
                                    <option value="DIY">BANK BPD DIY</option>
                                    <option value="JATIM">BANK JATIM</option>
                                    <option value="BALI">BANK BPD BALI</option>
                                    <option value="NTB">BANK NTB SYARIAH</option>
                                    <option value="NTT">BANK NTT</option>
                                    <option value="KALBAR">BANK KALBAR</option>
                                    <option value="KALSEL">BANK KALSEL</option>
                                    <option value="KALTENG">BANK KALTENG</option>
                                    <option value="KALTIM">BANK KALTIMTARA</option>
                                    <option value="SULUT">BANK SULUTGO</option>
                                                                <option value="SULTENG">BANK SULTENG</option>
                                                                <option value="SULTRA">BANK SULTRA</option>
                                    <option value="SULSEL">BANK SULSELBAR</option>
                                    <option value="MALUKU">BANK MALUKU MALUT</option>
                                    <option value="PAPUA">BANK PAPUA</option>
                                </optgroup>

                                <option value="LAINNYA">-- INPUT NAMA BANK MANUAL --</option>
                            </select>
                        </div>

                        <div class="mb-4 d-none" id="input_bank_lainnya">
                            <label class="label-gede text-danger">Nama Bank Manual</label>
                            <input type="text" class="form-control border-danger" name="bank_manual" placeholder="Ketik nama bank...">
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-mobile mb-3">
                                <label class="label-gede">Nomor Rekening / E-Wallet</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control border-primary" 
                                           id="rekening_vendor" 
                                           name="rekening_vendor" 
                                           inputmode="numeric" 
                                           pattern="[0-9]*" 
                                           placeholder="Contoh: 0812345678" 
                                           required>
                                    <button class="btn btn-primary px-3" type="button" id="btnPasteManual">PASTE</button>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-mobile mb-3">
                                <label class="label-gede">Atas Nama (A/N)</label>
                                <input type="text" class="form-control border-primary text-uppercase bg-light" name="nama_vendor" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-secondary fw-bold mb-0">RINCIAN NOMINAL</h6>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pakai_ppn" checked style="transform: scale(1.3);">
                                <label class="form-check-label fw-bold small ms-2" for="pakai_ppn">PPN 12%</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 col-sm-mobile mb-3">
                                <label class="label-gede">Harga Barang</label>
                                <input type="text" class="form-control border-primary text-primary" id="nominal_barang" name="nominal_barang" value="0" inputmode="numeric" style="font-size: 1.3rem !important;">
                            </div>
                            <div class="col-md-4 col-sm-mobile mb-3">
                                <label class="label-gede">Pajak (12%)</label>
                                <input type="text" class="form-control bg-light text-danger" id="pajak_ppn" name="pajak_ppn" value="0" readonly style="font-size: 1.3rem !important;">
                            </div>
                            <div class="col-md-4 col-sm-mobile mb-3">
                                <label class="label-gede">Ongkos Kirim</label>
                                <input type="text" class="form-control border-secondary" id="biaya_ongkir" name="biaya_ongkir" value="0" inputmode="numeric" style="font-size: 1.3rem !important;">
                            </div>
                        </div>

                        <div class="alert alert-primary py-4 d-flex justify-content-between align-items-center mt-3 border-0 shadow-sm" style="background-color: #e7e7ff;">
                            <span class="fw-bold text-dark">TOTAL PENGAJUAN :</span>
                            <h2 class="mb-0 text-primary fw-bold" id="label_total">Rp 0</h2>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 mt-3 shadow-lg fw-bold fs-4">
                            <i class="bi bi-send-check me-2"></i> KIRIM SEKARANG
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5 col-mobile-full">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 small fw-bold text-secondary text-uppercase">Riwayat Terbaru</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Penerima</th><th>Total</th><th>Status</th></tr>
                            </thead>
                           <tbody>
                                <?php if(empty($history_pengajuan)): ?>
                                    <tr><td colspan="3" class="text-center py-5">Belum ada riwayat.</td></tr>
                                <?php else: ?>
                                    <?php foreach($history_pengajuan as $row): ?>
                                    <tr>
                                        <td class="py-3">
                                            <b class="text-dark d-block text-uppercase"><?= $row['nama_vendor'] ?></b>
                                            <small class="text-muted"><?= $row['bank_vendor'] ?> - <?= $row['rekening_vendor'] ?></small>
                                        </td>
                                        <td>
                                            <span class="text-primary fw-bold">Rp <?= number_format($row['total_pengajuan'],0,',','.') ?></span>
                                        </td>
                                        <td><span class="badge bg-warning text-dark"><?= strtoupper($row['status']) ?></span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2-enable').select2({
            width: '100%',
            placeholder: "-- Cari Nama Bank --"
        });

        // --- KUNCI INPUT HANYA ANGKA (NOMOR REKENING, HARGA, ONGKIR) ---
        function setOnlyNumber(elementId) {
            const el = document.getElementById(elementId);
            el.addEventListener('input', function() {
                // Buang apapun yang bukan angka
                this.value = this.value.replace(/\D/g, '');
                // Jalankan hitung total kalau yang diinput adalah nominal
                if(elementId !== 'rekening_vendor') hitungTotal();
            });
        }

        setOnlyNumber('rekening_vendor');
        setOnlyNumber('nominal_barang');
        setOnlyNumber('biaya_ongkir');

        $('#bank_vendor').on('change', function() {
            if ($(this).val() === 'LAINNYA') {
                $('#input_bank_lainnya').removeClass('d-none');
            } else {
                $('#input_bank_lainnya').addClass('d-none');
            }
        });
    });

    function hitungTotal() {
        let harga = parseFloat(document.getElementById('nominal_barang').value) || 0;
        let ongkir = parseFloat(document.getElementById('biaya_ongkir').value) || 0;
        let pajak = document.getElementById('pakai_ppn').checked ? Math.round(harga * 0.12) : 0;
        document.getElementById('pajak_ppn').value = pajak;
        let total = harga + pajak + ongkir;
        document.getElementById('label_total').innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('pakai_ppn').addEventListener('change', hitungTotal);

    document.getElementById('btnPasteManual').addEventListener('click', async () => {
        try {
            const text = await navigator.clipboard.readText();
            document.getElementById('rekening_vendor').value = text.replace(/\D/g, '');
        } catch (err) { alert('Berikan izin paste!'); }
    });
</script>

<?= $this->endSection() ?>