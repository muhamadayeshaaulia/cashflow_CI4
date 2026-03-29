<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span> Verifikasi & History</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-clock-history me-2"></i> Menunggu Persetujuan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tgl / Proyek</th>
                        <th>Keperluan & Vendor</th>
                        <th>Total (Inc. PPN)</th>
                        <th class="text-center">Aksi Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pengajuan_pending)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-5">Semua pengajuan sudah diproses.</td></tr>
                    <?php else: ?>
                        <?php foreach($pengajuan_pending as $row): ?>
                        <tr>
                            <td>
                                <small class="text-muted d-block"><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></small>
                                <span class="fw-bold text-dark text-uppercase"><?= $row['divisi_peminta'] ?></span>
                            </td>
                            <td>
                                <div class="mb-1 text-dark fw-bold"><?= $row['deskripsi'] ?></div>
                                <small class="text-muted"><?= $row['nama_vendor'] ?> (<?= $row['bank_vendor'] ?>)</small>
                            </td>
                            <td>
                                <h6 class="mb-0 fw-bold text-primary">Rp <?= number_format($row['total_pengajuan'], 0, ',', '.') ?></h6>
                            </td>
                            <td class="text-center">
                                <form action="<?= base_url('/manajer/persetujuan/update') ?>" method="POST">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    <div class="btn-group">
                                        <button type="submit" name="status_aksi" value="acc" class="btn btn-sm btn-success px-3" onclick="return confirm('ACC Pengajuan ini?')">ACC</button>
                                        <button type="submit" name="status_aksi" value="ditolak" class="btn btn-sm btn-outline-danger px-3" onclick="return confirm('Tolak Pengajuan ini?')">Tolak</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr class="my-5">

<div class="card shadow-sm border-0 bg-light">
    <div class="card-header bg-transparent border-bottom py-3">
        <h5 class="mb-0 text-secondary fw-bold"><i class="bi bi-archive me-2"></i> History Keputusan Terakhir</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th>Waktu Verif</th>
                        <th>No / Proyek</th>
                        <th>Penerima</th>
                        <th>Total</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($history_verifikasi)): ?>
                        <tr><td colspan="5" class="text-center py-4">Belum ada riwayat keputusan.</td></tr>
                    <?php else: ?>
                        <?php foreach($history_verifikasi as $h): ?>
                        <tr class="bg-white">
                            <td><small><?= date('d/m/y H:i', strtotime($h['updated_at'])) ?></small></td>
                            <td>
                                <small class="text-primary d-block"><?= $h['no_pengajuan'] ?></small>
                                <b class="small text-uppercase"><?= $h['divisi_peminta'] ?></b>
                            </td>
                            <td>
                                <b class="small d-block"><?= $h['nama_vendor'] ?></b>
                                <small class="text-muted text-truncate" style="max-width: 150px;"><?= $h['deskripsi'] ?></small>
                            </td>
                            <td><b class="text-dark small">Rp <?= number_format($h['total_pengajuan'], 0, ',', '.') ?></b></td>
                            <td class="text-center">
                                <?php if($h['status'] == 'acc'): ?>
                                    <span class="badge bg-success small">DISETUJUI</span>
                                <?php elseif($h['status'] == 'dibayar'): ?>
                                    <span class="badge bg-info small text-white">SUDAH CAIR</span>
                                <?php else: ?>
                                    <span class="badge bg-danger small">DITOLAK</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>