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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="organizations" />

    <!-- Main Content Area: Form Tambah Organisasi Baru -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen md:h-screen overflow-y-auto custom-scrollbar pb-28 md:pb-8">

        <main class="p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">

            <!-- Top Header & Tombol Kembali -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">
                        Tambah Organisasi Baru
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Daftarkan entitas ormawa baru dan siapkan akun Staff/Bendahara pertamanya.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-white hover:bg-ice-100 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.15),-2px_-2px_6px_#ffffff] transition-all"
                    >
                        <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Daftar Organisasi</span>
                    </a>
                </div>
            </div>

            <!-- Error Global -->
            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-white/95 border border-danger/30 text-danger text-sm font-medium shadow-[4px_4px_16px_rgba(192,82,74,0.15),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-danger/10 text-danger flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">!</div>
                        <span class="text-xs sm:text-sm font-medium">Ada kesalahan saat pengisian formulir. Silakan periksa kolom yang ditandai merah di bawah.</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-danger hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- Card Formulir Pendaftaran Neumorphic -->
            <div class="bg-white/90 rounded-[28px] p-6 sm:p-8 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-6">
                <form action="{{ route('admin.organizations.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Kolom Kiri: Data Entitas Organisasi -->
                        <div class="space-y-5">
                            <div class="pb-3 border-b border-ice-200/70">
                                <h2 class="font-display font-bold text-base text-navy-900">
                                    1. Informasi Organisasi (Tenant)
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Identitas himpunan atau unit kegiatan mahasiswa.
                                </p>
                            </div>

                            <div>
                                <label for="name" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                    Nama Organisasi <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Contoh: Himpunan Mahasiswa Teknologi"
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                    Kode Organisasi (Huruf Kapital, Tanpa Spasi) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    value="{{ old('code') }}"
                                    required
                                    placeholder="Contoh: HIMTECH"
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-mono font-bold uppercase focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                                <p class="mt-1 text-[11px] text-navy-500 leading-relaxed">
                                    Kode unik ini akan digunakan oleh seluruh pengurus dan anggota untuk login.
                                </p>
                                @error('code')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                    Deskripsi Singkat (Opsional)
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    placeholder="Deskripsi singkat kegiatan, visi ormawa, atau profil singkat..."
                                    class="w-full p-3.5 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 resize-none leading-relaxed transition-all"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan: Data Akun Pengurus / Staff Awal -->
                        <div class="space-y-5">
                            <div class="pb-3 border-b border-ice-200/70">
                                <h2 class="font-display font-bold text-base text-navy-900">
                                    2. Akun Staff / Bendahara Awal
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Kredensial login untuk pengurus yang akan mengelola kas ormawa.
                                </p>
                            </div>

                            <div>
                                <label for="staff_name" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                    Nama Lengkap Staff Awal <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="staff_name"
                                    name="staff_name"
                                    value="{{ old('staff_name') }}"
                                    required
                                    placeholder="Contoh: Muhammad Zaki"
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                                @error('staff_name')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_username" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                    Username Staff (Untuk Login) <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="staff_username"
                                    name="staff_username"
                                    value="{{ old('staff_username') }}"
                                    required
                                    placeholder="Contoh: zaki atau bendahara_himtech"
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                                @error('staff_username')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="staff_email" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                        Email Staff <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="email"
                                        id="staff_email"
                                        name="staff_email"
                                        value="{{ old('staff_email') }}"
                                        required
                                        placeholder="nama@email.com"
                                        class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                    >
                                    @error('staff_email')
                                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="staff_phone" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                        Nomor WhatsApp <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="staff_phone"
                                        name="staff_phone"
                                        value="{{ old('staff_phone') }}"
                                        required
                                        placeholder="081234567890"
                                        class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-mono font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                    >
                                    @error('staff_phone')
                                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="staff_password" class="text-xs font-semibold text-navy-900">
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
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-mono focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                                <p id="copy-status" class="mt-1 text-xs text-emerald-700 hidden font-semibold">
                                    Kata sandi berhasil disalin ke clipboard.
                                </p>
                                @error('staff_password')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/70 text-xs text-navy-500 space-y-1 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15)]">
                                <p class="font-bold text-navy-900 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-navy-900"></span>
                                    <span>Pengiriman Kredensial Otomatis:</span>
                                </p>
                                <p class="text-[11px] leading-relaxed">Sistem akan langsung mengirimkan Kode Organisasi, Username, dan Password ke WhatsApp dan Email staff di atas begitu tombol simpan ditekan.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="pt-4 border-t border-ice-200/70 flex items-center justify-end gap-3">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="h-11 px-5 rounded-xl bg-ice-100 text-navy-700 font-semibold text-xs hover:bg-ice-200 transition-colors flex items-center justify-center cursor-pointer border border-ice-200/70"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            class="h-11 px-6 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Daftarkan & Kirim Kredensial Staff</span>
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>

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
