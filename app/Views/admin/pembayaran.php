<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold"><span class="text-muted fw-light">Transaksi /</span> Pembayaran Vendor</h4>
        <span class="badge bg-label-primary p-2" style="background-color: rgba(105, 108, 255, 0.1); color: #696cff;">
            <i class="bi bi-calendar-event me-1"></i> <?= date('d F Y') ?>
        </span>
    </div>

    <?php if(session()->getFlashdata('pesan')): ?>
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('pesan') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-hourglass-split me-2"></i> Konfirmasi Pembayaran Baru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase" style="font-size: 12px;">
                        <tr>
                            <th class="ps-4">Tgl / No Pengajuan</th>
                            <th>Vendor & Rekening (Klik Salin)</th>
                            <th>Total Bayar</th>
                            <th class="text-center">Upload Bukti & Selesaikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($siap_bayar)): ?>
                            <tr><td colspan="4" class="text-center py-5 text-muted">Tidak ada tagihan yang menunggu pembayaran.</td></tr>
                        <?php else: ?>
                            <?php foreach($siap_bayar as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <small class="text-muted d-block"><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])) ?></small>
                                    <span class="badge bg-light text-primary border" style="font-size: 11px;"><?= $row['no_pengajuan'] ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark text-uppercase"><?= $row['nama_vendor'] ?></div>
                                    <button type="button" class="btn btn-sm py-0 px-2 border-0" 
                                            onclick="copyAccount('<?= $row['rekening_vendor'] ?>')" 
                                            style="font-size: 11px; background-color: rgba(105, 108, 255, 0.1); color: #696cff;">
                                        <i class="bi bi-bank me-1"></i> <?= $row['bank_vendor'] ?> : <b><?= $row['rekening_vendor'] ?></b>
                                        <i class="bi bi-clipboard ms-1"></i>
                                    </button>
                                </td>
                                <td><span class="fw-bold text-primary">Rp <?= number_format($row['total_pengajuan'], 0, ',', '.') ?></span></td>
                                <td class="text-center">
                                    <form action="<?= base_url('/admin/pembayaran/proses') ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id_pengajuan" value="<?= $row['id'] ?>">
                                        <div class="input-group input-group-sm mx-auto" style="max-width: 250px;">
                                            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                                            <button type="submit" class="btn btn-success"><i class="bi bi-send-check"></i></button>
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

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 fw-bold text-secondary"><i class="bi bi-clock-history me-2"></i> Riwayat Pembayaran Selesai</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase" style="font-size: 12px;">
                        <tr>
                            <th class="ps-4">Tgl / No Pengajuan</th>
                            <th>Vendor & Rekening</th>
                            <th>Deskripsi Keperluan</th>
                            <th>Nominal Lunas</th>
                            <th class="text-center">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($riwayat)): ?>
                            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat pembayaran.</td></tr>
                        <?php else: ?>
                            <?php foreach($riwayat as $h): ?>
                            <tr>
                                <td class="ps-4">
                                    <small class="d-block text-muted mb-1"><?= date('d/m/Y', strtotime($h['updated_at'])) ?></small>
                                    <span class="badge bg-label-primary" style="background-color: rgba(105, 108, 255, 0.1); color: #696cff; font-size: 10px;">
                                        <?= $h['no_pengajuan'] ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold small text-uppercase text-dark"><?= $h['nama_vendor'] ?></div>
                                    <small class="text-muted small"><?= $h['bank_vendor'] ?> : <?= $h['rekening_vendor'] ?></small>
                                </td>
                                <td style="white-space: normal; max-width: 200px;">
                                    <small class="text-muted"><?= $h['deskripsi'] ?></small>
                                </td>
                                <td><b class="text-success small">Rp <?= number_format($h['total_pengajuan'], 0, ',', '.') ?></b></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3" 
                                            onclick="showImage('<?= base_url('uploads/bukti/' . $h['bukti_pembayaran']) ?>', '<?= $h['no_pengajuan'] ?>')">
                                        <i class="bi bi-eye me-1"></i> Lihat
                                    </button>
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

<div class="modal fade" id="modalImage" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="modalTitle">Bukti Transfer</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light m-3 rounded">
                <img src="" id="imgPreview" class="img-fluid rounded shadow-sm" style="max-height: 70vh;">
            </div>
            <div class="modal-footer border-0 justify-content-center pt-0">
                <a href="" id="btnDownload" download class="btn btn-primary btn-sm rounded-pill px-4">
                    <i class="bi bi-download me-1"></i> Download Bukti
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyAccount(text) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.style.cssText = 'position:fixed; top:20px; right:20px; background:#696cff; color:white; padding:10px 20px; border-radius:5px; z-index:9999; font-size:12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);';
        toast.innerHTML = '<i class="bi bi-check2-circle me-2"></i> Rekening ' + text + ' disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    });
}

function showImage(url, title) {
    document.getElementById('imgPreview').src = url;
    document.getElementById('modalTitle').innerText = 'Bukti : ' + title;
    document.getElementById('btnDownload').href = url;
    var myModal = new bootstrap.Modal(document.getElementById('modalImage'));
    myModal.show();
}
</script>

<?= $this->endSection() ?>