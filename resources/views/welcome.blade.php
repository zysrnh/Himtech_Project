<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Kasly') }} - Kas Organisasi Transparan</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="w-full bg-white border-b border-ice-200 sticky top-0 z-40">
        <div class="landing-neu__container h-18 flex items-center justify-between">
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

            <div class="flex items-center gap-3">
                <a href="{{ route('ajukan-organisasi') }}" class="inline-flex items-center justify-center h-10 px-4 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-sm hover:bg-ice-100 transition-colors">
                    Ajukan Organisasi
                </a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center h-10 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm">
                    Masuk
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="landing-neu__container landing-neu__hero-section">
            <div class="landing-neu__hero-grid">
                <!-- Kolom Kiri: Teks & Tombol Primer -->
                <div>
                    <h1 class="landing-neu__hero-title font-display font-semibold text-navy-900 tracking-tight">
                        Kas organisasi, rapi tanpa buku tulis.
                    </h1>
                    <p class="landing-neu__hero-desc mt-6 text-base sm:text-lg md:text-xl text-navy-500 leading-relaxed font-normal">
                        Catat iuran, pantau tunggakan, dan tampilkan laporan transparan untuk seluruh anggota.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="#pilih-akses" class="neu-btn neu-btn--primary">
                            Masuk ke Sistem Kasly
                        </a>
                    </div>
                </div>

                <!-- Kolom Kanan: Pratinjau Kas Neumorphic (Mengisi penuh kolom) -->
                <div class="w-full">
                    <div class="neu-card neu-card--preview">
                        <div class="pb-4 border-b border-ice-200/60">
                            <span class="text-xs font-semibold text-navy-500 uppercase tracking-wider block">Ormawa Terverifikasi</span>
                            <h4 class="font-display font-semibold text-base sm:text-lg text-navy-900">BEM Fasilkom 2026/2027</h4>
                        </div>

                        <!-- Ringkasan Saldo Besar Tabular -->
                        <div class="mt-6">
                            <span class="text-xs sm:text-sm font-medium text-navy-500">Total Saldo Kas Tersedia</span>
                            <div class="font-display font-semibold text-3xl sm:text-4xl lg:text-[44px] text-navy-900 mt-1 tabular-nums tracking-tight">
                                Rp 14.850.000
                            </div>
                        </div>

                        <!-- Panel Inset (Cekung) Aliran Kas -->
                        <div class="mt-6 grid grid-cols-2 gap-3 sm:gap-4">
                            <div class="neu-inset-panel">
                                <span class="text-xs text-navy-500 block">Total Pemasukan</span>
                                <span class="font-display font-semibold text-sm sm:text-lg text-navy-900 mt-0.5 block tabular-nums">+Rp 18.200.000</span>
                            </div>
                            <div class="neu-inset-panel">
                                <span class="text-xs text-navy-500 block">Total Pengeluaran</span>
                                <span class="font-display font-semibold text-sm sm:text-lg text-navy-900 mt-0.5 block tabular-nums">-Rp 3.350.000</span>
                            </div>
                        </div>

                        <!-- Status Kepatuhan & Progress Bar Inset -->
                        <div class="mt-6 pt-4 border-t border-ice-200/60">
                            <div class="flex items-center justify-between text-xs font-medium text-navy-500 mb-2">
                                <span>Kepatuhan Iuran Bulanan</span>
                                <span class="text-navy-900 font-semibold">94% Lunas (142/150)</span>
                            </div>
                            <div class="w-full h-3 rounded-full bg-ice-100 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.55),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-gradient-to-r from-ice-400 to-navy-500" style="width: 94%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pilih Akses Kamu Section -->
        <section id="pilih-akses" class="landing-neu__container landing-neu__access-section">
            <div class="mb-8">
                <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                    Pilih akses kamu
                </h2>
                <p class="text-sm text-navy-500 mt-1">Masuk sesuai peran kamu di dalam sistem pengelolaan kas organisasi.</p>
            </div>

            <div class="access-neu grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-7">
                <!-- Card 1: Anggota -->
                <div id="login-anggota" class="neu-card neu-card--access flex flex-col justify-between group">
                    <div>
                        <div class="mb-5">
                            <div class="neu-card--access-icon neu-card--access-icon--butter">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-xl text-navy-900">
                            Portal Anggota
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Cukup gunakan <strong>Kode Organisasi</strong> dan <strong>NIM</strong>. Pantau saldo kas, riwayat pembayaran, serta status tagihan pribadi secara mandiri.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-ice-200/60">
                        <a href="{{ route('login') }}" class="neu-btn w-full font-semibold text-navy-900 flex items-center justify-center gap-2">
                            <span>Masuk sebagai Anggota</span>
                            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Pengurus -->
                <div id="login-pengurus" class="neu-card neu-card--access flex flex-col justify-between group">
                    <div>
                        <div class="mb-5">
                            <div class="neu-card--access-icon neu-card--access-icon--ice">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-xl text-navy-900">
                            Staff & Bendahara
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Login dengan <strong>Kode Organisasi</strong> dan <strong>Username</strong>. Catat mutasi kas, upload bukti nota, buat tagihan, serta kelola anggota.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-ice-200/60">
                        <a href="{{ route('login') }}" class="neu-btn w-full font-semibold text-navy-900 flex items-center justify-center gap-2">
                            <span>Masuk sebagai Pengurus</span>
                            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Ajukan Organisasi -->
                <div class="neu-card neu-card--access flex flex-col justify-between group">
                    <div>
                        <div class="mb-5">
                            <div class="neu-card--access-icon neu-card--access-icon--navy">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-xl text-navy-900">
                            Ajukan Organisasi
                        </h3>
                        <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                            Organisasi atau himpunan kamu belum terdaftar? Ajukan pendaftaran online untuk mendapatkan database kas mandiri dan akun Staff pengelola awal.
                        </p>
                    </div>
                    <div class="mt-8 pt-4 border-t border-ice-200/60">
                        <a href="{{ route('ajukan-organisasi') }}" class="neu-btn w-full font-semibold text-navy-900 flex items-center justify-center gap-2">
                            <span>Ajukan Sekarang</span>
                            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Solusi & Keunggulan Utama Kasly Section -->
        <section id="fitur" class="landing-neu__container py-12 md:py-16">
            <div class="mb-8">
                <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                    Keunggulan sistem kas Kasly
                </h2>
                <p class="mt-1 text-sm sm:text-base text-navy-500 max-w-2xl">
                    Meningkatkan kedisiplinan bayar kas dan menjaga keterbukaan penggunaan dana bersama ormawa.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 1. Buku Kas Anti-Manipulasi -->
                <div class="neu-card neu-card--feature group">
                    <div class="neu-card--feature-accent neu-card--feature-accent--ice"></div>
                    <div class="relative">
                        <div class="mb-5">
                            <div class="neu-card--feature-icon">
                                <svg class="w-5 h-5 text-ice-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-lg text-navy-900">
                            Buku Kas Terkunci
                        </h3>
                        <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                            Setiap mutasi kas terkunci dan dilengkapi nota fisik serta jejak audit otomatis yang tidak dapat diubah sepihak.
                        </p>
                    </div>
                </div>

                <!-- 2. Evaluasi Disiplin Waktu -->
                <div class="neu-card neu-card--feature group">
                    <div class="neu-card--feature-accent neu-card--feature-accent--butter"></div>
                    <div class="relative">
                        <div class="mb-5">
                            <div class="neu-card--feature-icon">
                                <svg class="w-5 h-5 text-ice-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-lg text-navy-900">
                            Disiplin Jatuh Tempo
                        </h3>
                        <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                            Tagihan kas bulanan terbit terjadwal. Status ketepatan waktu tercatat transparan per anggota demi kedisiplinan.
                        </p>
                    </div>
                </div>

                <!-- 3. Notifikasi WhatsApp & Email -->
                <div class="neu-card neu-card--feature group">
                    <div class="neu-card--feature-accent neu-card--feature-accent--navy"></div>
                    <div class="relative">
                        <div class="mb-5">
                            <div class="neu-card--feature-icon">
                                <svg class="w-5 h-5 text-ice-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-lg text-navy-900">
                            Pengingat WhatsApp
                        </h3>
                        <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                            Terintegrasi gateway WhatsApp Fonnte dan email notifikasi untuk mengirimkan rincian akun serta tagihan berkala.
                        </p>
                    </div>
                </div>

                <!-- 4. Akses Mandiri Multi-Tenant -->
                <div class="neu-card neu-card--feature group">
                    <div class="neu-card--feature-accent neu-card--feature-accent--ice"></div>
                    <div class="relative">
                        <div class="mb-5">
                            <div class="neu-card--feature-icon">
                                <svg class="w-5 h-5 text-ice-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                        </div>
                        <h3 class="font-display font-semibold text-lg text-navy-900">
                            Portal Anggota Mandiri
                        </h3>
                        <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                            Anggota dapat memeriksa riwayat iuran dan saldo kas ormawa sendiri tanpa perlu menagih rekap manual ke bendahara.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cara Kerja Section -->
        <section id="cara-kerja" class="landing-neu__container py-12 md:py-16">
            <div class="mb-8">
                <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                    Cara kerja sistem Kasly
                </h2>
                <p class="text-sm text-navy-500 mt-1">Tiga langkah praktis memulai transparansi kas di organisasi kamu.</p>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Langkah 1 -->
                <div class="neu-card neu-card--step group">
                    <div class="neu-card--step-number neu-card--step-number--navy">
                        1
                    </div>
                    <h3 class="font-display font-semibold text-xl text-navy-900 mt-5">
                        Organisasi Didaftarkan
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Organisasi didaftarkan ke sistem dan otomatis mendapatkan <strong>Kode Organisasi</strong> unik, kategori kas default, serta akun Staff awal.
                    </p>
                    <div class="neu-card--step-progress mt-6">
                        <div class="neu-card--step-progress-fill" style="width: 33%"></div>
                    </div>
                </div>

                <!-- Langkah 2 -->
                <div class="neu-card neu-card--step group">
                    <div class="neu-card--step-number neu-card--step-number--ice">
                        2
                    </div>
                    <h3 class="font-display font-semibold text-xl text-navy-900 mt-5">
                        Anggota Ditambahkan
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Pengurus mendaftarkan anggota ormawa secara manual atau impor data. Setiap anggota langsung memiliki akses masuk menggunakan nomor identitas/NIM.
                    </p>
                    <div class="neu-card--step-progress mt-6">
                        <div class="neu-card--step-progress-fill" style="width: 66%"></div>
                    </div>
                </div>

                <!-- Langkah 3 -->
                <div class="neu-card neu-card--step group">
                    <div class="neu-card--step-number neu-card--step-number--butter">
                        3
                    </div>
                    <h3 class="font-display font-semibold text-xl text-navy-900 mt-5">
                        Kas Dikelola Transparan
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Tagihan kas berkala terbit otomatis. Pengurus mencatat setoran dan pengeluaran dengan bukti nota, anggota memantau saldo secara real-time.
                    </p>
                    <div class="neu-card--step-progress mt-6">
                        <div class="neu-card--step-progress-fill" style="width: 100%"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pertanyaan Umum Section -->
        <section id="faq" class="landing-neu__container py-12 md:py-16">
            <div class="mb-8">
                <h2 class="font-display font-semibold text-2xl md:text-[28px] text-navy-900">
                    Pertanyaan umum
                </h2>
                <p class="text-sm text-navy-500 mt-1">Jawaban atas pertanyaan seputar penggunaan sistem Kasly.</p>
            </div>

            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- FAQ 1 -->
                <div class="neu-card neu-card--faq group">
                    <div class="neu-card--faq-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display font-semibold text-base md:text-lg text-navy-900 mt-4">
                        Apakah anggota wajib memiliki email?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Tidak. Anggota cukup memasukkan <strong>Kode Organisasi</strong> dan <strong>NIM</strong> resmi mereka untuk langsung masuk dan memeriksa saldo serta tagihan.
                    </p>
                </div>

                <!-- FAQ 2 -->
                <div class="neu-card neu-card--faq group">
                    <div class="neu-card--faq-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display font-semibold text-base md:text-lg text-navy-900 mt-4">
                        Bagaimana jika terjadi pergantian pengurus?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Super Admin atau ketua ormawa dapat menonaktifkan akun pengurus lama dan menerbitkan akun pengurus baru. Seluruh arsip transaksi kas masa lalu tetap utuh.
                    </p>
                </div>

                <!-- FAQ 3 -->
                <div class="neu-card neu-card--faq group">
                    <div class="neu-card--faq-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-display font-semibold text-base md:text-lg text-navy-900 mt-4">
                        Apakah data organisasi aman & tidak tertukar?
                    </h3>
                    <p class="mt-3 text-sm text-navy-500 leading-relaxed">
                        Sangat aman. Setiap organisasi dipisahkan melalui isolasi multi-tenant di basis data. Anggota ormawa A tidak akan pernah bisa melihat data kas ormawa B.
                    </p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="w-full bg-white/80 border-t border-ice-200 mt-16">
        <div class="landing-neu__container py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-navy-500">
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
