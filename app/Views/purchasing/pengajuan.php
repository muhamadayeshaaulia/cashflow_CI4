<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Operasional /</span> Pengajuan Kas Proyek</h4>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <strong>Berhasil!</strong> <?= session()->getFlashdata('pesan'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-pencil-square me-2"></i> Form Pengajuan Dana</h5>
            </div>
            <div class="card-body pt-4">
                <form action="<?= base_url('/purchasing/pengajuan/simpan') ?>" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Tanggal Pengajuan</label>
                            <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fw-bold">Nama Proyek / Dinas</label>
                            <input type="text" class="form-control" name="divisi_peminta" placeholder="Contoh: Proyek Jembatan Citarum" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Keperluan Barang / Jasa</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Tuliskan detail barang/jasa..." rows="2" required></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary mb-3 fw-bold"><i class="bi bi-bank me-2"></i> Informasi Rekening Tujuan</h6>
                    
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label class="form-label small">Bank</label>
                            <select name="bank_vendor" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option value="BCA">BCA</option>
                                <option value="MANDIRI">Mandiri</option>
                                <option value="BNI">BNI</option>
                                <option value="BRI">BRI</option>
                                <option value="BSI">BSI</option>
                            </select>
                        </div> 
                        <div class="col-md-7 mb-3">
                            <label class="form-label small">Nomor Rekening</label>
                            <div class="input-group">
                                <input type="text" class="form-control fw-bold border-primary" id="rekening_vendor" name="rekening_vendor" placeholder="Paste di sini..." required>
                                <button class="btn btn-primary" type="button" id="btnPasteManual"><i class="bi bi-clipboard-plus"></i> Paste</button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold">Nama Pemilik Rekening (A/N)</label>
                        <input type="text" class="form-control fw-bold text-uppercase border-primary bg-light" name="nama_vendor" placeholder="PT SARILING..." required>
                    </div>

                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-secondary mb-0 fw-bold"><i class="bi bi-calculator me-2"></i> Rincian Biaya</h6>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="pakai_ppn" checked>
                            <label class="form-check-label fw-bold text-primary" for="pakai_ppn">Gunakan PPN 12%</label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Harga Barang</label>
                            <input type="number" class="form-control" id="nominal_barang" name="nominal_barang" value="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Pajak PPN (12%)</label>
                            <input type="number" class="form-control bg-light fw-bold" id="pajak_ppn" name="pajak_ppn" value="0" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Biaya Ongkir</label>
                            <input type="number" class="form-control" id="biaya_ongkir" name="biaya_ongkir" value="0">
                        </div>
                    </div>

                    <div class="alert alert-secondary py-3 border-0 shadow-sm d-flex justify-content-between align-items-center mt-3">
                        <span class="fw-bold">Total Dana:</span>
                        <h4 class="mb-0 text-primary fw-bold" id="label_total">Rp 0</h4>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 mt-3 shadow fw-bold">
                        <i class="bi bi-send-check me-2"></i> KIRIM PENGAJUAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-secondary fw-bold"><i class="bi bi-clock-history me-2"></i> Riwayat Pengajuan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proyek / Vendor</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($history_pengajuan)): ?>
                                <tr><td colspan="3" class="text-center py-5 text-muted">Belum ada riwayat.</td></tr>
                            <?php else: ?>
                                <?php foreach($history_pengajuan as $row): ?>
                                <tr>
                                    <td class="py-3">
                                        <small class="text-muted d-block"><?= $row['divisi_peminta'] ?></small>
                                        <span class="fw-bold text-dark text-uppercase"><?= $row['nama_vendor'] ?></span>
                                    </td>
                                    <td><strong class="text-primary">Rp <?= number_format($row['total_pengajuan'], 0, ',', '.') ?></strong></td>
                                    <td>
                                        <span class="badge rounded-pill <?= ($row['status'] == 'pending') ? 'bg-warning text-dark' : 'bg-success' ?>">
                                            <?= strtoupper($row['status']) ?>
                                        </span>
                                    </td>
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

<script>
    const inputBarang = document.getElementById('nominal_barang');
    const inputPajak = document.getElementById('pajak_ppn');
    const inputOngkir = document.getElementById('biaya_ongkir');
    const checkPPN = document.getElementById('pakai_ppn');
    const labelTotal = document.getElementById('label_total');

    //LOGIKA HITUNG OTOMATIS (LOCKED TAX)
    function hitungTotal() {
        let harga = parseFloat(inputBarang.value) || 0;
        let ongkir = parseFloat(inputOngkir.value) || 0;
        let pajak = 0;

        // Hitung Pajak hanya jika Checkbox dicentang
        if (checkPPN.checked) {
            pajak = Math.round(harga * 0.12); // PPN 12% 2026
        }

        inputPajak.value = pajak; // Update kolom pajak (readonly)
        
        let total = harga + pajak + ongkir;
        labelTotal.innerHTML = 'Rp ' + total.toLocaleString('id-ID');
    }

    // Listener untuk Harga Barang, Ongkir, dan Checkbox PPN
    inputBarang.addEventListener('input', hitungTotal);
    inputOngkir.addEventListener('input', hitungTotal);
    checkPPN.addEventListener('change', hitungTotal);

    // FITUR TOMBOL PASTE
    const inputRekening = document.getElementById('rekening_vendor');
    const btnPaste = document.getElementById('btnPasteManual');

    btnPaste.addEventListener('click', async function() {
        try {
            const text = await navigator.clipboard.readText();
            inputRekening.value = text.replace(/\D/g, '');
        } catch (err) {
            alert('Gagal tempel. Berikan izin akses clipboard browser.');
        }
    });

    inputRekening.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
    });
</script>

<?= $this->endSection() ?>