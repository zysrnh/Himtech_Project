<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP & Kata Sandi Baru - {{ config('app.name', 'Kasly') }}</title>

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
        <a href="{{ route('password.request') }}" class="neu-back-pill group" title="Kembali">
            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Ubah Data Akun</span>
        </a>
    </header>

    <!-- Center Card: Form Verifikasi OTP & Password Baru -->
    <main class="w-full max-w-[460px] mx-auto my-auto">
        <div class="neu-login-card">
            <!-- Brand Emblem & Title Header -->
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 border border-white/90 shadow-[4px_4px_12px_rgba(122,163,214,0.45),-3px_-3px_10px_rgba(255,255,255,0.95)] hover:scale-105 transition-transform duration-200">
                    <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>

                <h1 class="font-display font-bold text-2xl sm:text-[26px] text-navy-900 tracking-tight leading-tight">
                    Verifikasi OTP
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-navy-500 font-normal leading-relaxed max-w-xs mx-auto">
                    Akun: <strong class="text-navy-900">{{ $name }}</strong>
                    @if ($phone)
                        <span class="block text-[11px] text-navy-400 mt-0.5">Kode dikirim ke WA: {{ substr($phone, 0, 4) }}****{{ substr($phone, -3) }}</span>
                    @endif
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

            <!-- Hint Demo Praktis Saat Pengujian / Juri Lomba -->
            @if ($otpHint)
                <div class="mt-4 p-3 rounded-xl bg-butter-100/60 border border-amber-200 text-amber-900 text-xs flex items-center justify-between">
                    <span class="font-medium">Kode OTP Pengujian: <strong class="font-mono text-sm tracking-widest text-navy-900">{{ $otpHint }}</strong></span>
                    <button type="button" onclick="document.getElementById('otp').value='{{ $otpHint }}'" class="text-[11px] font-bold text-navy-700 underline cursor-pointer">Pasang Otomatis</button>
                </div>
            @endif

            <!-- Form Verifikasi OTP & Password Baru -->
            <form action="{{ route('password.verify.submit') }}" method="POST" class="mt-5 space-y-4">
                @csrf

                <!-- Input Kode OTP -->
                <div>
                    <label for="otp" class="neu-label flex items-center justify-between">
                        <span>Kode OTP (6 Digit)</span>
                        <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input
                            type="text"
                            id="otp"
                            name="otp"
                            value="{{ old('otp') }}"
                            required
                            maxlength="6"
                            placeholder="Contoh: 123456"
                            class="neu-input-field font-mono tracking-widest text-base text-center @error('otp') neu-input--error @enderror"
                        >
                    </div>
                </div>

                <!-- Input Kata Sandi Baru -->
                <div>
                    <label for="password" class="neu-label flex items-center justify-between">
                        <span>Kata Sandi Baru</span>
                        <span class="text-[11px] text-navy-500/70 font-normal">Min. 6 Karakter</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="Minimal 6 karakter"
                            class="neu-input-field @error('password') neu-input--error @enderror"
                        >
                    </div>
                </div>

                <!-- Input Konfirmasi Kata Sandi Baru -->
                <div>
                    <label for="password_confirmation" class="neu-label flex items-center justify-between">
                        <span>Ulangi Kata Sandi Baru</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            placeholder="Ulangi kata sandi baru"
                            class="neu-input-field"
                        >
                    </div>
                </div>

                <!-- Tombol Submit Simpan Password Baru -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="neu-btn neu-btn--primary w-full h-[50px] justify-center text-[15px] font-semibold group cursor-pointer"
                    >
                        <span>Simpan Kata Sandi Baru</span>
                        <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
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
