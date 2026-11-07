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
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="admins" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area: Kelola Administrator Kasly -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen md:h-screen overflow-y-auto custom-scrollbar pb-28 md:pb-8">

        <main class="p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">

            <!-- Alerts Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-white/95 border border-ice-200/80 text-navy-900 text-sm font-medium shadow-[4px_4px_16px_rgba(122,163,214,0.18),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-butter-100 text-navy-900 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">✓</div>
                        <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-400 hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-white/95 border border-danger/30 text-danger text-sm font-medium shadow-[4px_4px_16px_rgba(192,82,74,0.15),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-danger/10 text-danger flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">!</div>
                        <span class="text-xs sm:text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-danger hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">
                        Kelola Administrator Kasly
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Daftar seluruh akun Administrator platform dan penambahan pengelola pusat baru.
                    </p>
                </div>
            </div>

            <!-- Tabel Administrator Kasly Neumorphic -->
            <div class="bg-white/90 rounded-[28px] p-5 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3.5 border-b border-ice-200/70">
                    <div>
                        <h2 class="font-display font-bold text-base text-navy-900">
                            Daftar Administrator Kasly
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Setiap Administrator memiliki hak penuh mengelola organisasi, meng-ACC permohonan, dan menerima notifikasi sistem.
                        </p>
                    </div>
                    <span class="neu-badge-ice text-[10px] py-1 px-3 font-mono shrink-0 self-start sm:self-auto">{{ $admins->total() }} ADMIN AKTIF</span>
                </div>

                <div class="overflow-x-auto neu-scroll rounded-2xl border border-ice-200/80 shadow-xs">
                    <table class="w-full text-left text-xs min-w-[760px]">
                        <thead>
                            <tr class="bg-ice-100/70 border-b border-ice-200/80 text-navy-600 font-bold uppercase tracking-wider text-[11px]">
                                <th class="py-3.5 px-4">Nama Admin</th>
                                <th class="py-3.5 px-4">Username</th>
                                <th class="py-3.5 px-4">Email</th>
                                <th class="py-3.5 px-4">WhatsApp Notifikasi</th>
                                <th class="py-3.5 px-4">Terdaftar Sejak</th>
                                <th class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ice-100/80 bg-white/60">
                            @foreach ($admins as $admin)
                                <tr class="hover:bg-ice-100/50 transition-colors">
                                    <td class="py-3.5 px-4 font-semibold text-navy-900 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-navy-900 text-xs sm:text-sm">{{ $admin->name }}</span>
                                            @if ($admin->id === Auth::id())
                                                <span class="neu-badge-pill text-[9px] py-0.2 px-2 bg-butter-100 font-bold">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-xs text-navy-900 whitespace-nowrap">
                                        {{ '@' . ($admin->username ?: '-') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-navy-500 text-xs whitespace-nowrap">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="py-3.5 px-4 font-mono text-xs text-navy-900 whitespace-nowrap font-semibold">
                                        {{ $admin->phone ?: '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-navy-500 tabular-nums whitespace-nowrap font-mono text-[11px]">
                                        {{ $admin->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
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
                                                    class="h-8 px-3 rounded-lg border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium shadow-xs transition-colors cursor-pointer"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-[11px] text-navy-400 italic">Sesi Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($admins->hasPages())
                    <div class="pt-3 border-t border-ice-200/70">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>

            <!-- Form Tambah Administrator Baru Neumorphic -->
            <div class="bg-white/90 rounded-[28px] p-6 sm:p-8 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-6">
                <div class="pb-3.5 border-b border-ice-200/70">
                    <h2 class="font-display font-bold text-lg text-navy-900">
                        Tambah Administrator Kasly Baru
                    </h2>
                    <p class="text-xs text-navy-500 mt-0.5">
                        Buatkan akun pengelola pusat baru yang dapat login ke portal Super Admin Kasly.
                    </p>
                </div>

                <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Rian Anggara"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>

                        <div>
                            <label for="username" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Username Login <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                placeholder="Contoh: rian_admin"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="rian@kasly.id"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Nomor WhatsApp Notifikasi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                placeholder="081234567890"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-mono font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Kata Sandi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="Minimal 8 karakter"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-navy-900 mb-1.5">
                                Konfirmasi Kata Sandi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi kata sandi"
                                class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                            >
                        </div>
                    </div>

                    <div class="pt-3 border-t border-ice-200/70 flex items-center justify-end">
                        <button
                            type="submit"
                            class="h-11 px-6 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>Daftarkan Administrator Baru</span>
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>

</body>
</html>
