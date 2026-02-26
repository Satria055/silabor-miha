<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-navy-900">Kelola Portal Berita</h2>
        <p class="text-gray-500 text-sm md:text-base">Manajemen artikel, pengumuman, dan dokumentasi kegiatan lab.</p>
    </div>
    <a href="<?= base_url('berita/add') ?>" class="w-full md:w-auto bg-navy-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-navy-900 transition flex items-center justify-center gap-2 shadow-sm active:scale-95">
        <i class="ph ph-newspaper text-lg"></i> Tulis Berita Baru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">

    <div id="bulkActionContainer" class="hidden absolute top-0 left-0 w-full h-16 bg-blue-50 border-b border-blue-100 z-20 flex justify-between items-center px-4 md:px-6 transition-all">
        <span class="text-sm font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-check-circle text-xl text-blue-600"></i>
            <span id="selectedCount">0</span> Artikel Terpilih
        </span>
        <button onclick="confirmBulkDelete()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-bold transition flex items-center gap-2 shadow-sm text-sm">
            <i class="ph ph-trash text-lg"></i> <span class="hidden md:inline">Hapus Massal</span>
        </button>
    </div>

    <div class="hidden md:block">
        <table id="dataTable" class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-navy-900 border-b border-gray-100">
                    <th class="p-4 w-12 text-center" data-sortable="false">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-navy-800 border-gray-300 rounded cursor-pointer focus:ring-navy-800">
                    </th>
                    <th class="p-4 font-semibold w-12">No</th>
                    <th class="p-4 font-semibold w-24" data-sortable="false">Thumbnail</th>
                    <th class="p-4 font-semibold">Judul Berita</th>
                    <th class="p-4 font-semibold">Penulis</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <th class="p-4 font-semibold text-center w-32" data-sortable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($berita as $b) : ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                    <td class="p-4 text-center">
                        <input type="checkbox" class="row-checkbox w-4 h-4 text-navy-800 border-gray-300 rounded cursor-pointer focus:ring-navy-800" value="<?= $b->id ?>">
                    </td>
                    <td class="p-4 text-gray-500"><?= $no++ ?></td>
                    <td class="p-4">
                        <?php if($b->thumbnail): ?>
                            <img src="<?= base_url('uploads/berita/' . $b->thumbnail) ?>" alt="Thumb" class="w-16 h-12 object-cover rounded-lg shadow-sm border border-gray-200">
                        <?php else: ?>
                            <div class="w-16 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 border border-gray-200"><i class="ph ph-image text-xl"></i></div>
                        <?php endif; ?>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-gray-800 mb-1 leading-tight"><?= esc($b->judul) ?></div>
                        <div class="flex items-center gap-3 text-xs text-gray-400 font-mono mt-1">
                            <span><i class="ph ph-calendar-blank"></i> <?= date('d/m/Y', strtotime($b->created_at)) ?></span>
                            <span class="text-navy-600 font-bold"><i class="ph ph-eye"></i> <?= $b->views ?> views</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm font-semibold text-navy-800"><?= esc($b->penulis) ?></td>
                    <td class="p-4 text-center">
                        <?php if ($b->status == 'publish'): ?>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-green-200 inline-flex items-center gap-1">
                                <i class="ph ph-globe-hemisphere-west"></i> Publik
                            </span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-gray-200 inline-flex items-center gap-1">
                                <i class="ph ph-archive-box"></i> Draft
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center items-center gap-1">
                            <a href="<?= base_url('berita/edit/' . $b->id) ?>" class="bg-gray-100 text-gray-700 p-2 rounded-lg border border-gray-200 hover:bg-navy-800 hover:text-white transition shadow-sm" title="Edit Artikel">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <button onclick="confirmSingleDelete(<?= $b->id ?>)" class="bg-red-50 text-red-600 p-2 rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus Artikel">
                                <i class="ph ph-trash text-lg"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="md:hidden flex flex-col">
        <div id="mobileCardContainer" class="p-4 space-y-4 bg-gray-50">
            <?php foreach ($berita as $b) : ?>
            <div class="mobile-card-item bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative">
                <div class="absolute top-5 right-5">
                    <?php if ($b->status == 'publish'): ?>
                        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-green-200">Publik</span>
                    <?php else: ?>
                        <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-gray-200">Draft</span>
                    <?php endif; ?>
                </div>

                <div class="flex items-start gap-4 mb-4 pr-14">
                    <div class="shrink-0">
                        <?php if($b->thumbnail): ?>
                            <img src="<?= base_url('uploads/berita/' . $b->thumbnail) ?>" alt="Thumb" class="w-16 h-16 object-cover rounded-xl shadow-sm border border-gray-200">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200"><i class="ph ph-image text-2xl"></i></div>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-navy-900 text-sm leading-tight mb-1 line-clamp-2"><?= esc($b->judul) ?></h4>
                        <div class="flex items-center gap-2 text-xs text-gray-500 font-mono">
                            <span><i class="ph ph-user"></i> <?= esc($b->penulis) ?></span>
                            <span>•</span>
                            <span><i class="ph ph-eye"></i> <?= $b->views ?></span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-50">
                    <a href="<?= base_url('berita/edit/' . $b->id) ?>" class="flex-1 bg-white border border-gray-200 text-navy-800 py-2 rounded-xl text-xs font-bold text-center hover:bg-gray-50 shadow-sm">Edit</a>
                    <button onclick="confirmSingleDelete(<?= $b->id ?>)" class="flex-1 bg-red-50 text-red-600 border border-red-100 py-2 rounded-xl text-xs font-bold hover:bg-red-100 shadow-sm">Hapus</button>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($berita)): ?>
                <div class="text-center py-10 text-gray-400">
                    <i class="ph ph-newspaper text-4xl mb-2"></i>
                    <p class="text-sm">Tidak ada artikel berita.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="mobilePagination" class="p-4 flex justify-center items-center gap-4 bg-white border-t border-gray-100 mt-auto hidden">
            <button id="prevBtn" class="bg-white border border-gray-200 text-navy-800 w-10 h-10 rounded-xl flex items-center justify-center transition disabled:opacity-50 disabled:bg-gray-50 shadow-sm">
                <i class="ph ph-caret-left text-lg font-bold"></i>
            </button>
            <span id="pageInfo" class="text-sm font-bold text-gray-600">Halaman 1</span>
            <button id="nextBtn" class="bg-white border border-gray-200 text-navy-800 w-10 h-10 rounded-xl flex items-center justify-center transition disabled:opacity-50 disabled:bg-gray-50 shadow-sm">
                <i class="ph ph-caret-right text-lg font-bold"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Desktop DataTables Styling */
    .datatable-input { border: 2px solid #e2e8f0 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; width: 300px !important; }
    .datatable-input:focus { border-color: #1e3a8a !important; outline: none !important; }
</style>

<?php if (session()->getFlashdata('success')) : ?>
    <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, confirmButtonColor: '#1e3a8a' });</script>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <script>Swal.fire({ icon: 'error', title: 'Perhatian', text: '<?= session()->getFlashdata('error') ?>', confirmButtonColor: '#d33' });</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Desktop DataTables
    if(window.innerWidth > 768) {
        new simpleDatatables.DataTable("#dataTable", {
            searchable: true, perPage: 10,
            labels: { placeholder: "Cari Judul...", perPage: "entri", noRows: "Tidak ada artikel.", info: "Menampilkan {start} - {end} dari {rows}" }
        });
    }

    // 2. Mobile Pagination Logic
    const cards = document.querySelectorAll('.mobile-card-item');
    const itemsPerPage = 5; 
    let currentPage = 1;
    const totalPages = Math.ceil(cards.length / itemsPerPage);
    
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageInfo = document.getElementById('pageInfo');
    const paginationContainer = document.getElementById('mobilePagination');

    function showPage(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        
        cards.forEach((card, index) => {
            if (index >= start && index < end) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        pageInfo.innerText = `Halaman ${page} / ${totalPages}`;
        prevBtn.disabled = page === 1;
        nextBtn.disabled = page === totalPages;
    }

    if (cards.length > itemsPerPage) {
        paginationContainer.classList.remove('hidden');
        prevBtn.addEventListener('click', () => { if (currentPage > 1) { currentPage--; showPage(currentPage); window.scrollTo({top:0, behavior:'smooth'}); } });
        nextBtn.addEventListener('click', () => { if (currentPage < totalPages) { currentPage++; showPage(currentPage); window.scrollTo({top:0, behavior:'smooth'}); } });
        showPage(1);
    } else {
        cards.forEach(card => card.style.display = 'block');
    }

    // 3. Bulk Action Logic
    const bulkContainer = document.getElementById('bulkActionContainer');
    const selectedCountSpan = document.getElementById('selectedCount');
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'selectAll') {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
        }
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length > 0) { bulkContainer.classList.remove('hidden'); selectedCountSpan.innerText = checkedBoxes.length; } 
        else { bulkContainer.classList.add('hidden'); }
    });
});

function confirmSingleDelete(id) {
    Swal.fire({ title: 'Hapus Artikel?', text: "Artikel dan gambarnya akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus' })
    .then((result) => { if (result.isConfirmed) window.location.href = '<?= base_url('berita/delete/') ?>' + id; });
}

function confirmBulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    Swal.fire({ title: 'Hapus Massal?', text: `Yakin menghapus ${ids.length} artikel terpilih?`, icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus Semua' })
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form'); form.method = 'POST'; form.action = '<?= base_url('berita/bulk-delete') ?>';
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = '<?= csrf_hash() ?>'; form.appendChild(csrf);
            ids.forEach(id => { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'ids[]'; input.value = id; form.appendChild(input); });
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?>