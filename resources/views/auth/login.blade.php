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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[420px]">
        <!-- Brand Link Back to Home -->
        <div class="mb-6 flex items-center justify-between">
            <a href="/" class="inline-flex items-center">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-10 w-auto">
            </a>
            <span class="text-xs px-2.5 py-1 rounded bg-ice-200 text-navy-900 font-medium">
                Portal Masuk
            </span>
        </div>

        <!-- Card Login Tunggal Universal (Sesuai Desain Ice & Butter v2) -->
        <div class="bg-white rounded-lg p-8 shadow-card">
            <h1 class="font-display font-semibold text-2xl text-navy-900">
                Masuk Akun
            </h1>
            <p class="mt-2 text-sm text-navy-500 font-normal">
                Satu pintu masuk untuk seluruh pengurus dan anggota ormawa.
            </p>

            @if (session('success'))
                <div class="mt-4 p-3 rounded-sm bg-butter-100 text-navy-900 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-4 p-3 rounded-sm bg-danger/10 text-danger text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form Login Tunggal -->
            <form action="{{ route('login.submit') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <!-- Input Kode Organisasi -->
                <div>
                    <label for="organization_code" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Kode Organisasi
                    </label>
                    <input
                        type="text"
                        id="organization_code"
                        name="organization_code"
                        value="{{ old('organization_code') }}"
                        required
                        autofocus
                        placeholder="Contoh: HIMTECH"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Masukkan kode ormawa yang terdaftar di Kasly.
                    </p>
                </div>

                <!-- Input Username / NIM -->
                <div>
                    <label for="username" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Username / Nomor Anggota (NIM)
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        required
                        placeholder="Contoh: zaki atau 20240101"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Staff menggunakan username/email, anggota menggunakan NIM.
                    </p>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Untuk anggota baru, kata sandi awal adalah nomor anggota/NIM kamu.
                    </p>
                </div>

                <!-- Checkbox Ingat Saya -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded text-navy-900 border-ice-200 focus:ring-ice-400"
                    >
                    <label for="remember" class="ml-2 text-sm text-navy-500">
                        Ingat sesi saya di perangkat ini
                    </label>
                </div>

                <!-- Tombol Submit Tunggal -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer shadow-sm"
                    >
                        Masuk ke Sistem Kasly
                    </button>
                </div>

                <!-- Registrasi Anggota via QR Code -->
                <div class="pt-3 text-center border-t border-ice-200 mt-2">
                    <p class="text-xs text-navy-500">
                        Belum terdaftar sebagai anggota ormawa?
                    </p>
                    <a
                        href="{{ route('anggota.register.qr') }}"
                        class="inline-flex items-center gap-1 text-xs font-semibold text-navy-900 hover:text-navy-700 underline mt-1 transition-colors"
                    >
                        <span>Daftar Mandiri dengan QR Code Ormawa</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Back to Home -->
        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-navy-500 hover:text-navy-900 transition-colors">
                Kembali ke Beranda Utama Kasly
            </a>
        </div>
    </div>

</body>
</html>
