<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-upload-simple"></i> <?= $download ? 'Edit Data Dokumen' : 'Unggah Dokumen Baru' ?>
        </h2>
        <a href="<?= base_url('downloads/manage') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <form action="<?= base_url('downloads/save') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($download): ?> <input type="hidden" name="id" value="<?= $download->id ?>"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Judul Dokumen</label>
            <input type="text" name="judul" required value="<?= $download ? esc($download->judul) : '' ?>" placeholder="Contoh: Format Surat Peminjaman Lab" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white">
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Deskripsi Singkat</label>
            <textarea name="deskripsi" rows="3" required class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white" placeholder="Jelaskan kegunaan dokumen ini..."><?= $download ? esc($download->deskripsi) : '' ?></textarea>
        </div>

        <div class="bg-blue-50 border border-blue-100 p-4 rounded-lg">
            <label class="block text-sm font-bold mb-2 text-navy-900">Pilih File (.pdf, .doc, .docx, .xls, .xlsx)</label>
            <input type="file" name="file_dokumen" accept=".pdf,.doc,.docx,.xls,.xlsx" <?= $download ? '' : 'required' ?> class="w-full px-4 py-2 border border-blue-200 bg-white rounded-md focus:ring-2 focus:ring-navy-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-navy-800 file:text-white hover:file:bg-navy-900 cursor-pointer">
            <?php if($download && $download->nama_file): ?>
                <p class="text-xs text-green-600 mt-2 font-bold"><i class="ph ph-check-circle"></i> File saat ini: <?= esc($download->nama_file) ?> (Biarkan kosong jika tidak diganti)</p>
            <?php endif; ?>
        </div>

        <div class="pt-4 flex justify-end gap-3 mt-6">
            <a href="<?= base_url('downloads/manage') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> Simpan Dokumen
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>