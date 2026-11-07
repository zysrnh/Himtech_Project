<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kasly') }} - Kas Organisasi Transparan</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/icon-kasly.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/icon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/icon-192.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="w-full bg-white border-b border-ice-200 sticky top-0 z-40">
        <div class="max-w-[1120px] mx-auto px-6 h-18 flex items-center justify-between">
            <a href="/" class="inline-flex items-center gap-2">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-10 w-auto">
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-navy-500">
                <a href="#fitur" class="hover:text-navy-900 transition-colors">Fitur</a>
                <span class="text-ice-200">·</span>
                <a href="#cara-kerja" class="hover:text-navy-900 transition-colors">Cara Kerja</a>
                <span class="text-ice-200">·</span>
                <a href="#faq" class="hover:text-navy-900 transition-colors">FAQ</a>
            </nav>

            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center h-12 px-5 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="max-w-[1120px] mx-auto px-6 pt-16 pb-20 md:pt-24 md:pb-28">
            <div class="max-w-3xl">
                <h1 class="font-display font-semibold text-4xl md:text-5xl lg:text-[56px] text-navy-900 leading-[1.15] tracking-tight">
                    Kas organisasi, rapi tanpa buku tulis.
                </h1>
                <p class="mt-6 text-lg md:text-xl text-navy-500 leading-relaxed font-normal">
                    Catat iuran, pantau tunggakan, dan tampilkan laporan transparan untuk seluruh anggota.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login', ['role' => 'anggota']) }}" class="inline-flex items-center justify-center h-12 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors">
                        Masuk sebagai Anggota
                    </a>
                    <a href="{{ route('login', ['role' => 'pengurus']) }}" class="inline-flex items-center justify-center h-12 px-6 rounded-md bg-white border border-ice-200 text-navy-900 font-medium text-sm hover:bg-ice-100 transition-colors">
                        Masuk sebagai Pengurus
                    </a>
                </div>
            </div>
        </section>

        <!-- Pilih Akses Kamu Section -->
        <section id="pilih-akses" class="max-w-[1120px] mx-auto px-6 py-12">
            <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                Pilih akses kamu
            </h2>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Anggota -->
                <div id="login-anggota" class="bg-white rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <h3 class="font-display font-medium text-lg text-navy-900">
                            Anggota
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Login dengan kode organisasi dan ID anggota. Lihat status iuran dan riwayat pembayaranmu.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('login', ['role' => 'anggota']) }}" class="w-full inline-flex items-center justify-center h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors">
                            Masuk sebagai Anggota
                        </a>
                    </div>
                </div>

                <!-- Card Staff dan Bendahara -->
                <div id="login-pengurus" class="bg-white rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <h3 class="font-display font-medium text-lg text-navy-900">
                            Staff dan Bendahara
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Login dengan email dan password. Kelola kas, anggota, dan laporan organisasi.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('login', ['role' => 'pengurus']) }}" class="w-full inline-flex items-center justify-center h-12 px-5 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors">
                            Masuk sebagai Pengurus
                        </a>
                    </div>
                </div>

                <!-- Card Hubungi Kami -->
                <div class="bg-white rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <h3 class="font-display font-medium text-lg text-navy-900">
                            Belum punya organisasi?
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Hubungi admin untuk mendaftarkan organisasimu.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="https://wa.me/?text=Halo%20Admin%20Kasly,%20saya%20ingin%20mendaftarkan%20organisasi%20baru" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center h-12 px-5 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Yang Bisa Dilakukan Section -->
        <section id="fitur" class="max-w-[1120px] mx-auto px-6 py-16">
            <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                Yang bisa dilakukan
            </h2>

            <div class="mt-8 bg-white rounded-lg p-8 shadow-card">
                <div class="divide-y divide-ice-200">
                    <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between">
                        <span class="text-base text-navy-900 font-normal">Iuran otomatis untuk semua anggota aktif</span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-butter-100 text-navy-900">Otomatis</span>
                    </div>
                    <div class="py-4 flex items-center justify-between">
                        <span class="text-base text-navy-900 font-normal">Saldo dan tunggakan dihitung otomatis</span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-ice-100 text-navy-900">Real-time</span>
                    </div>
                    <div class="py-4 flex items-center justify-between">
                        <span class="text-base text-navy-900 font-normal">Laporan bulanan dengan export PDF dan Excel</span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-ice-100 text-navy-900">Laporan</span>
                    </div>
                    <div class="py-4 flex items-center justify-between">
                        <span class="text-base text-navy-900 font-normal">Riwayat perubahan untuk setiap transaksi</span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-butter-100 text-navy-900">Audit</span>
                    </div>
                    <div class="py-4 flex items-center justify-between">
                        <span class="text-base text-navy-900 font-normal">Data terpisah untuk setiap organisasi</span>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-ice-100 text-navy-900">Multi-tenant</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cara Kerja Section -->
        <section id="cara-kerja" class="max-w-[1120px] mx-auto px-6 py-16">
            <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                Cara kerja
            </h2>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-lg text-navy-900">
                        Organisasi didaftarkan
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Admin membuat organisasi dan akun Staff awal.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-lg text-navy-900">
                        Anggota ditambahkan
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Pengurus menambah anggota manual atau lewat CSV. Setiap anggota mendapat ID login.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-lg text-navy-900">
                        Kas dikelola
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Catat transaksi dan pembayaran. Anggota bisa memantau sendiri.
                    </p>
                </div>
            </div>
        </section>

        <!-- Pertanyaan Umum Section -->
        <section id="faq" class="max-w-[1120px] mx-auto px-6 py-16">
            <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                Pertanyaan umum
            </h2>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-base text-navy-900">
                        Apakah anggota perlu email?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Tidak. Anggota login dengan kode organisasi dan ID anggota.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-base text-navy-900">
                        Apa yang terjadi jika pengurus berganti?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Akun lama dinonaktifkan, akun baru dibuat, dan riwayat tetap tersimpan di organisasi.
                    </p>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-card">
                    <h3 class="font-display font-medium text-base text-navy-900">
                        Apakah organisasi saya bisa dipisahkan dari organisasi lain?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Ya. Data setiap organisasi terpisah dan tidak bisa diakses organisasi lain.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white border-t border-ice-200 mt-20">
        <div class="max-w-[1120px] mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-navy-500">
            <div class="flex items-center gap-3">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-6 w-auto opacity-70">
                <span>© {{ date('Y') }} {{ config('app.name', 'Kasly') }}</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#kebijakan" class="hover:text-navy-900 transition-colors">Kebijakan Privasi</a>
                <a href="#syarat" class="hover:text-navy-900 transition-colors">Syarat Penggunaan</a>
                <a href="#kontak" class="hover:text-navy-900 transition-colors">Kontak</a>
            </div>
        </div>
    </footer>

</body>
</html>
