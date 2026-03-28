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
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary"><i class="bi bi-pencil-square me-2"></i> Form Pengajuan Baru</h5>
            </div>
            <div class="card-body pt-4">
                <form action="<?= base_url('/purchasing/pengajuan/simpan') ?>" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label" for="tanggal_pengajuan">Tanggal Pengajuan</label>
                        <input type="date" class="form-control" id="tanggal_pengajuan" name="tanggal_pengajuan" value="<?= date('Y-m-d') ?>" required />
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="divisi_peminta">Nama Proyek / Instansi Dinas</label>
                        <input type="text" class="form-control" id="divisi_peminta" name="divisi_peminta" placeholder="Contoh: Dinas PUPR Prov. Jabar" required>
                        <small class="form-text text-muted">Tuliskan nama dinas atau proyek yang membutuhkan barang ini.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Keperluan / Deskripsi Barang</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Contoh: Pembelian 2 Unit Genset ke PT Sinar Maju" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label" for="nominal">Estimasi Nominal (Rp)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text bg-light border-end-0">Rp</span>
                            <input type="number" name="nominal" id="nominal" class="form-control border-start-0" placeholder="15000000" required />
                        </div>
                        <small class="form-text text-muted">Masukkan angka saja tanpa titik/koma.</small>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">
                        <i class="bi bi-send-check me-1"></i> Ajukan ke Manajer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i> Riwayat Pengajuan Anda</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Proyek & Keperluan</th>
                                <th>Nominal</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <?php if(empty($history_pengajuan)): ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        <i class="bi bi-folder2-open fs-1 d-block mb-2 text-light"></i>
                                        Belum ada data pengajuan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($history_pengajuan as $row): ?>
                                <tr>
                                    <td class="align-middle">
                                        <i class="bi bi-calendar2-event text-primary me-1"></i> <?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?>
                                    </td>
                                    
                                    <td class="align-middle" style="white-space: normal; min-width: 200px;">
                                        <small class="d-block text-primary fw-bold mb-1"><i class="bi bi-bank me-1"></i> <?= $row['divisi_peminta'] ?></small>
                                        <span><?= $row['deskripsi'] ?></span>
                                    </td>
                                    
                                    <td class="align-middle fw-semibold">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                                    <td class="align-middle text-center">
                                        <?php if($row['status'] == 'pending'): ?>
                                            <span class="badge" style="background-color: #fff2d6; color: #ffab00;">Menunggu ACC</span>
                                        <?php elseif($row['status'] == 'acc'): ?>
                                            <span class="badge" style="background-color: #e1f0ff; color: #03c3ec;">Di-ACC Manajer</span>
                                        <?php elseif($row['status'] == 'dibayar'): ?>
                                            <span class="badge" style="background-color: #e8fadf; color: #71dd37;">Selesai Dibayar</span>
                                        <?php else: ?>
                                            <span class="badge" style="background-color: #ffe0db; color: #ff3e1d;">Ditolak</span>
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