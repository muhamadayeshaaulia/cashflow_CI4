<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Cash Flow' ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f9;
            color: #566a7f;
            overflow-x: hidden;
        }

        /* SIDEBAR STYLING */
        #sidebar {
            width: 260px;
            height: 100vh;
            background: #ffffff;
            position: fixed;
            box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.15);
            transition: all 0.3s ease-in-out;
            z-index: 1040;
        }
        #sidebar.toggled {
            margin-left: -260px;
        }
        .sidebar-brand {
            padding: 1.5rem;
            font-weight: 700;
            color: #696cff;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }
        .sidebar-brand span { color: #566a7f; font-weight: 300; font-size: 0.9rem; display: block;}
        
        .sidebar-nav { padding: 0 1rem; }
        .sidebar-nav .nav-item { margin-bottom: 0.2rem; }
        .sidebar-nav .nav-link {
            color: #697a8d;
            border-radius: 0.375rem;
            padding: 0.625rem 1rem;
            font-weight: 400;
            transition: all 0.2s ease-in-out;
        }
        .sidebar-nav .nav-link:hover {
            background-color: #f4f5f7;
            color: #696cff;
        }
        .sidebar-nav .nav-link.active {
            background-color: rgba(105, 108, 255, 0.16);
            color: #696cff;
            font-weight: 500;
        }
        .sidebar-nav .nav-link i { font-size: 1.15rem; margin-right: 0.75rem; }

        /* MAIN CONTENT AREA */
        #main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s ease-in-out;
        }
        #main-content.toggled {
            margin-left: 0;
        }

        /* NAVBAR STYLING */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.15);
            margin: 1rem 1.5rem;
            padding: 0.75rem 1.25rem;
        }
        #sidebarToggle {
            background: none; border: none; font-size: 1.5rem; color: #697a8d;
        }

        /* CARD STYLING UNTUK DASHBOARD */
        .card {
            border: none;
            box-shadow: 0 0.25rem 1.125rem rgba(75, 70, 92, 0.1);
            border-radius: 0.5rem;
        }
        .card-header { background: transparent; border-bottom: 1px solid #eceef1; font-weight: 600; }
        
        /* TOMBOL CUSTOM */
        .btn-primary-custom {
            background-color: #696cff;
            border-color: #696cff;
            color: white;
            border-radius: 0.375rem;
        }
        .btn-primary-custom:hover { background-color: #5f61e6; border-color: #5f61e6; color: white;}
    </style>
</head>
<body>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-wallet2 me-2"></i>PT Sariling
            <span>Sistem Cash Flow</span>
        </div>
        
        <ul class="nav flex-column sidebar-nav mt-2">
            <?php 
                $uri = service('uri')->getSegment(1); 
                
                // Jika rolenya admin_keuangan, arahkan ke URL /admin
                $url_dashboard = (session()->get('role') == 'admin_keuangan') ? 'admin' : session()->get('role');
            ?>
            
            <li class="nav-item">
                <a class="nav-link <?= ($uri == $url_dashboard) ? 'active' : '' ?>" href="<?= base_url('/' . $url_dashboard) ?>">
                    <i class="bi bi-grid-fill"></i> Dashboard
                </a>
            </li>

            <?php if(session()->get('role') == 'purchasing'): ?>
            <li class="nav-item mt-3 mb-1 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600;">Menu Utama</li>
            <li class="nav-item">
                <a class="nav-link <?= ($uri == 'purchasing' && service('uri')->getSegment(2) == 'pengajuan') ? 'active' : '' ?>" href="<?= base_url('/purchasing/pengajuan') ?>">
                    <i class="bi bi-file-earmark-plus"></i> Form Pengajuan
                </a>
            </li>
            <?php endif; ?>

            <?php if(session()->get('role') == 'manajer'): ?>
            <li class="nav-item mt-3 mb-1 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600;">Menu Persetujuan</li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/persetujuan') ?>">
                    <i class="bi bi-check-circle"></i> Verifikasi ACC
                </a>
            </li>
            <li class="nav-item mt-3 mb-1 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600;">Laporan</li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/laporan') ?>">
                    <i class="bi bi-file-bar-graph"></i> Laporan Cash Flow
                </a>
            </li>
            <?php endif; ?>

            <?php if(session()->get('role') == 'admin_keuangan'): ?>
            <li class="nav-item mt-3 mb-1 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600;">Transaksi Eksternal</li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/pembayaran') ?>">
                    <i class="bi bi-send-check"></i> Pembayaran Vendor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/admin/kas-masuk') ?>">
                    <i class="bi bi-building-down"></i> Penerimaan Dinas
                </a>
            </li>
            <li class="nav-item mt-3 mb-1 px-3 text-uppercase text-muted" style="font-size: 0.75rem; font-weight: 600;">Laporan</li>
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('/laporan') ?>">
                    <i class="bi bi-file-bar-graph"></i> Laporan Cash Flow
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div id="main-content">
        
        <nav class="navbar navbar-expand navbar-custom">
            <div class="container-fluid">
                <button id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="d-flex align-items-center ms-auto">
                    <div class="me-3 text-end d-none d-md-block">
                        <span class="d-block text-dark fw-semibold" style="font-size: 0.9rem;"><?= ucfirst(session()->get('username')) ?></span>
                        <span class="text-muted d-block" style="font-size: 0.75rem;"><?= str_replace('_', ' ', strtoupper(session()->get('role'))) ?></span>
                    </div>
                    <div class="rounded-circle d-flex justify-content-center align-items-center text-white fw-bold me-3" style="width: 40px; height: 40px; background-color: #696cff;">
                        <?= strtoupper(substr(session()->get('username'), 0, 1)) ?>
                    </div>
                    <a href="<?= base_url('/logout') ?>" class="btn btn-sm btn-outline-danger" style="border-radius: 0.375rem;">
                        <i class="bi bi-power"></i>
                    </a>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4 pt-3 pb-5">
            <?= $this->renderSection('content') ?>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script untuk hamburger menu
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('toggled');
            mainContent.classList.toggle('toggled');
        });
    </script>
</body>
</html>