<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi Eksternal /</span> Penerimaan Dinas</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-white border-bottom cursor-pointer" data-bs-toggle="collapse" data-bs-target="#formTambah">
        <h5 class="mb-0 text-primary d-flex justify-content-between align-items-center">
            <span><i class="bi bi-plus-circle me-2"></i> Tambah Tagihan Proyek Dinas Baru</span>
            <i class="bi bi-chevron-down text-muted"></i>
        </h5>
    </div>
    <div id="formTambah" class="collapse show">
        <div class="card-body">
            <form action="<?= base_url('/admin/kas-masuk/simpan') ?>" method="POST" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Tanggal BAST</label>
                    <input type="date" name="tanggal_pembuatan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. BAST</label>
                    <input type="text" name="no_bast" class="form-control" placeholder="Contoh: BAST/123/2026" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">No. Tagihan</label>
                    <input type="text" name="no_tagihan" class="form-control" placeholder="Contoh: INV/456/2026" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nominal Proyek (Rp)</label>
                    <input type="number" name="nominal" class="form-control" placeholder="10000000" required>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary-custom px-4"><i class="bi bi-save me-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 text-secondary"><i class="bi bi-list-columns me-2"></i> Progress Penagihan Dinas</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>No. Dokumen</th>
                        <th>Nominal</th>
                        <th>Status Saat Ini</th>
                        <th class="text-center">Aksi / Update Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($kas_masuk)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data penagihan ke dinas.</td></tr>
                    <?php else: ?>
                        <?php foreach($kas_masuk as $row): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($row['tanggal_pembuatan'])) ?></td>
                            <td>
                                <small class="d-block text-muted">BAST: <?= $row['no_bast'] ?></small>
                                <strong class="d-block">Tagihan: <?= $row['no_tagihan'] ?></strong>
                                <?php if($row['no_sp2d']): ?>
                                    <span class="badge bg-light text-dark mt-1 border">SP2D: <?= $row['no_sp2d'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-success fw-bold">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                            
                            <td>
                                <?php if($row['status'] == 'proses_kirim'): ?>
                                    <span class="badge bg-secondary">Persiapan Kirim</span>
                                <?php elseif($row['status'] == 'tagihan_dikirim'): ?>
                                    <span class="badge bg-info">Tagihan Dikirim</span>
                                <?php elseif($row['status'] == 'sp2d_terbit'): ?>
                                    <span class="badge bg-warning text-dark">SP2D Terbit</span>
                                <?php elseif($row['status'] == 'lunas'): ?>
                                    <span class="badge bg-success">Lunas / Diterima</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center" style="min-width: 250px;">
                                
                                <?php if($row['status'] == 'proses_kirim'): ?>
                                    <form action="<?= base_url('/admin/kas-masuk/update') ?>" method="POST">
                                        <input type="hidden" name="id_kas_masuk" value="<?= $row['id'] ?>">
                                        <button type="submit" name="aksi_status" value="tagihan_dikirim" class="btn btn-sm btn-info text-white rounded-pill px-3 shadow-sm" onclick="return confirm('Ubah status menjadi Tagihan Dikirim?')">
                                            Kirim Tagihan <i class="bi bi-arrow-right-short"></i>
                                        </button>
                                    </form>

                                <?php elseif($row['status'] == 'tagihan_dikirim'): ?>
                                    <form action="<?= base_url('/admin/kas-masuk/update') ?>" method="POST" class="d-flex align-items-center justify-content-center">
                                        <input type="hidden" name="id_kas_masuk" value="<?= $row['id'] ?>">
                                        <input type="text" name="no_sp2d" class="form-control form-control-sm me-2" placeholder="Input No SP2D..." style="width: 130px;" required>
                                        <button type="submit" name="aksi_status" value="sp2d_terbit" class="btn btn-sm btn-warning rounded-pill shadow-sm">
                                            Terbitkan SP2D
                                        </button>
                                    </form>

                                <?php elseif($row['status'] == 'sp2d_terbit'): ?>
                                    <form action="<?= base_url('/admin/kas-masuk/update') ?>" method="POST" enctype="multipart/form-data" class="d-flex align-items-center justify-content-center">
                                        <input type="hidden" name="id_kas_masuk" value="<?= $row['id'] ?>">
                                        <input type="file" name="bukti_transfer" class="form-control form-control-sm me-2" style="width: 150px;" accept="image/*,application/pdf" required>
                                        <button type="submit" name="aksi_status" value="lunas" class="btn btn-sm btn-success rounded-pill shadow-sm">
                                            Terima Dana
                                        </button>
                                    </form>

                                <?php elseif($row['status'] == 'lunas'): ?>
                                    <?php if($row['bukti_transfer']): ?>
                                        <a href="<?= base_url('uploads/bukti_masuk/' . $row['bukti_transfer']) ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill">
                                            <i class="bi bi-receipt"></i> Lihat Bukti Dana Masuk
                                        </a>
                                    <?php else: ?>
                                        <i class="bi bi-check-all text-success fs-4"></i>
                                    <?php endif; ?>
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