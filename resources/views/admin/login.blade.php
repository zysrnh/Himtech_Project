<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Super Admin - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-[400px]">
        <!-- Brand Link Back to Home -->
        <div class="mb-6">
            <a href="/" class="inline-flex items-center">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-10 w-auto">
            </a>
        </div>

        <!-- Card Login Super Admin -->
        <div class="bg-white rounded-lg p-8 shadow-card">
            <h1 class="font-display font-semibold text-2xl text-navy-900">
                Login Super Admin
            </h1>
            <p class="mt-2 text-sm text-navy-500 font-normal">
                Panel pusat pengelolaan organisasi.
            </p>

            @if ($errors->any())
                <div class="mt-4 p-3 rounded-sm bg-danger/10 text-danger text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', 'admin@kasly.id') }}"
                        required
                        autofocus
                        placeholder="admin@kasly.id"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <!-- Input Password -->
                <div>
                    <label for="password" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Password
                    </label>
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
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Tombol Primer Tunggal -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer"
                    >
                        Masuk ke Panel Pusat
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Bantuan & Kembali -->
        <div class="mt-6 text-center">
            <a href="/" class="text-sm text-navy-500 hover:text-navy-900 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
