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
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Pengurus Neumorphic Interaktif -->
    <x-sidebar-pengurus active="profile" />

    <!-- Main Content Area: Profil Pengurus -->
    <main class="flex-1 min-w-0 p-6 lg:p-8">
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

            <!-- Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 sm:p-5 rounded-[22px] bg-butter-100 text-navy-900 text-sm font-semibold shadow-[var(--raised-xs)] flex items-center gap-3 border border-amber-200/50">
                    <div class="w-8 h-8 rounded-full bg-white/70 shadow-[var(--raised-xs)] flex items-center justify-center text-navy-900 shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="flex-1">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 sm:p-5 rounded-[22px] bg-danger/10 text-danger text-sm font-semibold shadow-[var(--inset-sm)] flex items-center gap-3 border border-danger/20">
                    <div class="w-8 h-8 rounded-full bg-white shadow-[var(--raised-xs)] flex items-center justify-center text-danger shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="flex-1">{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Card Formulir Profil -->
            <div class="neu-card p-6 sm:p-9">
                <form action="{{ route('pengurus.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Foto Profil Avatar (Rata dengan Card, Tanpa Sunken Box / Gap) -->
                    <div class="flex flex-col sm:flex-row sm:items-center gap-6 pb-6 border-b border-ice-200/60">
                        <div class="relative shrink-0">
                            <div class="neu-avatar-ring p-1 shadow-[var(--raised-xs)] inline-block">
                                <img
                                    id="avatar-preview"
                                    src="{{ $user->avatarUrl() }}"
                                    alt="{{ $user->name }}"
                                    class="w-20 h-20 rounded-full object-cover bg-ice-100"
                                >
                            </div>
                        </div>
                        <div class="space-y-2 flex-1 min-w-0">
                            <h3 class="font-display font-semibold text-base text-navy-900">
                                Foto Profil Pengurus
                            </h3>
                            <p class="text-xs text-navy-500 leading-relaxed">
                                Unggah foto profil resmi pengurus (JPG, PNG, atau WebP, maks. 2MB).
                            </p>
                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <label class="neu-btn text-xs py-2 px-5 font-semibold text-navy-500 hover:text-navy-900 cursor-pointer shadow-[var(--raised-xs)] hover:shadow-[var(--raised-sm)] active:shadow-[var(--inset-sm)]">
                                    <svg class="w-3.5 h-3.5 text-navy-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
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
                                    <label class="inline-flex items-center gap-2 text-xs text-danger font-semibold cursor-pointer">
                                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-ice-200 text-danger focus:ring-danger">
                                        <span>Hapus Foto Profil</span>
                                    </label>
                                @endif
                            </div>
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
                            class="neu-input @error('name') neu-input--error @enderror"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Grid Username & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                                class="neu-input @error('username') neu-input--error @enderror"
                            >
                            @error('username')
                                <p class="mt-1.5 text-xs text-danger font-medium">{{ $message }}</p>
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
                                class="neu-input @error('email') neu-input--error @enderror"
                            >
                            @error('email')
                                <p class="mt-1.5 text-xs text-danger font-medium">{{ $message }}</p>
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
                            class="neu-input font-mono @error('phone') neu-input--error @enderror"
                        >
                        @error('phone')
                            <p class="mt-1.5 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section Ubah Kata Sandi -->
                    <div class="pt-6 border-t border-ice-200/60">
                        <h2 class="font-display font-semibold text-base text-navy-900 mb-1">
                            Ubah Kata Sandi (Opsional)
                        </h2>
                        <p class="text-xs text-navy-500 mb-5">
                            Biarkan kosong jika Anda tidak ingin mengganti kata sandi login.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="password" class="neu-label">
                                    Kata Sandi Baru
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    class="neu-input @error('password') neu-input--error @enderror"
                                >
                                @error('password')
                                    <p class="mt-1.5 text-xs text-danger font-medium">{{ $message }}</p>
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
                                    class="neu-input"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-6 border-t border-ice-200/60 flex items-center justify-end">
                        <button
                            type="submit"
                            class="neu-btn neu-btn--primary"
                        >
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

