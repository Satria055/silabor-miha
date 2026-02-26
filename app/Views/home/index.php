<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="bg-navy-900 text-white rounded-2xl p-6 md:p-16 mb-12 shadow-lg relative overflow-hidden group">
    <i class="ph ph-desktop absolute -right-12 -bottom-16 text-[180px] md:text-[250px] text-white opacity-5 group-hover:scale-110 transition-transform duration-700"></i>
    
    <div class="relative z-10 max-w-3xl">
        <div class="inline-block bg-blue-800 text-blue-100 px-3 py-1 rounded-full text-[10px] md:text-xs font-bold tracking-widest mb-4 border border-blue-700">
            VERSI 1.2
        </div>
        
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight mb-4">
            Sistem Informasi <br class="hidden md:block"> Laboratorium Terpadu
        </h1>
        
        <p class="text-base md:text-lg text-blue-100 mb-8 leading-relaxed max-w-xl">
            Platform manajemen dan peminjaman ruang laboratorium lintas unit secara real-time. Memudahkan pengorganisasian jadwal KBM dan kegiatan khusus yayasan tanpa bentrok.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4">
            <a href="<?= base_url('jadwal') ?>" class="w-full sm:w-auto bg-white text-navy-900 hover:bg-gray-100 font-bold py-3.5 px-8 rounded-xl shadow-md transition flex items-center justify-center gap-2 group-hover:shadow-lg">
                <i class="ph ph-calendar-magnifying-glass text-xl"></i> Cek Jadwal Lab
            </a>
            
            <?php if(!session()->get('logged_in')): ?>
                <a href="<?= base_url('login') ?>" class="w-full sm:w-auto bg-transparent border border-blue-400 text-blue-100 hover:bg-blue-800 hover:text-white font-bold py-3.5 px-8 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="ph ph-sign-in text-xl"></i> Masuk Sistem
                </a>
            <?php else: ?>
                <a href="<?= base_url('dashboard') ?>" class="w-full sm:w-auto bg-transparent border border-blue-400 text-blue-100 hover:bg-blue-800 hover:text-white font-bold py-3.5 px-8 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="ph ph-squares-four text-xl"></i> Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition group">
        <div class="w-16 h-16 mx-auto bg-blue-50 text-navy-800 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="ph ph-door-open text-3xl"></i>
        </div>
        <h3 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-1"><?= esc($total_lab) ?></h3>
        <p class="text-gray-500 font-medium text-sm md:text-base">Laboratorium Aktif</p>
    </div>
    
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition group">
        <div class="w-16 h-16 mx-auto bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="ph ph-buildings text-3xl"></i>
        </div>
        <h3 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-1"><?= esc($total_unit) ?></h3>
        <p class="text-gray-500 font-medium text-sm md:text-base">Unit Pendidikan</p>
    </div>
    
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:shadow-md transition group">
        <div class="w-16 h-16 mx-auto bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="ph ph-calendar-check text-3xl"></i>
        </div>
        <h3 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-1"><?= esc($total_kegiatan) ?></h3>
        <p class="text-gray-500 font-medium text-sm md:text-base">Kegiatan Selesai/Aktif</p>
    </div>
</div>

<div class="mb-12">
    <div class="text-center mb-10 md:mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-navy-900 mb-2">Mengapa Menggunakan Silabor Miha?</h2>
        <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto">Solusi modern untuk efisiensi pengelolaan sarana pendidikan di lingkungan yayasan.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 text-left">
        <div class="bg-white md:bg-transparent p-6 md:p-0 rounded-xl shadow-sm md:shadow-none border border-gray-100 md:border-none">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-navy-800">
                    <i class="ph ph-clock-countdown text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-800">Peminjaman Real-Time</h4>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">
                Sistem mendeteksi irisan waktu secara presisi. Tidak ada lagi risiko jadwal KBM atau kegiatan khusus yang bentrok di satu ruangan yang sama.
            </p>
        </div>

        <div class="bg-white md:bg-transparent p-6 md:p-0 rounded-xl shadow-sm md:shadow-none border border-gray-100 md:border-none">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-navy-800">
                    <i class="ph ph-users-three text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-800">Manajemen Lintas Unit</h4>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">
                Satu gerbang untuk mengelola data jadwal laboratorium dari berbagai unit pendidikan dengan hak akses yang terisolasi aman.
            </p>
        </div>

        <div class="bg-white md:bg-transparent p-6 md:p-0 rounded-xl shadow-sm md:shadow-none border border-gray-100 md:border-none">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-navy-800">
                    <i class="ph ph-file-pdf text-2xl"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-800">Paperless & Terdokumentasi</h4>
            </div>
            <p class="text-gray-600 text-sm leading-relaxed">
                Proses unggah surat permohonan hingga persetujuan koordinator dilakukan sepenuhnya digital dan terekam dengan baik.
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>