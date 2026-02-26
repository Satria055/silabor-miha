<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>@media print { body { -webkit-print-color-adjust: exact; } }</style>
</head>
<body class="bg-white text-black p-8 max-w-4xl mx-auto" onload="window.print()">
    
    <div class="flex items-center border-b-4 border-black pb-4 mb-6">
        <img src="<?= base_url('img/logo.png') ?>" class="h-24 w-auto mr-6" alt="Logo">
        <div class="text-center flex-grow">
            <h2 class="text-xl font-bold uppercase tracking-wide">Yayasan Pondok Pesantren</h2>
            <h2 class="text-xl font-bold uppercase">Mabadi'ul Ihsan & Daar Al Ihsan</h2>
            <p class="text-sm mt-1">Jl. Pesantren No. 123, Tegalsari, Banyuwangi, Jawa Timur</p>
            <p class="text-sm italic">Website: www.mabadiulihsan.sch.id | Email: admin@mabadiulihsan.sch.id</p>
        </div>
    </div>

    <div class="text-center mb-8">
        <h3 class="text-xl font-bold underline">LAPORAN DATA INVENTARIS</h3>
        <p class="text-sm font-semibold mt-1">Filter Lokasi: <?= esc($filter['lab']) ?></p>
        <p class="text-sm font-semibold">Filter Kondisi: <?= ucwords(str_replace('_', ' ', $filter['kondisi'])) ?></p>
    </div>

    <table class="w-full border-collapse border border-black text-sm mb-8">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-black px-2 py-2 w-10">No</th>
                <th class="border border-black px-2 py-2">Kode</th>
                <th class="border border-black px-2 py-2">Nama Barang</th>
                <th class="border border-black px-2 py-2">Kategori</th>
                <th class="border border-black px-2 py-2">Lokasi Lab</th>
                <th class="border border-black px-2 py-2 w-16 text-center">Total</th>
                <th class="border border-black px-2 py-2 w-16 text-center bg-green-50">Baik</th>
                <th class="border border-black px-2 py-2 w-16 text-center bg-red-50">Rusak</th>
                <th class="border border-black px-2 py-2">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($items)): ?>
                <tr><td colspan="9" class="border border-black px-4 py-4 text-center italic">Data tidak ditemukan.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($items as $i): ?>
                <tr>
                    <td class="border border-black px-2 py-2 text-center"><?= $no++ ?></td>
                    <td class="border border-black px-2 py-2 font-mono text-xs font-bold"><?= esc($i->kode_barang) ?></td>
                    <td class="border border-black px-2 py-2 font-bold"><?= esc($i->nama_barang) ?></td>
                    <td class="border border-black px-2 py-2 text-xs uppercase"><?= esc($i->kategori) ?></td>
                    <td class="border border-black px-2 py-2"><?= esc($i->nama_lab ?? 'Gudang') ?></td>
                    <td class="border border-black px-2 py-2 text-center font-bold"><?= esc($i->jumlah_total) ?></td>
                    <td class="border border-black px-2 py-2 text-center text-green-700 bg-green-50"><?= esc($i->kondisi_baik) ?></td>
                    <td class="border border-black px-2 py-2 text-center text-red-700 bg-red-50 font-bold"><?= esc($i->kondisi_rusak) ?></td>
                    <td class="border border-black px-2 py-2 text-xs italic text-gray-600"><?= esc($i->keterangan) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="flex justify-end mt-12">
        <div class="text-center w-64">
            <p class="mb-20">Banyuwangi, <?= date('d F Y') ?><br>Kepala Sarana Prasarana,</p>
            <p class="font-bold border-b border-black inline-block min-w-[150px]"></p>
        </div>
    </div>
</body>
</html>