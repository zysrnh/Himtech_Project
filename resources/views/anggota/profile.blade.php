<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - {{ $user->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen">

    <!-- Top Navigation Bar Khusus Anggota -->
    <header class="w-full bg-white border-b border-ice-200 sticky top-0 z-40">
        <div class="max-w-[1120px] mx-auto px-6 h-18 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-8 w-auto">
                </a>
                <a href="{{ route('anggota.dashboard') }}" class="text-xs text-navy-500 hover:text-navy-900 font-medium">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <!-- Identitas Anggota & Logout -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-navy-900 leading-tight">
                        {{ $user->name }}
                    </p>
                    <p class="text-xs text-navy-500 font-mono">
                        NIM: {{ $member->member_number ?? $user->username }}
                    </p>
                </div>
                <form action="{{ route('anggota.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-9 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-xs hover:bg-ice-200 transition-colors cursor-pointer"
                    >
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-[680px] mx-auto p-6 md:p-8 space-y-6">

        <!-- Top Header -->
        <div>
            <h1 class="font-display font-semibold text-2xl md:text-3xl text-navy-900">
                Profil & Keamanan Akun
            </h1>
            <p class="text-sm text-navy-500 mt-1">
                Lengkapi kontak notifikasi Anda atau ubah kata sandi dari NIM default ke kata sandi baru.
            </p>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-md bg-butter-100 text-navy-900 text-sm font-medium shadow-card flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-md bg-danger/10 text-danger text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Edit Profil & Kata Sandi Anggota -->
        <div class="bg-white rounded-lg p-6 sm:p-8 shadow-card border border-ice-200">
            <form action="{{ route('anggota.profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- NIM / Username Editable -->
                <div>
                    <label for="member_number" class="block text-xs font-medium text-navy-500 mb-1.5">
                        Nomor Induk Mahasiswa (NIM / No. Anggota) *
                    </label>
                    <input
                        type="text"
                        id="member_number"
                        name="member_number"
                        value="{{ old('member_number', $member->member_number ?? $user->username) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500/70 mt-1">Ubah ID default ini ke NIM asli kampus Anda. Username akun login Anda akan otomatis mengikuti NIM ini.</p>
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
                        value="{{ old('name', $user->name) }}"
                        required
                        class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <!-- Nomor WhatsApp -->
                <div>
                    <label for="phone" class="block text-xs font-medium text-navy-500 mb-1.5">
                        Nomor WhatsApp (Opsional)
                    </label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        placeholder="Contoh: 081234567890"
                        class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                    <p class="text-[11px] text-navy-500/70 mt-1">Digunakan untuk pengingat batas waktu kas dari bendahara.</p>
                </div>

                <!-- Alamat Email Opsional -->
                <div>
                    <label for="email" class="block text-xs font-medium text-navy-500 mb-1.5">
                        Alamat Email (Opsional)
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        placeholder="nama@mahasiswa.ac.id"
                        class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                    >
                </div>

                <div class="pt-3 border-t border-ice-200 space-y-4">
                    <div>
                        <h2 class="text-sm font-semibold text-navy-900">Ubah Kata Sandi Akun</h2>
                        <p class="text-xs text-navy-500 mt-0.5">Biarkan kolom di bawah kosong jika tidak ingin mengubah kata sandi.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-medium text-navy-500 mb-1.5">
                                Kata Sandi Baru
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Minimal 6 karakter"
                                class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-medium text-navy-500 mb-1.5">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                placeholder="Ulangi kata sandi"
                                class="w-full h-11 px-3.5 rounded-sm bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a
                        href="{{ route('anggota.dashboard') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md border border-ice-200 bg-white text-navy-900 text-xs font-medium hover:bg-ice-100 transition-colors"
                    >
                        Kembali ke Dashboard
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    </main>

</body>
</html>

