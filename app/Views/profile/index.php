<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-navy-900">Profil Saya</h1>
        <p class="text-gray-500 text-sm">Kelola informasi akun dan kata sandi Anda.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-navy-900 px-6 py-8 text-center md:text-left md:flex md:items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-white text-navy-900 flex items-center justify-center font-bold text-3xl border-4 border-blue-500 shadow-lg mx-auto md:mx-0">
                <?= strtoupper(substr(session()->get('nama'), 0, 1)) ?>
            </div>
            <div class="text-white mt-4 md:mt-0">
                <h2 class="text-xl font-bold"><?= esc($user->nama) ?></h2>
                <span class="bg-blue-600 text-blue-100 text-xs px-2 py-1 rounded-md font-bold uppercase tracking-wider">
                    <?= str_replace('_', ' ', session()->get('role')) ?>
                </span>
            </div>
        </div>

        <form action="<?= base_url('/profile/update') ?>" method="POST" class="p-6 md:p-8 space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= esc($user->nama) ?>" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-800 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Username / ID Login</label>
                    <input type="text" name="username" value="<?= esc($user->username) ?>" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-800 focus:border-transparent">
                </div>
            </div>

            <hr class="border-gray-100 my-6">

            <div>
                <h3 class="font-bold text-navy-900 text-lg mb-4 flex items-center gap-2">
                    <i class="ph ph-lock-key"></i> Ganti Kata Sandi
                </h3>
                <p class="text-sm text-gray-500 mb-4 bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <i class="ph ph-info"></i> Biarkan kosong jika tidak ingin mengubah kata sandi.
                </p>

                <div class="space-y-4 max-w-lg">
                    <div>
                        <label class="block text-sm font-semibold text-gray-600 mb-1">Kata Sandi Saat Ini (Wajib jika ingin ubah)</label>
                        <input type="password" name="password_lama" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-800 bg-gray-50 focus:bg-white transition">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password_baru" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-800 bg-gray-50 focus:bg-white transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-600 mb-1">Ulangi Kata Sandi Baru</label>
                            <input type="password" name="konfirmasi_password" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-800 bg-gray-50 focus:bg-white transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition flex items-center gap-2">
                    <i class="ph ph-floppy-disk text-xl"></i> Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>
<?= $this->endSection() ?>