<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Administrator Kasly - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="admins" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area: Kelola Administrator Kasly -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900 leading-tight">
                        Kelola Administrator Kasly
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Daftar seluruh akun Administrator platform dan penambahan pengelola pusat baru.
                    </p>
                </div>
                <div>
                    <a
                        href="#form-tambah-admin"
                        class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:shadow-[inset_2px_2px_4px_rgba(0,0,0,0.4)] transition-all cursor-pointer inline-flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Administrator Baru</span>
                    </a>
                </div>
            </div>

            <!-- Notifikasi Sukses / Error Sesuai Jadwal Tagihan -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-white border border-ice-200/80 text-navy-900 text-sm font-medium shadow-[3px_3px_10px_rgba(122,163,214,0.15)] flex items-center justify-between">
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

            <!-- Tabel Administrator Kasly (Sesuai Gaya Tabel Bendahara) -->
            <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-ice-200">
                    <div>
                        <h2 class="font-display font-semibold text-base sm:text-lg text-navy-900">
                            Daftar Administrator Kasly
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Setiap Administrator memiliki hak penuh mengelola organisasi, meng-ACC permohonan, dan menerima notifikasi sistem.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/50">
                                <th class="py-3.5 px-4 font-semibold">Nama Admin</th>
                                <th class="py-3.5 px-4 font-semibold">Username</th>
                                <th class="py-3.5 px-4 font-semibold">Email</th>
                                <th class="py-3.5 px-4 font-semibold">WhatsApp Notifikasi</th>
                                <th class="py-3.5 px-4 font-semibold">Terdaftar Sejak</th>
                                <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ice-200 text-sm">
                            @foreach ($admins as $admin)
                                <tr class="hover:bg-ice-100/40 transition-colors">
                                    <td class="py-4 px-4 font-semibold text-navy-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $admin->name }}</span>
                                            @if ($admin->id === Auth::id())
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-butter-100 text-navy-900 border border-amber-200">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 font-mono text-xs text-navy-900">
                                        {{ '@' . ($admin->username ?: '-') }}
                                    </td>
                                    <td class="py-4 px-4 text-navy-500 text-xs">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="py-4 px-4 font-mono text-xs text-navy-900">
                                        {{ $admin->phone ?: '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-navy-500 tabular-nums text-xs">
                                        {{ $admin->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-4 text-right whitespace-nowrap">
                                        @if ($admin->id !== Auth::id())
                                            <form
                                                action="{{ route('admin.admins.destroy', $admin) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun Administrator {{ $admin->name }}?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="h-8 px-3 rounded-lg border border-red-200 text-danger bg-red-50/50 hover:bg-red-100 text-xs font-semibold transition-colors cursor-pointer inline-flex items-center gap-1.5"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-navy-500/60 font-medium italic">Sesi Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($admins->hasPages())
                    <div class="pt-3 border-t border-ice-200">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>

            <!-- Form Tambah Administrator Baru (Sesuai Gaya Kartu Profil Pengurus) -->
            <div id="form-tambah-admin" class="bg-white/90 rounded-2xl p-6 sm:p-8 border border-ice-200/70 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.9)] space-y-6">
                <div class="pb-3 border-b border-ice-200/70">
                    <h2 class="font-display font-semibold text-lg text-navy-900">
                        Tambah Administrator Kasly Baru
                    </h2>
                    <p class="text-xs text-navy-500 mt-0.5">
                        Buatkan akun pengelola pusat baru yang dapat login ke portal Administrator Kasly.
                    </p>
                </div>

                <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Nama Lengkap <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Rian Anggara"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('name') border-red-400 @else border-ice-200/80 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                            @error('name')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Username Login <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                placeholder="Contoh: rian_admin"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('username') border-red-400 @else border-ice-200/80 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                            @error('username')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Alamat Email <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="rian@kasly.id"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('email') border-red-400 @else border-ice-200/80 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                            @error('email')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Nomor WhatsApp Notifikasi <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                placeholder="081234567890"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm font-mono border @error('phone') border-red-400 @else border-ice-200/80 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                            @error('phone')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Kata Sandi <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="Minimal 8 karakter"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border @error('password') border-red-400 @else border-ice-200/80 @enderror shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                            @error('password')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-danger font-bold">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi kata sandi"
                                class="w-full h-11 px-4 rounded-[10px] bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 transition-all"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ice-200/70 flex items-center justify-end">
                        <button
                            type="submit"
                            class="h-10 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Daftarkan Administrator Baru</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>
