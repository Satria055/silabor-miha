<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-clock"></i> <?= $jam ? 'Edit Jam Pelajaran' : 'Tambah Jam Pelajaran' ?>
        </h2>
        <a href="<?= base_url('jam-pelajaran') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <form action="<?= base_url('jam-pelajaran/save') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($jam): ?> <input type="hidden" name="id" value="<?= $jam->id ?>"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Unit Pendidikan</label>
            <select name="unit_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white shadow-sm">
                <option value="">-- Pilih Unit --</option>
                <?php foreach($units as $u): ?>
                    <option value="<?= $u->id ?>" <?= ($jam && $jam->unit_id == $u->id) ? 'selected' : '' ?>><?= esc($u->nama_unit) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Sesi (Contoh: Jam ke-1 & 2)</label>
            <input type="text" name="nama_sesi" required value="<?= $jam ? esc($jam->nama_sesi) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm" placeholder="Masukkan nama atau urutan jam">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Waktu Mulai</label>
                <input type="time" name="waktu_mulai" required value="<?= $jam ? substr($jam->waktu_mulai, 0, 5) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Waktu Selesai</label>
                <input type="time" name="waktu_selesai" required value="<?= $jam ? substr($jam->waktu_selesai, 0, 5) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm">
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-4">
            <a href="<?= base_url('jam-pelajaran') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> <?= $jam ? 'Simpan Perubahan' : 'Simpan Data' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>