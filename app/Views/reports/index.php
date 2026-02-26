<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-3xl font-extrabold text-navy-900 flex items-center gap-3">
        <i class="ph ph-files"></i> Manajemen Laporan
    </h1>
    <p class="text-gray-500">Cetak laporan aktivitas dan aset laboratorium untuk arsip Yayasan.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-4 border-b pb-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="ph ph-calendar-check text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-navy-900">Laporan Peminjaman</h3>
        </div>

        <form action="<?= base_url('reports/print-booking') ?>" method="GET" target="_blank" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Rentang Tanggal</label>
                <div class="flex gap-2">
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <span class="self-center text-gray-400">-</span>
                    <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Laboratorium</label>
                <select name="lab_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">-- Semua Laboratorium --</option>
                    <?php foreach($labs as $lab): ?>
                        <option value="<?= $lab->id ?>"><?= esc($lab->nama_lab) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Status Peminjaman</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">Semua (Disetujui/Selesai)</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="selesai">Selesai</option>
                    <option value="ditolak">Ditolak (Log)</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-navy-800 hover:bg-navy-900 text-white font-bold py-2.5 rounded-md transition flex items-center justify-center gap-2">
                <i class="ph ph-printer"></i> Cetak PDF Peminjaman
            </button>
        </form>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3 mb-4 border-b pb-4">
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                <i class="ph ph-archive-box text-xl"></i>
            </div>
            <h3 class="text-lg font-bold text-navy-900">Laporan Inventaris</h3>
        </div>

        <form action="<?= base_url('reports/print-inventory') ?>" method="GET" target="_blank" class="space-y-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Laboratorium</label>
                <select name="lab_id" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">-- Semua Laboratorium --</option>
                    <?php foreach($labs as $lab): ?>
                        <option value="<?= $lab->id ?>"><?= esc($lab->nama_lab) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Kondisi Barang</label>
                <select name="kondisi" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                    <option value="">-- Semua Kondisi --</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                </select>
            </div>

            <div class="pt-14"> <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 rounded-md transition flex items-center justify-center gap-2">
                    <i class="ph ph-printer"></i> Cetak PDF Inventaris
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>