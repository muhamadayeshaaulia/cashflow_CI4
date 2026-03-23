<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Operasional /</span> Pengajuan Kas</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i> Form Pengajuan Baru</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/purchasing/pengajuan/simpan') ?>" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label" for="tanggal_pengajuan">Tanggal Pengajuan</label>
                        <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required />
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Tujuan Pembayaran (Deskripsi Vendor/Barang)</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Contoh: Pembayaran ATK ke PT Sinar Maju" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="nominal">Nominal (Rp)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-light border-end-0">Rp</span>
                            <input type="number" name="nominal" id="nominal" class="form-control border-start-0" placeholder="1500000" required />
                        </div>
                        <small class="form-text text-muted">Masukkan angka saja tanpa titik/koma.</small>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-send-check me-1"></i> Ajukan ke Manajer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i> Riwayat Pengajuan Anda</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php if(empty($history_pengajuan)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data pengajuan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($history_pengajuan as $row): ?>
                                <tr>
                                    <td><i class="bi bi-calendar2-event text-primary me-2"></i> <?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                                    <td><strong><?= $row['deskripsi'] ?></strong></td>
                                    <td>Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if($row['status'] == 'pending'): ?>
                                            <span class="badge bg-label-warning text-warning" style="background-color: #fff2d6; padding: 5px 10px; border-radius: 5px;">Menunggu ACC</span>
                                        <?php elseif($row['status'] == 'acc'): ?>
                                            <span class="badge bg-label-info text-info" style="background-color: #e1f0ff; padding: 5px 10px; border-radius: 5px;">Di-ACC Manajer</span>
                                        <?php elseif($row['status'] == 'dibayar'): ?>
                                            <span class="badge bg-label-success text-success" style="background-color: #e8fadf; padding: 5px 10px; border-radius: 5px;">Selesai Dibayar</span>
                                        <?php else: ?>
                                            <span class="badge bg-label-danger text-danger" style="background-color: #ffe0db; padding: 5px 10px; border-radius: 5px;">Ditolak</span>
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
    </div>
</div>

<?= $this->endSection() ?>