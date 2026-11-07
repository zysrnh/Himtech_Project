<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Mandiri Anggota - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col justify-between py-6 sm:py-10 px-4 selection:bg-ice-200 selection:text-navy-900">

    <!-- Top Navigation Bar -->
    <header class="w-full max-w-[520px] mx-auto flex items-center justify-between mb-4">
        <a href="/" class="neu-back-pill group" title="Kembali ke Beranda">
            <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Beranda</span>
        </a>

        <a href="/" class="inline-flex items-center hover:scale-105 transition-transform" title="{{ config('app.name', 'Kasly') }}">
            <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
        </a>

        <a href="{{ route('login') }}" class="neu-back-pill group text-xs hidden sm:inline-flex" title="Masuk Akun">
            <span class="text-navy-500">Punya Akun?</span>
            <strong class="text-navy-900 group-hover:underline">Masuk &rarr;</strong>
        </a>
    </header>

    <!-- Main Card Neumorphic -->
    <main class="w-full max-w-[520px] mx-auto my-auto">
        <div class="bg-white/90 rounded-[32px] p-6 sm:p-9 border border-white/80 shadow-[12px_16px_36px_rgba(122,163,214,0.35),-10px_-10px_28px_rgba(255,255,255,0.95)] space-y-6">

            @if ($tokenStatus === 'valid')
                <!-- ======================================================== -->
                <!-- KONDISI 1: TOKEN / QR CODE VALID - KONFIRMASI ORGANISASI -->
                <!-- ======================================================== -->
                <div class="space-y-5 text-center">
                    <!-- Emblem Logo / Ikon Ormawa -->
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto border border-white/90 shadow-[4px_4px_12px_rgba(122,163,214,0.3),-3px_-3px_10px_rgba(255,255,255,0.95)]">
                        <svg class="w-8 h-8 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs text-navy-500 uppercase tracking-wider font-semibold">Anda akan bergabung ke organisasi:</p>
                        <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-navy-900 mt-1 tracking-tight">
                            {{ $organization->name }}
                        </h1>
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-ice-100/90 border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.18)]">
                            <span class="text-[10px] text-navy-500 uppercase font-bold tracking-wider">KODE:</span>
                            <span class="text-xs font-mono font-bold text-navy-900">{{ $organization->code }}</span>
                        </div>
                    </div>

                    @if ($organization->description)
                        <p class="text-xs text-navy-500 max-w-sm mx-auto leading-relaxed">
                            {{ $organization->description }}
                        </p>
                    @endif

                    <!-- Info Ringkas Masa Berlaku & Kuota (Inset Neumorphic) -->
                    <div class="p-4 rounded-2xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.18),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] grid grid-cols-2 gap-3 text-left text-xs">
                        <div>
                            <span class="text-navy-500 block text-[11px]">Sisa Kuota QR:</span>
                            <span class="text-navy-900 font-display font-bold text-sm">{{ $token->remainingUses() }} anggota</span>
                        </div>
                        <div>
                            <span class="text-navy-500 block text-[11px]">Masa Berlaku:</span>
                            <span class="text-navy-900 font-display font-bold text-sm">{{ $token->expires_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <!-- Kotak Pemberitahuan Akun Default (Inset) -->
                    <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.15),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] text-left text-xs text-navy-600 space-y-1.5">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-navy-900"></span>
                            <p class="font-bold text-navy-900 font-display text-xs">Informasi Keanggotaan:</p>
                        </div>
                        <p class="text-navy-500 leading-relaxed text-[11px]">
                            Akun Anda akan langsung otomatis dibuat dengan ID anggota default. Anda dapat melengkapi nama asli, NIM kampus, dan nomor WhatsApp kapan saja di menu <strong>Profil Saya</strong>.
                        </p>
                    </div>

                    @if ($errors->any())
                        <div class="neu-alert-danger">
                            <svg class="w-4 h-4 text-danger shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="text-xs font-medium">{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Tombol Konfirmasi & Batal -->
                    <div class="pt-2 space-y-3">
                        <form action="{{ route('anggota.register.qr.submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token->token }}">
                            <button
                                type="submit"
                                class="neu-btn neu-btn--primary w-full h-12 rounded-full font-bold text-xs sm:text-sm shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
                            >
                                <span>Konfirmasi & Masuk sebagai Anggota</span>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </form>

                        <a
                            href="{{ route('anggota.register.qr') }}"
                            class="neu-back-pill w-full justify-center text-xs"
                        >
                            Bukan Organisasi Saya (Scan QR Lain)
                        </a>
                    </div>
                </div>

            @elseif ($tokenStatus === 'expired')
                <!-- ======================================================== -->
                <!-- KONDISI 2: TOKEN EXPIRED                                  -->
                <!-- ======================================================== -->
                <div class="text-center py-4 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-danger/10 border border-danger/20 text-danger mx-auto flex items-center justify-center font-bold text-2xl shadow-[4px_4px_12px_rgba(192,82,74,0.2),-3px_-3px_10px_#ffffff]">
                        !
                    </div>
                    <div>
                        <h2 class="font-display font-extrabold text-xl sm:text-2xl text-navy-900 tracking-tight">
                            QR Code Pendaftaran Kedaluwarsa
                        </h2>
                        <p class="text-xs text-navy-500 leading-relaxed max-w-sm mx-auto mt-2">
                            Masa berlaku QR Code pendaftaran untuk organisasi <strong>{{ $token->organization->name ?? 'ini' }}</strong> telah habis pada {{ $token->expires_at->format('d M Y H:i') }}.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.15)] text-xs text-navy-700 font-medium">
                        💡 Silakan hubungi pengurus ormawa Anda untuk meminta QR Code pendaftaran terbaru.
                    </div>

                    <div class="pt-3 flex flex-col gap-2.5">
                        <a
                            href="{{ route('anggota.register.qr') }}"
                            class="neu-btn neu-btn--primary h-11 px-6 rounded-full font-bold text-xs shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
                        >
                            Scan QR Lainnya
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="neu-back-pill justify-center text-xs"
                        >
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

            @elseif ($tokenStatus === 'quota_full')
                <!-- ======================================================== -->
                <!-- KONDISI 3: KUOTA PENUH                                    -->
                <!-- ======================================================== -->
                <div class="text-center py-4 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-danger/10 border border-danger/20 text-danger mx-auto flex items-center justify-center font-bold text-2xl shadow-[4px_4px_12px_rgba(192,82,74,0.2),-3px_-3px_10px_#ffffff]">
                        !
                    </div>
                    <div>
                        <h2 class="font-display font-extrabold text-xl sm:text-2xl text-navy-900 tracking-tight">
                            Batas Kuota Pendaftaran Tercapai
                        </h2>
                        <p class="text-xs text-navy-500 leading-relaxed max-w-sm mx-auto mt-2">
                            QR Code pendaftaran ini memiliki batas maksimal <strong>{{ $token->max_uses }} anggota</strong> dan seluruh kuotanya telah terisi penuh.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-2xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.15)] text-xs text-navy-700 font-medium">
                        💡 Silakan hubungi pengurus ormawa Anda untuk menambah batas kuota pendaftaran QR.
                    </div>

                    <div class="pt-3 flex flex-col gap-2.5">
                        <a
                            href="{{ route('anggota.register.qr') }}"
                            class="neu-btn neu-btn--primary h-11 px-6 rounded-full font-bold text-xs shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
                        >
                            Scan QR Lainnya
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="neu-back-pill justify-center text-xs"
                        >
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

            @else
                <!-- ======================================================== -->
                <!-- KONDISI 4: SCAN ATAU UPLOAD FOTO QR CODE (DEFAULT)        -->
                <!-- ======================================================== -->
                <div class="space-y-3 text-center">
                    <!-- Emblem QR Code -->
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mx-auto border border-white/90 shadow-[4px_4px_12px_rgba(122,163,214,0.3),-3px_-3px_10px_rgba(255,255,255,0.95)]">
                        <svg class="w-8 h-8 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                    </div>

                    <h1 class="font-display font-extrabold text-2xl sm:text-[26px] text-navy-900 tracking-tight">
                        Scan QR Code Ormawa
                    </h1>
                    <p class="text-xs text-navy-500 leading-relaxed max-w-sm mx-auto">
                        Pindai QR Code atau unggah screenshot foto QR yang dibagikan oleh pengurus di grup WhatsApp Anda.
                    </p>
                </div>

                @if ($tokenStatus === 'invalid' && ! empty($tokenString))
                    <div class="neu-alert-danger">
                        <svg class="w-4 h-4 text-danger shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-xs font-medium">QR Code atau token pendaftaran tidak ditemukan. Pastikan Anda memindai QR resmi dari pengurus.</span>
                    </div>
                @endif

                <!-- Pilihan Mode Scan & Upload (3 Kartu Neumorphic) -->
                <div class="space-y-3.5 pt-1">

                    <!-- OPSI 1: Upload Screenshot / Gambar QR dari File -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-ice-200/80 shadow-[3px_3px_10px_rgba(122,163,214,0.14),-3px_-3px_10px_#ffffff] text-center space-y-3 hover:shadow-[4px_4px_14px_rgba(122,163,214,0.2),-4px_-4px_14px_#ffffff] transition-all duration-200">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-ice-100 flex items-center justify-center text-navy-900 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h2 class="text-xs font-bold text-navy-900">
                                Pilihan 1: Upload File Gambar / Screenshot QR
                            </h2>
                        </div>
                        <p class="text-[11px] text-navy-500 leading-relaxed">
                            Ambil foto QR yang dikirimkan pengurus dari galeri perangkat Anda.
                        </p>
                        <div>
                            <input
                                type="file"
                                id="qr-file-input"
                                accept="image/*"
                                class="hidden"
                            >
                            <button
                                type="button"
                                onclick="document.getElementById('qr-file-input').click()"
                                class="neu-btn neu-btn--primary h-10 px-5 rounded-full font-bold text-xs shadow-sm hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 mx-auto cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span>Pilih Gambar QR dari File / Galeri</span>
                            </button>
                        </div>
                        <div id="file-scan-status" class="p-2.5 rounded-xl bg-ice-100 text-xs text-navy-700 hidden font-medium shadow-[inset_1px_1px_3px_rgba(122,163,214,0.18)]"></div>
                    </div>

                    <!-- OPSI 2: Scan Langsung Menggunakan Kamera -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-ice-200/80 shadow-[3px_3px_10px_rgba(122,163,214,0.14),-3px_-3px_10px_#ffffff] text-center space-y-3 hover:shadow-[4px_4px_14px_rgba(122,163,214,0.2),-4px_-4px_14px_#ffffff] transition-all duration-200">
                        <div class="flex items-center justify-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-ice-100 flex items-center justify-center text-navy-900 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-xs font-bold text-navy-900">
                                Pilihan 2: Pindai Langsung via Kamera
                            </h2>
                        </div>
                        <p class="text-[11px] text-navy-500 leading-relaxed">
                            Arahkan kamera smartphone atau webcam langsung ke kode QR.
                        </p>
                        <div id="qr-reader-container" class="space-y-3">
                            <button
                                type="button"
                                id="start-camera-btn"
                                onclick="startCameraScanner()"
                                class="neu-btn h-10 px-5 rounded-full font-bold text-xs bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.2),-2px_-2px_6px_#ffffff] text-navy-900 hover:scale-105 active:scale-95 flex items-center justify-center gap-2 mx-auto cursor-pointer transition-all"
                            >
                                <svg class="w-3.5 h-3.5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span>Aktifkan Kamera Pemindai</span>
                            </button>
                            <div id="qr-camera-box" class="hidden overflow-hidden rounded-2xl border-2 border-ice-300 shadow-[4px_4px_14px_rgba(122,163,214,0.25)] max-w-[320px] mx-auto"></div>
                        </div>
                    </div>

                    <!-- OPSI 3: Masukkan Token / Kode Pendaftaran Manual -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-ice-200/80 shadow-[3px_3px_10px_rgba(122,163,214,0.14),-3px_-3px_10px_#ffffff] space-y-2.5 hover:shadow-[4px_4px_14px_rgba(122,163,214,0.2),-4px_-4px_14px_#ffffff] transition-all duration-200">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-ice-100 flex items-center justify-center text-navy-900 shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <label for="manual-token-input" class="text-xs font-bold text-navy-900">
                                Pilihan 3: Masukkan Kode Token Manual
                            </label>
                        </div>
                        <div class="flex gap-2">
                            <div class="neu-input-wrapper flex-1">
                                <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <input
                                    type="text"
                                    id="manual-token-input"
                                    placeholder="Contoh: kasly_himtech_..."
                                    class="neu-input-field font-mono text-xs"
                                >
                            </div>
                            <button
                                type="button"
                                onclick="submitManualToken()"
                                class="neu-btn neu-btn--primary h-12 px-6 rounded-full font-bold text-xs shadow-sm hover:scale-[1.02] active:scale-95 transition-all shrink-0 cursor-pointer"
                            >
                                Lanjut &rarr;
                            </button>
                        </div>
                    </div>

                </div>

                <script>
                    const html5QrCode = new Html5Qrcode("qr-camera-box", { verbose: false });

                    // Logika Pindai via Upload File Gambar
                    document.getElementById('qr-file-input').addEventListener('change', function (e) {
                        if (e.target.files.length === 0) return;
                        const imageFile = e.target.files[0];
                        const statusBox = document.getElementById('file-scan-status');
                        statusBox.classList.remove('hidden');
                        statusBox.innerText = 'Memindai gambar QR code...';

                        html5QrCode.scanFile(imageFile, true)
                            .then(decodedText => {
                                statusBox.innerText = 'QR Code berhasil terbaca! Mengalihkan...';
                                handleDecodedQr(decodedText);
                            })
                            .catch(err => {
                                statusBox.innerText = 'Tidak dapat membaca QR Code dari gambar ini. Pastikan gambar jelas dan tidak blur.';
                            });
                    });

                    // Logika Pindai via Kamera
                    function startCameraScanner() {
                        const box = document.getElementById('qr-camera-box');
                        const btn = document.getElementById('start-camera-btn');
                        box.classList.remove('hidden');
                        btn.classList.add('hidden');

                        html5QrCode.start(
                            { facingMode: "environment" },
                            { fps: 10, qrbox: { width: 250, height: 250 } },
                            (decodedText, decodedResult) => {
                                html5QrCode.stop().then(() => {
                                    handleDecodedQr(decodedText);
                                });
                            },
                            (errorMessage) => {
                                // scanning...
                            }
                        ).catch(err => {
                            alert('Gagal mengakses kamera: ' + err);
                            box.classList.add('hidden');
                            btn.classList.remove('hidden');
                        });
                    }

                    // Pengalihan Berdasarkan Isi QR
                    function handleDecodedQr(qrText) {
                        try {
                            if (qrText.startsWith('http://') || qrText.startsWith('https://')) {
                                window.location.href = qrText;
                            } else if (qrText.includes('token=')) {
                                const url = new URL(qrText, window.location.origin);
                                window.location.href = url.pathname + url.search;
                            } else {
                                window.location.href = '{{ route('anggota.register.qr') }}?token=' + encodeURIComponent(qrText.trim());
                            }
                        } catch (e) {
                            window.location.href = '{{ route('anggota.register.qr') }}?token=' + encodeURIComponent(qrText.trim());
                        }
                    }

                    // Submit Token Manual
                    function submitManualToken() {
                        const val = document.getElementById('manual-token-input').value.trim();
                        if (val) {
                            window.location.href = '{{ route('anggota.register.qr') }}?token=' + encodeURIComponent(val);
                        }
                    }
                </script>
            @endif

        </div>

        <!-- Footer Kembali ke Login -->
        <div class="mt-6 text-center text-xs text-navy-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-navy-900 underline hover:text-navy-700 ml-1">
                Masuk di Sini
            </a>
        </div>
    </main>

    <!-- Footer hak cipta -->
    <footer class="text-center text-xs text-navy-500/70 pt-6">
        &copy; {{ date('Y') }} {{ config('app.name', 'Kasly') }}. Sistem Transparansi Kas Organisasi Mahasiswa.
    </footer>

</body>
</html>
