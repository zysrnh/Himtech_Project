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

    @php
        $initialTab = session('active_tab', $activeRole ?? 'pengurus');
    @endphp

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

        <!-- Card Login Terpadu -->
        <div class="bg-white rounded-lg p-8 shadow-card">
            <h1 class="font-display font-semibold text-2xl text-navy-900">
                Masuk Akun
            </h1>
            <p class="mt-2 text-sm text-navy-500 font-normal">
                Pilih peran kamu untuk masuk ke sistem Kasly.
            </p>

            <!-- Tab Switcher (Pengurus vs Anggota) Sesuai Ice & Butter v2 -->
            <div class="mt-6 p-1 bg-ice-100 rounded-md grid grid-cols-2 gap-1 border border-ice-200">
                <button
                    type="button"
                    id="tab-btn-pengurus"
                    onclick="switchTab('pengurus')"
                    class="py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer {{ $initialTab === 'pengurus' ? 'bg-navy-900 text-white shadow-sm' : 'text-navy-500 hover:text-navy-900' }}"
                >
                    Pengurus / Staff
                </button>
                <button
                    type="button"
                    id="tab-btn-anggota"
                    onclick="switchTab('anggota')"
                    class="py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer {{ $initialTab === 'anggota' ? 'bg-navy-900 text-white shadow-sm' : 'text-navy-500 hover:text-navy-900' }}"
                >
                    Anggota Ormawa
                </button>
            </div>

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

            <!-- Form 1: Pengurus / Staff -->
            <form
                id="form-pengurus"
                action="{{ route('login.pengurus') }}"
                method="POST"
                class="mt-6 space-y-5 {{ $initialTab === 'pengurus' ? '' : 'hidden' }}"
            >
                @csrf

                <!-- Input Kode Organisasi Pengurus -->
                <div>
                    <label for="pengurus_organization_code" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Kode Organisasi
                    </label>
                    <input
                        type="text"
                        id="pengurus_organization_code"
                        name="organization_code"
                        value="{{ $initialTab === 'pengurus' ? old('organization_code') : '' }}"
                        required
                        placeholder="Contoh: HIMTECH"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Masukkan kode ormawa tempat Anda bertugas sebagai pengurus.
                    </p>
                </div>

                <!-- Input Email -->
                <div>
                    <label for="pengurus_email" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Alamat Email Pengurus
                    </label>
                    <input
                        type="email"
                        id="pengurus_email"
                        name="email"
                        value="{{ $initialTab === 'pengurus' ? old('email') : '' }}"
                        required
                        placeholder="staff@organisasi.id"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <!-- Input Password -->
                <div>
                    <label for="pengurus_password" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="pengurus_password"
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
                        id="pengurus_remember"
                        name="remember"
                        class="w-4 h-4 rounded text-navy-900 border-ice-200 focus:ring-ice-400"
                    >
                    <label for="pengurus_remember" class="ml-2 text-sm text-navy-500">
                        Ingat sesi saya di perangkat ini
                    </label>
                </div>

                <!-- Tombol Submit Pengurus -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer"
                    >
                        Masuk ke Panel Pengurus
                    </button>
                </div>
            </form>

            <!-- Form 2: Anggota Ormawa -->
            <form
                id="form-anggota"
                action="{{ route('login.anggota') }}"
                method="POST"
                class="mt-6 space-y-5 {{ $initialTab === 'anggota' ? '' : 'hidden' }}"
            >
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
                        value="{{ $initialTab === 'anggota' ? old('organization_code') : '' }}"
                        required
                        placeholder="Contoh: HIMTECH"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Dapatkan kode ormawa dari bendahara atau pengurus kamu.
                    </p>
                </div>

                <!-- Input Nomor Anggota / NIM -->
                <div>
                    <label for="member_number" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Nomor Anggota / NIM
                    </label>
                    <input
                        type="text"
                        id="member_number"
                        name="member_number"
                        value="{{ $initialTab === 'anggota' ? old('member_number') : '' }}"
                        required
                        placeholder="Contoh: 20240101"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <!-- Input Password Anggota -->
                <div>
                    <label for="anggota_password" class="block text-[13px] text-navy-500 font-normal mb-2">
                        Kata Sandi
                    </label>
                    <input
                        type="password"
                        id="anggota_password"
                        name="password"
                        required
                        placeholder="••••••••"
                        class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500 mt-1">
                        Untuk login pertama kali, kata sandi awal adalah nomor anggota/NIM kamu.
                    </p>
                </div>

                <!-- Checkbox Ingat Saya -->
                <div class="flex items-center">
                    <input
                        type="checkbox"
                        id="anggota_remember"
                        name="remember"
                        class="w-4 h-4 rounded text-navy-900 border-ice-200 focus:ring-ice-400"
                    >
                    <label for="anggota_remember" class="ml-2 text-sm text-navy-500">
                        Ingat sesi saya di perangkat ini
                    </label>
                </div>

                <!-- Tombol Submit Anggota -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full h-12 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer"
                    >
                        Masuk ke Portal Anggota
                    </button>
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

    <!-- Script Tab Switcher Sederhana -->
    <script>
        function switchTab(role) {
            const formPengurus = document.getElementById('form-pengurus');
            const formAnggota = document.getElementById('form-anggota');
            const btnPengurus = document.getElementById('tab-btn-pengurus');
            const btnAnggota = document.getElementById('tab-btn-anggota');

            if (role === 'anggota') {
                formPengurus.classList.add('hidden');
                formAnggota.classList.remove('hidden');

                btnAnggota.className = 'py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer bg-navy-900 text-white shadow-sm';
                btnPengurus.className = 'py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer text-navy-500 hover:text-navy-900';

                window.history.replaceState(null, '', '{{ url('/login') }}?role=anggota');
            } else {
                formAnggota.classList.add('hidden');
                formPengurus.classList.remove('hidden');

                btnPengurus.className = 'py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer bg-navy-900 text-white shadow-sm';
                btnAnggota.className = 'py-2.5 text-xs font-medium rounded transition-colors text-center cursor-pointer text-navy-500 hover:text-navy-900';

                window.history.replaceState(null, '', '{{ url('/login') }}?role=pengurus');
            }
        }
    </script>

</body>
</html>
