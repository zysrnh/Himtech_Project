<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name', 'Kasly') }} - Kas Organisasi Transparan</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col">

    <!-- Floating Capsule Navbar (Desktop & Tablet >= 768px: NEUMORPHISM.md) -->
    <header class="hidden md:block floating-capsule-wrapper">
        <div class="landing-neu__container w-full">
            <nav class="floating-capsule-nav" aria-label="Navigasi Utama">
                <!-- Left Circular Icon Button with Official Kasly Logo -->
                <a href="/" class="floating-capsule-nav__icon-circle" title="{{ config('app.name', 'Kasly') }}">
                    <img src="{{ asset('logo/icon-kasly.svg') }}" onerror="this.onerror=null; this.src='{{ asset('favicon.ico') }}';" alt="{{ config('app.name', 'Kasly') }}" class="w-6 h-6 object-contain rounded-[6px]">
                </a>

                <!-- Center Navigation Links with Micro-Animations & Smooth Scroll -->
                <div class="floating-capsule-nav__links">
                    <a href="#fitur" class="floating-capsule-nav__link">Fitur</a>
                    <a href="#cara-kerja" class="floating-capsule-nav__link hidden sm:inline-block">Cara Kerja</a>
                    <a href="#faq" class="floating-capsule-nav__link hidden md:inline-block">FAQ</a>
                    <a href="{{ route('ajukan-organisasi') }}" class="floating-capsule-nav__link hidden lg:inline-block">Ajukan Organisasi</a>
                </div>

                <!-- Right Pill Button with Micro-Animations -->
                @auth
                    <a href="{{ route('dashboard') }}" class="floating-capsule-nav__btn">
                        {{ auth()->user()->name ?? (auth()->user()->username ?? 'Dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="floating-capsule-nav__btn">
                        Masuk
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Mobile Top Header (Mobile < 768px: Rapi & Bersih per NEUMORPHISM.md) -->
    <header class="md:hidden w-full pt-4 pb-2 px-4 sm:px-6">
        <div class="landing-neu__container flex items-center justify-start">
            <a href="/" class="flex items-center gap-2.5 group" title="{{ config('app.name', 'Kasly.') }}">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-white/90 shadow-[3px_3px_8px_rgba(122,163,214,0.35),-3px_-3px_8px_rgba(255,255,255,0.9)] group-hover:scale-105 transition-transform">
                    <img src="{{ asset('logo/icon-kasly.svg') }}" onerror="this.onerror=null; this.src='{{ asset('favicon.ico') }}';" alt="{{ config('app.name', 'Kasly.') }}" class="w-5 h-5 object-contain rounded-sm">
                </div>
                <span class="font-display font-bold text-xl text-navy-900 tracking-tight">Kasly.</span>
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="landing-neu__container landing-neu__hero-section">
            <div class="landing-neu__hero-grid">
                <!-- Kolom Kiri: Teks Judul & Deskripsi -->
                <div class="contents lg:block">
                    <h1 class="landing-neu__hero-title font-display font-semibold text-navy-900 tracking-tight order-1">
                        Kas organisasi, rapi tanpa buku tulis.
                    </h1>
                    <p class="landing-neu__hero-desc mt-2 lg:mt-6 text-base sm:text-lg md:text-xl text-navy-500 leading-relaxed font-normal order-3">
                        Catat iuran, pantau tunggakan, dan tampilkan laporan transparan untuk seluruh anggota.
                    </p>
                </div>

                <!-- Kolom Kanan: Pratinjau Kas Digital Model Stacked Card Interaktif -->
                <div class="w-full order-2 lg:order-none">
                    <div class="tabbed-wallet-card">
                        <!-- The 3-Tab Bar across the top: Exact Match to media_1794038706460_3d4b4e58.png (Berdekatan) -->
                        <div class="wallet-tabs-bar" role="tablist">
                            <!-- Tab 1: Kas Cadangan (Left - Deep Midnight) -->
                            <button type="button" class="wallet-tab-btn tab-btn--cadangan" id="tab-cadangan" onclick="switchKasCard('cadangan')" role="tab" aria-selected="false">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Kas Cadangan</span>
                            </button>

                            <!-- Tab 2: Iuran Wajib (Middle - Butter) -->
                            <button type="button" class="wallet-tab-btn tab-btn--iuran" id="tab-iuran" onclick="switchKasCard('iuran')" role="tab" aria-selected="false">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span>Iuran Wajib</span>
                            </button>

                            <!-- Tab 3: Kas Utama (Right Front Tab) -->
                            <button type="button" class="wallet-tab-btn tab-btn--kas is-active" id="tab-kas" onclick="switchKasCard('kas')" role="tab" aria-selected="true">
                                <span id="tab-kas-text">AKTIF</span>
                                <span class="active-dot" id="active-dot"></span>
                            </button>
                        </div>

                        <!-- Main Card Body with Dynamic Content -->
                        <div class="card-main-body theme--kas" id="hero-card-body">
                            <!-- Top row: Brand & Contactless -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="card-text-title font-display font-bold text-xl sm:text-2xl tracking-tight">
                                        Kasly
                                    </span>
                                    <span id="hero-card-tag" class="card-text-title font-display font-medium text-xl sm:text-2xl tracking-tight opacity-85">
                                        Saldo
                                    </span>
                                </div>

                                <!-- Contactless WiFi waves -->
                                <svg class="w-5 h-5 card-text-muted rotate-90" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true">
                                    <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                                    <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                                    <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                                    <line x1="12" y1="20" x2="12.01" y2="20"></line>
                                </svg>
                            </div>

                            <!-- Saldo & EMV Chip -->
                            <div class="mt-5 flex items-end justify-between gap-3">
                                <div>
                                    <span id="hero-card-label" class="card-text-muted text-xs font-medium block">Total Saldo Kas Tersedia</span>
                                    <div id="hero-card-amount" class="card-text-title font-display font-bold text-2xl sm:text-3xl md:text-[34px] mt-1 tabular-nums tracking-tight leading-tight">
                                        Rp 14.850.000
                                    </div>
                                </div>
                                <div class="emv-chip" aria-hidden="true"></div>
                            </div>

                            <!-- Detail Note -->
                            <div id="hero-card-detail" class="card-text-muted text-xs mt-2 font-medium">
                                100% Kas Transparan &bull; Terisolasi Multi-Tenant
                            </div>

                            <!-- Card Number (Masked) -->
                            <div class="card-text-muted font-mono text-sm sm:text-base tracking-[0.18em] mt-4 select-none opacity-90">
                                •••• &nbsp;•••• &nbsp;•••• &nbsp;2026
                            </div>

                            <!-- Bottom row: Holder & Period -->
                            <div class="card-border-line mt-4 pt-3.5 border-t flex items-center justify-between">
                                <div>
                                    <span class="card-text-muted text-[10px] font-semibold uppercase tracking-wider block">Pengelola Kas</span>
                                    <span class="card-text-title font-display font-semibold text-sm mt-0.5 block">Hima Informatika</span>
                                </div>
                                <div class="text-right">
                                    <span class="card-text-muted text-[10px] font-semibold uppercase tracking-wider block">Periode</span>
                                    <span class="card-text-title font-display font-semibold text-sm mt-0.5 block tabular-nums">2026/2027</span>
                                </div>
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
                <!-- Card 1: Anggota (Cutout Notch Referensi image copy 3.png) -->
                <div id="login-anggota" class="cutout-card group">
                    <div>
                        <!-- Top Row: Icon & Masked ID -->
                        <div class="flex items-center justify-between">
                            <div class="neu-card--access-icon neu-card--access-icon--butter">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="font-mono text-xs font-semibold text-navy-500 tracking-[0.1em]">
                                •••• &nbsp;01
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div class="mt-5">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-navy-500 block">Peran Akses</span>
                            <h3 class="font-display font-bold text-xl sm:text-[22px] text-navy-900 mt-0.5">
                                Portal Anggota
                            </h3>
                            <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                                Cukup gunakan <strong>Kode Organisasi</strong> dan <strong>NIM</strong>. Pantau saldo kas, riwayat pembayaran, serta status tagihan pribadi secara mandiri.
                            </p>
                        </div>
                    </div>

                    <!-- Bottom Info & Cutout Nest -->
                    <div class="mt-6 max-w-[55%]">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-navy-500 block">Metode</span>
                        <span class="text-xs font-bold text-navy-900 block">NIM & Kode Ormawa</span>
                    </div>

                    <!-- Cutout Notch Nest at Bottom Right -->
                    <div class="cutout-nest">
                        <svg class="cutout-fillet-top" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M16 0 C16 8.83656 8.83656 16 0 16 H16 V0 Z" fill="#DDEBFC"/>
                        </svg>
                        <svg class="cutout-fillet-left" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M0 16 C8.83656 16 16 8.83656 16 0 V16 H0 Z" fill="#DDEBFC"/>
                        </svg>
                        <a href="{{ route('login') }}" class="cutout-pill-btn group-hover:scale-105">
                            <span>Masuk</span>
                            <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2: Pengurus (Cutout Notch Referensi image copy 3.png) -->
                <div id="login-pengurus" class="cutout-card group">
                    <div>
                        <!-- Top Row: Icon & Masked ID -->
                        <div class="flex items-center justify-between">
                            <div class="neu-card--access-icon neu-card--access-icon--ice">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <span class="font-mono text-xs font-semibold text-navy-500 tracking-[0.1em]">
                                •••• &nbsp;02
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div class="mt-5">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-navy-500 block">Peran Akses</span>
                            <h3 class="font-display font-bold text-xl sm:text-[22px] text-navy-900 mt-0.5">
                                Staff & Bendahara
                            </h3>
                            <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                                Login dengan <strong>Kode Organisasi</strong> dan <strong>Username</strong>. Catat mutasi kas, upload bukti nota, buat tagihan, serta kelola data anggota.
                            </p>
                        </div>
                    </div>

                    <!-- Bottom Info & Cutout Nest -->
                    <div class="mt-6 max-w-[55%]">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-navy-500 block">Metode</span>
                        <span class="text-xs font-bold text-navy-900 block">Akun Pengurus</span>
                    </div>

                    <!-- Cutout Notch Nest at Bottom Right -->
                    <div class="cutout-nest">
                        <svg class="cutout-fillet-top" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M16 0 C16 8.83656 8.83656 16 0 16 H16 V0 Z" fill="#DDEBFC"/>
                        </svg>
                        <svg class="cutout-fillet-left" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M0 16 C8.83656 16 16 8.83656 16 0 V16 H0 Z" fill="#DDEBFC"/>
                        </svg>
                        <a href="{{ route('login') }}" class="cutout-pill-btn group-hover:scale-105">
                            <span>Masuk</span>
                            <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3: Ajukan Organisasi (Cutout Notch Referensi image copy 3.png) -->
                <div class="cutout-card group">
                    <div>
                        <!-- Top Row: Icon & Masked ID -->
                        <div class="flex items-center justify-between">
                            <div class="neu-card--access-icon neu-card--access-icon--navy">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <span class="font-mono text-xs font-semibold text-navy-500 tracking-[0.1em]">
                                •••• &nbsp;03
                            </span>
                        </div>

                        <!-- Title & Description -->
                        <div class="mt-5">
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-navy-500 block">Peran Akses</span>
                            <h3 class="font-display font-bold text-xl sm:text-[22px] text-navy-900 mt-0.5">
                                Ajukan Organisasi
                            </h3>
                            <p class="mt-2.5 text-sm text-navy-500 leading-relaxed">
                                Organisasi kamu belum terdaftar? Ajukan pendaftaran online mandiri untuk mendapatkan database kas dan akun Staff pengelola.
                            </p>
                        </div>
                    </div>

                    <!-- Bottom Info & Cutout Nest -->
                    <div class="mt-6 max-w-[55%]">
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-navy-500 block">Pendaftaran</span>
                        <span class="text-xs font-bold text-navy-900 block">Ormawa Baru</span>
                    </div>

                    <!-- Cutout Notch Nest at Bottom Right -->
                    <div class="cutout-nest">
                        <svg class="cutout-fillet-top" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M16 0 C16 8.83656 8.83656 16 0 16 H16 V0 Z" fill="#DDEBFC"/>
                        </svg>
                        <svg class="cutout-fillet-left" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M0 16 C8.83656 16 16 8.83656 16 0 V16 H0 Z" fill="#DDEBFC"/>
                        </svg>
                        <a href="{{ route('ajukan-organisasi') }}" class="cutout-pill-btn group-hover:scale-105">
                            <span>Ajukan</span>
                            <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
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

    <!-- Mobile Bottom Toolbar (HP & Tablet < 768px per NEUMORPHISM.md section 4 & 7) -->
    <nav class="neu-bottomnav md:hidden" data-nav aria-label="Navigasi Bawah">
        <!-- 1. Beranda -->
        <a href="#" class="neu-bottomnav__item is-active" data-section="top" aria-label="Beranda" aria-current="page">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Beranda</span>
        </a>

        <!-- 2. Fitur -->
        <a href="#fitur" class="neu-bottomnav__item" data-section="fitur" aria-label="Fitur">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span>Fitur</span>
        </a>

        <!-- 3. Cara Kerja / Alur -->
        <a href="#cara-kerja" class="neu-bottomnav__item" data-section="cara-kerja" aria-label="Cara Kerja">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span>Alur</span>
        </a>

        <!-- 4. FAQ -->
        <a href="#faq" class="neu-bottomnav__item" data-section="faq" aria-label="FAQ">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>FAQ</span>
        </a>

        <!-- 5. Masuk / Akun (Primary CTA Button) -->
        @auth
            <a href="{{ route('dashboard') }}" class="neu-bottomnav__item neu-bottomnav__item--cta" aria-label="Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Akun</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="neu-bottomnav__item neu-bottomnav__item--cta" aria-label="Masuk">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    <script>
        const kasThemes = {
            kas: {
                themeClass: 'theme--kas',
                tag: 'Saldo',
                label: 'Total Saldo Kas Tersedia',
                amount: 'Rp 14.850.000',
                detail: '100% Kas Transparan • Terisolasi Multi-Tenant',
                filletColor: '#34506E',
                kasTabBg: '#34506E',
                kasTabColor: '#FFFFFF',
                kasLabel: 'AKTIF'
            },
            iuran: {
                themeClass: 'theme--iuran',
                tag: 'Iuran',
                label: 'Total Iuran Terkumpul',
                amount: 'Rp 18.200.000',
                detail: '142 Anggota Lunas (94%) • 8 Tagihan Berjalan',
                filletColor: '#FFF9D9',
                kasTabBg: '#FFF9D9',
                kasTabColor: '#2F4862',
                kasLabel: 'Kas Utama'
            },
            cadangan: {
                themeClass: 'theme--cadangan',
                tag: 'Cadangan',
                label: 'Total Kas Cadangan',
                amount: 'Rp 3.350.000',
                detail: 'Dana Darurat & Alokasi Khusus Ormawa',
                filletColor: '#182635',
                kasTabBg: '#182635',
                kasTabColor: '#FFFFFF',
                kasLabel: 'Kas Utama'
            }
        };

        function switchKasCard(type) {
            const data = kasThemes[type];
            if (!data) return;

            // 1. Reset all tab buttons
            document.querySelectorAll('.wallet-tab-btn').forEach(btn => {
                btn.classList.remove('is-active');
                btn.setAttribute('aria-selected', 'false');
                const dot = btn.querySelector('.active-dot');
                if (dot) dot.remove();
            });

            // 2. Activate the clicked tab
            const activeBtn = document.getElementById(`tab-${type}`);
            if (activeBtn) {
                activeBtn.classList.add('is-active');
                activeBtn.setAttribute('aria-selected', 'true');
            }

            // 3. Update the SVG fillet fill to match the card header color
            const filletSvg = document.getElementById('tab-fillet');
            if (filletSvg) {
                const path = filletSvg.querySelector('path');
                if (path) path.setAttribute('fill', data.filletColor);
            }

            // 4. Update the right-hand shoulder tab
            const kasTab = document.getElementById('tab-kas');
            const kasTabText = document.getElementById('tab-kas-text');
            if (kasTab) {
                kasTab.style.background = data.kasTabBg;
                kasTab.style.color = data.kasTabColor;
            }

            if (type === 'kas') {
                if (kasTabText) kasTabText.textContent = 'AKTIF';
                if (kasTab && !kasTab.querySelector('.active-dot')) {
                    const dot = document.createElement('span');
                    dot.className = 'active-dot';
                    dot.id = 'active-dot';
                    kasTab.appendChild(dot);
                }
            } else {
                if (kasTabText) kasTabText.textContent = 'Kas Utama';
                if (activeBtn && !activeBtn.querySelector('.active-dot')) {
                    const dot = document.createElement('span');
                    dot.className = 'active-dot';
                    activeBtn.appendChild(dot);
                }
            }

            // 5. Update Card Body content & theme with smooth transition
            const cardBody = document.getElementById('hero-card-body');
            if (cardBody) {
                cardBody.style.opacity = '0.7';
                setTimeout(() => {
                    cardBody.className = 'card-main-body ' + data.themeClass;
                    const tagEl = document.getElementById('hero-card-tag');
                    const labelEl = document.getElementById('hero-card-label');
                    const amountEl = document.getElementById('hero-card-amount');
                    const detailEl = document.getElementById('hero-card-detail');

                    if (tagEl) tagEl.textContent = data.tag;
                    if (labelEl) labelEl.textContent = data.label;
                    if (amountEl) amountEl.textContent = data.amount;
                    if (detailEl) detailEl.textContent = data.detail;

                    cardBody.style.opacity = '1';
                }, 100);
            }
        }

        // Global Micro-Animations: Press reaction on buttons & smooth nav interactions
        document.addEventListener('DOMContentLoaded', () => {
            const interactiveSelectors = [
                '.floating-capsule-nav__link',
                '.floating-capsule-nav__btn',
                '.floating-capsule-nav__icon-circle',
                '.neu-bottomnav__item',
                '.neu-btn',
                '.wallet-tab-btn',
                '.cutout-pill-btn',
                '.neu-card--access-icon'
            ];

            document.querySelectorAll(interactiveSelectors.join(', ')).forEach(el => {
                el.addEventListener('mousedown', () => el.classList.add('is-pressed'));
                el.addEventListener('mouseup', () => el.classList.remove('is-pressed'));
                el.addEventListener('mouseleave', () => el.classList.remove('is-pressed'));
                el.addEventListener('touchstart', () => el.classList.add('is-pressed'), { passive: true });
                el.addEventListener('touchend', () => el.classList.remove('is-pressed'), { passive: true });
            });

            // Enhanced Smooth Scrolling with offset & target animation
            function handleSmoothScroll(targetId, activeTrigger) {
                if (targetId === 'top' || targetId === '' || targetId === '#') {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                    updateActiveNav('top');
                    return;
                }

                const targetEl = document.getElementById(targetId);
                if (!targetEl) return;

                // Hitung offset navbar floating secara presisi (desktop fixed navbar, mobile static header)
                const isMobile = window.innerWidth < 768;
                const nav = document.querySelector('.floating-capsule-wrapper');
                const offset = isMobile ? 20 : (nav ? nav.offsetHeight + 18 : 96);
                const targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;

                window.scrollTo({
                    top: Math.max(0, targetPosition),
                    behavior: 'smooth'
                });

                updateActiveNav(targetId);

                // Micro animation efek pulse pada section yang dituju
                targetEl.classList.remove('section-scroll-focused');
                void targetEl.offsetWidth; // trigger reflow
                targetEl.classList.add('section-scroll-focused');
            }

            function updateActiveNav(activeId) {
                // Update Desktop Navbar
                document.querySelectorAll('.floating-capsule-nav__link').forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === `#${activeId}`) {
                        link.classList.add('is-active');
                    } else {
                        link.classList.remove('is-active');
                    }
                });

                // Update Mobile Bottom Toolbar
                document.querySelectorAll('.neu-bottomnav__item').forEach(item => {
                    const sec = item.getAttribute('data-section');
                    if (sec === activeId) {
                        item.classList.add('is-active');
                        item.setAttribute('aria-current', 'page');
                    } else {
                        item.classList.remove('is-active');
                        item.removeAttribute('aria-current');
                    }
                });
            }

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    const targetId = href === '#' ? (this.getAttribute('data-section') || 'top') : href.replace('#', '');
                    if (targetId) {
                        e.preventDefault();
                        handleSmoothScroll(targetId, this);
                    }
                });
            });

            // Scroll Spy: otomatis sorot tautan navigasi sesuai posisi scroll halaman
            const sections = ['fitur', 'cara-kerja', 'faq', 'pilih-akses'].map(id => document.getElementById(id)).filter(Boolean);
            window.addEventListener('scroll', () => {
                const scrollPos = window.pageYOffset;

                // Jika di area paling atas (Hero)
                if (scrollPos < 180) {
                    updateActiveNav('top');
                    return;
                }

                const offsetCheck = scrollPos + 180;
                sections.forEach(sec => {
                    const top = sec.offsetTop;
                    const height = sec.offsetHeight;
                    if (offsetCheck >= top && offsetCheck < top + height) {
                        updateActiveNav(sec.id);
                    }
                });
            }, { passive: true });
        });
    </script>
</body>
</html>
