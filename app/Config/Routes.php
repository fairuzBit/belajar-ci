<?php

use CodeIgniter\Router\RouteCollection;


/**
 * @var RouteCollection $routes
 */
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->group('produk', ['filter' => 'auth'], function ($routes) { 
    $routes->get('', 'ProdukController::index');
    $routes->post('', 'ProdukController::create');
    $routes->post('edit/(:any)', 'ProdukController::edit/$1');
    $routes->get('delete/(:any)', 'ProdukController::delete/$1');
    $routes->get('download', 'ProdukController::download');
});

$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);
$routes->post('keranjang/add', 'TransaksiController::add', ['filter' => 'auth']);
$routes->get('keranjang/remove/(:any)', 'TransaksiController::remove/$1', ['filter' => 'auth']);
$routes->get('keranjang/clear', 'TransaksiController::cart_clear', ['filter' => 'auth']);
$routes->get('checkout', 'TransaksiController::checkout', ['filter' => 'auth']);

$routes->get('ajax/destinations', 'TransaksiController::destinations', ['filter' => 'auth']);
$routes->get('ajax/costs', 'TransaksiController::costs', ['filter' => 'auth']);

$routes->post('buy', 'TransaksiController::buy', ['filter' => 'auth']);

$routes->get('history', 'TransaksiController::history', ['filter' => 'auth']);

$routes->resource('api/products', ['controller' => 'Api\ProdukController']);
$routes->resource('api/discount', ['controller' => 'Api\DiscountController']);
$routes->get('api/transactions', 'Api\TransaksiController::index');

$routes->group('pembelian', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('', 'TransactionAdminController::index');
    $routes->get('status/(:any)', 'TransactionAdminController::status/$1');
});

$routes->group('diskon', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('', 'DiscountController::index');
    $routes->post('create', 'DiscountController::create');
    $routes->post('edit/(:any)', 'DiscountController::edit/$1');
    $routes->get('delete/(:any)', 'DiscountController::delete/$1');
});

$routes->get('profile', 'ProfileController::index', ['filter' => 'auth']);
$routes->post('profile/update', 'ProfileController::update', ['filter' => 'auth']);
