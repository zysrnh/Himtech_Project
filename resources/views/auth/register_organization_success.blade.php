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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex items-center justify-center p-6">

    <div class="max-w-[560px] w-full bg-white rounded-lg p-8 sm:p-10 shadow-card border border-ice-200 text-center space-y-6">

        <div class="w-16 h-16 rounded-full bg-butter-100 mx-auto flex items-center justify-center">
            <span class="font-display font-semibold text-2xl text-navy-900">✓</span>
        </div>

        <div class="space-y-2">
            <span class="inline-block px-3 py-1 rounded-full bg-ice-100 text-navy-900 text-xs font-semibold">
                Status: Menunggu Persetujuan
            </span>
            <h1 class="font-display font-semibold text-2xl sm:text-3xl text-navy-900">
                Pengajuan Berhasil Dikirim!
            </h1>
            <p class="text-sm text-navy-500 leading-relaxed">
                Pengajuan organisasi <strong class="text-navy-900">{{ session('org_name', 'Organisasi Anda') }}</strong> (Kode: <span class="font-mono text-navy-900">{{ session('org_code', 'ORMAWA') }}</span>) telah kami terima dan diteruskan ke Admin Kasly.
            </p>
        </div>

        <div class="p-5 rounded-md bg-ice-100/60 border border-ice-200 text-left text-xs text-navy-500 space-y-2">
            <p class="font-medium text-navy-900">Langkah Selanjutnya:</p>
            <ul class="space-y-1.5 list-disc list-inside">
                <li>Admin Kasly akan memvalidasi data pengajuan organisasi Anda.</li>
                <li>Setelah disetujui (di-ACC), kata sandi akun Pengelola Anda (<strong class="text-navy-900">{{ session('pic_name', 'Pemohon') }}</strong>) akan otomatis dikirimkan ke WhatsApp <span class="font-mono text-navy-900">{{ session('pic_phone', '-') }}</span>.</li>
                <li>Setelah login, Anda dapat langsung mengelola keuangan ormawa dan membuat akun staf pengurus Anda sendiri.</li>
            </ul>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a
                href="/"
                class="w-full sm:w-auto inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm"
            >
                Kembali ke Beranda Kasly
            </a>
            <a
                href="{{ route('login') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center h-11 px-6 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-sm hover:bg-ice-100 transition-colors"
            >
                Halaman Masuk
            </a>
        </div>

    </div>

</body>
</html>

