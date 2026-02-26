<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-sm border border-gray-100">
    <div class="mb-6 border-b pb-4 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-navy-900 flex items-center gap-2">
            <i class="ph ph-user-gear"></i> <?= $user ? 'Edit Pengguna' : 'Tambah Pengguna Baru' ?>
        </h2>
        <a href="<?= base_url('users') ?>" class="text-gray-500 hover:text-navy-800 transition"><i class="ph ph-x text-2xl"></i></a>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-50 text-red-600 p-3 rounded-md mb-4 text-sm font-semibold border border-red-200"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('users/save') ?>" method="POST" class="space-y-5">
        <?= csrf_field() ?>
        <?php if($user): ?> <input type="hidden" name="id" value="<?= $user->id ?>"> <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama" required value="<?= $user ? esc($user->nama) : '' ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Username (Login)</label>
                <input type="text" name="username" required value="<?= $user ? esc($user->username) : '' ?>" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm font-mono">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-50 pt-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Unit Pendidikan</label>
                <select name="unit_id" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white shadow-sm">
                    <option value="">-- PUSAT / YAYASAN --</option>
                    <?php foreach($units as $u): ?>
                        <option value="<?= $u->id ?>" <?= ($user && $user->unit_id == $u->id) ? 'selected' : '' ?>><?= esc($u->nama_unit) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Role / Hak Akses</label>
                <select name="role" required class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-semibold text-navy-800">
                    <option value="guru" <?= ($user && $user->role == 'guru') ? 'selected' : '' ?>>Guru</option>
                    <option value="staff" <?= ($user && $user->role == 'staff') ? 'selected' : '' ?>>Staff</option>
                    <option value="panitia" <?= ($user && $user->role == 'panitia') ? 'selected' : '' ?>>Panitia</option>
                    <option value="admin_lab" <?= ($user && $user->role == 'admin_lab') ? 'selected' : '' ?>>Admin Lab / Koordinator</option>
                    <option value="admin_unit" <?= ($user && $user->role == 'admin_unit') ? 'selected' : '' ?>>Admin Unit</option>
                    <option value="super_admin" <?= ($user && $user->role == 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-50 pt-4">
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">
                    Password <?= $user ? '<span class="text-[10px] text-red-500 font-bold ml-1 italic">(Kosongkan jika tidak diganti)</span>' : '' ?>
                </label>
                <input type="password" name="password" <?= $user ? '' : 'required' ?> class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 shadow-sm" placeholder="Masukkan password">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Status Akun</label>
                <select name="is_active" class="w-full px-4 py-2 border-[1.5px] border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-navy-800 bg-white shadow-sm font-bold">
                    <option value="1" class="text-green-600" <?= ($user && $user->is_active == 1) ? 'selected' : '' ?>>Aktif (Bisa Login)</option>
                    <option value="0" class="text-red-600" <?= ($user && $user->is_active == 0) ? 'selected' : '' ?>>Nonaktifkan (Blokir)</option>
                </select>
            </div>
        </div>

        <div class="pt-4 flex justify-end gap-3 border-t border-gray-100 mt-6">
            <a href="<?= base_url('users') ?>" class="px-5 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md font-semibold transition">Batal</a>
            <button type="submit" class="bg-navy-800 hover:bg-navy-900 text-white px-6 py-2 rounded-md font-bold transition shadow-sm flex items-center gap-2">
                <i class="ph ph-floppy-disk text-lg"></i> <?= $user ? 'Simpan Perubahan' : 'Simpan Pengguna' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>