<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Operasional /</span> Pengajuan Kas Proyek</h4>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-plus me-2"></i> Form Pengajuan Dana Proyek</h5>
            </div>
            <div class="card-body pt-4">
                <form action="<?= base_url('/purchasing/pengajuan/simpan') ?>" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pengajuan</label>
                            <input type="date" class="form-control" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Proyek / Dinas</label>
                            <input type="text" class="form-control" name="divisi_peminta" placeholder="Dinas PUPR Jabar" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan Barang / Jasa</label>
                        <textarea name="deskripsi" class="form-control" placeholder="Pembelian 2 Unit Genset" rows="2" required></textarea>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-secondary mb-3"><i class="bi bi-credit-card me-2"></i> Detail Rekening Vendor</h6>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Vendor (Tujuan Bayar)</label>
                        <input type="text" class="form-control" name="nama_vendor" placeholder="PT Sinar Maju" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" class="form-control" name="bank_vendor" placeholder="BCA / Mandiri" required>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Nomor Rekening Vendor</label>
                            <input type="text" class="form-control" name="rekening_vendor" placeholder="123456xxx" required>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-secondary mb-3"><i class="bi bi-calculator me-2"></i> Rincian Biaya (Estimasi)</h6>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga Barang (DPP)</label>
                            <input type="number" class="form-control" name="nominal_barang" placeholder="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pajak PPN (11%)</label>
                            <input type="number" class="form-control" name="pajak_ppn" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Biaya Ongkir</label>
                            <input type="number" class="form-control" name="biaya_ongkir" placeholder="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 mt-3">
                        <i class="bi bi-send-check me-1"></i> Ajukan Pengajuan Dana
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i> Riwayat Pengajuan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Proyek/Vendor</th>
                                <th>Total Dana</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($history_pengajuan as $row): ?>
                            <tr>
                                <td>
                                    <small class="text-muted d-block"><?= $row['divisi_peminta'] ?></small>
                                    <span class="fw-bold"><?= $row['nama_vendor'] ?></span>
                                </td>
                                <td>Rp <?= number_format($row['total_pengajuan'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge bg-light text-primary"><?= $row['status'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>