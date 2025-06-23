<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('', function ($routes) {
    $routes->get('/', 'Home::index');
    $routes->get('/profile', 'Page::profile');
    $routes->post('/profile_update', 'Page::profile_update');
});

// Product routes
$routes->group('product', function ($routes) {
    $routes->get('/', 'ProductController::index');
    $routes->get('tambah_ke_keranjang/(:segment)', 'ProductController::tambah_ke_keranjang/$1');
    $routes->get('tambah_menu', 'ProductController::tambah_menu', ['filter'=>'role:admin']);
    $routes->post('store_product', 'ProductController::store', ['filter'=>'role:admin']);
    $routes->get('edit_menu/(:num)', 'ProductController::edit_menu/$1', ['filter'=>'role:admin']);
    $routes->post('update_menu/(:num)', 'ProductController::update_menu/$1', ['filter'=>'role:admin']);
    $routes->get('delete_menu/(:num)', 'ProductController::delete_menu/$1', ['filter'=>'role:admin']);
});

// Cart routes
$routes->group('cart', function ($routes) {
    $routes->get('/', 'CartController::index');
    $routes->get('tambah_qty/(:segment)', 'CartController::tambah_qty/$1');
    $routes->get('kurang_qty/(:segment)', 'CartController::kurang_qty/$1');
    $routes->get('hapus/(:segment)', 'CartController::hapus/$1');
    $routes->get('checkout', 'CartController::checkout');
    $routes->post('checkout_process', 'CartController::checkout_process');
});

// Order routes
$routes->group('orders', function ($routes) {
    $routes->get('/', 'OrderController::index');
});

$routes->group('admin', ['filter' => 'role:admin'], function($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('orders_list', 'AdminController::orderlist');
    $routes->get('orders_list/detail/(:num)', 'AdminController::orderDetail/$1'); // Rute baru untuk detail pesanan
    $routes->post('updateStatus/(:num)', 'AdminController::updateStatus/$1');
    $routes->post('verifyPayment/(:num)', 'AdminController::verifyPayment/$1');
    $routes->get('users', 'AdminController::users'); 
    $routes->get('users/edit/(:num)', 'AdminController::editUser/$1'); 
    $routes->post('users/update/(:num)', 'AdminController::updateUser/$1');
    $routes->get('users/delete/(:num)', 'AdminController::deleteUser/$1');
    $routes->get('exportReport', 'AdminController::exportReport'); 
});