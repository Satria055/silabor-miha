<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-12">
    
    <div class="p-6 md:p-12 pb-6 border-b border-gray-100 text-center">
        <div class="flex justify-start mb-6">
            <a href="<?= base_url('berita') ?>" class="inline-flex items-center gap-2 text-xs md:text-sm font-bold text-gray-500 hover:text-navy-800 transition bg-gray-50 px-4 py-2.5 rounded-lg active:scale-95 transform duration-150">
                <i class="ph ph-arrow-left text-lg"></i> Kembali
            </a>
        </div>
        
        <h1 class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-navy-900 leading-tight mb-6">
            <?= esc($berita->judul) ?>
        </h1>
        
        <div class="flex flex-wrap items-center justify-center gap-y-2 gap-x-4 md:gap-8 text-xs md:text-sm font-semibold text-gray-500">
            <span class="flex items-center gap-2 text-navy-800 bg-blue-50 px-3 py-1 rounded-full"><i class="ph ph-user-circle text-lg"></i> <?= esc($berita->penulis) ?></span>
            <span class="flex items-center gap-2"><i class="ph ph-calendar-blank text-lg"></i> <?= date('d M Y', strtotime($berita->created_at)) ?></span>
            <span class="flex items-center gap-2"><i class="ph ph-eye text-lg"></i> <?= $berita->views ?>x Dibaca</span>
        </div>
    </div>

    <?php if($berita->thumbnail): ?>
        <div class="w-full bg-gray-50 border-b border-gray-100">
            <img src="<?= base_url('uploads/berita/' . $berita->thumbnail) ?>" alt="<?= esc($berita->judul) ?>" class="w-full max-h-[300px] md:max-h-[500px] object-cover">
        </div>
    <?php endif; ?>

    <div class="p-6 md:p-12 text-gray-800 leading-relaxed prose prose-blue max-w-none">
        <?= $berita->konten ?>
    </div>

    <div class="p-6 md:p-12 pt-6 border-t border-gray-50 bg-gray-50 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-6">
        <h4 class="font-bold text-gray-700 text-sm md:text-base">Bagikan Informasi Ini:</h4>
        
        <?php 
            $currentUrl = urlencode(current_url()); 
            $shareText  = urlencode($berita->judul);
        ?>
        <div class="flex items-center gap-3 md:gap-4 w-full md:w-auto justify-center md:justify-end">
            <a href="https://api.whatsapp.com/send?text=<?= $shareText ?>%20-%20<?= $currentUrl ?>" target="_blank" class="flex-1 md:flex-none w-12 h-12 rounded-xl bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition shadow-sm active:scale-95" title="WhatsApp">
                <i class="ph ph-whatsapp-logo text-2xl"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $currentUrl ?>" target="_blank" class="flex-1 md:flex-none w-12 h-12 rounded-xl bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition shadow-sm active:scale-95" title="Facebook">
                <i class="ph ph-facebook-logo text-2xl"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?text=<?= $shareText ?>&url=<?= $currentUrl ?>" target="_blank" class="flex-1 md:flex-none w-12 h-12 rounded-xl bg-gray-900 text-white flex items-center justify-center hover:bg-black transition shadow-sm active:scale-95" title="X (Twitter)">
                <i class="ph ph-x-logo text-2xl"></i>
            </a>
            <button onclick="navigator.clipboard.writeText('<?= urldecode($currentUrl) ?>'); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Tautan disalin!', showConfirmButton:false, timer:2000});" class="flex-1 md:flex-none w-12 h-12 rounded-xl bg-gray-200 text-gray-700 flex items-center justify-center hover:bg-gray-300 transition shadow-sm active:scale-95" title="Salin Tautan">
                <i class="ph ph-link text-2xl"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Styling Konten Berita Responsif */
    .prose h1, .prose h2, .prose h3 { color: #1e3a8a; font-weight: 800; margin-top: 1.5rem; margin-bottom: 0.75rem; line-height: 1.3; }
    
    /* Font size konten: Agak lebih kecil di HP (16px), Besar di Desktop (18px) */
    .prose p { margin-bottom: 1rem; font-size: 1rem; color: #374151; line-height: 1.7; }
    @media (min-width: 768px) {
        .prose p { font-size: 1.125rem; margin-bottom: 1.25rem; }
        .prose h1, .prose h2, .prose h3 { margin-top: 2rem; margin-bottom: 1rem; }
    }

    .prose ul { list-style-type: disc; padding-left: 1.25rem; margin-bottom: 1rem; }
    .prose ol { list-style-type: decimal; padding-left: 1.25rem; margin-bottom: 1rem; }
    .prose li { margin-bottom: 0.25rem; font-size: 1rem; }
    @media (min-width: 768px) { .prose li { font-size: 1.125rem; margin-bottom: 0.5rem; } }

    .prose a { color: #2563eb; text-decoration: underline; font-weight: 600; }
    .prose blockquote { border-left: 4px solid #1e3a8a; padding-left: 1rem; font-style: italic; color: #4b5563; background: #f9fafb; padding: 1rem; border-radius: 0 0.5rem 0.5rem 0; margin-bottom: 1.5rem; }
    .prose img { border-radius: 0.75rem; margin-top: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); width: 100%; height: auto; }
</style>
<?= $this->endSection() ?>