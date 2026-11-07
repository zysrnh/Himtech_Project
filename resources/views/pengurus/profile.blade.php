@php
    $role = session('active_role', 'staff');
    $roleName = match($role) {
        'admin_organisasi' => 'Admin Organisasi',
        'bendahara' => 'Bendahara',
        default => 'Staff Ormawa',
    };
    if ($user && $user->is_super_admin && !session('active_role')) {
        $roleName = 'Super Admin';
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Pengurus - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Pengurus Neumorphic Interaktif -->
    <x-sidebar-pengurus active="profile" />

    <!-- Main Content Area: Profil Pengurus -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div>
                <h1 class="font-display font-semibold text-2xl sm:text-[28px] text-navy-900 leading-tight">
                    Pengaturan Profil Pengurus
                </h1>
                <p class="text-xs sm:text-sm text-navy-500 mt-1">
                    Kelola foto profil, data identitas akun pengurus, nomor WhatsApp notifikasi, dan kata sandi login Anda.
                </p>
            </div>

            <!-- Notifikasi Sukses / Error Sesuai Jadwal Tagihan -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-white/90 border border-ice-200/80 text-navy-900 text-sm font-medium shadow-[3px_3px_10px_rgba(122,163,214,0.15)] flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-500 hover:text-navy-900 text-xs font-bold cursor-pointer">Tutup</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50/90 border border-red-200/80 text-danger text-sm font-medium shadow-[3px_3px_10px_rgba(192,82,74,0.15)] flex items-center justify-between">
                    <span>{{ $errors->first() }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-danger hover:text-navy-900 text-xs font-bold cursor-pointer">Tutup</button>
                </div>
            @endif

            <!-- Card Formulir Profil (Pewarnaan Putih Bersih Sesuai Jadwal Tagihan) -->
            <div class="bg-white/90 rounded-2xl p-6 sm:p-8 border border-ice-200/70 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.9)]">
                <form action="{{ route('pengurus.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil Avatar (Gaya Konsisten dengan Sidebar) -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/50 border border-ice-200/70 shadow-[2px_2px_8px_rgba(122,163,214,0.08)] flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                        <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                            <div class="neu-avatar-ring shrink-0">
                                <img
                                    id="avatar-preview"
                                    src="{{ $user->avatarUrl() }}"
                                    alt="{{ $user->name }}"
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover bg-ice-100"
                                >
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h3 class="font-display font-bold text-base sm:text-lg text-navy-900 truncate">
                                        {{ $user->name }}
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-ice-100 text-navy-700 border border-ice-200/70">
                                        {{ $roleName }}
                                    </span>
                                </div>
                                <p class="text-xs text-navy-500 leading-relaxed">
                                    Unggah foto profil resmi pengurus (JPG, PNG, atau WebP, maks. 2MB).
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                            <label class="h-9 px-4 rounded-xl bg-white text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_2px_rgba(255,255,255,0.9),1px_1px_3px_rgba(122,163,214,0.25)] hover:bg-ice-100/70 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.35)] transition-all cursor-pointer inline-flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Pilih Foto</span>
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
                                <label class="inline-flex items-center gap-1.5 text-xs text-danger font-semibold cursor-pointer ml-1">
                                    <input type="checkbox" name="remove_avatar" value="1" class="rounded border-ice-200 text-danger focus:ring-danger">
                                    <span>Hapus</span>
                                </label>
                            @endif
                        </div>
                    </div>

                    <!-- Input Nama Lengkap -->
                    <div>
                        <label for="name" class="neu-label">
                            Nama Lengkap Pengurus <span class="text-danger font-bold ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('name') border-red-400 focus:ring-red-400 @else border-ice-200/80 focus:ring-ice-400 focus:border-ice-400 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 transition-all"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid Username & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="neu-label">
                                Username Login <span class="text-danger font-bold ml-0.5">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                required
                                placeholder="Username pengurus"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('username') border-red-400 focus:ring-red-400 @else border-ice-200/80 focus:ring-ice-400 focus:border-ice-400 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 transition-all"
                            >
                            @error('username')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="neu-label">
                                Alamat Email <span class="text-danger font-bold ml-0.5">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                placeholder="nama@email.com"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('email') border-red-400 focus:ring-red-400 @else border-ice-200/80 focus:ring-ice-400 focus:border-ice-400 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 transition-all"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Input Nomor WhatsApp -->
                    <div>
                        <label for="phone" class="neu-label">
                            Nomor WhatsApp <span class="text-danger font-bold ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                            placeholder="Contoh: 081234567890"
                            class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-mono text-sm border @error('phone') border-red-400 focus:ring-red-400 @else border-ice-200/80 focus:ring-ice-400 focus:border-ice-400 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 transition-all"
                        >
                        @error('phone')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Ubah Kata Sandi -->
                    <div class="pt-6 border-t border-ice-200/70">
                        <h2 class="font-display font-semibold text-base text-navy-900 mb-1">
                            Ubah Kata Sandi (Opsional)
                        </h2>
                        <p class="text-xs text-navy-500 mb-4">
                            Biarkan kosong jika Anda tidak ingin mengganti kata sandi login.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="neu-label">
                                    Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('password') border-red-400 focus:ring-red-400 @else border-ice-200/80 focus:ring-ice-400 focus:border-ice-400 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 transition-all"
                                >
                                @error('password')
                                    <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="neu-label">
                                    Konfirmasi Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi kata sandi baru"
                                    class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 focus:border-ice-400 transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-6 border-t border-ice-200/70 flex items-center justify-end">
                        <button
                            type="submit"
                            class="h-11 px-7 rounded-xl bg-navy-900 text-white font-semibold text-sm shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:shadow-[inset_2px_2px_4px_rgba(0,0,0,0.4)] transition-all cursor-pointer inline-flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan Profil</span>
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

