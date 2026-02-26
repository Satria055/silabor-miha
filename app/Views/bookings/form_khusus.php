<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php 
    // SETUP VARIABEL PHP UNTUK FRONTEND
    $isAdmin = in_array($user_role, ['super_admin', 'admin_lab']);
    $minDateHtml = '';
    $minDateValue = '';
    
    $currentTime = \CodeIgniter\I18n\Time::now('Asia/Jakarta')->format('H:i');
    $jamBuka = substr($sys_setting->jam_buka, 0, 5);
    $jamTutup = substr($sys_setting->jam_tutup, 0, 5);

    if (!$isAdmin) {
        $minDateValue = date('Y-m-d', strtotime('+' . $sys_setting->lead_time . ' days'));
        $minDateHtml = 'min="' . $minDateValue . '"';
    }
?>

<div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100 relative">
    
    <div id="loader" class="hidden absolute inset-0 bg-white/80 backdrop-blur-sm z-10 flex items-center justify-center rounded-xl">
        <div class="text-navy-800 font-bold flex flex-col items-center">
            <i class="ph ph-spinner animate-spin text-4xl mb-2"></i> Mengecek Ketersediaan Jadwal...
        </div>
    </div>

    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-calendar-star"></i> <?= isset($booking) ? 'Edit Kegiatan Khusus' : 'Form Kegiatan Khusus' ?>
        </h2>
        <a href="<?= base_url('bookings') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <?php if(!$isAdmin): ?>
        <div class="bg-purple-50 text-purple-700 p-3 rounded-md text-xs font-semibold mb-6 border border-purple-100">
            <ul class="list-disc pl-4 space-y-1">
                <li>Form Pengajuan hanya dilayani pukul <b><?= $jamBuka ?> - <?= $jamTutup ?> WIB</b>.</li>
                <li>Pemesanan minimal H-<b><?= $sys_setting->lead_time ?></b> sebelum hari pelaksanaan.</li>
                <li>Pemakaian Lab diizinkan pukul <b><?= $jamBuka ?> - <?= $jamTutup ?> WIB</b>.</li>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('bookings/save-khusus') ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrfToken">
        <?php if(isset($booking)): ?> <input type="hidden" name="id" value="<?= $booking->id ?>" id="bookingId"> <?php endif; ?>

        <div>
            <label class="block text-sm font-semibold mb-1 text-gray-700">Pilih Laboratorium</label>
            <select name="lab_id" id="labId" required class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 bg-white check-trigger">
                <option value="">-- Pilih Ruang Lab --</option>
                <?php foreach($labs as $lab): ?>
                    <option value="<?= $lab->id ?>" <?= (isset($booking) && $booking->lab_id == $lab->id) ? 'selected' : '' ?>>
                        <?= esc($lab->nama_lab) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-50 pt-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tglMulai" <?= $minDateHtml ?> required value="<?= isset($booking) ? esc($booking->tanggal_mulai) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 check-trigger">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Tanggal Selesai (Opsional)</label>
                <input type="date" name="tanggal_selesai" id="tglSelesai" <?= $minDateHtml ?> value="<?= isset($booking) ? esc($booking->tanggal_selesai) : '' ?>" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 check-trigger" title="Isi jika lebih dari 1 hari">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Waktu Mulai Pemakaian</label>
                <input type="time" name="waktu_mulai" id="wktMulai" required value="<?= isset($booking) ? substr($booking->waktu_mulai, 0, 5) : '' ?>" class="time-limit w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 check-trigger">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Waktu Selesai Pemakaian</label>
                <input type="time" name="waktu_selesai" id="wktSelesai" required value="<?= isset($booking) ? substr($booking->waktu_selesai, 0, 5) : '' ?>" class="time-limit w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 check-trigger">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-50 pt-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Keperluan Kegiatan</label>
                <input type="text" name="keperluan" required value="<?= isset($booking) ? esc($booking->keperluan) : '' ?>" placeholder="Contoh: Ujian Berbasis Komputer" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" required value="<?= isset($booking) ? esc($booking->penanggung_jawab) : '' ?>" placeholder="Nama / Kepanitiaan" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800">
            </div>
        </div>

        <div class="border-t border-gray-50 pt-4">
            <label class="block text-sm font-semibold mb-1 text-gray-700">Unggah Surat Peminjaman (.pdf / .jpg / .png)</label>
            <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" <?= isset($booking) ? '' : 'required' ?> class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-navy-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-navy-700 hover:file:bg-blue-100">
            
            <?php if(isset($booking) && $booking->file_surat): ?>
                <p class="text-xs text-green-600 mt-2 font-semibold">
                    <i class="ph ph-check-circle"></i> File tersimpan: <a href="<?= base_url('uploads/surat_peminjaman/'.$booking->file_surat) ?>" target="_blank" class="underline hover:text-green-800">Lihat File</a>
                    <br><span class="text-gray-500 font-normal">*Biarkan kosong jika tidak ingin mengubah surat.</span>
                </p>
            <?php else: ?>
                <p class="text-xs text-gray-500 mt-1">Wajib menyertakan surat berstempel untuk verifikasi Koordinator Lab.</p>
            <?php endif; ?>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-6">
            <a href="<?= base_url('bookings') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" id="submitBtn" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-paper-plane-right text-lg"></i> <?= isset($booking) ? 'Simpan Perubahan' : 'Ajukan Kegiatan Khusus' ?>
            </button>
        </div>
    </form>
</div>

<?php if (session()->getFlashdata('error')) : ?>
    <script>Swal.fire({ icon: 'error', title: 'Peringatan', text: '<?= session()->getFlashdata('error') ?>', confirmButtonColor: '#d33' });</script>
<?php endif; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // ==========================================================
    // 1. SISTEM BLOKIR REAL-TIME
    // ==========================================================
    <?php if(!$isAdmin): ?>
    const currentTime = '<?= $currentTime ?>';
    const jamBukaSistem = '<?= $jamBuka ?>';
    const jamTutupSistem = '<?= $jamTutup ?>';
    const minDateAllowed = '<?= $minDateValue ?>';
    const leadTime = <?= $sys_setting->lead_time ?>;
    
    // A. Blokir akses jika membuka di jam terlarang
    if (currentTime < jamBukaSistem || currentTime > jamTutupSistem) {
        Swal.fire({
            icon: 'error',
            title: 'Layanan Ditutup!',
            text: `Pengajuan peminjaman hanya dilayani pada pukul ${jamBukaSistem} hingga ${jamTutupSistem} WIB. Waktu server saat ini: ${currentTime} WIB.`,
            allowOutsideClick: false,
            confirmButtonText: 'Kembali Ke Daftar',
            confirmButtonColor: '#1e3a8a'
        }).then(() => {
            window.location.href = '<?= base_url('bookings') ?>';
        });

        const submitBtn = document.getElementById('submitBtn');
        if(submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('hidden');
        }
        document.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
    }

    // B. Blokir jika memilih tanggal di bawah batas H-x
    document.getElementById('tglMulai').addEventListener('change', function() {
        if(this.value && this.value < minDateAllowed) {
            Swal.fire('Pemesanan Ditolak!', `Sistem mewajibkan pemesanan minimal H-${leadTime} sebelum pemakaian.`, 'error');
            this.value = ''; 
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
        }
    });

    // C. Validasi Jam Pemakaian Fisik Lab
    document.querySelectorAll('.time-limit').forEach(input => {
        input.addEventListener('change', function() {
            if(this.value && (this.value < jamBukaSistem || this.value > jamTutupSistem)) {
                Swal.fire('Di Luar Jam Operasional!', `Lab hanya beroperasi jam ${jamBukaSistem} - ${jamTutupSistem} WIB. Pengajuan akan ditolak.`, 'warning');
                this.value = '';
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
            }
        });
    });
    <?php endif; ?>

    // ==========================================================
    // 2. AJAX REAL-TIME CEK BENTROK
    // ==========================================================
    const labInput = document.getElementById('labId');
    const tglMulai = document.getElementById('tglMulai');
    const tglSelesai = document.getElementById('tglSelesai');
    const wktMulai = document.getElementById('wktMulai');
    const wktSelesai = document.getElementById('wktSelesai');
    const submitBtn = document.getElementById('submitBtn');
    const loader = document.getElementById('loader');
    const bookingId = document.getElementById('bookingId') ? document.getElementById('bookingId').value : '';
    let currentCsrfHash = document.getElementById('csrfToken').value;

    function checkConflict() {
        if(labInput.value && tglMulai.value && wktMulai.value && wktSelesai.value) {
            loader.classList.remove('hidden');
            submitBtn.disabled = true;

            const formData = new FormData();
            formData.append('jenis', 'khusus');
            formData.append('lab_id', labInput.value);
            formData.append('tanggal_mulai', tglMulai.value);
            formData.append('tanggal_selesai', tglSelesai.value);
            formData.append('waktu_mulai', wktMulai.value);
            formData.append('waktu_selesai', wktSelesai.value);
            formData.append('booking_id', bookingId);
            formData.append('csrf_test_name', currentCsrfHash);

            fetch('<?= base_url('bookings/api/check-conflict') ?>', {
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                loader.classList.add('hidden');
                
                if(data.csrfHash) {
                    currentCsrfHash = data.csrfHash;
                    document.getElementById('csrfToken').value = currentCsrfHash;
                }

                if(data.status === 'conflict') {
                    Swal.fire({ icon: 'error', title: 'Jadwal Bentrok!', text: data.message, confirmButtonColor: '#d33' });
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else if (data.status === 'available') {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            })
            .catch(error => {
                loader.classList.add('hidden');
                console.error('Error Cek Bentrok:', error);
            });
        }
    }

    document.querySelectorAll('.check-trigger').forEach(item => {
        item.addEventListener('change', checkConflict);
    });
});
</script>
<?= $this->endSection() ?>