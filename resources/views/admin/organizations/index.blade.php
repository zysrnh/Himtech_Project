<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ Auth::user()->isAdminPenerima() ? 'Pengajuan Organisasi' : 'Manajemen Organisasi' }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="organizations" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        {{ Auth::user()->isAdminPenerima() ? 'Pengajuan Organisasi Masuk' : 'Manajemen Organisasi' }}
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        {{ Auth::user()->isAdminPenerima()
                            ? 'Tinjau identitas pendaftar ormawa dan lakukan persetujuan (ACC) pengajuan yang masuk dari landing page.'
                            : 'Daftar seluruh entitas ormawa yang terdaftar dan persetujuan pengajuan baru.' }}
                    </p>
                </div>
                @if (Auth::user()->isSuperAdmin())
                    <div>
                        <a
                            href="{{ route('admin.organizations.create') }}"
                            class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm"
                        >
                            + Tambah Organisasi Manual
                        </a>
                    </div>
                @endif
            </div>

            <!-- Ringkasan Statistik -->
            @if (Auth::user()->isAdminPenerima())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Pengajuan Masuk Butuh ACC</p>
                            <p class="font-display font-semibold text-3xl text-navy-900 mt-1 tabular-nums">{{ $pendingRequestsCount }}</p>
                        </div>
                        @if ($pendingRequestsCount > 0)
                            <span class="px-3 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-bold">
                                Perlu Ditinjau
                            </span>
                        @endif
                    </div>
                    <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm">
                        <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Peran Anda</p>
                        <p class="font-display font-semibold text-lg text-navy-900 mt-1">Admin Penerima Organisasi</p>
                        <p class="text-xs text-navy-500 mt-0.5">Fokus menerima & memvalidasi ormawa baru</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
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
                    <div class="p-5 rounded-lg bg-white border border-ice-200 shadow-sm">
                        <p class="text-xs font-medium text-navy-500 uppercase tracking-wider">Pengajuan Masuk (Pending)</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums flex items-center gap-2">
                            <span>{{ $pendingRequestsCount }}</span>
                            @if ($pendingRequestsCount > 0)
                                <span class="text-xs font-sans font-semibold px-2 py-0.5 rounded-full bg-butter-100 text-navy-900">
                                    Butuh ACC
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif

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

            <!-- Tab Navigasi (Hanya ditampilkan untuk Super Admin) -->
            @if (Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-2 border-b border-ice-200 pb-3">
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'organizations']) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition-colors {{ $activeTab === 'organizations' ? 'bg-navy-900 text-white' : 'text-navy-500 hover:text-navy-900 hover:bg-white' }}"
                    >
                        <span>Organisasi Terdaftar</span>
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'organizations' ? 'bg-white/20 text-white' : 'bg-ice-100 text-navy-900' }}">
                            {{ $totalOrganizations }}
                        </span>
                    </a>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition-colors {{ $activeTab === 'requests' ? 'bg-navy-900 text-white' : 'text-navy-500 hover:text-navy-900 hover:bg-white' }}"
                    >
                        <span>Pengajuan Masuk (Pending)</span>
                        @if ($pendingRequestsCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-xs bg-butter-100 text-navy-900 font-bold">
                                {{ $pendingRequestsCount }}
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'requests' ? 'bg-white/20 text-white' : 'bg-ice-100 text-navy-900' }}">
                                0
                            </span>
                        @endif
                    </a>
                </div>
            @endif

            <!-- TAMPILAN TABEL PENGAJUAN (Default untuk Admin Penerima atau tab=requests) -->
            @if (Auth::user()->isAdminPenerima() || $activeTab === 'requests')
                <div class="bg-white rounded-lg shadow-card overflow-hidden border border-ice-200">
                    <div class="p-6 border-b border-ice-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Daftar Pengajuan Pendaftaran Masuk
                            </h2>
                            <p class="text-sm text-navy-500 mt-0.5">
                                Formulir pengajuan dari calon pengelola ormawa. Klik tombol <strong>Setujui (ACC)</strong> untuk mengaktifkan organisasi dan mengirimkan kredensial akun ke ketua/pemohon.
                            </p>
                        </div>
                    </div>

                    @if ($pendingRequests->isEmpty())
                        <div class="p-12 text-center space-y-2">
                            <p class="text-sm font-medium text-navy-900">
                                Tidak ada pengajuan organisasi yang menunggu persetujuan.
                            </p>
                            <p class="text-xs text-navy-500">
                                Semua pengajuan yang masuk telah diproses. Formulir pengajuan baru yang diisi di landing page akan otomatis muncul di sini.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/40">
                                        <th class="py-3.5 px-6">Kode Diajukan</th>
                                        <th class="py-3.5 px-6">Nama Organisasi</th>
                                        <th class="py-3.5 px-6">Ketua / PIC Pemohon</th>
                                        <th class="py-3.5 px-6">Kontak Pemohon</th>
                                        <th class="py-3.5 px-6">Tanggal Diajukan</th>
                                        <th class="py-3.5 px-6 text-right">Aksi Persetujuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-200 text-sm">
                                    @foreach ($pendingRequests as $req)
                                        <tr class="hover:bg-ice-100/50 transition-colors">
                                            <td class="py-4 px-6 font-semibold text-navy-900 font-display">
                                                <span class="inline-block px-2.5 py-1 rounded bg-ice-100 text-navy-900 text-xs tracking-wider">
                                                    {{ $req->code }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-navy-900 font-medium">
                                                <div>{{ $req->name }}</div>
                                                @if ($req->description)
                                                    <div class="text-xs text-navy-500 mt-0.5 max-w-[200px] truncate">{{ $req->description }}</div>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 text-navy-900 font-medium text-xs">
                                                <div>{{ $req->pic_name }}</div>
                                                <div class="text-navy-500 font-mono mt-0.5">{{ '@' . $req->pic_username }}</div>
                                            </td>
                                            <td class="py-4 px-6 text-xs text-navy-500">
                                                <div class="font-mono text-navy-900 font-medium">{{ $req->pic_phone }}</div>
                                                <div class="text-navy-500">{{ $req->pic_email }}</div>
                                            </td>
                                            <td class="py-4 px-6 text-navy-500 tabular-nums text-xs">
                                                {{ $req->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <!-- Tombol Setujui (ACC) -->
                                                    <form
                                                        action="{{ route('admin.organizations.requests.approve', $req) }}"
                                                        method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('SETUJUI (ACC) pengajuan organisasi {{ $req->name }} ({{ $req->code }})?\n\nSistem akan otomatis mengaktifkan organisasi, membuat akun pengelola, dan mengirimkan password via WhatsApp ke {{ $req->pic_phone }}.');"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center h-8 px-3 rounded-md bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold transition-colors cursor-pointer shadow-sm"
                                                            title="Setujui pengajuan organisasi ini"
                                                        >
                                                            Setujui (ACC)
                                                        </button>
                                                    </form>

                                                    <!-- Tombol Tolak -->
                                                    <form
                                                        action="{{ route('admin.organizations.requests.reject', $req) }}"
                                                        method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Tolak pengajuan organisasi {{ $req->name }}?');"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-ice-200 text-danger bg-white hover:bg-danger/10 text-xs font-medium transition-colors cursor-pointer"
                                                            title="Tolak pengajuan organisasi ini"
                                                        >
                                                            Tolak
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($pendingRequests->hasPages())
                            <div class="p-4 border-t border-ice-200">
                                {{ $pendingRequests->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            @else
                <!-- TAB 1: ORGANISASI TERDAFTAR (HANYA SUPER ADMIN) -->
                <div class="bg-white rounded-lg shadow-card overflow-hidden border border-ice-200">
                    <div class="p-6 border-b border-ice-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Daftar Seluruh Organisasi
                            </h2>
                            <p class="text-sm text-navy-500 mt-0.5">
                                Menampilkan data tenant ormawa terdaftar beserta status keaktifan dan kontrol akses.
                            </p>
                        </div>
                    </div>

                    @if ($organizations->isEmpty())
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
                                            <td class="py-4 px-6 text-navy-500 text-xs max-w-[200px] truncate">
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
                                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                                <div class="inline-flex items-center gap-2">
                                                    <!-- Tombol Toggle Aktif / Nonaktif -->
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
                                                                class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-ice-200 text-navy-900 bg-white hover:bg-ice-100 text-xs font-medium transition-colors cursor-pointer"
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

                                                    <!-- Tombol Hapus Permanen -->
                                                    <form
                                                        action="{{ route('admin.organizations.destroy', $org) }}"
                                                        method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN organisasi {{ $org->name }} ({{ $org->code }})?\n\nSeluruh data anggota, transaksi kas, dan tagihan akan terhapus selamanya.');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            type="submit"
                                                            class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium transition-colors cursor-pointer"
                                                            title="Hapus permanen organisasi dan seluruh datanya"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
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
            @endif

        </div>
    </main>

</body>
</html>
