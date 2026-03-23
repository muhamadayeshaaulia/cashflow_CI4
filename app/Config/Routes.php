<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Route untuk halaman utama dan login
$routes->get('/', 'AuthController::index');
$routes->get('/login', 'AuthController::index');
$routes->post('/login/process', 'AuthController::process');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/laporan', 'LaporanController::index');

// routes purchasing
$routes->group('purchasing', function($routes) {

    $routes->get('/', 'PurchasingController::index');     
    $routes->get('pengajuan', 'PurchasingController::pengajuan');
    $routes->post('pengajuan/simpan', 'PurchasingController::simpanPengajuan');
});

// routes manager
$routes->group('manajer', function($routes) {
    $routes->get('/', 'ManajerController::index');
    $routes->get('persetujuan', 'ManajerController::persetujuan');
    $routes->post('persetujuan/update', 'ManajerController::updateStatus');
});

$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::index');
    
    // Rute Pembayaran Vendor (Kas Keluar)
    $routes->get('pembayaran', 'AdminController::pembayaran');
    $routes->post('pembayaran/proses', 'AdminController::prosesBayar'); 
    
    // Rute Penerimaan Dinas (Kas Masuk)
    $routes->get('kas-masuk', 'AdminController::kasMasuk');
    $routes->post('kas-masuk/simpan', 'AdminController::simpanKasMasuk');
    $routes->post('kas-masuk/update', 'AdminController::updateKasMasuk');
});

