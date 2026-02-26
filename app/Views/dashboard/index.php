<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
    // Library Time CodeIgniter agar Akurat zona Asia/Jakarta
    use CodeIgniter\I18n\Time;

    // Paksa ambil waktu Jakarta saat ini
    $now = Time::now('Asia/Jakarta');
    $hour = $now->getHour();

    // Logika Sapaan yang Lebih Halus
    if ($hour >= 5 && $hour < 11) { 
        $salam = "Selamat Pagi"; 
        $iconSalam = "ph-sun"; 
    }
    elseif ($hour >= 11 && $hour < 15) { 
        $salam = "Selamat Siang"; 
        $iconSalam = "ph-sun-dim"; 
    }
    elseif ($hour >= 15 && $hour < 18) { 
        $salam = "Selamat Sore"; 
        $iconSalam = "ph-cloud-sun"; 
    }
    elseif ($hour >= 18 || $hour < 24) { 
        $salam = "Selamat Malam"; 
        $iconSalam = "ph-moon-stars"; 
    }
    else {
        // Handle jam 00:00 - 04:59 (Dini Hari)
        // Opsional: Bisa tetap "Selamat Malam" atau "Selamat Pagi" jika mau
        $salam = "Selamat Malam"; 
        $iconSalam = "ph-moon-stars";
    }
?>

<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        <h2 class="text-2xl md:text-3xl font-extrabold text-navy-900 flex items-center gap-2">
            <?= $salam ?>, <span class="text-blue-600"><?= esc($nama) ?></span> <i class="ph <?= $iconSalam ?> text-yellow-500 animate-pulse"></i>
        </h2>
        <p class="text-gray-500 text-sm md:text-base mt-1">Berikut adalah ringkasan aktivitas laboratorium hari ini.</p>
    </div>
    
    <div class="self-start md:self-auto inline-flex items-center gap-2 bg-white border border-gray-200 shadow-sm px-4 py-2 rounded-full">
        <div class="w-2 h-2 rounded-full bg-green-500 animate-ping"></div>
        <span class="text-xs md:text-sm font-bold text-navy-800 tracking-wide">
            AKSES: <?= strtoupper(esc($role)) ?>
        </span>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    
    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Total Lab</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-navy-900 group-hover:text-blue-600 transition-colors"><?= esc($total_lab) ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="ph ph-door-open text-2xl"></i>
        </div>
    </div>
    
    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Jadwal Aktif</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-navy-900 group-hover:text-green-600 transition-colors"><?= esc($peminjaman_aktif) ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="ph ph-calendar-check text-2xl"></i>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Menunggu</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-navy-900 group-hover:text-yellow-600 transition-colors"><?= esc($menunggu_validasi) ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="ph ph-clock-countdown text-2xl"></i>
        </div>
    </div>

    <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow group">
        <div>
            <p class="text-xs md:text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Pengguna</p>
            <h3 class="text-2xl md:text-3xl font-extrabold text-navy-900 group-hover:text-purple-600 transition-colors"><?= esc($total_pengguna) ?></h3>
        </div>
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="ph ph-users-three text-2xl"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 md:p-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-activity text-blue-600"></i> Aktivitas Terkini
        </h3>
        <a href="<?= base_url('bookings') ?>" class="text-xs font-bold text-blue-600 hover:text-blue-800">Lihat Semua</a>
    </div>
    
    <div class="p-2 md:p-4">
        <?php if(empty($recent_activities)): ?>
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-300">
                    <i class="ph ph-folder-dashed text-3xl"></i>
                </div>
                <p class="text-gray-500 font-medium">Belum ada aktivitas baru.</p>
            </div>
        <?php else: ?>
            <div class="space-y-2">
                <?php foreach($recent_activities as $activity): ?>
                    <div class="group flex items-start gap-3 md:gap-4 p-3 hover:bg-gray-50 rounded-xl transition-colors border border-transparent hover:border-gray-100">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mt-0.5">
                            <i class="ph ph-calendar-plus text-lg"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-navy-900 font-semibold truncate">
                                <?= esc($activity->peminjam) ?>
                            </p>
                            <p class="text-xs text-gray-500 truncate">
                                Mengajukan: <span class="font-medium text-gray-700"><?= esc($activity->nama_lab) ?></span>
                            </p>
                            <p class="text-[10px] md:text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="ph ph-clock"></i> <?= date('d M, H:i', strtotime($activity->updated_at)) ?>
                            </p>
                        </div>
                        
                        <div class="shrink-0 text-right">
                            <?php if ($activity->status == 'pending'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-800">
                                    Menunggu
                                </span>
                            <?php elseif ($activity->status == 'disetujui'): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>