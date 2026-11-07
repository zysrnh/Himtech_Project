<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk sebagai Pengurus - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/icon-kasly.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/icon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/icon-192.png') }}">

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
                Portal Pengurus
            </span>
        </div>

        <!-- Card Login Pengurus -->
        <div class="bg-white rounded-lg p-8 shadow-card">
            <h1 class="font-display font-semibold text-2xl text-navy-900">
                Masuk Pengurus
            </h1>
            <p class="mt-2 text-sm text-navy-500 font-normal">
                Kelola kas, tagihan iuran, dan pembukuan transparan ormawa.
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

            <form action="{{ route('pengurus.login.submit') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Alamat Email Pengurus
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="staff@organisasi.id"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <!-- Input Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-[13px] text-navy-500 font-normal">
                            Kata Sandi
                        </label>
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
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

                <!-- Tombol Masuk -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer"
                    >
                        Masuk ke Panel Pengurus
                    </button>
                </div>
            </form>

            <!-- Tautan Navigasi Antar Peran -->
            <div class="mt-6 pt-6 border-t border-ice-200 text-center text-sm text-navy-500">
                Bukan pengurus organisasi?
                <a href="{{ route('anggota.login') }}" class="text-navy-900 font-medium hover:underline ml-1">
                    Masuk sebagai Anggota
                </a>
            </div>
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
