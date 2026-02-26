<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Silabor Miha - Administrasi Laboratorium</title>
    
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { 700: '#1e3a8a', 800: '#172554', 900: '#0f172a' } // Definisi warna Navy manual
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">
    
    <?php 
        // ====================================================================
        // 1. HELPER FUNGSI UNTUK STATUS AKTIF MENU
        // ====================================================================
        $currentPath = uri_string();
        if (empty($currentPath)) $currentPath = 'home';
        
        $isLoggedIn = session()->get('logged_in');
        $role = session()->get('role');

        // Fungsi Cek Aktif Desktop (Garis Bawah)
        $deskActive = function($path) use ($currentPath) {
            $isActive = ($path === 'home') ? ($currentPath === 'home' || $currentPath === '/') : (strpos($currentPath, $path) === 0);
            return $isActive ? 'text-blue-300 font-bold border-b-2 border-blue-300 pb-1' : 'text-white hover:text-blue-200 transition pb-1 border-b-2 border-transparent';
        };

        // Fungsi Cek Aktif Mobile (Background Navy Terang)
        $mobActive = function($path) use ($currentPath) {
            $isActive = ($path === 'home') ? ($currentPath === 'home' || $currentPath === '/') : (strpos($currentPath, $path) === 0);
            return $isActive ? 'bg-navy-700 text-blue-200 border-l-4 border-blue-400 pl-3 py-2 rounded-r-md font-bold' : 'text-blue-100 hover:text-white hover:bg-navy-700 px-4 py-2 rounded-md transition';
        };

        // Fungsi Cek Parent Dropdown Aktif (Untuk Menandai Menu Induk)
        $isGroupActive = function($paths) use ($currentPath) {
            foreach ($paths as $p) {
                if (strpos($currentPath, $p) === 0) return true;
            }
            return false;
        };

        // ====================================================================
        // 2. LOGIKA NOTIFIKASI
        // ====================================================================
        $unreadNotifCount = 0;
        $latestNotifs = [];
        if($isLoggedIn) {
            $db = \Config\Database::connect();
            if ($db->tableExists('notifications')) {
                $latestNotifs = $db->table('notifications')
                                   ->where('user_id', session()->get('id'))
                                   ->where('is_read', 0)
                                   ->orderBy('created_at', 'DESC')
                                   ->limit(5)
                                   ->get()->getResult();
                $unreadNotifCount = count($latestNotifs);
            }
        }
    ?>

    <header class="bg-navy-900 text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            
            <a href="<?= base_url('/') ?>" class="text-xl font-bold tracking-wide flex items-center gap-3 hover:text-blue-200 transition">
                <img src="<?= base_url('img/logo.png') ?>" alt="Logo Silabor" class="h-9 w-auto drop-shadow-md">
            </a>
            
            <div class="flex items-center gap-3 md:hidden">
                <button id="mobileMenuBtn" class="text-white hover:text-blue-200 focus:outline-none">
                    <i class="ph ph-list text-3xl"></i>
                </button>
            </div>

            <nav class="hidden md:flex items-center gap-6 font-semibold text-sm pt-1">
                
                <?php if(!$isLoggedIn): ?>
                    <a href="<?= base_url('/home') ?>" class="<?= $deskActive('home') ?>">Beranda</a>
                    <a href="<?= base_url('/berita') ?>" class="<?= $deskActive('berita') ?>">Portal Berita</a>
                    <a href="<?= base_url('/jadwal') ?>" class="<?= $deskActive('jadwal') ?>">Jadwal Lab</a>
                    <a href="<?= base_url('/struktur') ?>" class="<?= $deskActive('struktur') ?>">Struktur Organisasi</a>
                    <a href="<?= base_url('/downloads') ?>" class="<?= $deskActive('downloads') ?>">Pusat Unduhan</a>
                    
                    <div class="border-l border-blue-700 pl-6 ml-2">
                        <a href="<?= base_url('/login') ?>" class="bg-white text-navy-900 hover:bg-gray-100 px-5 py-2 rounded-md transition flex items-center gap-1 shadow-sm"><i class="ph ph-sign-in text-lg"></i> Login</a>
                    </div>

                <?php else: ?>
                    <a href="<?= base_url('/dashboard') ?>" class="<?= $deskActive('dashboard') ?>">Dashboard</a>

                    <?php if(in_array($role, ['super_admin', 'admin_unit', 'admin_lab'])): ?>
                    <div class="relative group pb-1">
                        <button class="<?= $isGroupActive(['berita/manage', 'struktur/manage', 'downloads/manage']) ? 'text-blue-300 font-bold border-b-2 border-blue-300' : 'text-white hover:text-blue-200 border-b-2 border-transparent' ?> transition focus:outline-none flex items-center gap-1 pb-1">
                            Hal. Utama <i class="ph ph-caret-down text-xs mt-0.5"></i>
                        </button>
                        <div class="absolute left-0 top-full mt-1 w-60 bg-white text-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100 font-normal">
                            <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?>
                                <a href="<?= base_url('/berita/manage') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-newspaper-clipping"></i> Kelola Berita</a>
                            <?php endif; ?>
                            <?php if($role == 'super_admin'): ?>
                                <a href="<?= base_url('/struktur/manage') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-identification-badge"></i> Struktur Organisasi</a>
                            <?php endif; ?>
                            <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?>
                                <a href="<?= base_url('/downloads/manage') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition flex items-center gap-2"><i class="ph ph-folder-open"></i> Pusat Unduhan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?>
                    <div class="relative group pb-1">
                        <button class="<?= $isGroupActive(['units', 'jam-pelajaran']) ? 'text-blue-300 font-bold border-b-2 border-blue-300' : 'text-white hover:text-blue-200 border-b-2 border-transparent' ?> transition focus:outline-none flex items-center gap-1 pb-1">
                            Manajemen Unit <i class="ph ph-caret-down text-xs mt-0.5"></i>
                        </button>
                        <div class="absolute left-0 top-full mt-1 w-56 bg-white text-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100 font-normal">
                            <?php if($role == 'super_admin'): ?>
                                <a href="<?= base_url('/units') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-buildings"></i> Unit Pendidikan</a>
                            <?php endif; ?>
                            <a href="<?= base_url('/jam-pelajaran') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition flex items-center gap-2"><i class="ph ph-clock"></i> Jam KBM</a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="relative group pb-1">
                        <button class="<?= $isGroupActive(['bookings', 'laboratories', 'inventory', 'reports']) ? 'text-blue-300 font-bold border-b-2 border-blue-300' : 'text-white hover:text-blue-200 border-b-2 border-transparent' ?> transition focus:outline-none flex items-center gap-1 pb-1">
                            Manajemen Lab <i class="ph ph-caret-down text-xs mt-0.5"></i>
                        </button>
                        <div class="absolute left-0 top-full mt-1 w-60 bg-white text-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100 font-normal">
                            <a href="<?= base_url('/bookings') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-calendar-plus"></i> Peminjaman Lab</a>
                            
                            <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?>
                                <a href="<?= base_url('/laboratories') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-door-open"></i> Kelola Lab</a>
                                <a href="<?= base_url('/inventory') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-archive-box"></i> Inventaris Lab</a>
                                <a href="<?= base_url('/reports') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition flex items-center gap-2"><i class="ph ph-files"></i> Laporan</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(in_array($role, ['super_admin', 'admin_lab', 'admin_unit'])): ?>
                    <div class="relative group pb-1">
                        <button class="<?= $isGroupActive(['settings', 'users']) ? 'text-blue-300 font-bold border-b-2 border-blue-300' : 'text-white hover:text-blue-200 border-b-2 border-transparent' ?> transition focus:outline-none flex items-center gap-1 pb-1">
                            Pengaturan <i class="ph ph-caret-down text-xs mt-0.5"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-1 w-56 bg-white text-gray-800 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100 font-normal">
                            <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?>
                                <a href="<?= base_url('/settings') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition border-b border-gray-50 flex items-center gap-2"><i class="ph ph-gear"></i> Pengaturan Sistem</a>
                            <?php endif; ?>
                            <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?>
                                <a href="<?= base_url('/users') ?>" class="block px-4 py-2.5 hover:bg-blue-50 hover:text-navy-800 transition flex items-center gap-2"><i class="ph ph-users"></i> Kelola Pengguna</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="border-l border-blue-700 pl-4 flex items-center gap-4 ml-2">
                        
                        <div class="relative group mt-1">
                            <button class="text-white hover:text-blue-200 transition focus:outline-none relative">
                                <i class="ph ph-bell text-2xl"></i>
                                <?php if($unreadNotifCount > 0): ?>
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-pulse"><?= $unreadNotifCount ?></span>
                                <?php endif; ?>
                            </button>
                            <div class="absolute right-[-60px] top-full mt-3 w-72 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100 font-normal">
                                <div class="bg-gray-50 border-b border-gray-100 px-4 py-2 font-bold text-sm text-navy-900 flex justify-between items-center">
                                    <span>Pemberitahuan</span>
                                    <?php if($unreadNotifCount > 0): ?>
                                        <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded-full"><?= $unreadNotifCount ?> Baru</span>
                                    <?php endif; ?>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <?php if($unreadNotifCount > 0): ?>
                                        <?php foreach($latestNotifs as $notif): ?>
                                            <a href="<?= base_url('notification/read/' . $notif->id) ?>" class="block px-4 py-3 border-b border-gray-50 hover:bg-blue-50 transition">
                                                <p class="text-xs font-bold text-navy-800 mb-0.5"><?= esc($notif->judul) ?></p>
                                                <p class="text-[11px] text-gray-500 leading-tight"><?= esc($notif->pesan) ?></p>
                                                <span class="text-[9px] text-gray-400 mt-1 block"><?= date('d M Y, H:i', strtotime($notif->created_at)) ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="px-4 py-6 text-center text-gray-400 text-xs"><i class="ph ph-bell-slash text-3xl mb-2 text-gray-300"></i><p>Belum ada notifikasi baru.</p></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php
                            $fullName = session()->get('nama') ?? 'User';
                            $names = explode(' ', trim($fullName));
                            $shortName = $names[0]; 
                            $initial = strtoupper(substr($shortName, 0, 1));
                            $roleName = session()->get('role') ?? 'Guest';
                            $userId = session()->get('id');
                        ?>
                        <div class="relative group">
                            <button class="flex items-center gap-3 focus:outline-none text-left">
                                <div class="hidden md:block"> 
                                    <div class="text-white font-bold text-sm leading-tight text-right"><?= esc($shortName) ?></div>
                                    <div class="text-blue-300 text-[10px] font-bold uppercase tracking-wider text-right"><?= strtoupper(str_replace('_', ' ', esc($roleName))) ?></div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-lg border-2 border-blue-400 shadow-sm group-hover:bg-blue-500 transition shrink-0" style="flex-shrink: 0;">
                                    <?= $initial ?>
                                </div>
                            </button>
                            <div class="absolute right-0 top-full mt-2 w-64 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100">
                                <div class="bg-navy-900 px-4 py-4 text-center flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-2xl border-2 border-blue-400 mb-2 shadow-sm shrink-0" style="width: 56px; height: 56px; min-width: 56px; flex-shrink: 0;">
                                        <?= $initial ?>
                                    </div>
                                    <p class="text-white font-bold text-sm truncate w-full"><?= esc($fullName) ?></p>
                                    <span class="bg-blue-800 text-blue-200 text-[10px] px-2 py-0.5 rounded-full uppercase tracking-wider font-bold border border-blue-700 mt-1 inline-block">
                                        <?= strtoupper(str_replace('_', ' ', esc($roleName))) ?>
                                    </span>
                                </div>
                                <div class="py-2">
                                    <a href="<?= base_url('/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-navy-800 transition flex items-center gap-2"><i class="ph ph-user-gear text-lg"></i> Edit Profil Saya</a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?= base_url('/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2 font-bold"><i class="ph ph-sign-out text-lg"></i> Keluar Aplikasi</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </nav>
        </div>

        <div id="mobileMenu" class="hidden md:hidden bg-navy-800 border-t border-navy-700 px-4 py-4 flex flex-col gap-2 text-sm font-semibold overflow-y-auto max-h-[80vh]">
            
            <?php if(!$isLoggedIn): ?>
                <a href="<?= base_url('/home') ?>" class="block <?= $mobActive('home') ?> flex items-center gap-2"><i class="ph ph-house text-lg"></i> Beranda</a>
                <a href="<?= base_url('/berita') ?>" class="block <?= $mobActive('berita') ?> flex items-center gap-2"><i class="ph ph-newspaper text-lg"></i> Portal Berita</a>
                <a href="<?= base_url('/jadwal') ?>" class="block <?= $mobActive('jadwal') ?> flex items-center gap-2"><i class="ph ph-calendar text-lg"></i> Jadwal Lab</a>
                <a href="<?= base_url('/struktur') ?>" class="block <?= $mobActive('struktur') ?> flex items-center gap-2"><i class="ph ph-users-three text-lg"></i> Struktur Organisasi</a>
                <a href="<?= base_url('/downloads') ?>" class="block <?= $mobActive('downloads') ?> flex items-center gap-2"><i class="ph ph-download-simple text-lg"></i> Pusat Unduhan</a>
                
                <hr class="border-navy-700 my-2">
                <a href="<?= base_url('/login') ?>" class="bg-white text-navy-900 text-center py-2 mx-4 rounded-md font-bold transition flex items-center justify-center gap-1"><i class="ph ph-sign-in text-lg"></i> Login</a>

            <?php else: ?>
                <?php if($unreadNotifCount > 0): ?>
                    <div class="bg-navy-700 rounded-md overflow-hidden mb-2">
                        <div class="px-4 py-2 text-xs font-bold text-blue-200 border-b border-navy-600 flex justify-between">
                            <span><i class="ph ph-bell text-sm"></i> Notifikasi Baru</span>
                            <span class="bg-red-500 text-white px-1.5 py-0.5 rounded-full text-[10px]"><?= $unreadNotifCount ?></span>
                        </div>
                        <div class="max-h-40 overflow-y-auto">
                            <?php foreach($latestNotifs as $notif): ?>
                                <a href="<?= base_url('notification/read/' . $notif->id) ?>" class="block px-4 py-2 border-b border-navy-600 hover:bg-navy-600 transition">
                                    <p class="text-xs font-bold text-white"><?= esc($notif->judul) ?></p>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <a href="<?= base_url('/dashboard') ?>" class="block <?= $mobActive('dashboard') ?> flex items-center gap-2"><i class="ph ph-squares-four text-lg"></i> Dashboard</a>
                
                <?php if(in_array($role, ['super_admin', 'admin_unit', 'admin_lab'])): ?>
                    <button onclick="toggleMobMenu('mobMainPage')" class="w-full text-left flex items-center justify-between text-blue-100 hover:text-white transition px-4 py-2 mt-1">
                        <span class="flex items-center gap-2"><i class="ph ph-globe text-lg"></i> Hal. Utama</span><i class="ph ph-caret-down"></i>
                    </button>
                    <div id="mobMainPage" class="hidden flex-col gap-1 pl-6 border-l-2 border-navy-700 ml-2">
                        <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?> <a href="<?= base_url('/berita/manage') ?>" class="block <?= $mobActive('berita/manage') ?>">Kelola Berita</a> <?php endif; ?>
                        <?php if($role == 'super_admin'): ?> <a href="<?= base_url('/struktur/manage') ?>" class="block <?= $mobActive('struktur/manage') ?>">Struktur Organisasi</a> <?php endif; ?>
                        <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?> <a href="<?= base_url('/downloads/manage') ?>" class="block <?= $mobActive('downloads/manage') ?>">Pusat Unduhan</a> <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?>
                    <button onclick="toggleMobMenu('mobUnit')" class="w-full text-left flex items-center justify-between text-blue-100 hover:text-white transition px-4 py-2 mt-1">
                        <span class="flex items-center gap-2"><i class="ph ph-buildings text-lg"></i> Manajemen Unit</span><i class="ph ph-caret-down"></i>
                    </button>
                    <div id="mobUnit" class="hidden flex-col gap-1 pl-6 border-l-2 border-navy-700 ml-2">
                        <?php if($role == 'super_admin'): ?> <a href="<?= base_url('/units') ?>" class="block <?= $mobActive('units') ?>">Unit Pendidikan</a> <?php endif; ?>
                        <a href="<?= base_url('/jam-pelajaran') ?>" class="block <?= $mobActive('jam-pelajaran') ?>">Jam KBM</a>
                    </div>
                <?php endif; ?>

                <button onclick="toggleMobMenu('mobLab')" class="w-full text-left flex items-center justify-between text-blue-100 hover:text-white transition px-4 py-2 mt-1">
                    <span class="flex items-center gap-2"><i class="ph ph-flask text-lg"></i> Manajemen Lab</span><i class="ph ph-caret-down"></i>
                </button>
                <div id="mobLab" class="hidden flex-col gap-1 pl-6 border-l-2 border-navy-700 ml-2">
                    <a href="<?= base_url('/bookings') ?>" class="block <?= $mobActive('bookings') ?>">Peminjaman Lab</a>
                    <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?>
                        <a href="<?= base_url('/laboratories') ?>" class="block <?= $mobActive('laboratories') ?>">Kelola Lab</a>
                        <a href="<?= base_url('/inventory') ?>" class="block <?= $mobActive('inventory') ?>">Inventaris</a>
                        <a href="<?= base_url('/reports') ?>" class="block <?= $mobActive('reports') ?>">Laporan</a>
                    <?php endif; ?>
                </div>

                <?php if(in_array($role, ['super_admin', 'admin_lab', 'admin_unit'])): ?>
                    <button onclick="toggleMobMenu('mobSettings')" class="w-full text-left flex items-center justify-between text-blue-100 hover:text-white transition px-4 py-2 mt-1">
                        <span class="flex items-center gap-2"><i class="ph ph-gear text-lg"></i> Pengaturan</span><i class="ph ph-caret-down"></i>
                    </button>
                    <div id="mobSettings" class="hidden flex-col gap-1 pl-6 border-l-2 border-navy-700 ml-2">
                        <?php if(in_array($role, ['super_admin', 'admin_lab'])): ?> <a href="<?= base_url('/settings') ?>" class="block <?= $mobActive('settings') ?>">Pengaturan Sistem</a> <?php endif; ?>
                        <?php if(in_array($role, ['super_admin', 'admin_unit'])): ?> <a href="<?= base_url('/users') ?>" class="block <?= $mobActive('users') ?>">Kelola Pengguna</a> <?php endif; ?>
                    </div>
                <?php endif; ?>

                <hr class="border-navy-700 my-2">
                <div class="px-4 mb-3">
                     <a href="<?= base_url('/profile') ?>" class="block text-center w-full bg-blue-800 hover:bg-blue-700 text-blue-100 py-2 rounded-md font-bold text-xs transition mb-2">
                        <i class="ph ph-user-gear text-lg align-middle"></i> Edit Profil Saya
                    </a>
                </div>
                <div class="flex justify-between items-center px-4">
                    <div>
                        <div class="font-bold text-white"><?= esc(session()->get('nama')) ?></div>
                        <div class="text-blue-300 text-xs flex items-center gap-1"><i class="ph ph-shield-check"></i> <?= strtoupper(esc($role)) ?></div>
                    </div>
                    <a href="<?= base_url('/logout') ?>" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md transition flex items-center gap-1"><i class="ph ph-sign-out text-lg"></i> Keluar</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="flex-grow container mx-auto p-4 md:p-6">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="bg-navy-900 text-blue-50 py-10 border-t border-navy-800 mt-auto">
        <div class="container mx-auto px-4 md:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <a href="<?= base_url('/') ?>" class="flex items-center gap-3 mb-4">
                        <img src="<?= base_url('img/logo.png') ?>" alt="Logo Silabor" class="h-12 w-auto opacity-90 grayscale-[20%] hover:grayscale-0 transition">
                    </a>
                    <p class="text-sm text-blue-200 leading-relaxed mb-4 pr-4">
                        Sistem Informasi Laboratorium Terpadu Yayasan Mabadi'ul Ihsan. Mengelola jadwal, peminjaman, dan inventaris dengan efisien.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm text-blue-200">
                        <li><a href="<?= base_url('/home') ?>" class="hover:text-white transition flex items-center gap-2"><i class="ph ph-caret-right"></i> Beranda</a></li>
                        <li><a href="<?= base_url('/jadwal') ?>" class="hover:text-white transition flex items-center gap-2"><i class="ph ph-caret-right"></i> Jadwal Laboratorium</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Kontak Kami</h4>
                    <ul class="space-y-3 text-sm text-blue-200">
                        <li class="flex items-start gap-3"><i class="ph ph-map-pin text-xl mt-0.5"></i><span>Jl. K.H. Achmad Musayyidi No.177, Karangdoro, Kec. Tegalsari, Kabupaten Banyuwangi, Jawa Timur 68485</span></li>
                        <li class="flex items-center gap-3"><i class="ph ph-globe text-xl"></i><a href="https://ponpesmabadiulihsan.or.id" target="_blank" class="hover:text-white transition">ponpesmabadiulihsan.or.id</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-navy-700 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-blue-300">
                <p>&copy; <?= date('Y') ?> Yayasan Mabadi'ul Ihsan | Satria Yudha Pratama, S.Kom.</p>
            </div>
        </div>
    </footer>

    <script>
        // Toggle Mobile Menu Utama
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        const icon = btn?.querySelector('i');
        if(btn) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                if (menu.classList.contains('hidden')) {
                    icon.classList.replace('ph-x', 'ph-list');
                } else {
                    icon.classList.replace('ph-list', 'ph-x');
                }
            });
        }

        // Fungsi Helper untuk Toggle Dropdown Mobile
        function toggleMobMenu(id) {
            const el = document.getElementById(id);
            if(el) {
                el.classList.toggle('hidden');
                el.classList.toggle('flex');
                
                // Rotasi Icon Caret (Opsional, cari child i ph-caret)
                // Implementasi simpel: classList toggle ph-caret-down/up bisa ditambahkan jika perlu
            }
        }

        // === SWEETALERT (Notifikasi Sukses/Gagal) ===
        <?php if(session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success') ?>',
                timer: 3000, showConfirmButton: false, background: '#fff', color: '#1e3a8a'
            });
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error', title: 'Gagal!', text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#ef4444', background: '#fff', color: '#7f1d1d'
            });
        <?php endif; ?>
    </script>
</body>
</html>