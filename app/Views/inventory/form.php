<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-box-arrow-up"></i> <?= $inventory ? 'Edit Barang Inventaris' : 'Tambah Barang Baru' ?>
        </h2>
        <a href="<?= base_url('inventory') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 text-sm font-semibold border border-red-200"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('inventory/save') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        <?php if($inventory): ?> <input type="hidden" name="id" value="<?= $inventory->id ?>"> <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Laboratorium Penempatan</label>
                <select name="lab_id" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
                    <option value="">-- Pilih Laboratorium --</option>
                    <?php foreach($labs as $l): ?>
                        <option value="<?= $l->id ?>" <?= ($inventory && $inventory->lab_id == $l->id) ? 'selected' : '' ?>><?= esc($l->nama_lab) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Kategori Barang</label>
                <select name="kategori" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
                    <?php $kategoriList = ['Elektronik & Komputer', 'Perabot / Furniture', 'Alat Peraga Praktik', 'Bahan Habis Pakai', 'Lainnya']; ?>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach($kategoriList as $kat): ?>
                        <option value="<?= $kat ?>" <?= ($inventory && $inventory->kategori == $kat) ? 'selected' : '' ?>><?= $kat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Kode Barang / SKU</label>
                <input type="text" name="kode_barang" required value="<?= $inventory ? esc($inventory->kode_barang) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 font-mono" placeholder="Contoh: INV-KOMP-001">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Barang</label>
                <input type="text" name="nama_barang" required value="<?= $inventory ? esc($inventory->nama_barang) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800" placeholder="Contoh: PC Desktop Lenovo ThinkCentre">
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-bold mb-1 text-navy-900">Total Barang</label>
                <input type="number" min="0" name="jumlah_total" required value="<?= $inventory ? $inventory->jumlah_total : '0' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md font-bold text-center">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1 text-green-700">Kondisi Baik</label>
                <input type="number" min="0" name="kondisi_baik" required value="<?= $inventory ? $inventory->kondisi_baik : '0' ?>" class="w-full px-4 py-2 border border-green-300 rounded-md font-bold text-center text-green-700 focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-bold mb-1 text-red-700">Kondisi Rusak</label>
                <input type="number" min="0" name="kondisi_rusak" required value="<?= $inventory ? $inventory->kondisi_rusak : '0' ?>" class="w-full px-4 py-2 border border-red-300 rounded-md font-bold text-center text-red-700 focus:ring-2 focus:ring-red-500">
            </div>
        </div>
        <p class="text-xs text-gray-500 italic mt-1">* Pastikan jumlah (Baik + Rusak) sama dengan Total Barang.</p>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Keterangan / Spesifikasi (Opsional)</label>
            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800" placeholder="Tuliskan spesifikasi, tahun pengadaan, atau catatan kerusakan..."><?= $inventory ? esc($inventory->keterangan) : '' ?></textarea>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-2">
            <a href="<?= base_url('inventory') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 text-white px-8 py-2 rounded-md font-bold hover:bg-navy-900 shadow-sm flex items-center gap-2 transition">
                <i class="ph ph-floppy-disk"></i> <?= $inventory ? 'Simpan Perubahan' : 'Simpan Inventaris' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>