<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Organisasi - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

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
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Organisasi
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

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-ice-200">
            <div class="px-2 mb-3">
                <p class="text-sm font-medium text-navy-900 truncate">
                    {{ Auth::user()->name ?? 'Super Admin' }}
                </p>
                <p class="text-[13px] text-navy-500 truncate">
                    {{ Auth::user()->email ?? 'admin@kasly.id' }}
                </p>
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

    <!-- Main Content Area: Manajemen Organisasi -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Manajemen Organisasi
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftar seluruh entitas ormawa yang terdaftar di dalam platform Kasly.
                    </p>
                </div>
                <div>
                    <a
                        href="{{ route('admin.organizations.create') }}"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm"
                    >
                        + Tambah Organisasi Baru
                    </a>
                </div>
            </div>

            <!-- Ringkasan Statistik Status Organisasi -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm">
                    <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Total Organisasi</p>
                    <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">{{ $totalOrganizations }}</p>
                </div>
                <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm">
                    <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Organisasi Aktif</p>
                    <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">{{ $activeOrganizations }}</p>
                </div>
                <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm">
                    <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Organisasi Nonaktif</p>
                    <p class="font-display font-semibold text-2xl text-danger mt-1 tabular-nums">{{ $inactiveOrganizations }}</p>
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
                    Ada kesalahan dalam pemrosesan data. Silakan coba kembali.
                </div>
            @endif

            <!-- Bagian Tabel Organisasi -->
            <div class="bg-white rounded-lg shadow-card overflow-hidden border border-ice-200">
                <div class="p-6 border-b border-ice-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h2 class="font-display font-medium text-lg text-navy-900">
                            Daftar Seluruh Organisasi
                        </h2>
                        <p class="text-sm text-navy-500 mt-0.5">
                            Menampilkan data tenant ormawa terdaftar beserta status keaktifan sistem.
                        </p>
                    </div>
                </div>

                @if ($organizations->isEmpty())
                    <!-- Empty State -->
                    <div class="p-12 text-center space-y-4">
                        <p class="text-sm text-navy-500">
                            Belum ada organisasi yang terdaftar di dalam sistem Kasly.
                        </p>
                        <div>
                            <a
                                href="{{ route('admin.organizations.create') }}"
                                class="inline-flex items-center justify-center h-10 px-4 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors"
                            >
                                Daftarkan Organisasi Pertama
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/40">
                                    <th class="py-3.5 px-6">Kode Organisasi</th>
                                    <th class="py-3.5 px-6">Nama Organisasi</th>
                                    <th class="py-3.5 px-6">Deskripsi</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6">Terdaftar Sejak</th>
                                    <th class="py-3.5 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-200 text-sm">
                                @foreach ($organizations as $org)
                                    <tr class="hover:bg-ice-100/50 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-navy-900 font-display">
                                            <span class="inline-block px-2.5 py-1 rounded bg-ice-100 text-navy-900 text-xs tracking-wider">
                                                {{ $org->code }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-navy-900 font-medium">
                                            {{ $org->name }}
                                        </td>
                                        <td class="py-4 px-6 text-navy-500 text-xs max-w-[240px] truncate">
                                            {{ $org->description ?: '-' }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($org->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-danger/10 text-danger text-xs font-semibold">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-navy-500 tabular-nums text-xs">
                                            {{ $org->created_at->format('d M Y') }}
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <form
                                                action="{{ route('admin.organizations.toggle-status', $org) }}"
                                                method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin {{ $org->status === 'active' ? 'menonaktifkan' : 'mengaktifkan kembali' }} organisasi {{ $org->name }} ({{ $org->code }})? {{ $org->status === 'active' ? 'Staff dan anggota tidak akan dapat login selama dinonaktifkan.' : 'Akses login staff dan anggota akan dipulihkan.' }}');"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                @if ($org->status === 'active')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium transition-colors cursor-pointer"
                                                        title="Nonaktifkan akses organisasi"
                                                    >
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center h-8 px-3 rounded-md bg-navy-900 text-white hover:bg-navy-700 text-xs font-medium transition-colors cursor-pointer"
                                                        title="Aktifkan kembali akses organisasi"
                                                    >
                                                        Aktifkan
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($organizations->hasPages())
                        <div class="p-4 border-t border-ice-200">
                            {{ $organizations->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </main>

</body>
</html>
