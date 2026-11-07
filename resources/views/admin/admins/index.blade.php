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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Web (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 px-6 flex items-center border-b border-ice-200">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
                </a>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Overview
                </a>
                <a
                    href="{{ route('admin.organizations.index') }}"
                    class="flex items-center justify-between h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    <span>Organisasi</span>
                    @if ($pendingRequestsCount > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-butter-100 text-navy-900 text-xs font-bold font-display">
                            {{ $pendingRequestsCount }}
                        </span>
                    @endif
                </a>
                <a
                    href="{{ route('admin.admins.index') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Kelola Admin
                </a>
                <a
                    href="{{ route('admin.profile') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Profil Admin
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
                    <p class="text-sm font-medium text-navy-900 truncate">
                        {{ Auth::user()->name ?? 'Super Admin' }}
                    </p>
                    <p class="text-[12px] text-navy-500 truncate">
                        {{ Auth::user()->email ?? 'admin@kasly.id' }}
                    </p>
                </div>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
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

    <!-- Main Content Area: Kelola Administrator Kasly -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Kelola Administrator Kasly
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftar seluruh akun Administrator platform dan penambahan pengelola pusat baru.
                    </p>
                </div>
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

            <!-- Tabel Administrator Kasly -->
            <div class="bg-white rounded-lg shadow-card overflow-hidden border border-ice-200">
                <div class="p-6 border-b border-ice-200">
                    <h2 class="font-display font-medium text-lg text-navy-900">
                        Daftar Administrator Kasly
                    </h2>
                    <p class="text-sm text-navy-500 mt-0.5">
                        Setiap Administrator memiliki hak penuh mengelola organisasi, meng-ACC permohonan, dan menerima notifikasi sistem.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/40">
                                <th class="py-3.5 px-6">Nama Admin</th>
                                <th class="py-3.5 px-6">Username</th>
                                <th class="py-3.5 px-6">Email</th>
                                <th class="py-3.5 px-6">WhatsApp Notifikasi</th>
                                <th class="py-3.5 px-6">Terdaftar Sejak</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ice-200 text-sm">
                            @foreach ($admins as $admin)
                                <tr class="hover:bg-ice-100/50 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-navy-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $admin->name }}</span>
                                            @if ($admin->id === Auth::id())
                                                <span class="px-2 py-0.5 rounded text-[11px] bg-butter-100 text-navy-900 font-semibold font-sans">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-navy-900">
                                        {{ '@' . ($admin->username ?: '-') }}
                                    </td>
                                    <td class="py-4 px-6 text-navy-500 text-xs">
                                        {{ $admin->email }}
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-navy-900">
                                        {{ $admin->phone ?: '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-navy-500 tabular-nums text-xs">
                                        {{ $admin->created_at->format('d M Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
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
                                                    class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium transition-colors cursor-pointer"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-navy-500/50 italic">Sesi Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($admins->hasPages())
                    <div class="p-4 border-t border-ice-200">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>

            <!-- Form Tambah Administrator Baru -->
            <div class="bg-white rounded-lg p-8 shadow-card border border-ice-200">
                <div class="pb-4 border-b border-ice-200 mb-6">
                    <h2 class="font-display font-medium text-xl text-navy-900">
                        Tambah Administrator Kasly Baru
                    </h2>
                    <p class="text-sm text-navy-500 mt-1">
                        Buatkan akun pengelola pusat baru yang dapat login ke portal Super Admin Kasly.
                    </p>
                </div>

                <form action="{{ route('admin.admins.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Rian Anggara"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="username" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Username Login <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                placeholder="Contoh: rian_admin"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="rian@kasly.id"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Nomor WhatsApp Notifikasi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                placeholder="081234567890"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Kata Sandi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="Minimal 8 karakter"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Konfirmasi Kata Sandi <span class="text-danger">*</span>
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi kata sandi"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ice-200 flex items-center justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                        >
                            Daftarkan Administrator Baru
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>

