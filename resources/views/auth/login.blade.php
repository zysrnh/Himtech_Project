<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Akun - {{ config('app.name', 'Kasly') }}</title>

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
        <a href="/" class="neu-back-pill group" title="Kembali ke Beranda">
            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Beranda</span>
        </a>
    </header>

    <!-- Center Login Card -->
    <main class="w-full max-w-[460px] mx-auto my-auto">
        <div class="neu-login-card">
            <!-- Brand Emblem & Title Header -->
            <div class="text-center">
                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto mb-4 border border-white/90 shadow-[4px_4px_12px_rgba(122,163,214,0.45),-3px_-3px_10px_rgba(255,255,255,0.95)] hover:scale-105 transition-transform duration-200">
                    <img src="{{ asset('logo/icon-kasly.svg') }}" onerror="this.onerror=null; this.src='{{ asset('favicon.ico') }}';" alt="{{ config('app.name', 'Kasly') }}" class="w-8 h-8 object-contain rounded-md">
                </div>

                <h1 class="font-display font-bold text-2xl sm:text-[26px] text-navy-900 tracking-tight leading-tight">
                    Masuk Akun
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-navy-500 font-normal leading-relaxed max-w-xs mx-auto">
                    Satu pintu masuk untuk seluruh pengurus dan civitas ormawa.
                </p>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="neu-alert-success mt-5">
                    <svg class="w-4 h-4 text-navy-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

            <!-- Form Login Tunggal -->
            <form action="{{ route('login.submit') }}" method="POST" class="mt-6 space-y-5">
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
                        Masukkan kode ormawa yang terdaftar di Kasly.
                    </p>
                </div>

                <!-- Input Username / NIM -->
                <div>
                    <label for="username" class="neu-label flex items-center justify-between">
                        <span>Username / NIM</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            placeholder="Contoh: zaki atau 20240101"
                            class="neu-input-field @error('username') neu-input--error @enderror"
                        >
                    </div>
                    <p class="text-[11px] text-navy-500/80 mt-1.5 pl-1">
                        Staff menggunakan username/email, mahasiswa menggunakan NIM.
                    </p>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="neu-label flex items-center justify-between">
                        <span>Kata Sandi</span>
                    </label>
                    <div class="neu-input-wrapper">
                        <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="neu-input-field has-toggle @error('password') neu-input--error @enderror"
                        >
                        <!-- Password Reveal Eye Toggle -->
                        <button
                            type="button"
                            id="toggle-password-btn"
                            class="neu-input-password-toggle"
                            onclick="togglePasswordVisibility()"
                            title="Tampilkan / Sembunyikan Kata Sandi"
                            aria-label="Tampilkan / Sembunyikan Kata Sandi"
                        >
                            <svg id="eye-icon-open" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eye-icon-closed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-[11px] text-navy-500/80 mt-1.5 pl-1">
                        Kata sandi awal akun baru adalah NIM kamu.
                    </p>
                </div>

                <!-- Checkbox Ingat Sesi -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="neu-checkbox-custom"
                        >
                        <span class="text-xs sm:text-[13px] text-navy-500 group-hover:text-navy-900 transition-colors">
                            Ingat sesi saya di perangkat ini
                        </span>
                    </label>
                </div>

                <!-- Tombol Submit Primer -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="neu-btn neu-btn--primary w-full h-[50px] justify-center text-[15px] font-semibold group cursor-pointer"
                    >
                        <span>Masuk ke Sistem Kasly</span>
                        <svg class="w-4 h-4 text-white transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>

                <!-- Registrasi Mandiri via QR Code -->
                <div class="mt-6 pt-4 border-t border-ice-200/50 text-center">
                    <p class="text-xs text-navy-500">
                        Belum terdaftar di ormawa?
                    </p>
                    <a
                        href="{{ route('anggota.register.qr') }}"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-navy-900 hover:text-navy-700 underline mt-1.5 transition-colors group"
                    >
                        <span>Daftar Mandiri dengan QR Code Ormawa</span>
                        <span class="transition-transform group-hover:translate-x-1">&rarr;</span>
                    </a>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer Copyright -->
    <footer class="w-full text-center py-4 text-xs text-navy-500/70">
        <p>{{ config('app.name', 'Kasly') }} &copy; {{ date('Y') }} &bull; Sistem Kas Organisasi Transparan</p>
    </footer>

    <!-- Interactive Scripts & Micro-Animations -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-icon-open');
            const eyeClosed = document.getElementById('eye-icon-closed');

            if (!passwordInput || !eyeOpen || !eyeClosed) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Micro-animation tap feedback on interactive buttons
            const clickables = document.querySelectorAll('.neu-btn, .neu-back-pill, .neu-input-password-toggle');
            clickables.forEach(el => {
                el.addEventListener('mousedown', () => el.classList.add('is-pressed'));
                el.addEventListener('mouseup', () => el.classList.remove('is-pressed'));
                el.addEventListener('mouseleave', () => el.classList.remove('is-pressed'));
                el.addEventListener('touchstart', () => el.classList.add('is-pressed'), { passive: true });
                el.addEventListener('touchend', () => el.classList.remove('is-pressed'), { passive: true });
            });
        });
    </script>
</body>
</html>
