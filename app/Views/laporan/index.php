<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Laporan /</span> Cash Flow (Buku Besar)</h4>
    
    <button onclick="window.print()" class="btn btn-primary-custom shadow-sm d-print-none">
        <i class="bi bi-printer me-2"></i> Cetak PDF
    </button>
</div>

<div class="card shadow-sm border-0" id="areaCetak">
    <div class="card-header bg-white border-bottom text-center pt-4 pb-3">
        <h4 class="mb-1 text-primary fw-bold" style="letter-spacing: 1px;">PT SARILING ANEKA ENERGI</h4>
        <h6 class="text-muted mb-0">Laporan Arus Kas (Cash Flow)</h6>
        <small class="text-muted d-block mt-1">Dicetak pada: <?= date('d F Y - H:i') ?></small>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th width="12%">Tanggal</th>
                        <th>Keterangan / Deskripsi</th>
                        <th width="18%" class="text-success">Kas Masuk (Debit)</th>
                        <th width="18%" class="text-danger">Kas Keluar (Kredit)</th>
                        <th width="20%" class="bg-primary text-white bg-opacity-75">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $saldo_akhir = 0;
                        $total_masuk = 0;
                        $total_keluar = 0;
                    ?>

                    <?php if(empty($buku_besar)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-5">Belum ada transaksi kas yang lunas/dibayar.</td></tr>
                    <?php else: ?>
                        <?php foreach($buku_besar as $baris): ?>
                            <?php 
                                // Kalkulasi otomatis
                                if ($baris['kategori'] == 'masuk') {
                                    $saldo_akhir += $baris['nominal'];
                                    $total_masuk += $baris['nominal'];
                                } else {
                                    $saldo_akhir -= $baris['nominal'];
                                    $total_keluar += $baris['nominal'];
                                }
                            ?>
                            <tr>
                                <td class="text-center align-middle"><?= date('d/m/Y', strtotime($baris['tanggal'])) ?></td>
                                <td class="align-middle"><?= $baris['keterangan'] ?></td>
                                
                                <td class="text-end text-success align-middle">
                                    <?= ($baris['kategori'] == 'masuk') ? '+ Rp ' . number_format($baris['nominal'], 0, ',', '.') : '-' ?>
                                </td>
                                
                                <td class="text-end text-danger align-middle">
                                    <?= ($baris['kategori'] == 'keluar') ? '- Rp ' . number_format($baris['nominal'], 0, ',', '.') : '-' ?>
                                </td>
                                
                                <td class="text-end fw-bold align-middle <?= ($saldo_akhir < 0) ? 'text-danger' : 'text-primary' ?>" style="background-color: #f8f9fa;">
                                    Rp <?= number_format($saldo_akhir, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="2" class="text-end py-3">TOTAL MUTASI :</td>
                        <td class="text-end text-success py-3">Rp <?= number_format($total_masuk, 0, ',', '.') ?></td>
                        <td class="text-end text-danger py-3">Rp <?= number_format($total_keluar, 0, ',', '.') ?></td>
                        <td class="text-end bg-primary text-white py-3">Saldo: Rp <?= number_format($saldo_akhir, 0, ',', '.') ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background-color: #fff !important; }
        #sidebar, .navbar-custom, .d-print-none { display: none !important; }
        #main-content { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

<?= $this->endSection() ?>