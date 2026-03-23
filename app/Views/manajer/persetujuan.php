<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Persetujuan /</span> Verifikasi ACC</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-info-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 text-primary"><i class="bi bi-card-checklist me-2"></i> Daftar Pengajuan Menunggu Persetujuan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Deskripsi (Keperluan)</th>
                        <th>Nominal</th>
                        <th class="text-center">Aksi (Verifikasi)</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    <?php if(empty($pengajuan_pending)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Tidak ada pengajuan kas yang menunggu persetujuan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($pengajuan_pending as $row): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                            <td style="white-space: normal; min-width: 250px;"><strong><?= $row['deskripsi'] ?></strong></td>
                            <td class="text-primary fw-semibold">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <form action="<?= base_url('/manajer/persetujuan/update') ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    
                                    <button type="submit" name="status_aksi" value="acc" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm me-1" onclick="return confirm('Yakin ingin menyetujui pengajuan ini?')">
                                        <i class="bi bi-check-lg me-1"></i> ACC
                                    </button>
                                    
                                    <button type="submit" name="status_aksi" value="ditolak" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin menolak pengajuan ini?')">
                                        <i class="bi bi-x-lg me-1"></i> Tolak
                                    </button>
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