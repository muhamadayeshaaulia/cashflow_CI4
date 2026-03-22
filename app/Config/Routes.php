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

// routes purchasing
$routes->get('/purchasing', 'PurchasingController::index');
//routes manager
$routes->get('/manajer', 'ManajerController::index');
//routes admin
$routes->get('/admin', 'AdminController::index');
