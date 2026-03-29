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
        :root {
            --sidebar-width: 260px;
            --primary-color: #696cff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f9;
            color: #566a7f;
            overflow-x: hidden;
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
            transition: all 0.3s ease-in-out;
            z-index: 1100;
        }

        /* --- LOGIKA RESPONSIVE HP --- */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.toggled {
                margin-left: 0;
            }
            #main-content {
                margin-left: 0 !important;
            }
        }

        /* Overlay gelap saat sidebar muncul di HP */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100vw; height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }
        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-brand {
            padding: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.25rem;
            border-bottom: 1px solid #f0f0f2;
        }
        
        .sidebar-nav { padding: 1rem; }
        .sidebar-nav .nav-link {
            color: #697a8d;
            border-radius: 0.375rem;
            padding: 0.7rem 1rem;
            margin-bottom: 0.3rem;
            transition: 0.2s;
            display: flex;
            align-items: center;
        }
        .sidebar-nav .nav-link i { font-size: 1.2rem; margin-right: 10px; }
        .sidebar-nav .nav-link:hover { background: #f4f5f7; color: var(--primary-color); }
        .sidebar-nav .nav-link.active {
            background-color: rgba(105, 108, 255, 0.16);
            color: var(--primary-color);
            font-weight: 600;
        }

        /* --- MAIN CONTENT --- */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease-in-out;
        }

        /* NAVBAR STYLING */
        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin: 15px;
            border-radius: 8px;
            padding: 10px 20px;
        }

        #sidebarToggle {
            background: none; border: none; font-size: 1.5rem; color: #697a8d;
        }

        /* Section Header di Form */
        .menu-header {
            font-size: 0.75rem;
            font-weight: 700;
            color: #a1acb8;
            margin: 1.5rem 0 0.5rem 1rem;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-wallet2 me-2"></i>PT Sariling
            <small class="d-block text-muted fw-light" style="font-size: 12px;">Cash Flow System</small>
        </div>
        
        <ul class="nav flex-column sidebar-nav">
            <?php 
                $uri1 = service('uri')->getSegment(1); 
                $uri2 = service('uri')->getSegment(2);
                $role = session()->get('role');
                $dashboard_url = ($role == 'admin_keuangan') ? 'admin' : $role;
            ?>
            
            <li class="nav-item">
                <a class="nav-link <?= ($uri1 == $dashboard_url && $uri2 == '') ? 'active' : '' ?>" href="<?= base_url('/' . $dashboard_url) ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <?php if($role == 'purchasing'): ?>
                <div class="menu-header">Menu Utama</div>
                <li class="nav-item">
                    <a class="nav-link <?= ($uri2 == 'pengajuan') ? 'active' : '' ?>" href="<?= base_url('/purchasing/pengajuan') ?>">
                        <i class="bi bi-file-earmark-plus"></i> Form Pengajuan
                    </a>
                </li>
            <?php endif; ?>

            <?php if($role == 'manajer'): ?>
                <div class="menu-header">Persetujuan</div>
                <li class="nav-item">
                    <a class="nav-link <?= ($uri2 == 'persetujuan') ? 'active' : '' ?>" href="<?= base_url('/manajer/persetujuan') ?>">
                        <i class="bi bi-shield-check"></i> Verifikasi ACC
                    </a>
                </li>
                <div class="menu-header">Laporan</div>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/laporan') ?>">
                        <i class="bi bi-bar-chart-line"></i> Cash Flow
                    </a>
                </li>
            <?php endif; ?>

            <?php if($role == 'admin_keuangan'): ?>
                <div class="menu-header">Transaksi</div>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/pembayaran') ?>">
                        <i class="bi bi-cash-stack"></i> Bayar Vendor
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('/admin/kas-masuk') ?>">
                        <i class="bi bi-arrow-down-left-square"></i> Kas Masuk
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div id="main-content">
        
        <nav class="navbar navbar-expand navbar-custom">
            <div class="container-fluid p-0">
                <button id="sidebarToggle" type="button">
                    <i class="bi bi-list"></i>
                </button>
                
                <div class="ms-auto d-flex align-items-center">
                    <div class="text-end me-3 d-none d-sm-block">
                        <span class="d-block fw-bold small"><?= session()->get('nama_lengkap') ?></span>
                        <span class="text-muted extra-small" style="font-size: 10px;"><?= strtoupper($role) ?></span>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" style="width: 35px; height: 35px;">
                        <?= substr(session()->get('nama_lengkap'), 0, 1) ?>
                    </div>
                    <a href="<?= base_url('/logout') ?>" class="btn btn-sm btn-outline-danger ms-2">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </div>
            </div>
        </nav>

        <div class="container-fluid px-3 px-md-4">
            <?= $this->renderSection('content') ?>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebarToggle');
        const mainContent = document.getElementById('main-content');

        function toggleSidebar() {
            sidebar.classList.toggle('toggled');
            overlay.classList.toggle('active');
            
            // Di layar lebar, geser konten ke samping
            if (window.innerWidth > 992) {
                if(sidebar.classList.contains('toggled')) {
                    mainContent.style.marginLeft = "0";
                } else {
                    mainContent.style.marginLeft = "260px";
                }
            }
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Reset saat layar diubah ke Desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('toggled');
                overlay.classList.remove('active');
                mainContent.style.marginLeft = "260px";
            } else {
                mainContent.style.marginLeft = "0";
            }
        });
    </script>
</body>
</html>