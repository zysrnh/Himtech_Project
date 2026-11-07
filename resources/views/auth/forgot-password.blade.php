<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Kata Sandi - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col justify-between p-4 sm:p-6 relative select-none">

    <!-- Top Navigation Bar -->
    <header class="w-full max-w-[460px] mx-auto flex items-center justify-start pt-2 sm:pt-4 mb-4">
        <a href="{{ route('login') }}" class="neu-back-pill group" title="Kembali ke Halaman Masuk">
            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali ke Login</span>
        </a>
    </header>

    <!-- Center Card: Form Permintaan Reset Password -->
    <main class="w-full max-w-[460px] mx-auto my-auto">
        <div class="neu-login-card">
            <!-- Brand Emblem & Title Header -->
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 border border-white/90 shadow-[4px_4px_12px_rgba(122,163,214,0.45),-3px_-3px_10px_rgba(255,255,255,0.95)] hover:scale-105 transition-transform duration-200">
                    <svg class="w-8 h-8 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>

                <h1 class="font-display font-bold text-2xl sm:text-[26px] text-navy-900 tracking-tight leading-tight">
                    Lupa Kata Sandi
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-navy-500 font-normal leading-relaxed max-w-xs mx-auto">
                    Masukkan kode organisasi dan NIM/Username Anda untuk menerima kode pemulihan via WhatsApp.
                </p>
            </div>

            <!-- Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="neu-alert-success mt-5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="neu-alert-danger mt-5">
                    <svg class="w-4 h-4 text-danger shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Minta Kode OTP -->
            <form action="{{ route('password.request.submit') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <!-- Input Kode Organisasi -->
                <div>
                    <label for="organization_code" class="neu-label flex items-center justify-between">
                        <span>Kode Organisasi</span>
                        <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <input
                            type="text"
                            id="organization_code"
                            name="organization_code"
                            value="{{ old('organization_code') }}"
                            required
                            autofocus
                            placeholder="Contoh: HIMTECH"
                            class="neu-input-field uppercase @error('organization_code') neu-input--error @enderror"
                        >
                    </div>
                    <p class="text-[11px] text-navy-500/80 mt-1.5 pl-1">
                        Kode organisasi tempat akun Anda terdaftar.
                    </p>
                </div>

                <!-- Input NIM / Username / Email -->
                <div>
                    <label for="identity" class="neu-label flex items-center justify-between">
                        <span>NIM / Username / Email</span>
                        <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input
                            type="text"
                            id="identity"
                            name="identity"
                            value="{{ old('identity') }}"
                            required
                            placeholder="Contoh: 20240101 atau zaki"
                            class="neu-input-field @error('identity') neu-input--error @enderror"
                        >
                    </div>
                    <p class="text-[11px] text-navy-500/80 mt-1.5 pl-1">
                        Anggota gunakan NIM kampus, pengurus gunakan username/email.
                    </p>
                </div>

                <!-- Tombol Kirim Kode OTP -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="neu-btn neu-btn--primary w-full h-[50px] justify-center text-[15px] font-semibold group cursor-pointer"
                    >
                        <span>Kirim Kode OTP Pemulihan</span>
                        <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <!-- Tautan Batal / Kembali -->
                <div class="mt-4 text-center">
                    <a href="{{ route('login') }}" class="text-xs text-navy-500 hover:text-navy-900 font-medium">
                        Ingat kata sandi? <span class="font-bold underline underline-offset-2">Masuk sekarang</span>
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="w-full max-w-[460px] mx-auto text-center py-4 text-xs text-navy-500/60 font-medium">
        &copy; {{ date('Y') }} {{ config('app.name', 'Kasly') }}. Platform Tata Kelola Kas Ormawa.
    </footer>

</body>
</html>

