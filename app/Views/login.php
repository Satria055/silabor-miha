<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem - Silabor Miha</title>
    
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Animasi Masuk */
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; opacity: 0; transform: translateY(20px); }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="bg-gray-900 antialiased min-h-screen flex items-center justify-center p-4 bg-cover bg-center relative" 
      style="background-image: url('<?= base_url('img/pattern-bg.png') ?>');">
    
    <div class="absolute inset-0 bg-navy-900/40 backdrop-blur-[2px]"></div>

    <div class="bg-white relative z-10 p-6 md:p-10 rounded-3xl shadow-2xl w-full max-w-[420px] border border-white/20 fade-in-up">
        
        <div class="text-center mb-8">
            <div class="inline-block p-3 rounded-full bg-blue-50 mb-4 shadow-sm">
                <img src="<?= base_url('img/logo.png') ?>" alt="Logo Silabor" class="h-16 w-auto drop-shadow-sm">
            </div>
            <h1 class="text-2xl font-bold text-navy-900 tracking-tight">Selamat Datang</h1>
            <p class="text-sm text-gray-500 mt-2 leading-relaxed">Masuk untuk mengelola administrasi & jadwal laboratorium.</p>
        </div>

        <form action="<?= base_url('/auth/process') ?>" method="POST" class="space-y-5">
            <?= csrf_field() ?>
            
            <div>
                <label for="username" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2 ml-1">ID Pengguna / Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ph ph-user text-xl text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username" required placeholder="Masukkan username anda"
                        class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-600 block transition-all placeholder-gray-400 font-medium">
                </div>
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2 ml-1">Kata Sandi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ph ph-lock-key text-xl text-gray-400 group-focus-within:text-blue-600 transition-colors"></i>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="Masukkan kata sandi"
                        class="block w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-600 block transition-all placeholder-gray-400 font-medium">
                    
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer transition">
                        <i class="ph ph-eye text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-navy-900 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.98] flex justify-center items-center gap-2 group">
                    <span>Masuk Aplikasi</span>
                    <i class="ph ph-arrow-right font-bold group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

        </form>
        
        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-[10px] text-gray-400 font-semibold tracking-wide">
                &copy; <?= date('Y') ?> YAYASAN MABADI'UL IHSAN<br>
                <span class="font-normal text-gray-300">Developed by Satria Yudha Pratama, S.Kom.</span>
            </p>
        </div>
    </div>

    <script>
        // 1. Logika Show/Hide Password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            // Toggle tipe input
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle ikon mata (Ganti class Phosphor Icon)
            if (type === 'text') {
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-slash');
                icon.classList.add('text-blue-600'); // Beri warna biru saat terlihat
            } else {
                icon.classList.remove('ph-eye-slash');
                icon.classList.add('ph-eye');
                icon.classList.remove('text-blue-600');
            }
        });

        // 2. SweetAlert Notifikasi (Sesuai Controller Anda)
        <?php if (session()->getFlashdata('error')) : ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Masuk',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'Coba Lagi',
                confirmButtonColor: '#111827', // Warna Navy-900
                background: '#fff',
                customClass: { popup: 'rounded-2xl' }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= session()->getFlashdata('success') ?>',
                showConfirmButton: false,
                timer: 2000,
                background: '#fff',
                customClass: { popup: 'rounded-2xl' }
            });
        <?php endif; ?>
    </script>
</body>
</html>