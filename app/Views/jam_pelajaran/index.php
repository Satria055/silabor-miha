<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-navy-900">Manajemen Jam KBM</h2>
        <p class="text-gray-500 text-sm md:text-base">Atur sesi jam pelajaran untuk masing-masing unit pendidikan.</p>
    </div>
    
    <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
        <form action="<?= base_url('jam-pelajaran') ?>" method="GET" class="w-full md:w-auto">
            <div class="flex gap-2">
                <select name="unit_id" class="w-full md:w-64 px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-gray-700 cursor-pointer text-sm">
                    <option value="">-- Semua Unit Pendidikan --</option>
                    <?php foreach($units as $u): ?>
                        <option value="<?= $u->id ?>" <?= ($unit_id == $u->id) ? 'selected' : '' ?>><?= esc($u->nama_unit) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-navy-900 border border-gray-300 px-4 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
                    <i class="ph ph-funnel text-lg"></i>
                </button>
            </div>
        </form>

        <a href="<?= base_url('jam-pelajaran/add') ?>" class="w-full md:w-auto bg-navy-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-navy-900 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap active:scale-95">
            <i class="ph ph-plus-circle text-lg"></i> Tambah Jam
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
    
    <div id="bulkActionContainer" class="hidden absolute top-0 left-0 w-full h-16 bg-blue-50 border-b border-blue-100 z-20 flex justify-between items-center px-4 md:px-6 transition-all">
        <span class="text-sm font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-check-circle text-xl text-blue-600"></i>
            <span id="selectedCount">0</span> Sesi Terpilih
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
                    <th class="p-4 font-semibold">Unit Pendidikan</th>
                    <th class="p-4 font-semibold">Nama Sesi / Jam Ke-</th>
                    <th class="p-4 font-semibold text-center">Waktu Mulai</th>
                    <th class="p-4 font-semibold text-center">Waktu Selesai</th>
                    <th class="p-4 font-semibold text-center w-32" data-sortable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($jams as $jp) : ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                    <td class="p-4 text-center">
                        <input type="checkbox" class="row-checkbox w-4 h-4 text-navy-800 border-gray-300 rounded cursor-pointer focus:ring-navy-800" value="<?= $jp->id ?>">
                    </td>
                    <td class="p-4 text-gray-500"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-gray-800">
                        <?= $jp->nama_unit ? esc($jp->nama_unit) : '<span class="text-xs text-gray-400 italic">Global (Tanpa Unit)</span>' ?>
                    </td>
                    <td class="p-4 font-semibold text-navy-800"><?= esc($jp->nama_sesi) ?></td>
                    <td class="p-4 text-center"><span class="bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-md text-sm shadow-sm font-mono"><?= substr($jp->waktu_mulai, 0, 5) ?></span></td>
                    <td class="p-4 text-center"><span class="bg-blue-50 text-blue-700 font-bold px-3 py-1 rounded-md text-sm shadow-sm font-mono"><?= substr($jp->waktu_selesai, 0, 5) ?></span></td>
                    <td class="p-4">
                        <div class="flex justify-center items-center gap-2">
                            <a href="<?= base_url('jam-pelajaran/edit/' . $jp->id) ?>" class="bg-gray-100 text-gray-700 p-2 rounded-lg border border-gray-200 hover:bg-navy-800 hover:text-white transition shadow-sm" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <button onclick="confirmSingleDelete(<?= $jp->id ?>)" class="bg-red-50 text-red-600 p-2 rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition shadow-sm" title="Hapus">
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
            <?php foreach ($jams as $jp) : ?>
            <div class="mobile-card-item bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative">
                <div class="absolute top-5 right-5">
                    <span class="bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-gray-200">
                        <?= $jp->nama_unit ? esc($jp->nama_unit) : 'Global' ?>
                    </span>
                </div>

                <div class="flex items-start gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-navy-800 flex items-center justify-center shrink-0 border border-blue-100">
                        <i class="ph ph-clock text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-navy-900 text-base leading-tight mb-1"><?= esc($jp->nama_sesi) ?></h4>
                        <p class="text-xs text-gray-400 font-semibold">Sesi Pelajaran</p>
                    </div>
                </div>

                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-xl border border-gray-100 mb-4">
                    <div class="text-center w-1/2 border-r border-gray-200 pr-2">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Mulai</p>
                        <p class="text-lg font-mono font-bold text-blue-700"><?= substr($jp->waktu_mulai, 0, 5) ?></p>
                    </div>
                    <div class="text-center w-1/2 pl-2">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Selesai</p>
                        <p class="text-lg font-mono font-bold text-blue-700"><?= substr($jp->waktu_selesai, 0, 5) ?></p>
                    </div>
                </div>

                <div class="flex gap-2 pt-2 border-t border-gray-50">
                    <a href="<?= base_url('jam-pelajaran/edit/' . $jp->id) ?>" class="flex-1 bg-white border border-gray-200 text-navy-800 py-2 rounded-xl text-xs font-bold text-center hover:bg-gray-50 shadow-sm">Edit</a>
                    <button onclick="confirmSingleDelete(<?= $jp->id ?>)" class="flex-1 bg-red-50 text-red-600 border border-red-100 py-2 rounded-xl text-xs font-bold hover:bg-red-100 shadow-sm">Hapus</button>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($jams)): ?>
                <div class="text-center py-10 text-gray-400">
                    <i class="ph ph-clock-slash text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada data jam pelajaran.</p>
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

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Desktop DataTables
    if(window.innerWidth > 768) {
        new simpleDatatables.DataTable("#dataTable", {
            searchable: true, perPage: 10,
            labels: { placeholder: "Cari Sesi...", perPage: "entri", noRows: "Tidak ada data", info: "Menampilkan {start} - {end} dari {rows}" }
        });
    }

    // 2. Mobile Pagination
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
    Swal.fire({ title: 'Hapus Jam?', text: "Sesi ini akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus' })
    .then((result) => { if (result.isConfirmed) window.location.href = '<?= base_url('jam-pelajaran/delete/') ?>' + id; });
}

function confirmBulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    Swal.fire({ title: 'Hapus Massal?', text: `Yakin menghapus ${ids.length} sesi terpilih?`, icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus Semua' })
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form'); form.method = 'POST'; form.action = '<?= base_url('jam-pelajaran/bulk-delete') ?>';
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = '<?= csrf_hash() ?>'; form.appendChild(csrf);
            ids.forEach(id => { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'ids[]'; input.value = id; form.appendChild(input); });
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?>