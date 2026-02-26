<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-navy-900">Inventaris Laboratorium</h2>
        <p class="text-gray-500 text-sm md:text-base">Manajemen aset, fasilitas, dan kondisi barang.</p>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-3 w-full xl:w-auto">
        <form action="<?= base_url('inventory') ?>" method="GET" class="flex flex-col md:flex-row gap-2 w-full">
            <select name="lab_id" class="px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-gray-700 cursor-pointer text-sm w-full md:w-auto">
                <option value="">-- Semua Lab --</option>
                <?php foreach($labs as $l): ?>
                    <option value="<?= $l->id ?>" <?= ($lab_id == $l->id) ? 'selected' : '' ?>><?= esc($l->nama_lab) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="kategori" class="px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-gray-700 cursor-pointer text-sm w-full md:w-auto">
                <?php $kategoriList = ['Elektronik & Komputer', 'Perabot / Furniture', 'Alat Peraga Praktik', 'Bahan Habis Pakai', 'Lainnya']; ?>
                <option value="">-- Semua Kategori --</option>
                <?php foreach($kategoriList as $kat): ?>
                    <option value="<?= $kat ?>" <?= ($kategori == $kat) ? 'selected' : '' ?>><?= $kat ?></option>
                <?php endforeach; ?>
            </select>

            <select name="kondisi" class="px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-gray-700 cursor-pointer text-sm w-full md:w-auto">
                <option value="">-- Semua Kondisi --</option>
                <option value="rusak" <?= ($kondisi == 'rusak') ? 'selected' : '' ?>>Ada Kerusakan</option>
                <option value="baik" <?= ($kondisi == 'baik') ? 'selected' : '' ?>>100% Kondisi Baik</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 md:flex-none bg-gray-100 hover:bg-gray-200 text-navy-900 border border-gray-300 px-4 py-2.5 rounded-xl font-bold transition flex items-center justify-center gap-1 shadow-sm text-sm">
                    <i class="ph ph-funnel text-lg"></i> <span class="md:hidden lg:inline">Filter</span>
                </button>
                <?php if($lab_id || $kategori || $kondisi): ?>
                    <a href="<?= base_url('inventory') ?>" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-3 py-2.5 rounded-xl font-bold transition flex items-center justify-center shadow-sm" title="Reset Filter">
                        <i class="ph ph-x text-lg font-bold"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <div class="hidden lg:block border-l-2 border-gray-200 mx-1"></div>

        <a href="<?= base_url('inventory/add') ?>" class="w-full md:w-auto bg-navy-800 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-navy-900 transition flex items-center justify-center gap-2 shadow-sm whitespace-nowrap active:scale-95">
            <i class="ph ph-plus-circle text-lg"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
    
    <div id="bulkActionContainer" class="hidden absolute top-0 left-0 w-full h-16 bg-blue-50 border-b border-blue-100 z-20 flex justify-between items-center px-4 md:px-6 transition-all">
        <span class="text-sm font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-check-circle text-xl text-blue-600"></i>
            <span id="selectedCount">0</span> Aset Terpilih
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
                    <th class="p-4 font-semibold">Kode & Nama Barang</th>
                    <th class="p-4 font-semibold">Lokasi Lab</th>
                    <th class="p-4 font-semibold text-center">Total</th>
                    <th class="p-4 font-semibold text-center" data-sortable="false">Kondisi</th>
                    <th class="p-4 font-semibold text-center w-32" data-sortable="false">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($inventories as $inv) : ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                    <td class="p-4 text-center">
                        <input type="checkbox" class="row-checkbox w-4 h-4 text-navy-800 border-gray-300 rounded cursor-pointer focus:ring-navy-800" value="<?= $inv->id ?>">
                    </td>
                    <td class="p-4 text-gray-500"><?= $no++ ?></td>
                    <td class="p-4">
                        <div class="text-xs text-gray-400 font-mono mb-1"><?= esc($inv->kode_barang) ?></div>
                        <div class="font-bold text-gray-800"><?= esc($inv->nama_barang) ?></div>
                        <div class="text-[10px] text-navy-600 mt-1 bg-blue-50 inline-block px-2 py-0.5 rounded font-bold uppercase tracking-wide"><?= esc($inv->kategori) ?></div>
                    </td>
                    <td class="p-4 font-semibold text-gray-700"><?= esc($inv->nama_lab) ?></td>
                    <td class="p-4 text-center font-extrabold text-lg text-navy-900"><?= $inv->jumlah_total ?></td>
                    <td class="p-4">
                        <div class="flex flex-col gap-1 text-xs font-bold w-24 mx-auto">
                            <span class="text-green-700 bg-green-100 px-2 py-1 rounded flex justify-between items-center">
                                <span>Baik</span> <span class="bg-white px-1.5 rounded-sm text-[10px] shadow-sm"><?= $inv->kondisi_baik ?></span>
                            </span>
                            <?php if($inv->kondisi_rusak > 0): ?>
                            <span class="text-red-700 bg-red-100 px-2 py-1 rounded flex justify-between items-center">
                                <span>Rusak</span> <span class="bg-white px-1.5 rounded-sm text-[10px] shadow-sm"><?= $inv->kondisi_rusak ?></span>
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center items-center gap-2">
                            <a href="<?= base_url('inventory/edit/' . $inv->id) ?>" class="bg-gray-100 text-gray-700 p-2 rounded-lg border border-gray-200 hover:bg-navy-800 hover:text-white transition" title="Edit">
                                <i class="ph ph-pencil-simple text-lg"></i>
                            </a>
                            <button onclick="confirmSingleDelete(<?= $inv->id ?>)" class="bg-red-50 text-red-600 p-2 rounded-lg border border-red-100 hover:bg-red-600 hover:text-white transition" title="Hapus">
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
            <?php foreach ($inventories as $inv) : ?>
            <div class="mobile-card-item bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative">
                <div class="absolute top-5 right-5">
                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold border border-blue-100">
                        <?= esc($inv->kategori) ?>
                    </span>
                </div>

                <div class="flex items-start gap-3 mb-4 pr-16">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center shrink-0 font-mono text-xs font-bold border border-gray-200">
                        <?= substr($inv->kode_barang, -3) ?>
                    </div>
                    <div>
                        <h4 class="font-bold text-navy-900 text-sm leading-tight mb-1"><?= esc($inv->nama_barang) ?></h4>
                        <p class="text-xs text-gray-500 font-mono"><?= esc($inv->kode_barang) ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Total Stok</p>
                        <p class="text-lg font-extrabold text-navy-900"><?= $inv->jumlah_total ?> <span class="text-xs font-normal text-gray-500">Unit</span></p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-xl border border-gray-100">
                        <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Lokasi</p>
                        <p class="text-xs font-bold text-navy-800 line-clamp-2 leading-tight"><?= esc($inv->nama_lab) ?></p>
                    </div>
                </div>

                <div class="flex gap-2 text-xs font-bold mb-4">
                    <div class="flex-1 bg-green-50 text-green-700 py-1.5 px-3 rounded-lg flex justify-between items-center border border-green-100">
                        <span>Baik</span> <span><?= $inv->kondisi_baik ?></span>
                    </div>
                    <?php if($inv->kondisi_rusak > 0): ?>
                    <div class="flex-1 bg-red-50 text-red-700 py-1.5 px-3 rounded-lg flex justify-between items-center border border-red-100">
                        <span>Rusak</span> <span><?= $inv->kondisi_rusak ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-50">
                    <a href="<?= base_url('inventory/edit/' . $inv->id) ?>" class="flex-1 bg-white border border-gray-200 text-navy-800 py-2 rounded-xl text-xs font-bold text-center hover:bg-gray-50">Edit</a>
                    <button onclick="confirmSingleDelete(<?= $inv->id ?>)" class="flex-1 bg-red-50 text-red-600 border border-red-100 py-2 rounded-xl text-xs font-bold hover:bg-red-100">Hapus</button>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($inventories)): ?>
                <div class="text-center py-10 text-gray-400">
                    <i class="ph ph-cube text-4xl mb-2"></i>
                    <p class="text-sm">Tidak ada data inventaris.</p>
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
    /* Styling DataTables Desktop */
    .datatable-input { border: 2px solid #e2e8f0 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; width: 300px !important; }
    .datatable-input:focus { border-color: #1e3a8a !important; outline: none !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Desktop DataTables
    if(window.innerWidth > 768) {
        const dataTable = new simpleDatatables.DataTable("#dataTable", {
            searchable: true, perPage: 10,
            labels: { placeholder: "Cari Barang...", perPage: "entri", noRows: "Tidak ada data", info: "Menampilkan {start} - {end} dari {rows}" }
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
    Swal.fire({ title: 'Hapus Barang?', text: "Data aset ini akan dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Hapus' })
    .then((result) => { if (result.isConfirmed) window.location.href = '<?= base_url('inventory/delete/') ?>' + id; });
}

function confirmBulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    Swal.fire({ title: 'Hapus Massal?', text: `Yakin menghapus ${ids.length} aset terpilih?`, icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus Semua' })
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form'); form.method = 'POST'; form.action = '<?= base_url('inventory/bulk-delete') ?>';
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = '<?= csrf_hash() ?>'; form.appendChild(csrf);
            ids.forEach(id => { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'ids[]'; input.value = id; form.appendChild(input); });
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?>