<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Cash Flow' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background-color: #2c3e50; }
        .sidebar a { color: #ecf0f1; text-decoration: none; padding: 12px 20px; display: block; }
        .sidebar a:hover { background-color: #34495e; border-left: 4px solid #3498db; }
        .content-area { width: 100%; }
        .bg-light-custom { background-color: #f4f6f9; }
    </style>
</head>
<body class="bg-light-custom">
    <div class="d-flex">
        
        <div class="sidebar text-white" style="width: 260px;">
            <div class="p-4 border-bottom border-secondary">
                <h5 class="mb-0">PT Sariling</h5>
                <small class="text-muted">Cash Flow System</small>
            </div>
            
            <ul class="nav flex-column mt-3">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/' . session()->get('role')) ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <?php if(session()->get('role') == 'purchasing'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/purchasing/pengajuan') ?>">
                        <i class="bi bi-file-earmark-plus me-2"></i> Pengajuan Kas
                    </a>
                </li>
                <?php endif; ?>

                <?php if(session()->get('role') == 'manajer'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/manajer/persetujuan') ?>">
                        <i class="bi bi-check2-square me-2"></i> Persetujuan ACC
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/laporan') ?>">
                        <i class="bi bi-bar-chart-line me-2"></i> Laporan Cash Flow
                    </a>
                </li>
                <?php endif; ?>

                <?php if(session()->get('role') == 'admin_keuangan'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/pembayaran') ?>">
                        <i class="bi bi-wallet2 me-2"></i> Pembayaran Vendor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/kas-masuk') ?>">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Penerimaan Dinas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/laporan') ?>">
                        <i class="bi bi-bar-chart-line me-2"></i> Laporan Cash Flow
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="content-area">
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom px-4 py-3 shadow-sm">
                <div class="container-fluid justify-content-end">
                    <span class="navbar-text me-4">
                        Halo, <strong class="text-primary"><?= ucfirst(session()->get('username')) ?></strong> 
                        <span class="badge bg-secondary ms-1"><?= session()->get('role') ?></span>
                    </span>
                    <a href="<?= base_url('/logout') ?>" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </nav>

            <div class="p-4">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>