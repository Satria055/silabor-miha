<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>

<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6 mb-8">
    <div>
        <h2 class="text-2xl md:text-3xl font-bold text-navy-900">Riwayat Peminjaman</h2>
        <p class="text-gray-500 text-sm md:text-base">Pantau status pengajuan dan riwayat pemakaian lab.</p>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-4 w-full xl:w-auto">
        <form action="<?= base_url('bookings') ?>" method="GET" class="flex flex-col md:flex-row gap-3 w-full">
            <select name="lab_id" class="px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-gray-700 cursor-pointer transition w-full md:w-48 text-sm">
                <option value="">-- Semua Lab --</option>
                <?php foreach($labs as $l): ?>
                    <option value="<?= $l->id ?>" <?= ($selected_lab == $l->id) ? 'selected' : '' ?>><?= esc($l->nama_lab) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="flex flex-col md:flex-row items-center gap-2 w-full md:w-auto">
                <input type="date" name="start_date" value="<?= esc($start_date ?? '') ?>" class="w-full md:w-36 px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 text-sm font-semibold text-gray-700 shadow-sm">
                <span class="hidden md:inline text-gray-400 font-bold">-</span>
                <input type="date" name="end_date" value="<?= esc($end_date ?? '') ?>" class="w-full md:w-36 px-3 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-navy-800 text-sm font-semibold text-gray-700 shadow-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 md:flex-none bg-gray-100 hover:bg-gray-200 text-navy-900 border border-gray-300 px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-sm text-sm">
                    <i class="ph ph-funnel text-lg"></i> <span class="md:hidden lg:inline">Filter</span>
                </button>
                <?php if($selected_lab || $start_date || $end_date): ?>
                    <a href="<?= base_url('bookings') ?>" class="bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 px-4 py-2.5 rounded-xl font-bold transition flex items-center justify-center shadow-sm" title="Reset Filter">
                        <i class="ph ph-x text-lg font-bold"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <div class="hidden lg:block border-l-2 border-gray-200 mx-2 h-10 self-center"></div>

        <div class="flex gap-3">
            <a href="<?= base_url('bookings/kbm') ?>" class="flex-1 md:flex-none bg-white border border-navy-800 text-navy-800 hover:bg-blue-50 px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-sm text-sm whitespace-nowrap">
                <i class="ph ph-chalkboard-teacher text-lg"></i> KBM
            </a>
            <a href="<?= base_url('bookings/khusus') ?>" class="flex-1 md:flex-none bg-navy-800 hover:bg-navy-900 text-white border border-navy-800 px-5 py-2.5 rounded-xl font-bold transition flex items-center justify-center gap-2 shadow-sm text-sm whitespace-nowrap">
                <i class="ph ph-calendar-star text-lg"></i> Khusus
            </a>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
    
    <div id="bulkActionContainer" class="hidden absolute top-0 left-0 w-full h-16 bg-blue-50 border-b border-blue-100 z-20 flex justify-between items-center px-4 md:px-6 transition-all">
        <span class="text-sm font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-check-circle text-xl text-blue-600"></i>
            <span id="selectedCount">0</span> Data Terpilih
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
                    <th class="p-4 font-semibold">Laboratorium</th>
                    <th class="p-4 font-semibold">Jenis</th>
                    <th class="p-4 font-semibold">Waktu Pemakaian</th>
                    <th class="p-4 font-semibold">Peminjam</th>
                    <th class="p-4 font-semibold text-center">Status</th>
                    <?php if(in_array(session()->get('role'), ['super_admin', 'admin_lab'])): ?>
                        <th class="p-4 font-semibold text-center w-40" data-sortable="false">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($bookings as $b) : ?>
                <tr class="border-b border-gray-50 hover:bg-gray-50 transition group">
                    <td class="p-4 text-center">
                        <input type="checkbox" class="row-checkbox w-4 h-4 text-navy-800 border-gray-300 rounded cursor-pointer focus:ring-navy-800" value="<?= $b->id ?>">
                    </td>
                    <td class="p-4 text-gray-500"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-gray-800"><?= esc($b->nama_lab) ?></td>
                    <td class="p-4">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold <?= $b->jenis_peminjaman == 'KBM' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' ?>">
                            <?= esc($b->jenis_peminjaman) ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        <div class="font-bold text-navy-900 leading-tight">
                            <?php if($b->jenis_peminjaman == 'Khusus' && $b->tanggal_mulai != $b->tanggal_selesai): ?>
                                <?= date('d/m/y', strtotime($b->tanggal_mulai)) ?> - <?= date('d/m/y', strtotime($b->tanggal_selesai)) ?>
                            <?php else: ?>
                                <?= date('d M Y', strtotime($b->tanggal_mulai)) ?>
                            <?php endif; ?>
                        </div>
                        <div class="text-xs mt-1 text-gray-500 font-medium">
                            <?= substr($b->waktu_mulai, 0, 5) ?> - <?= substr($b->waktu_selesai, 0, 5) ?> WIB
                        </div>
                    </td>
                    <td class="p-4 text-sm text-gray-600">
                        <div class="font-bold text-gray-800"><?= esc($b->peminjam) ?></div>
                        <div class="text-xs italic text-gray-500"><?= esc($b->kelas ?? $b->penanggung_jawab) ?></div>
                    </td>
                    <td class="p-4 text-center">
                        <?php if ($b->status == 'pending'): ?>
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-yellow-200">Pending</span>
                        <?php elseif ($b->status == 'disetujui'): ?>
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-green-200">Disetujui</span>
                        <?php else: ?>
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] uppercase font-bold border border-red-200">Ditolak</span>
                        <?php endif; ?>
                    </td>
                    <?php if(in_array(session()->get('role'), ['super_admin', 'admin_lab'])): ?>
                    <td class="p-4">
                        <div class="flex justify-center items-center gap-2">
                            <?php if($b->status == 'pending'): ?>
                                <button onclick="confirmApprove(<?= $b->id ?>)" class="bg-green-100 hover:bg-green-600 text-green-700 hover:text-white p-2 rounded-lg transition" title="Setujui"><i class="ph ph-check font-bold"></i></button>
                                <button onclick="confirmReject(<?= $b->id ?>)" class="bg-red-100 hover:bg-red-600 text-red-700 hover:text-white p-2 rounded-lg transition" title="Tolak"><i class="ph ph-x font-bold"></i></button>
                            <?php endif; ?>
                            <?php if($b->jenis_peminjaman == 'Khusus' && $b->file_surat): ?>
                                <a href="<?= base_url('uploads/surat_peminjaman/'.$b->file_surat) ?>" target="_blank" class="bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-600 hover:text-white transition" title="Lihat Surat"><i class="ph ph-file-pdf text-lg"></i></a>
                            <?php endif; ?>
                            <a href="<?= base_url('bookings/edit/'.$b->id) ?>" class="bg-gray-100 text-gray-600 p-2 rounded-lg hover:bg-navy-800 hover:text-white transition" title="Edit"><i class="ph ph-pencil-simple text-lg"></i></a>
                        </div>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="md:hidden bg-gray-50 flex flex-col h-full">
        <div id="mobileCardContainer" class="p-4 space-y-4">
            <?php foreach ($bookings as $b) : ?>
            <div class="mobile-card-item bg-white p-5 rounded-2xl shadow-sm border border-gray-100 relative">
                <div class="absolute top-5 right-5">
                    <?php if ($b->status == 'pending'): ?>
                        <span class="bg-yellow-100 text-yellow-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold">Pending</span>
                    <?php elseif ($b->status == 'disetujui'): ?>
                        <span class="bg-green-100 text-green-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold">Disetujui</span>
                    <?php else: ?>
                        <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-lg text-[10px] uppercase font-bold">Ditolak</span>
                    <?php endif; ?>
                </div>

                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-navy-800 flex items-center justify-center shrink-0">
                        <i class="ph ph-chalkboard-teacher text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-navy-900 text-sm"><?= esc($b->nama_lab) ?></h4>
                        <p class="text-xs text-gray-500 font-semibold mt-0.5">
                            <?= $b->jenis_peminjaman == 'KBM' ? 'KBM - Reguler' : 'Kegiatan Khusus' ?>
                        </p>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="ph ph-calendar-blank text-lg text-gray-400"></i>
                        <span class="font-medium">
                            <?= date('d M Y', strtotime($b->tanggal_mulai)) ?>
                            <span class="text-gray-400 mx-1">|</span>
                            <?= substr($b->waktu_mulai, 0, 5) ?> - <?= substr($b->waktu_selesai, 0, 5) ?>
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i class="ph ph-user text-lg text-gray-400"></i>
                        <span class="font-medium"><?= esc($b->peminjam) ?></span>
                    </div>
                </div>

                <?php if(in_array(session()->get('role'), ['super_admin', 'admin_lab'])): ?>
                <div class="flex gap-2 pt-3 border-t border-gray-50">
                    <?php if($b->status == 'pending'): ?>
                        <button onclick="confirmApprove(<?= $b->id ?>)" class="flex-1 bg-green-50 text-green-700 py-2 rounded-lg text-xs font-bold hover:bg-green-100">Setujui</button>
                        <button onclick="confirmReject(<?= $b->id ?>)" class="flex-1 bg-red-50 text-red-700 py-2 rounded-lg text-xs font-bold hover:bg-red-100">Tolak</button>
                    <?php endif; ?>
                    <a href="<?= base_url('bookings/edit/'.$b->id) ?>" class="flex-1 bg-gray-100 text-gray-700 py-2 rounded-lg text-xs font-bold text-center hover:bg-gray-200">Edit</a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            
            <?php if(empty($bookings)): ?>
                <div class="text-center py-10 text-gray-400">
                    <i class="ph ph-folder-dashed text-4xl mb-2"></i>
                    <p class="text-sm">Tidak ada data peminjaman.</p>
                </div>
            <?php endif; ?>
        </div>

        <div id="mobilePagination" class="p-4 flex justify-center items-center gap-4 bg-white border-t border-gray-100 mt-auto hidden">
            <button id="prevBtn" class="bg-gray-100 hover:bg-navy-800 hover:text-white text-navy-800 w-10 h-10 rounded-lg flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="ph ph-caret-left text-lg font-bold"></i>
            </button>
            <span id="pageInfo" class="text-sm font-bold text-gray-600">Halaman 1 / 1</span>
            <button id="nextBtn" class="bg-gray-100 hover:bg-navy-800 hover:text-white text-navy-800 w-10 h-10 rounded-lg flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="ph ph-caret-right text-lg font-bold"></i>
            </button>
        </div>
    </div>
</div>

<style>
    .datatable-input { border: 1px solid #e2e8f0 !important; border-radius: 0.5rem !important; padding: 0.5rem 1rem !important; font-size: 0.875rem !important; }
    .datatable-input:focus { border-color: #1e3a8a !important; outline: none !important; box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1) !important; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Desktop DataTables
    if(window.innerWidth > 768) {
        new simpleDatatables.DataTable("#dataTable", {
            searchable: true, perPage: 10,
            labels: { placeholder: "Cari Peminjaman...", perPage: "entri per halaman", noRows: "Data tidak ditemukan", info: "Menampilkan {start} - {end} dari {rows}" }
        });
    }

    // 2. Mobile Pagination Logic (Script Baru)
    const cards = document.querySelectorAll('.mobile-card-item');
    const itemsPerPage = 5; // Tampilkan 5 kartu per halaman di HP
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
        paginationContainer.classList.remove('hidden'); // Munculkan navigasi jika data > 5
        
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) { currentPage--; showPage(currentPage); }
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < totalPages) { currentPage++; showPage(currentPage); }
        });

        showPage(1); // Init
    } else {
        // Jika data sedikit, tampilkan semua tanpa tombol navigasi
        cards.forEach(card => card.style.display = 'block');
    }

    // 3. Logic Bulk Action
    const bulkContainer = document.getElementById('bulkActionContainer');
    const selectedCountSpan = document.getElementById('selectedCount');
    
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'selectAll') {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
        }
        
        const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
        if (checkedBoxes.length > 0) {
            bulkContainer.classList.remove('hidden');
            selectedCountSpan.innerText = checkedBoxes.length;
        } else {
            bulkContainer.classList.add('hidden');
        }
    });
});

// Fungsi Konfirmasi
function confirmApprove(id) {
    Swal.fire({ title: 'Setujui?', text: "Jadwal akan disahkan.", icon: 'question', showCancelButton: true, confirmButtonColor: '#16a34a', confirmButtonText: 'Ya, Setujui' })
    .then((result) => { if (result.isConfirmed) window.location.href = '<?= base_url('bookings/approve/') ?>' + id; });
}
function confirmReject(id) {
    Swal.fire({ title: 'Tolak?', text: "Peminjaman akan ditandai ditolak.", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Tolak' })
    .then((result) => { if (result.isConfirmed) window.location.href = '<?= base_url('bookings/reject/') ?>' + id; });
}
function confirmBulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
    Swal.fire({ title: 'Hapus Massal?', text: `Yakin menghapus ${ids.length} data terpilih?`, icon: 'error', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Hapus Sekarang' })
    .then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form'); form.method = 'POST'; form.action = '<?= base_url('bookings/bulk-delete') ?>';
            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '<?= csrf_token() ?>'; csrf.value = '<?= csrf_hash() ?>'; form.appendChild(csrf);
            ids.forEach(id => { const input = document.createElement('input'); input.type = 'hidden'; input.name = 'ids[]'; input.value = id; form.appendChild(input); });
            document.body.appendChild(form); form.submit();
        }
    });
}
</script>
<?= $this->endSection() ?>