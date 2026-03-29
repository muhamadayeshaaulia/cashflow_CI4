<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Cash Flow' ?> | PT Sariling</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #696cff;
            --bg-body: #f5f5f9;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: var(--bg-body);
            color: #566a7f;
            margin: 0;
        }

        /* --- SIDEBAR STYLING --- */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 0 0.125rem 0.25rem rgba(161, 172, 184, 0.15);
            transition: all 0.3s ease;
            z-index: 1100;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.3rem;
            border-bottom: 1px solid #f0f0f2;
            display: flex;
            align-items: center;
        }
        
        .sidebar-nav { padding: 0.8rem; }
        .sidebar-nav .nav-link {
            color: #697a8d;
            border-radius: 0.375rem;
            padding: 0.6rem 1rem;
            margin-bottom: 0.2rem;
            transition: 0.3s;
            display: flex;
            align-items: center;
            font-size: 0.9375rem;
        }
        .sidebar-nav .nav-link i { font-size: 1.1rem; margin-right: 12px; }
        .sidebar-nav .nav-link:hover { 
            background: rgba(105, 108, 255, 0.08); 
            color: var(--primary-color); 
            padding-left: 1.2rem;
        }
        .sidebar-nav .nav-link.active {
            background: var(--primary-color);
            color: #fff !important;
            box-shadow: 0 2px 6px 0 rgba(105, 108, 255, 0.48);
        }

        .menu-header {
            font-size: 0.7rem;
            font-weight: 600;
            color: #a1acb8;
            margin: 1.5rem 0 0.5rem 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* --- MAIN CONTENT AREA --- */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
            padding-bottom: 2rem;
        }

        /* NAVBAR TOP */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(6px);
            margin: 12px 25px;
            border-radius: 0.5rem;
            padding: 0.6rem 1.2rem;
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
        }

        #sidebarToggle {
            background: transparent;
            border: none;
            color: #697a8d;
            font-size: 1.4rem;
            cursor: pointer;
        }

        /* LOGIKA RESPONSIVE */
        @media (max-width: 991.98px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.toggled {
                left: 0;
            }
            #main-content {
                margin-left: 0 !important;
            }
            .navbar-custom {
                margin: 10px;
            }
        }

        /* Overlay di HP */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block;
        }

        .extra-small { font-size: 10px; }
        .cursor-pointer { cursor: pointer; }
    </style>
</head>
<body>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-layers-fill me-2"></i>
            <div>
                <span class="d-block lh-1">PT SARILING</span>
                <small class="text-muted fw-normal" style="font-size: 11px;">CASH FLOW SYSTEM</small>
            </div>
        </div>
        
        <ul class="nav flex-column sidebar-nav">
            <?php 
                $uri = service('uri');
                $segment1 = $uri->getSegment(1); 
                $segment2 = $uri->getSegment(2);
                $role = session()->get('role');
                
                // Menentukan URL Dashboard berdasarkan role
                $dashboard_url = ($role == 'admin_keuangan') ? 'admin' : $role;
            ?>
            
            <li class="nav-item">
                <a class="nav-link <?= ($segment1 == $dashboard_url && $segment2 == '') ? 'active' : '' ?>" href="<?= base_url('/' . $dashboard_url) ?>">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>

            <?php if($role == 'purchasing'): ?>
                <div class="menu-header">Modul Purchasing</div>
                <li class="nav-item">
                    <a class="nav-link <?= ($segment2 == 'pengajuan') ? 'active' : '' ?>" href="<?= base_url('/purchasing/pengajuan') ?>">
                        <i class="bi bi-file-earmark-plus"></i> Form Pengajuan
                    </a>
                </li>
            <?php endif; ?>

            <?php if($role == 'manajer'): ?>
                <div class="menu-header">Verifikasi</div>
                <li class="nav-item">
                    <a class="nav-link <?= ($segment2 == 'persetujuan') ? 'active' : '' ?>" href="<?= base_url('/manajer/persetujuan') ?>">
                        <i class="bi bi-patch-check"></i> Persetujuan ACC
                    </a>
                </li>
            <?php endif; ?>

            <?php if($role == 'admin_keuangan'): ?>
                <div class="menu-header">Kelola Kas</div>
                <li class="nav-item">
                    <a class="nav-link <?= ($segment2 == 'pembayaran') ? 'active' : '' ?>" href="<?= base_url('/admin/pembayaran') ?>">
                        <i class="bi bi-cash-stack"></i> Bayar Vendor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($segment2 == 'kas-masuk') ? 'active' : '' ?>" href="<?= base_url('/admin/kas-masuk') ?>">
                        <i class="bi bi-arrow-down-left-square"></i> Kas Masuk
                    </a>
                </li>
            <?php endif; ?>

            <div class="menu-header">Akun</div>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= base_url('/logout') ?>" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <div id="main-content">
        <nav class="navbar navbar-expand navbar-custom">
            <div class="container-fluid p-0">
                <button id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="text-end me-3 d-none d-md-block">
                        <span class="d-block fw-bold small text-dark"><?= session()->get('nama_lengkap') ?></span>
                        <span class="text-muted extra-small fw-semibold text-uppercase"><?= str_replace('_', ' ', $role) ?></span>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px;">
                        <?= substr(session()->get('nama_lengkap'), 0, 1) ?>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-4 pt-2">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('main-content');

        // Fungsi buka/tutup sidebar
        function handleToggle() {
            if (window.innerWidth <= 992) {
                // Di HP: Geser sidebar
                sidebar.classList.toggle('toggled');
                overlay.classList.toggle('active');
            } else {
                // Di Desktop: Sembunyikan sidebar dan geser konten
                if (sidebar.style.left === '-260px') {
                    sidebar.style.left = '0';
                    mainContent.style.marginLeft = '260px';
                } else {
                    sidebar.style.left = '-260px';
                    mainContent.style.marginLeft = '0';
                }
            }
        }

        toggleBtn.addEventListener('click', handleToggle);
        overlay.addEventListener('click', handleToggle);

        // Responsive adjustment saat layar di-resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('toggled');
                overlay.classList.remove('active');
                sidebar.style.left = '0';
                mainContent.style.marginLeft = '260px';
            } else {
                sidebar.style.left = '';
                mainContent.style.marginLeft = '0';
            }
        });
    </script>
</body>
</html>