<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
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
        <h3 class="text-xl font-bold underline">LAPORAN PEMINJAMAN LABORATORIUM</h3>
        <p class="text-sm mt-1">Periode: <?= date('d M Y', strtotime($filter['start'])) ?> s/d <?= date('d M Y', strtotime($filter['end'])) ?></p>
        <p class="text-sm font-semibold">Lokasi: <?= esc($filter['lab']) ?></p>
    </div>

    <table class="w-full border-collapse border border-black text-sm mb-8">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-black px-2 py-2 w-10">No</th>
                <th class="border border-black px-2 py-2">Tanggal</th>
                <th class="border border-black px-2 py-2">Jam</th>
                <th class="border border-black px-2 py-2">Lab</th>
                <th class="border border-black px-2 py-2">Peminjam</th>
                <th class="border border-black px-2 py-2">Kegiatan/Mapel</th>
                <th class="border border-black px-2 py-2 w-20">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($bookings)): ?>
                <tr><td colspan="7" class="border border-black px-4 py-4 text-center italic">Tidak ada data peminjaman pada periode ini.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($bookings as $b): ?>
                <tr>
                    <td class="border border-black px-2 py-2 text-center"><?= $no++ ?></td>
                    <td class="border border-black px-2 py-2 whitespace-nowrap"><?= date('d/m/Y', strtotime($b->tanggal_mulai)) ?></td>
                    <td class="border border-black px-2 py-2 text-center"><?= substr($b->waktu_mulai,0,5) ?>-<?= substr($b->waktu_selesai,0,5) ?></td>
                    <td class="border border-black px-2 py-2"><?= esc($b->nama_lab) ?></td>
                    <td class="border border-black px-2 py-2">
                        <?= esc($b->nama_peminjam) ?><br>
                        <span class="text-xs text-gray-600"><?= esc($b->penanggung_jawab ?? $b->guru_pengajar) ?></span>
                    </td>
                    <td class="border border-black px-2 py-2">
                        <?= $b->jenis_peminjaman == 'KBM' ? esc($b->mata_pelajaran) . ' (' . esc($b->kelas) . ')' : esc($b->keperluan) ?>
                    </td>
                    <td class="border border-black px-2 py-2 text-center capitalize"><?= $b->status ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="flex justify-end mt-12">
        <div class="text-center w-64">
            <p class="mb-20">Banyuwangi, <?= date('d F Y') ?><br>Kepala Laboratorium,</p>
            <p class="font-bold border-b border-black inline-block min-w-[150px]"></p>
            <p class="mt-1">NIP. ...........................</p>
        </div>
    </div>

</body>
</html>