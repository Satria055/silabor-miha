<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-navy-900">Kelola Pusat Unduhan</h2>
        <p class="text-gray-500">Unggah SOP, Surat Peminjaman, atau Tata Tertib untuk pengguna.</p>
    </div>
    <a href="<?= base_url('downloads/add') ?>" class="bg-navy-800 text-white px-4 py-2 rounded-md font-bold hover:bg-navy-900 transition flex items-center gap-2 shadow-sm">
        <i class="ph ph-upload-simple text-lg"></i> Unggah File Baru
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-navy-900 border-b border-gray-100">
                <th class="p-4 font-semibold w-16">No</th>
                <th class="p-4 font-semibold">Judul Dokumen</th>
                <th class="p-4 font-semibold text-center">Tipe File</th>
                <th class="p-4 font-semibold text-center w-32">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no=1; foreach ($downloads as $d) : ?>
            <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                <td class="p-4 text-center font-bold text-gray-500"><?= $no++ ?></td>
                <td class="p-4">
                    <div class="font-bold text-navy-900"><?= esc($d->judul) ?></div>
                    <div class="text-xs text-gray-500 truncate w-64"><?= esc($d->deskripsi) ?></div>
                </td>
                <td class="p-4 text-center font-bold text-blue-600 uppercase text-xs"><?= esc($d->tipe_file) ?></td>
                <td class="p-4">
                    <div class="flex justify-center gap-1">
                        <a href="<?= base_url('downloads/edit/' . $d->id) ?>" class="bg-gray-100 text-gray-700 p-2 rounded hover:bg-navy-800 hover:text-white transition"><i class="ph ph-pencil-simple text-lg"></i></a>
                        <a href="<?= base_url('downloads/delete/' . $d->id) ?>" onclick="return confirm('Hapus file ini?')" class="bg-red-50 text-red-600 p-2 rounded hover:bg-red-600 hover:text-white transition"><i class="ph ph-trash text-lg"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <script>Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= session()->getFlashdata('success') ?>', timer: 3000, confirmButtonColor: '#1e3a8a' });</script>
<?php endif; ?>
<?= $this->endSection() ?>