<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-10 text-center md:text-left px-4 md:px-0">
    <h1 class="text-2xl md:text-4xl font-extrabold text-navy-900 mb-3 flex flex-col md:flex-row items-center md:justify-start gap-2 md:gap-3">
        <i class="ph ph-users-three text-3xl md:text-4xl text-blue-600"></i> 
        <span>Struktur Organisasi</span>
    </h1>
    <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto md:mx-0 leading-relaxed">
        Kenali lebih dekat tim pengelola dan koordinator Laboratorium Yayasan Mabadi'ul Ihsan. Kami siap membantu kelancaran kegiatan Anda.
    </p>
</div>

<?php if(empty($tim)): ?>
    <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100 text-center mx-4 md:mx-0">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-users-three text-3xl md:text-4xl"></i>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-700 mb-2">Belum Ada Data Tim</h3>
        <p class="text-sm md:text-base text-gray-500">Struktur organisasi pengelola laboratorium belum ditambahkan.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-12 px-4 md:px-0">
        <?php foreach($tim as $t): ?>
            <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden group flex flex-col h-full shadow-[0_2px_8px_rgb(0,0,0,0.08)] hover:shadow-[0_8px_16px_rgb(0,0,0,0.12)] hover:-translate-y-1 transition-all duration-300">
                
                <div class="h-64 sm:h-72 overflow-hidden bg-gray-50 relative md:aspect-[4/5]">
                    <?php if($t->foto): ?>
                        <img src="<?= base_url('uploads/tim/' . $t->foto) ?>" alt="<?= esc($t->nama) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100"><i class="ph ph-user text-7xl"></i></div>
                    <?php endif; ?>
                     </div>
                
                <div class="p-5 flex-grow flex flex-col text-center">
                    <div class="mb-4 flex-grow">
                        <h3 class="text-[1.1rem] font-bold text-navy-900 leading-tight mb-1"><?= esc($t->nama) ?></h3>
                        <p class="text-xs font-medium text-blue-600/80 uppercase tracking-widest"><?= esc($t->jabatan) ?></p>
                    </div>
                    
                    <div class="flex justify-center gap-2 pt-4 border-t border-gray-50 mt-auto">
                        <?php if($t->wa): ?>
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $t->wa) ?>" target="_blank" class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-green-500 hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="WhatsApp">
                                <i class="ph ph-whatsapp-logo text-lg"></i>
                            </a>
                        <?php endif; ?>
                        <?php if($t->ig): ?>
                            <a href="<?= esc($t->ig) ?>" target="_blank" class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-pink-500 hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="Instagram">
                                <i class="ph ph-instagram-logo text-lg"></i>
                            </a>
                        <?php endif; ?>
                        <?php if($t->fb): ?>
                            <a href="<?= esc($t->fb) ?>" target="_blank" class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="Facebook">
                                <i class="ph ph-facebook-logo text-lg"></i>
                            </a>
                        <?php endif; ?>
                        <?php if($t->web): ?>
                            <a href="<?= esc($t->web) ?>" target="_blank" class="w-9 h-9 rounded-xl bg-gray-100 text-gray-500 flex items-center justify-center hover:bg-navy-800 hover:text-white transition-all duration-300 shadow-sm active:scale-95" title="Website/Portofolio">
                                <i class="ph ph-globe text-lg"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>