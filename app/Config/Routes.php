<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/produk', 'ProdukController::index');
$routes->get('/keranjang', 'TransaksiController::index');
$routes->post('/produk/simpan','ProdukController::simpan');
$routes->put('/produk/update/(:num)','ProdukController::update/$1');
$routes->delete('/produk/delete/(:num)','ProdukController::delete/$1');
