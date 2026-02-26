<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 md:mb-10 text-center md:text-left px-4 md:px-0">
    <h1 class="text-2xl md:text-4xl font-extrabold text-navy-900 mb-3 flex flex-col md:flex-row items-center md:justify-start gap-2 md:gap-3">
        <i class="ph ph-download-simple text-3xl md:text-4xl text-blue-600"></i> 
        <span>Pusat Unduhan</span>
    </h1>
    <p class="text-base md:text-lg text-gray-500 max-w-2xl mx-auto md:mx-0 leading-relaxed">
        Dapatkan format surat peminjaman, Standar Operasional Prosedur (SOP), dan Tata Tertib Laboratorium secara langsung di sini.
    </p>
</div>

<?php if(empty($downloads)): ?>
    <div class="bg-white p-8 md:p-12 rounded-2xl shadow-sm border border-gray-100 text-center mx-4 md:mx-0">
        <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-folder-open text-3xl md:text-4xl"></i>
        </div>
        <h3 class="text-lg md:text-xl font-bold text-gray-700 mb-2">Belum Ada Dokumen</h3>
        <p class="text-sm md:text-base text-gray-500">Berkas unduhan belum tersedia saat ini.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 px-4 md:px-0 mb-12">
        <?php foreach($downloads as $doc): ?>
            <?php 
                // Logika Ikon & Warna (Tetap Dipertahankan)
                $icon = 'ph-file-text text-gray-500'; $bg = 'bg-gray-50';
                if(in_array(strtolower($doc->tipe_file), ['pdf'])) { $icon = 'ph-file-pdf text-red-500'; $bg = 'bg-red-50'; }
                elseif(in_array(strtolower($doc->tipe_file), ['doc', 'docx'])) { $icon = 'ph-file-doc text-blue-600'; $bg = 'bg-blue-50'; }
                elseif(in_array(strtolower($doc->tipe_file), ['xls', 'xlsx'])) { $icon = 'ph-file-xls text-green-600'; $bg = 'bg-green-50'; }
            ?>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 md:p-6 flex flex-col h-full hover:shadow-lg transition-shadow duration-300 group">
                
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 md:w-14 md:h-14 <?= $bg ?> rounded-xl flex items-center justify-center shrink-0 transition-transform group-hover:scale-110">
                        <i class="ph <?= $icon ?> text-2xl md:text-3xl"></i>
                    </div>
                    
                    <div class="min-w-0"> <h3 class="text-base md:text-lg font-bold text-navy-900 leading-snug line-clamp-2" title="<?= esc($doc->judul) ?>">
                            <?= esc($doc->judul) ?>
                        </h3>
                        <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">
                            Format: <?= esc($doc->tipe_file) ?>
                        </span>
                    </div>
                </div>

                <p class="text-sm text-gray-500 flex-grow mb-6 leading-relaxed line-clamp-3">
                    <?= esc($doc->deskripsi) ?>
                </p>

                <a href="<?= base_url('uploads/dokumen/' . $doc->nama_file) ?>" download class="mt-auto w-full bg-white hover:bg-navy-50 text-navy-800 border border-gray-200 hover:border-navy-800 font-bold py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 active:scale-95 active:bg-navy-100 shadow-sm">
                    <i class="ph ph-download-simple text-lg font-bold"></i> 
                    <span>Unduh Berkas</span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>