<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Transaksi Eksternal /</span> Pembayaran Vendor</h4>

<?php if(session()->getFlashdata('pesan')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-5">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 text-primary"><i class="bi bi-wallet2 me-2"></i> Menunggu Realisasi Pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <th>Deskripsi (Keperluan)</th>
                        <th>Nominal</th>
                        <th class="text-center">Upload Bukti & Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($siap_bayar)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada tagihan yang menunggu pembayaran.</td></tr>
                    <?php else: ?>
                        <?php foreach($siap_bayar as $row): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></td>
                            <td><strong><?= $row['deskripsi'] ?></strong></td>
                            <td class="text-primary fw-bold">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                
                                <form action="<?= base_url('/admin/pembayaran/proses') ?>" method="POST" enctype="multipart/form-data" class="d-flex justify-content-center align-items-center">
                                    <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                    
                                    <input type="file" name="bukti_pembayaran" class="form-control form-control-sm me-2" style="max-width: 200px;" accept="image/*,application/pdf" required>
                                    
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" onclick="return confirm('Proses pembayaran ini?')">
                                        <i class="bi bi-send-check me-1"></i> Selesai
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

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h5 class="mb-0 text-secondary"><i class="bi bi-clock-history me-2"></i> Riwayat Pembayaran</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tgl Dibayar</th>
                        <th>Deskripsi</th>
                        <th>Nominal</th>
                        <th class="text-center">Bukti Transfer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($riwayat)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada riwayat pembayaran.</td></tr>
                    <?php else: ?>
                        <?php foreach($riwayat as $row): ?>
                        <tr>
                            <td><?= date('d M Y', strtotime($row['updated_at'])) ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td class="text-success">Rp <?= number_format($row['nominal'], 0, ',', '.') ?></td>
                            <td class="text-center">
                                <?php if($row['bukti_pembayaran']): ?>
                                    <a href="<?= base_url('uploads/bukti/' . $row['bukti_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                        <i class="bi bi-eye"></i> Lihat Bukti
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
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