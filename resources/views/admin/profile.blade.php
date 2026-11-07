<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Super Admin - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="profile" />

    <!-- Main Content Area: Profil Admin -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[760px] mx-auto space-y-8">

            <!-- Top Header -->
            <div>
                <h1 class="font-display font-semibold text-[28px] text-navy-900">
                    Pengaturan Profil Administrator
                </h1>
                <p class="text-sm text-navy-500 mt-1">
                    Kelola foto profil, data identitas Administrator, dan nomor WhatsApp penerima notifikasi pengajuan ormawa.
                </p>
            </div>

            <!-- Notifikasi Sukses / Error -->
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

            <!-- Card Formulir Profil -->
            <div class="bg-white rounded-lg p-8 shadow-card border border-ice-200">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
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
                                Foto Profil Administrator
                            </label>
                            <p class="text-[11px] text-navy-500">
                                Unggah foto profil resmi Administrator (JPG, PNG, atau WebP, maks. 2MB).
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

                    <div>
                        <label for="name" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Nama Lengkap Administrator <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Username (Opsional)
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                                placeholder="admin_kasly"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                            @error('username')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kolom Kunci: Nomor WhatsApp Penerima Notifikasi Alert Fonnte -->
                    <div>
                        <label for="phone" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Nomor WhatsApp Notifikasi Sistem <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            required
                            placeholder="Contoh: 081234567890"
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        <p class="mt-1.5 text-xs text-navy-500 leading-relaxed">
                            Setiap kali ada calon ormawa yang mengajukan pendaftaran organisasi baru di web, gateway Fonnte akan otomatis mengirimkan pesan pemberitahuan ke nomor WhatsApp ini.
                        </p>
                        @error('phone')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-ice-200">
                        <h2 class="font-display font-medium text-base text-navy-900 mb-1">
                            Ubah Kata Sandi (Opsional)
                        </h2>
                        <p class="text-xs text-navy-500 mb-4">
                            Biarkan kosong jika Anda tidak ingin mengganti kata sandi.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('password')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-[13px] text-navy-500 font-medium mb-2">
                                    Konfirmasi Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi kata sandi baru"
                                    class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-ice-200 flex items-center justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                        >
                            Simpan Perubahan Profil
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

