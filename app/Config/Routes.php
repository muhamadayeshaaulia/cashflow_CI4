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
//routes untuk halamah dashboard sementara
$routes->get('/manajer', 'ManajerController::index');
$routes->get('/admin', 'AdminController::index');

// routes purchasing
$routes->group('purchasing', function($routes) {

    $routes->get('/', 'PurchasingController::index');     
    $routes->get('pengajuan', 'PurchasingController::pengajuan');
    $routes->post('pengajuan/simpan', 'PurchasingController::simpanPengajuan');
});

