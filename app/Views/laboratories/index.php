<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-navy-900">Manajemen Laboratorium</h2>
        <p class="text-gray-500 text-sm md:text-base">Kelola daftar ruang lab, kapasitas, dan status operasional.</p>
    </div>
    <a href="<?= base_url('laboratories/add') ?>" class="w-full md:w-auto bg-navy-800 hover:bg-navy-900 text-white px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-sm active:scale-95">
        <i class="ph ph-plus-circle text-lg"></i> Tambah Lab Baru
    </a>
</div>

<div class="hidden md:block bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table id="dataTable" class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-navy-900 border-b border-gray-100">
                <th class="p-4 font-semibold w-12">No</th>
                <th class="p-4 font-semibold">Nama Laboratorium</th>
                <th class="p-4 font-semibold">Unit Pendidikan</th>
                <th class="p-4 font-semibold text-center">Kapasitas</th>
                <th class="p-4 font-semibold text-center">Status</th>
                <th class="p-4 font-semibold text-center w-32" data-sortable="false">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($labs as $l) : ?>
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="p-4 text-gray-500"><?= $no++ ?></td>
                <td class="p-4 font-bold text-gray-800"><?= esc($l->nama_lab) ?></td>
                <td class="p-4 text-gray-600">
                    <?= $l->nama_unit ? esc($l->nama_unit) : '<span class="bg-gray-200 text-gray-600 px-2 py-0.5 rounded text-xs font-bold">GLOBAL</span>' ?>
                </td>
                <td class="p-4 text-gray-600 text-center font-semibold"><?= esc($l->kapasitas) ?> Kursi</td>
                <td class="p-4 text-center">
                    <?php if ($l->status == 'aktif'): ?>
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-green-200">
                            <i class="ph ph-check-circle align-middle"></i> Aktif
                        </span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-red-200">
                            <i class="ph ph-warning align-middle"></i> Maintenance
                        </span>
                    <?php endif; ?>
                </td>
                <td class="p-4">
                    <div class="flex justify-center items-center gap-2">
                        <a href="<?= base_url('laboratories/edit/' . $l->id) ?>" class="bg-gray-100 text-gray-700 p-2 rounded-lg border border-gray-200 hover:bg-navy-800 hover:text-white transition" title="Edit">
                            <i class="ph ph-pencil-simple text-lg"></i>
                        </a>
                        <button onclick="confirmDelete(<?= $l->id ?>)" class="bg-red-50 text-red-600 p-2 rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition" title="Hapus">
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
    <div id="mobileCardContainer" class="space-y-4">
        <?php foreach ($labs as $l) : ?>
        <div class="mobile-card-item bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative">
            <div class="absolute top-5 right-5">
                <?php if ($l->status == 'aktif'): ?>
                    <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-green-200">Aktif</span>
                <?php else: ?>
                    <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-red-200">MT</span>
                <?php endif; ?>
            </div>

            <div class="flex items-start gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-navy-800 flex items-center justify-center shrink-0">
                    <i class="ph ph-flask text-2xl"></i>
                </div>
                <div class="pr-12"> <h4 class="font-bold text-navy-900 text-lg leading-tight mb-1"><?= esc($l->nama_lab) ?></h4>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <?= $l->nama_unit ? esc($l->nama_unit) : 'Global (Semua Unit)' ?>
                    </p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-3 mb-4 flex items-center gap-3">
                <i class="ph ph-users-three text-xl text-gray-400"></i>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Kapasitas Ruangan</p>
                    <p class="text-sm font-bold text-navy-900"><?= esc($l->kapasitas) ?> Kursi</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="<?= base_url('laboratories/edit/' . $l->id) ?>" class="flex-1 bg-white border border-gray-200 text-navy-800 py-2.5 rounded-xl text-sm font-bold text-center hover:bg-gray-50 transition shadow-sm">
                    Edit
                </a>
                <button onclick="confirmDelete(<?= $l->id ?>)" class="flex-1 bg-red-50 text-red-600 border border-red-100 py-2.5 rounded-xl text-sm font-bold hover:bg-red-100 transition shadow-sm">
                    Hapus
                </button>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if(empty($labs)): ?>
            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-gray-200">
                <i class="ph ph-flask text-4xl text-gray-300 mb-2"></i>
                <p class="text-gray-500">Belum ada data laboratorium.</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="mobilePagination" class="p-4 flex justify-center items-center gap-4 mt-4 hidden">
        <button id="prevBtn" class="bg-white border border-gray-200 text-navy-800 w-10 h-10 rounded-xl flex items-center justify-center transition disabled:opacity-50 disabled:bg-gray-50 shadow-sm">
            <i class="ph ph-caret-left text-lg font-bold"></i>
        </button>
        <span id="pageInfo" class="text-sm font-bold text-gray-600">Halaman 1</span>
        <button id="nextBtn" class="bg-white border border-gray-200 text-navy-800 w-10 h-10 rounded-xl flex items-center justify-center transition disabled:opacity-50 disabled:bg-gray-50 shadow-sm">
            <i class="ph ph-caret-right text-lg font-bold"></i>
        </button>
    </div>
</div>

<style>
    /* Desktop DataTables Styling Customization */
    .datatable-input { border: 2px solid #e2e8f0 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; width: 300px !important; }
    .datatable-input:focus { border-color: #1e3a8a !important; outline: none !important; }
    .datatable-pagination .active a { background-color: #1e3a8a !important; border-color: #1e3a8a !important; }
</style>

<?php if (session()->getFlashdata('success')) : ?>
    <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', confirmButtonColor: '#1e3a8a', timer: 3000 });</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Desktop DataTables (Hanya init jika layar besar)
    if(window.innerWidth > 768) {
        new simpleDatatables.DataTable("#dataTable", {
            searchable: true, perPage: 10,
            labels: { placeholder: "Cari Laboratorium...", perPage: "entri", noRows: "Data tidak ditemukan", info: "Menampilkan {start} - {end} dari {rows}" }
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
                // Tambahkan animasi fade-in sederhana
                card.style.opacity = '0';
                setTimeout(() => card.style.opacity = '1', 50);
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
        
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) { currentPage--; showPage(currentPage); window.scrollTo({top:0, behavior:'smooth'}); }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) { currentPage++; showPage(currentPage); window.scrollTo({top:0, behavior:'smooth'}); }
        });

        showPage(1);
    } else {
        cards.forEach(card => card.style.display = 'block');
    }
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Lab?',
        text: "Data akan dihapus permanen! Riwayat peminjaman terkait mungkin akan kehilangan referensi nama lab.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = '<?= base_url('laboratories/delete/') ?>' + id;
    })
}
</script>
<?= $this->endSection() ?>