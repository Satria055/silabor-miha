<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-buildings"></i> <?= $unit ? 'Edit Unit Pendidikan' : 'Tambah Unit Baru' ?>
        </h2>
        <a href="<?= base_url('units') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <form action="<?= base_url('units/save') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($unit): ?> <input type="hidden" name="id" value="<?= $unit->id ?>"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Unit Pendidikan</label>
            <input type="text" name="nama_unit" required value="<?= $unit ? esc($unit->nama_unit) : '' ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm" placeholder="Contoh: SMA Negeri 1 / Lembaga Kursus Bahasa">
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-4">
            <a href="<?= base_url('units') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> <?= $unit ? 'Simpan Perubahan' : 'Simpan Data' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>