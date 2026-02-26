<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-door-open"></i> <?= $lab ? 'Edit Laboratorium' : 'Tambah Laboratorium Baru' ?>
        </h2>
        <a href="<?= base_url('laboratories') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <form action="<?= base_url('laboratories/save') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($lab): ?> <input type="hidden" name="id" value="<?= $lab->id ?>"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Laboratorium</label>
            <input type="text" name="nama_lab" required value="<?= $lab ? esc($lab->nama_lab) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800" placeholder="Contoh: Lab Komputer 1">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Unit Pendidikan</label>
                <select name="unit_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white">
                    <option value="">-- Pilih Unit (Global) --</option>
                    <?php foreach($units as $u): ?>
                        <option value="<?= $u->id ?>" <?= ($lab && $lab->unit_id == $u->id) ? 'selected' : '' ?>><?= esc($u->nama_unit) ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="text-[10px] text-gray-500 mt-1 italic">*Biarkan Global jika lab dipakai bersama.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Kapasitas (Kursi)</label>
                <input type="number" name="kapasitas" required value="<?= $lab ? esc($lab->kapasitas) : '30' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Status Operasional</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white">
                <option value="aktif" <?= ($lab && $lab->status == 'aktif') ? 'selected' : '' ?>>Aktif (Bisa Dipinjam)</option>
                <option value="maintenance" <?= ($lab && $lab->status == 'maintenance') ? 'selected' : '' ?>>Maintenance (Nonaktif)</option>
            </select>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-4">
            <a href="<?= base_url('laboratories') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> <?= $lab ? 'Simpan Perubahan' : 'Simpan Data' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>