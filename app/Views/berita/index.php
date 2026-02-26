<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 md:mb-12 text-center md:text-left px-2 md:px-0">
    <h1 class="text-2xl md:text-4xl font-extrabold text-navy-900 mb-3 flex flex-col md:flex-row items-center md:justify-start gap-2 md:gap-3">
        <i class="ph ph-newspaper text-3xl md:text-4xl text-blue-600"></i> 
        <span>Portal Berita & Informasi</span>
    </h1>
    <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto md:mx-0 leading-relaxed">
        Ikuti perkembangan terbaru, pengumuman, dan dokumentasi kegiatan dari seluruh laboratorium di lingkungan Yayasan Mabadi'ul Ihsan.
    </p>
</div>

<?php if(empty($berita)): ?>
    <div class="bg-white p-10 md:p-12 rounded-2xl shadow-sm border border-gray-100 text-center mx-4 md:mx-0">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-article text-3xl md:text-4xl"></i>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-700 mb-2">Belum Ada Publikasi</h3>
        <p class="text-sm md:text-base text-gray-500">Nantikan informasi menarik seputar kegiatan laboratorium kami segera.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 mb-12">
        <?php foreach($berita as $b): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col h-full group">
                
                <a href="<?= base_url('berita/baca/' . $b->slug) ?>" class="block relative aspect-video overflow-hidden bg-gray-100">
                    <?php if($b->thumbnail): ?>
                        <img src="<?= base_url('uploads/berita/' . $b->thumbnail) ?>" alt="<?= esc($b->judul) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <i class="ph ph-image text-5xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-[10px] md:text-xs font-bold px-2.5 py-1 rounded-full flex items-center gap-1 shadow-sm">
                        <i class="ph ph-eye"></i> <?= $b->views ?>
                    </div>
                </a>

                <div class="p-5 md:p-6 flex flex-col flex-grow">
                    <div class="flex flex-wrap items-center gap-3 text-[10px] md:text-xs font-bold text-gray-500 mb-3 uppercase tracking-wide">
                        <span class="flex items-center gap-1 text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">
                            <i class="ph ph-user-circle text-sm"></i> <?= esc($b->penulis) ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="ph ph-calendar-blank text-sm"></i> <?= date('d M Y', strtotime($b->created_at)) ?>
                        </span>
                    </div>
                    
                    <a href="<?= base_url('berita/baca/' . $b->slug) ?>">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 leading-snug mb-3 group-hover:text-blue-600 transition-colors line-clamp-2">
                            <?= esc($b->judul) ?>
                        </h3>
                    </a>
                    
                    <div class="mt-auto pt-4 border-t border-gray-50">
                        <a href="<?= base_url('berita/baca/' . $b->slug) ?>" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-navy-900 transition p-1 -ml-1">
                            Baca Selengkapnya <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="flex justify-center mt-8 ci4-pagination px-4 overflow-x-auto">
    <?= $pager->links('berita') ?>
</div>

<style>
    /* Styling Pagination Responsif */
    .ci4-pagination ul { 
        display: flex; 
        flex-wrap: wrap; 
        justify-content: center; 
        gap: 0.5rem; 
        list-style: none; 
        padding: 0; 
    }
    .ci4-pagination li a, .ci4-pagination li span { 
        display: flex; align-items: center; justify-content: center; 
        min-width: 2.25rem; height: 2.25rem; padding: 0 0.5rem; /* Ukuran touch target */
        border-radius: 0.5rem; font-weight: 600; font-size: 0.8rem;
        background-color: white; border: 1px solid #e5e7eb; color: #4b5563;
        transition: all 0.2s;
    }
    .ci4-pagination li a:hover { background-color: #f3f4f6; color: #1e3a8a; border-color: #d1d5db; }
    .ci4-pagination li.active a, .ci4-pagination li.active span { 
        background-color: #1e3a8a; color: white; border-color: #1e3a8a; 
    }
</style>

<?= $this->endSection() ?>