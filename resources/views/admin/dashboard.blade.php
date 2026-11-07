<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Pusat Super Admin - {{ config('app.name', 'Kasly') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Web (240px) -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 px-6 flex items-center border-b border-ice-200">
                <a href="{{ route('admin.dashboard') }}" class="font-display font-semibold text-2xl text-navy-900 tracking-tight">
                    {{ config('app.name', 'Kasly') }}
                </a>
            </div>

            <!-- Nav Items (Tanpa kelompok judul sesuai aturan v2) -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
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

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Panel Pusat Super Admin
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Kelola data organisasi dan pantau penggunaan sistem Kasly secara terpusat.
                    </p>
                </div>
            </div>

            <!-- Kartu Hero Stat (radius-blob khas Ice & Butter v2) -->
            <div class="bg-navy-900 text-white rounded-blob p-8 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-white/70">Total Organisasi Terdaftar</p>
                    <p class="font-display font-semibold text-4xl md:text-5xl tabular-nums mt-2">
                        {{ $totalOrganizations }}
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-4">
                    <div class="bg-white/10 px-5 py-3 rounded-md">
                        <span class="text-xs text-white/70 block">Organisasi Aktif</span>
                        <span class="font-display font-semibold text-xl tabular-nums mt-0.5 block">
                            {{ $activeOrganizations }}
                        </span>
                    </div>
                    <div class="bg-white/10 px-5 py-3 rounded-md">
                        <span class="text-xs text-white/70 block">Total Anggota Terdata</span>
                        <span class="font-display font-semibold text-xl tabular-nums mt-0.5 block">
                            {{ $totalMembers }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Bagian Tabel Organisasi -->
            <div class="bg-white rounded-lg shadow-card overflow-hidden">
                <div class="p-6 border-b border-ice-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-display font-medium text-lg text-navy-900">
                            Daftar Organisasi
                        </h2>
                        <p class="text-sm text-navy-500 mt-0.5">
                            Organisasi terdaftar dengan partisi data terisolasi.
                        </p>
                    </div>
                </div>

                @if ($organizations->isEmpty())
                    <!-- Empty State Sesuai v2: 1 Kalimat & 1 Tombol -->
                    <div class="p-12 text-center">
                        <p class="text-sm text-navy-500">
                            Belum ada organisasi yang terdaftar di dalam sistem Kasly.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-ice-200 text-xs font-medium text-navy-500">
                                    <th class="py-3 px-6">Kode Organisasi</th>
                                    <th class="py-3 px-6">Nama Organisasi</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6">Terdaftar Sejak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-200 text-sm">
                                @foreach ($organizations as $org)
                                    <tr class="hover:bg-ice-100/50 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-navy-900 font-display">
                                            {{ $org->code }}
                                        </td>
                                        <td class="py-4 px-6 text-navy-900">
                                            {{ $org->name }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($org->status === 'active')
                                                <span class="inline-block px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="text-danger font-medium text-xs">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-navy-500 tabular-nums">
                                            {{ $org->created_at->format('d M Y') }}
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

