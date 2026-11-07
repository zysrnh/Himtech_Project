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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 sm:p-6">

    <div class="w-full max-w-[480px]">
        <!-- Brand Link Back to Home -->
        <div class="mb-6 text-center">
            <a href="/" class="inline-flex items-center">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-10 w-auto">
            </a>
        </div>

        <!-- Card Utama Pendaftaran Anggota -->
        <div class="bg-white rounded-lg p-6 sm:p-8 shadow-card border border-ice-200">

            @if ($tokenStatus === 'valid')
                <!-- KONDISI 1: TOKEN / QR CODE VALID -->
                <div class="space-y-2 text-center pb-4 border-b border-ice-200">
                    <span class="inline-block px-3 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-bold">
                        Pendaftaran Anggota Ormawa
                    </span>
                    <h1 class="font-display font-semibold text-2xl text-navy-900">
                        {{ $organization->name }}
                    </h1>
                    <p class="text-xs text-navy-500 font-mono">
                        Kode Organisasi: <span class="font-bold text-navy-900">{{ $organization->code }}</span>
                    </p>
                </div>

                <!-- Info Kuota & Masa Berlaku Token -->
                <div class="mt-4 p-3.5 rounded-md bg-ice-100/70 border border-ice-200 grid grid-cols-2 gap-2 text-xs">
                    <div>
                        <span class="text-navy-500 block">Sisa Kuota:</span>
                        <strong class="text-navy-900 font-display font-semibold text-sm">{{ $token->remainingUses() }}</strong>
                        <span class="text-navy-500/70 text-[11px]">/ {{ $token->max_uses }} orang</span>
                    </div>
                    <div>
                        <span class="text-navy-500 block">Berlaku Hingga:</span>
                        <strong class="text-navy-900 font-display font-semibold text-sm">{{ $token->expires_at->format('d M H:i') }}</strong>
                        <span class="text-navy-500/70 text-[11px] block">({{ $token->expires_at->diffForHumans() }})</span>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="mt-4 p-3 rounded-md bg-danger/10 text-danger text-xs font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Formulir Data Anggota -->
                <form action="{{ route('anggota.register.qr.submit') }}" method="POST" class="mt-5 space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token->token }}">

                    <!-- NIM / Nomor Anggota -->
                    <div>
                        <label for="member_number" class="block text-xs font-medium text-navy-500 mb-1.5">
                            Nomor Induk Mahasiswa (NIM) *
                        </label>
                        <input
                            type="text"
                            id="member_number"
                            name="member_number"
                            value="{{ old('member_number') }}"
                            required
                            autofocus
                            placeholder="Contoh: 20240101"
                            class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors font-mono"
                        >
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-xs font-medium text-navy-500 mb-1.5">
                            Nama Lengkap Mahasiswa *
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Muhammad Budi"
                            class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label for="phone" class="block text-xs font-medium text-navy-500 mb-1.5">
                            Nomor WhatsApp Aktif *
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            required
                            placeholder="Contoh: 081234567890"
                            class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors font-mono"
                        >
                        <p class="text-[11px] text-navy-500/70 mt-1">Digunakan untuk pengingat batas waktu kas otomatis.</p>
                    </div>

                    <!-- Kata Sandi Opsional -->
                    <div>
                        <label for="password" class="block text-xs font-medium text-navy-500 mb-1.5">
                            Kata Sandi (Opsional)
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Biarkan kosong jika ingin default sama dengan NIM"
                            class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        <p class="text-[11px] text-navy-500/70 mt-1">Bisa diubah kapan saja di menu profil anggota.</p>
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer shadow-sm"
                        >
                            Daftar & Masuk ke Kasly
                        </button>
                    </div>
                </form>

            @elseif ($tokenStatus === 'expired')
                <!-- KONDISI 2: TOKEN EXPIRED -->
                <div class="text-center py-4 space-y-3">
                    <div class="w-12 h-12 rounded-full bg-danger/10 text-danger mx-auto flex items-center justify-center font-bold text-xl">
                        !
                    </div>
                    <h2 class="font-display font-semibold text-xl text-navy-900">
                        QR Code Pendaftaran Telah Kedaluwarsa
                    </h2>
                    <p class="text-xs text-navy-500 leading-relaxed max-w-sm mx-auto">
                        Masa berlaku QR Code pendaftaran untuk organisasi <strong>{{ $token->organization->name ?? 'ini' }}</strong> telah habis pada {{ $token->expires_at->format('d M Y H:i') }}.
                    </p>
                    <p class="text-xs text-navy-900 font-medium pt-2">
                        Silakan hubungi pengurus ormawa Anda untuk membuat QR Code pendaftaran baru.
                    </p>
                    <div class="pt-4 flex flex-col gap-2">
                        <a
                            href="{{ route('anggota.register.qr') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-md bg-navy-900 text-white text-xs font-medium hover:bg-navy-700 transition-colors"
                        >
                            Scan QR Lainnya
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-md border border-ice-200 text-navy-900 text-xs font-medium hover:bg-ice-100 transition-colors"
                        >
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

            @elseif ($tokenStatus === 'quota_full')
                <!-- KONDISI 3: KUOTA PENUH -->
                <div class="text-center py-4 space-y-3">
                    <div class="w-12 h-12 rounded-full bg-danger/10 text-danger mx-auto flex items-center justify-center font-bold text-xl">
                        !
                    </div>
                    <h2 class="font-display font-semibold text-xl text-navy-900">
                        Batas Kuota Pendaftaran Tercapai
                    </h2>
                    <p class="text-xs text-navy-500 leading-relaxed max-w-sm mx-auto">
                        QR Code pendaftaran ini memiliki batas maksimal <strong>{{ $token->max_uses }} anggota</strong> dan kuotanya telah terisi penuh.
                    </p>
                    <p class="text-xs text-navy-900 font-medium pt-2">
                        Silakan hubungi pengurus ormawa Anda untuk memperbarui / menambah kuota pendaftaran QR.
                    </p>
                    <div class="pt-4 flex flex-col gap-2">
                        <a
                            href="{{ route('anggota.register.qr') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-md bg-navy-900 text-white text-xs font-medium hover:bg-navy-700 transition-colors"
                        >
                            Scan QR Lainnya
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-md border border-ice-200 text-navy-900 text-xs font-medium hover:bg-ice-100 transition-colors"
                        >
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </div>

            @else
                <!-- KONDISI 4: BELUM ADA TOKEN / SCAN ATAU UPLOAD FOTO QR CODE -->
                <div class="space-y-2 text-center pb-3 border-b border-ice-200">
                    <span class="inline-block px-3 py-1 rounded-full bg-ice-100 text-navy-900 text-xs font-semibold">
                        Registrasi Anggota Baru
                    </span>
                    <h1 class="font-display font-semibold text-2xl text-navy-900">
                        Scan QR Code Ormawa
                    </h1>
                    <p class="text-xs text-navy-500 leading-relaxed">
                        Pindai QR Code atau unggah screenshot foto QR yang dibagikan oleh pengurus di grup WhatsApp Anda.
                    </p>
                </div>

                @if ($tokenStatus === 'invalid' && ! empty($tokenString))
                    <div class="mt-4 p-3 rounded-md bg-danger/10 text-danger text-xs font-medium text-center">
                        QR Code atau token pendaftaran tidak ditemukan. Pastikan Anda memindai QR resmi dari pengurus.
                    </div>
                @endif

                <!-- Pilihan Mode Scan & Upload -->
                <div class="mt-5 space-y-4">
                    <!-- OPSI A: Upload Screenshot / Gambar QR dari File -->
                    <div class="p-4 rounded-md bg-ice-100/50 border border-ice-200 text-center space-y-3">
                        <p class="text-xs font-semibold text-navy-900">
                            Pilihan 1: Upload File Gambar / Screenshot QR
                        </p>
                        <p class="text-[11px] text-navy-500">
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
                                class="inline-flex items-center justify-center h-10 px-5 rounded-md bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors cursor-pointer shadow-sm"
                            >
                                Pilih Gambar QR dari File / Galeri
                            </button>
                        </div>
                        <div id="file-scan-status" class="text-xs text-navy-500 hidden font-medium"></div>
                    </div>

                    <!-- OPSI B: Scan Langsung Menggunakan Kamera -->
                    <div class="p-4 rounded-md bg-white border border-ice-200 text-center space-y-3">
                        <p class="text-xs font-semibold text-navy-900">
                            Pilihan 2: Pindai Langsung via Kamera
                        </p>
                        <div id="qr-reader-container" class="space-y-3">
                            <button
                                type="button"
                                id="start-camera-btn"
                                onclick="startCameraScanner()"
                                class="inline-flex items-center justify-center h-10 px-5 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-xs hover:bg-ice-100 transition-colors cursor-pointer"
                            >
                                Aktifkan Kamera Pemindai
                            </button>
                            <div id="qr-camera-box" class="hidden overflow-hidden rounded-md border border-ice-200 max-w-[320px] mx-auto"></div>
                        </div>
                    </div>

                    <!-- OPSI C: Masukkan Token / Kode Pendaftaran Manual -->
                    <div class="p-4 rounded-md bg-ice-100/30 border border-ice-200 space-y-2">
                        <label for="manual-token-input" class="block text-xs font-medium text-navy-900">
                            Pilihan 3: Masukkan Kode Token Pendaftaran Manual
                        </label>
                        <div class="flex gap-2">
                            <input
                                type="text"
                                id="manual-token-input"
                                placeholder="Contoh: kasly_himtech_..."
                                class="flex-1 h-10 px-3 rounded-sm bg-white border border-ice-200 text-xs font-mono text-navy-900 focus:outline-none focus:ring-2 focus:ring-ice-400"
                            >
                            <button
                                type="button"
                                onclick="submitManualToken()"
                                class="h-10 px-4 rounded-sm bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors"
                            >
                                Lanjut
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
            <a href="{{ route('login') }}" class="font-medium text-navy-900 underline hover:text-navy-700 ml-1">
                Masuk di Sini
            </a>
        </div>
    </div>

</body>
</html>

