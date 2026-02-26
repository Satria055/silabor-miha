<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-user-circle-plus"></i> <?= $anggota ? 'Edit Anggota Tim' : 'Tambah Anggota Tim' ?>
        </h2>
        <a href="<?= base_url('struktur/manage') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <form action="<?= base_url('struktur/save') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($anggota): ?> <input type="hidden" name="id" value="<?= $anggota->id ?>"> <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-50 pb-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" required value="<?= $anggota ? esc($anggota->nama) : '' ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
            </div>
            <div class="flex gap-4">
                <div class="flex-grow">
                    <label class="block text-sm font-semibold mb-1 text-gray-700">Jabatan</label>
                    <input type="text" name="jabatan" required value="<?= $anggota ? esc($anggota->jabatan) : '' ?>" placeholder="Contoh: Kepala Lab Komputer" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
                </div>
                <div class="w-24">
                    <label class="block text-sm font-semibold mb-1 text-gray-700" title="1=Paling Atas">Urutan</label>
                    <input type="number" name="urutan" required value="<?= $anggota ? esc($anggota->urutan) : '1' ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white text-center font-bold">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-b border-gray-50 pb-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Nomor WhatsApp</label>
                <div class="relative">
                    <i class="ph ph-whatsapp-logo absolute left-3 top-2.5 text-green-500 text-lg"></i>
                    <input type="text" name="wa" value="<?= $anggota ? esc($anggota->wa) : '' ?>" placeholder="Contoh: 628123456789" class="w-full pl-10 pr-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Link URL Instagram</label>
                <div class="relative">
                    <i class="ph ph-instagram-logo absolute left-3 top-2.5 text-pink-500 text-lg"></i>
                    <input type="text" name="ig" value="<?= $anggota ? esc($anggota->ig) : '' ?>" placeholder="https://instagram.com/..." class="w-full pl-10 pr-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Link URL Facebook</label>
                <div class="relative">
                    <i class="ph ph-facebook-logo absolute left-3 top-2.5 text-blue-600 text-lg"></i>
                    <input type="text" name="fb" value="<?= $anggota ? esc($anggota->fb) : '' ?>" placeholder="https://facebook.com/..." class="w-full pl-10 pr-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Link Website / Portofolio</label>
                <div class="relative">
                    <i class="ph ph-globe absolute left-3 top-2.5 text-gray-500 text-lg"></i>
                    <input type="text" name="web" value="<?= $anggota ? esc($anggota->web) : '' ?>" placeholder="https://..." class="w-full pl-10 pr-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
                </div>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Foto Profil Resmi (.jpg / .png)</label>
            <input type="file" name="foto" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-navy-700 hover:file:bg-blue-100">
            <?php if($anggota && $anggota->foto): ?>
                <p class="text-xs text-green-600 mt-2 font-semibold">Foto saat ini sudah tersimpan. Biarkan kosong jika tidak ingin mengubah.</p>
            <?php endif; ?>
        </div>

        <div class="pt-4 flex justify-end gap-3 mt-6">
            <a href="<?= base_url('struktur/manage') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> Simpan Profil
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>