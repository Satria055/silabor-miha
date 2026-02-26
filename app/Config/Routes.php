<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ====================================================================
// ZONA PUBLIK (Bisa diakses tanpa login)
// ====================================================================
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/jadwal', 'Home::jadwal');
$routes->get('/struktur', 'Struktur::index');
$routes->get('/downloads', 'Download::index');

// Menyuplai data JSON ke kalender
$routes->get('/api/events', 'Home::getEvents');

// Portal Berita Publik
$routes->get('/berita', 'Berita::index');
$routes->get('/berita/baca/(:segment)', 'Berita::detail/$1');

// Rute untuk Autentikasi
$routes->get('/login', 'Auth::index');
$routes->post('/auth/process', 'Auth::process');
$routes->get('/logout', 'Auth::logout');


// ====================================================================
// ZONA WAJIB LOGIN (Semua yang punya akun boleh masuk ke grup ini)
// ====================================================================
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    
    // Semua User: Dashboard & Modul Peminjaman (Lihat & Input)
    $routes->get('/dashboard', 'Dashboard::index');

    // --- RUTE PROFIL (BISA DIAKSES SEMUA ROLE)
    $routes->get('/profile', 'Profile::index');
    $routes->post('/profile/update', 'Profile::update');
    
    $routes->get('/bookings', 'Booking::index');
    $routes->get('/bookings/kbm', 'Booking::formKbm');
    $routes->get('/bookings/khusus', 'Booking::formKhusus');
    $routes->post('/bookings/save-kbm', 'Booking::saveKbm');
    $routes->post('/bookings/save-khusus', 'Booking::saveKhusus');
    $routes->get('/bookings/edit/(:num)', 'Booking::edit/$1');
    $routes->post('/bookings/bulk-delete', 'Booking::bulkDelete');

    // API AJAX UNTUK CEK BENTROK REAL-TIME
    $routes->post('/bookings/api/check-conflict', 'Booking::checkConflictApi');

    // Rute Notifikasi Navbar
    $routes->get('/notification/read/(:num)', 'Notification::read/$1');

    // ====================================================================
    // ZONA SUPER ADMIN & ADMIN LAB (Manajemen Ruang & Fasilitas)
    // ====================================================================
    $routes->group('', ['filter' => 'role:super_admin,admin_lab'], static function ($routes) {
        // Validasi Peminjaman Admin Lab
        $routes->get('/bookings/approve/(:num)', 'Booking::approve/$1');
        $routes->get('/bookings/reject/(:num)', 'Booking::reject/$1');
        
        // Manajemen Laboratorium (CRUD Lab)
        $routes->get('/laboratories', 'Laboratory::index');
        $routes->get('/laboratories/add', 'Laboratory::form');
        $routes->get('/laboratories/edit/(:num)', 'Laboratory::form/$1');
        $routes->post('/laboratories/save', 'Laboratory::save');
        $routes->get('/laboratories/delete/(:num)', 'Laboratory::delete/$1');
        $routes->post('/laboratories/bulk-delete', 'Laboratory::bulkDelete');
        
        // Manajemen Inventaris Lab
        $routes->get('/inventory', 'Inventory::index');
        $routes->get('/inventory/add', 'Inventory::form');
        $routes->get('/inventory/edit/(:num)', 'Inventory::form/$1');
        $routes->post('/inventory/save', 'Inventory::save');
        $routes->get('/inventory/delete/(:num)', 'Inventory::delete/$1');
        $routes->post('/inventory/bulk-delete', 'Inventory::bulkDelete');

        // Unduhan File
        $routes->get('/downloads/manage', 'Download::manage');
        $routes->get('/downloads/add', 'Download::form');
        $routes->get('/downloads/edit/(:num)', 'Download::form/$1');
        $routes->post('/downloads/save', 'Download::save');
        $routes->get('/downloads/delete/(:num)', 'Download::delete/$1');

        // Manajemen Laporan
        $routes->get('/reports', 'Reports::index');
        $routes->get('/reports/print-booking', 'Reports::printBooking');
        $routes->get('/reports/print-inventory', 'Reports::printInventory');

        // ROUTE PENGATURAN SISTEM (Jam Operasional & Batas Hari H-x)
        $routes->get('/settings', 'Settings::index');
        $routes->post('/settings/save', 'Settings::save');
    });

    // ====================================================================
    // ZONA SUPER ADMIN & ADMIN UNIT (Manajemen Akun, Waktu & Info)
    // ====================================================================
    $routes->group('', ['filter' => 'role:super_admin,admin_unit'], static function ($routes) {
        // Manajemen Jam Pelajaran
        $routes->get('/jam-pelajaran', 'JamPelajaran::index');
        $routes->get('/jam-pelajaran/add', 'JamPelajaran::form');
        $routes->get('/jam-pelajaran/edit/(:num)', 'JamPelajaran::form/$1');
        $routes->post('/jam-pelajaran/save', 'JamPelajaran::save');
        $routes->get('/jam-pelajaran/delete/(:num)', 'JamPelajaran::delete/$1');
        $routes->post('/jam-pelajaran/bulk-delete', 'JamPelajaran::bulkDelete');

        // Manajemen Pengguna
        $routes->get('/users', 'User::index');
        $routes->get('/users/add', 'User::form');
        $routes->get('/users/edit/(:num)', 'User::form/$1');
        $routes->post('/users/save', 'User::save');
        $routes->get('/users/delete/(:num)', 'User::delete/$1');
        $routes->post('/users/bulk-delete', 'User::bulkDelete');

        // Manajemen Berita CMS
        $routes->get('/berita/manage', 'Berita::manage');
        $routes->get('/berita/add', 'Berita::form');
        $routes->get('/berita/edit/(:num)', 'Berita::form/$1');
        $routes->post('/berita/save', 'Berita::save');
        $routes->get('/berita/delete/(:num)', 'Berita::delete/$1');
        $routes->post('/berita/bulk-delete', 'Berita::bulkDelete');
        $routes->post('/berita/upload-image', 'Berita::uploadImage'); 
    });

    // ====================================================================
    // ZONA KHUSUS SUPER ADMIN (Level Tertinggi)
    // ====================================================================
    $routes->group('', ['filter' => 'role:super_admin'], static function ($routes) {
        // Manajemen Unit Pendidikan Pusat
        $routes->get('/units', 'Unit::index');
        $routes->get('/units/add', 'Unit::form');
        $routes->get('/units/edit/(:num)', 'Unit::form/$1');
        $routes->post('/units/save', 'Unit::save');
        $routes->get('/units/delete/(:num)', 'Unit::delete/$1');
        $routes->post('/units/bulk-delete', 'Unit::bulkDelete');

        // Struktur Organisasi
        $routes->get('/struktur/manage', 'Struktur::manage');
        $routes->get('/struktur/add', 'Struktur::form');
        $routes->get('/struktur/edit/(:num)', 'Struktur::form/$1');
        $routes->post('/struktur/save', 'Struktur::save');
        $routes->get('/struktur/delete/(:num)', 'Struktur::delete/$1');
    });

});