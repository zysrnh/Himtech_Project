<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Organisasi Baru - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="organizations" />

    <!-- Main Content Area: Form Tambah Organisasi Baru -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Tambah Organisasi Baru
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftarkan entitas ormawa baru dan siapkan akun Staff/Bendahara pertamanya.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-sm hover:bg-ice-100 transition-colors"
                    >
                        Kembali ke Daftar Organisasi
                    </a>
                </div>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="p-4 rounded-md bg-danger/10 text-danger text-sm font-medium">
                    Ada kesalahan saat pengisian formulir. Silakan periksa kolom yang ditandai merah di bawah.
                </div>
            @endif

            <!-- Card Formulir Pendaftaran -->
            <div class="bg-white rounded-lg p-8 shadow-card border border-ice-200">
                <form action="{{ route('admin.organizations.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kolom Kiri: Data Entitas Organisasi -->
                        <div class="space-y-5">
                            <div class="pb-2 border-b border-ice-200">
                                <h2 class="font-display font-medium text-base text-navy-900">
                                    1. Informasi Organisasi (Tenant)
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Identitas himpunan atau unit kegiatan mahasiswa.
                                </p>
                            </div>

                            <div>
                                <label for="name" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Nama Organisasi <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Contoh: Himpunan Mahasiswa Teknologi"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Kode Organisasi (Huruf Kapital, Tanpa Spasi) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    value="{{ old('code') }}"
                                    required
                                    placeholder="Contoh: HIMTECH"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                <p class="mt-1 text-xs text-navy-500">
                                    Kode unik ini akan digunakan oleh seluruh pengurus dan anggota untuk login.
                                </p>
                                @error('code')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Deskripsi Singkat (Opsional)
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="Deskripsi singkat kegiatan, visi ormawa, atau profil singkat..."
                                    class="w-full p-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan: Data Akun Pengurus / Staff Awal -->
                        <div class="space-y-5">
                            <div class="pb-2 border-b border-ice-200">
                                <h2 class="font-display font-medium text-base text-navy-900">
                                    2. Akun Staff / Bendahara Awal
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Kredensial login untuk pengurus yang akan mengelola kas ormawa.
                                </p>
                            </div>

                            <div>
                                <label for="staff_name" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Nama Lengkap Staff Awal <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="staff_name"
                                    name="staff_name"
                                    value="{{ old('staff_name') }}"
                                    required
                                    placeholder="Contoh: Muhammad Zaki"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_name')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_username" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Username Staff (Untuk Login) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="staff_username"
                                    name="staff_username"
                                    value="{{ old('staff_username') }}"
                                    required
                                    placeholder="Contoh: zaki atau bendahara_himtech"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_username')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="staff_email" class="block text-[13px] text-navy-500 font-medium mb-2">
                                        Email Staff <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="staff_email"
                                        name="staff_email"
                                        value="{{ old('staff_email') }}"
                                        required
                                        placeholder="nama@email.com"
                                        class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                    >
                                    @error('staff_email')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="staff_phone" class="block text-[13px] text-navy-500 font-medium mb-2">
                                        Nomor WhatsApp <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="staff_phone"
                                        name="staff_phone"
                                        value="{{ old('staff_phone') }}"
                                        required
                                        placeholder="081234567890"
                                        class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                    >
                                    @error('staff_phone')
                                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label for="staff_password" class="text-[13px] text-navy-500 font-medium">
                                        Kata Sandi Akun Staff
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            id="btn-generate-password"
                                            class="text-xs font-semibold text-navy-900 hover:text-navy-500 underline decoration-ice-400 underline-offset-4 cursor-pointer"
                                        >
                                            Generate Acak Kuat
                                        </button>
                                        <span class="text-ice-200">|</span>
                                        <button
                                            type="button"
                                            id="btn-copy-password"
                                            class="text-xs font-semibold text-navy-900 hover:text-navy-500 underline decoration-ice-400 underline-offset-4 cursor-pointer"
                                        >
                                            Salin
                                        </button>
                                    </div>
                                </div>
                                <input
                                    type="text"
                                    id="staff_password"
                                    name="staff_password"
                                    value="{{ old('staff_password') }}"
                                    placeholder="Biarkan kosong untuk generate otomatis (10 karakter)"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                <p id="copy-status" class="mt-1 text-xs text-navy-900 hidden font-medium">
                                    Kata sandi berhasil disalin ke clipboard.
                                </p>
                                @error('staff_password')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 rounded-md bg-ice-100/60 border border-ice-200 text-xs text-navy-500 space-y-1">
                                <p class="font-medium text-navy-900">Pengiriman Kredensial Otomatis:</p>
                                <p>Sistem akan langsung mengirimkan Kode Organisasi, Username, dan Password ke WhatsApp dan Email staff di atas begitu tombol simpan ditekan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-6 border-t border-ice-200 flex items-center justify-end gap-3">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="inline-flex items-center justify-center h-11 px-5 rounded-md border border-ice-200 text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                        >
                            Daftarkan & Kirim Kredensial Staff
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('staff_password');
            const btnGenerate = document.getElementById('btn-generate-password');
            const btnCopy = document.getElementById('btn-copy-password');
            const copyStatus = document.getElementById('copy-status');

            function generateStrongPassword(length = 10) {
                const uppers = "ABCDEFGHJKLMNPQRSTUVWXYZ";
                const lowers = "abcdefghijkmnpqrstuvwxyz";
                const numbers = "23456789";
                const symbols = "!@#$%^&*";
                const all = uppers + lowers + numbers + symbols;

                let pwd = "";
                pwd += uppers[Math.floor(Math.random() * uppers.length)];
                pwd += lowers[Math.floor(Math.random() * lowers.length)];
                pwd += numbers[Math.floor(Math.random() * numbers.length)];
                pwd += symbols[Math.floor(Math.random() * symbols.length)];

                for (let i = 4; i < length; i++) {
                    pwd += all[Math.floor(Math.random() * all.length)];
                }

                return pwd.split('').sort(() => 0.5 - Math.random()).join('');
            }

            if (btnGenerate && passwordInput) {
                btnGenerate.addEventListener('click', function () {
                    const newPwd = generateStrongPassword(10);
                    passwordInput.value = newPwd;
                    if (copyStatus) {
                        copyStatus.textContent = "Kata sandi acak baru telah dibuat.";
                        copyStatus.classList.remove('hidden');
                        setTimeout(() => copyStatus.classList.add('hidden'), 2500);
                    }
                });
            }

            if (btnCopy && passwordInput) {
                btnCopy.addEventListener('click', function () {
                    if (!passwordInput.value) {
                        const newPwd = generateStrongPassword(10);
                        passwordInput.value = newPwd;
                    }

                    navigator.clipboard.writeText(passwordInput.value).then(() => {
                        if (copyStatus) {
                            copyStatus.textContent = "Kata sandi berhasil disalin ke clipboard.";
                            copyStatus.classList.remove('hidden');
                            setTimeout(() => copyStatus.classList.add('hidden'), 2500);
                        }
                    }).catch(() => {
                        passwordInput.select();
                        document.execCommand('copy');
                        if (copyStatus) {
                            copyStatus.textContent = "Kata sandi disalin.";
                            copyStatus.classList.remove('hidden');
                            setTimeout(() => copyStatus.classList.add('hidden'), 2500);
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>

        