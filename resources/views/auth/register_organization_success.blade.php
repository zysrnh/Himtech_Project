<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Berhasil Dikirim - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6 selection:bg-ice-200 selection:text-navy-900">

    <div class="max-w-[580px] w-full bg-white/95 rounded-[32px] p-8 sm:p-10 shadow-[12px_16px_36px_rgba(122,163,214,0.35),-10px_-10px_28px_rgba(255,255,255,0.95)] border border-white/80 text-center space-y-6">

        <!-- Checkmark Emblem Neumorphic -->
        <div class="w-20 h-20 rounded-full bg-white border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.3),-4px_-4px_14px_#ffffff] flex items-center justify-center mx-auto text-emerald-600 hover:scale-105 transition-transform duration-200">
            <div class="w-14 h-14 rounded-full bg-emerald-50 border border-emerald-200/80 shadow-[inset_2px_2px_4px_rgba(16,185,129,0.2)] flex items-center justify-center">
                <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.8" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>

        <div class="space-y-2">
            <div class="inline-flex items-center gap-2">
                <span class="neu-badge-pill text-[10px] py-1 px-3">PENDAFTARAN DITERIMA</span>
                <span class="neu-badge-ice text-[10px] py-1 px-3 font-mono">STATUS: MENUNGGU ACC</span>
            </div>
            <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-navy-900 tracking-tight">
                Pengajuan Berhasil Dikirim!
            </h1>
            <p class="text-xs sm:text-sm text-navy-500 leading-relaxed max-w-md mx-auto">
                Pengajuan organisasi <strong class="text-navy-900 font-bold">{{ session('org_name', 'Organisasi Anda') }}</strong> (Kode: <span class="font-mono font-bold text-navy-900">{{ session('org_code', 'ORMAWA') }}</span>) telah kami terima dan diteruskan ke Administrator Kasly.
            </p>
        </div>

        <!-- Langkah Selanjutnya Inset Neumorphic -->
        <div class="p-5 rounded-2xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.18),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] text-left text-xs text-navy-600 space-y-2.5">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-navy-900"></span>
                <p class="font-bold text-navy-900 font-display text-xs">Langkah Selanjutnya:</p>
            </div>
            <ul class="space-y-2 pl-2">
                <li class="flex items-start gap-2">
                    <span class="text-navy-900 font-bold shrink-0 font-mono">1.</span>
                    <span>Admin Kasly akan memvalidasi kesesuaian data pengajuan ormawa Anda.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-navy-900 font-bold shrink-0 font-mono">2.</span>
                    <span>Setelah disetujui (ACC), kredensial kata sandi akun Pengelola (<strong class="text-navy-900 font-semibold">{{ session('pic_name', 'Pemohon') }}</strong>) otomatis dikirimkan ke WhatsApp <span class="font-mono font-semibold text-navy-900">{{ session('pic_phone', '-') }}</span>.</span>
                </li>
                <li class="flex items-start gap-2">
                    <span class="text-navy-900 font-bold shrink-0 font-mono">3.</span>
                    <span>Setelah masuk, Anda dapat langsung mengelola keuangan ormawa dan mendaftarkan anggota serta staf pengurus.</span>
                </li>
            </ul>
        </div>

        <!-- Tombol Aksi -->
        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="/"
                class="w-full sm:w-auto neu-btn neu-btn--primary h-11 px-6 rounded-full font-bold text-xs shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span>Kembali ke Beranda</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            <a
                href="{{ route('login') }}"
                class="w-full sm:w-auto neu-back-pill justify-center text-center text-xs"
            >
                Ke Halaman Masuk
            </a>
        </div>

    </div>

</body>
</html>
