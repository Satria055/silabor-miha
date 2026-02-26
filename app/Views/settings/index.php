<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-gear"></i> Pengaturan Sistem
        </h2>
        <p class="text-gray-500 text-sm mt-1">Atur jam operasional dan batas minimum waktu pemesanan (Lead Time) untuk pengguna.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', confirmButtonColor: '#1e3a8a', timer: 3000 });</script>
    <?php endif; ?>

    <form action="<?= base_url('settings/save') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="bg-blue-50 border border-blue-100 p-5 rounded-lg">
            <h3 class="font-bold text-navy-900 mb-3 flex items-center gap-2">
                <i class="ph ph-clock text-xl"></i> Jam Operasional Lab
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Jam Buka Peminjaman</label>
                    <input type="time" name="jam_buka" required value="<?= esc(substr($jam_buka, 0, 5)) ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm bg-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Jam Tutup Peminjaman</label>
                    <input type="time" name="jam_tutup" required value="<?= esc(substr($jam_tutup, 0, 5)) ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm bg-white">
                </div>
            </div>
            <p class="text-[11px] text-gray-500 mt-2 font-medium italic">*Pengguna biasa tidak dapat mengajukan form di luar rentang jam ini.</p>
        </div>

        <div class="bg-purple-50 border border-purple-100 p-5 rounded-lg">
            <h3 class="font-bold text-purple-900 mb-3 flex items-center gap-2">
                <i class="ph ph-calendar-warning text-xl"></i> Batas Waktu Pemesanan (Lead Time)
            </h3>
            <div>
                <label class="block text-sm font-semibold mb-2 text-gray-700">Minimal H- (Hari)</label>
                <div class="flex items-center gap-3">
                    <input type="number" min="0" name="lead_time" required value="<?= esc($lead_time) ?>" class="w-24 px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm text-center font-extrabold text-xl text-navy-900 bg-white">
                    <span class="font-semibold text-gray-600 text-sm">Hari sebelum tanggal pemakaian.</span>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 mt-2 font-medium italic">*Contoh: Isi "1" berarti minimal H-1 (Tidak boleh meminjam dadakan untuk hari ini). Isi "2" berarti minimal H-2.</p>
        </div>

        <div class="pt-2 flex justify-end mt-6">
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-8 py-2.5 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>