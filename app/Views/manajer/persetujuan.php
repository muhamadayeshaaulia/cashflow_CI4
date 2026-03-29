<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Persetujuan /</span> Verifikasi ACC</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-primary fw-bold"><i class="bi bi-card-checklist me-2"></i> Daftar Pengajuan Menunggu Persetujuan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tgl / Proyek</th>
                        <th>Keperluan & Rekening Vendor</th>
                        <th>Rincian Biaya</th>
                        <th class="text-primary">Total (Inc. PPN 12%)</th>
                        <th class="text-center">Aksi Otorisasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pengajuan_pending)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Tidak ada pengajuan kas yang menunggu persetujuan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($pengajuan_pending as $row): ?>
                        <tr>
                            <td>
                                <small class="text-muted d-block"><?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></small>
                                <span class="fw-bold text-dark text-uppercase"><?= $row['divisi_peminta'] ?></span>
                            </td>

                            <td style="white-space: normal; min-width: 250px;">
                                <div class="mb-1 text-dark fw-bold"><?= $row['deskripsi'] ?></div>
                                <div class="badge bg-light text-primary border border-primary p-2">
                                    <i class="bi bi-bank me-1"></i> <?= $row['bank_vendor'] ?> - <?= $row['rekening_vendor'] ?> <br>
                                    <span class="text-uppercase text-dark small">A/N: <?= $row['nama_vendor'] ?></span>
                                </div>
                            </td>

                            <td>
                                <small class="d-block text-muted">Brg: Rp <?= number_format($row['nominal_barang'], 0, ',', '.') ?></small>
                                <small class="d-block text-danger">PPN (12%): Rp <?= number_format($row['pajak_ppn'], 0, ',', '.') ?></small>
                                <small class="d-block text-muted">Ongkir: Rp <?= number_format($row['biaya_ongkir'], 0, ',', '.') ?></small>
                            </td>

                            <td>
                                <h5 class="mb-0 fw-bold text-primary">Rp <?= number_format($row['total_pengajuan'], 0, ',', '.') ?></h5>
                            </td>

                            <td class="text-center">
                                <form action="<?= base_url('/manajer/persetujuan/update') ?>" method="POST">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="submit" name="status_aksi" value="acc" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" onclick="return confirm('Setujui pembayaran ini?')">
                                            <i class="bi bi-check-lg me-1"></i> ACC
                                        </button>
                                        
                                        <button type="submit" name="status_aksi" value="ditolak" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tolak pengajuan ini?')">
                                            <i class="bi bi-x-lg me-1"></i> Tolak
                                        </button>
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

<?= $this->endSection() ?>