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
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Anggota (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand & Organization Code -->
            <div class="h-18 px-6 flex items-center justify-between border-b border-ice-200">
                <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
                </a>
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-ice-100 text-navy-900 text-xs font-bold tracking-wider font-display">
                    {{ $organization?->code ?? 'KASLY' }}
                </span>
            </div>

            <!-- Organisasi Aktif -->
            <div class="px-6 py-3 border-b border-ice-200/50 bg-ice-100/30">
                <p class="text-[11px] text-navy-500 uppercase tracking-wider font-medium">Organisasi Aktif</p>
                <p class="text-xs font-semibold text-navy-900 truncate mt-0.5" title="{{ $organization?->name ?? 'Organisasi' }}">
                    {{ $organization?->name ?? 'Organisasi Mahasiswa' }}
                </p>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('anggota.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Overview Kas
                </a>
                <a
                    href="{{ route('anggota.dashboard') }}#tagihan"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Tagihan Iuran Saya
                </a>
                <a
                    href="{{ route('anggota.dashboard') }}#buku-kas"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Buku Kas Ormawa
                </a>
                <a
                    href="{{ route('anggota.profile') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Profil Saya
                </a>
                <a
                    href="/"
                    target="_blank"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Lihat Beranda Web
                </a>
            </nav>
        </div>

        <!-- User Info & Logout dengan Avatar -->
        <div class="p-4 border-t border-ice-200">
            <div class="flex items-center gap-3 mb-3 px-1">
                <img
                    src="{{ Auth::user()->avatarUrl() }}"
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full object-cover border border-ice-200 shrink-0 bg-ice-100"
                >
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-navy-900 truncate" title="{{ $member?->name ?? $user->name }}">
                        {{ $member?->name ?? $user->name }}
                    </p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="inline-block text-[10px] px-1.5 py-0.2 rounded bg-butter-100 text-navy-900 font-semibold uppercase">
                            Anggota
                        </span>
                        <span class="text-[11px] text-navy-500 font-mono truncate">
                            {{ $member?->member_number ?? $user->username }}
                        </span>
                    </div>
                </div>
            </div>
            <form action="{{ route('anggota.logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors cursor-pointer"
                >
                    Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area: Profil Anggota -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[760px] space-y-6">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Profil & Keamanan Akun
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Sesuaikan foto profil, nama asli, NIM kampus Anda, kontak WhatsApp, atau ubah kata sandi akun.
                    </p>
                </div>
                <a
                    href="{{ route('anggota.dashboard') }}"
                    class="inline-flex items-center justify-center h-10 px-4 rounded-md border border-ice-200 bg-white text-navy-900 text-xs font-medium hover:bg-ice-100 transition-colors"
                >
                    &larr; Kembali ke Dashboard
                </a>
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
                <form action="{{ route('anggota.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil Avatar -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-5 p-4 rounded-md bg-ice-100/50 border border-ice-200">
                        <div class="relative shrink-0">
                            <img
                                id="avatar-preview"
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $user->name }}"
                                class="w-20 h-20 rounded-full object-cover border-2 border-white shadow-sm bg-ice-100"
                            >
                        </div>
                        <div class="space-y-2 flex-1">
                            <label class="block text-xs font-semibold text-navy-900">
                                Foto Profil
                            </label>
                            <p class="text-[11px] text-navy-500">
                                Unggah foto profil resmi Anda (JPG, PNG, atau WebP, maks. 2MB).
                            </p>
                            <div class="flex flex-wrap items-center gap-2 pt-1">
                                <label class="inline-flex items-center justify-center h-8 px-3 rounded-md bg-white border border-ice-200 text-navy-900 text-xs font-medium hover:bg-ice-100 transition-colors cursor-pointer shadow-xs">
                                    Pilih Foto
                                    <input
                                        type="file"
                                        name="avatar"
                                        id="avatar-input"
                                        accept="image/png,image/jpeg,image/webp"
                                        class="hidden"
                                        onchange="previewAvatar(this)"
                                    >
                                </label>
                                @if ($user->avatar)
                                    <label class="inline-flex items-center gap-1.5 text-xs text-danger font-medium cursor-pointer ml-2">
                                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-ice-200 text-danger focus:ring-danger">
                                        Hapus Foto Profil
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- NIM / Nomor Anggota Editable -->
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

                    <!-- Nama Lengkap Mahasiswa -->
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

                    <div class="pt-4 border-t border-ice-200">
                        <h3 class="font-display font-medium text-base text-navy-900 mb-1">
                            Ubah Kata Sandi Akun
                        </h3>
                        <p class="text-xs text-navy-500 mb-4">
                            Biarkan kolom di bawah kosong jika tidak ingin mengubah kata sandi.
                        </p>

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

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a
                            href="{{ route('anggota.dashboard') }}"
                            class="inline-flex items-center justify-center h-11 px-5 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-xs hover:bg-ice-100 transition-colors"
                        >
                            Batal
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

        </div>
    </main>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</body>
</html>
