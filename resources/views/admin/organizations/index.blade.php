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
    <main class="flex-1 min-w-0 p-6 lg:p-8">
        <div class="max-w-[1120px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900">
                        {{ Auth::user()->isAdminPenerima() ? 'Pengajuan Organisasi Masuk' : 'Manajemen Organisasi' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        {{ Auth::user()->isAdminPenerima()
                            ? 'Tinjau identitas pendaftar ormawa dan lakukan persetujuan (ACC) pengajuan yang masuk dari landing page.'
                            : 'Daftar seluruh entitas ormawa yang terdaftar dan persetujuan pengajuan baru.' }}
                    </p>
                </div>
                @if (Auth::user()->isSuperAdmin())
                    <div>
                        <a
                            href="{{ route('admin.organizations.create') }}"
                            class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:shadow-[inset_2px_2px_4px_rgba(0,0,0,0.4)] transition-all cursor-pointer inline-flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Organisasi Manual</span>
                        </a>
                    </div>
                @endif
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

            <!-- Ringkasan Statistik (Sesuai Kartu Metrik Jadwal Tagihan) -->
            @if (Auth::user()->isAdminPenerima())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs text-navy-500">Pengajuan Masuk Butuh ACC</p>
                            <p class="font-display font-semibold text-3xl text-navy-900 mt-1 tabular-nums">{{ $pendingRequestsCount }}</p>
                            <p class="text-[11px] text-navy-500 mt-0.5">
                                {{ $pendingRequestsCount > 0 ? 'Menunggu diverifikasi & disetujui' : 'Semua pengajuan telah diproses' }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-xl {{ $pendingRequestsCount > 0 ? 'bg-butter-100 text-amber-800 border border-amber-200/80' : 'bg-ice-100 text-navy-700 border border-ice-200' }} flex items-center justify-center font-bold shrink-0 shadow-xs">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs text-navy-500">Peran Anda</p>
                            <p class="font-display font-semibold text-xl text-navy-900 mt-1">Admin Penerima Organisasi</p>
                            <p class="text-[11px] text-navy-500 mt-0.5">Fokus menerima & memvalidasi ormawa baru</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-ice-100 text-navy-700 border border-ice-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                            <svg class="w-6 h-6 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm">
                        <p class="text-xs text-navy-500">Total Organisasi</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">{{ $totalOrganizations }}</p>
                        <p class="text-[11px] text-navy-500 mt-0.5">Terdaftar di sistem</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm">
                        <p class="text-xs text-navy-500">Organisasi Aktif</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">{{ $activeOrganizations }}</p>
                        <p class="text-[11px] text-emerald-600 font-medium mt-0.5">Dapat beroperasi</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm">
                        <p class="text-xs text-navy-500">Organisasi Nonaktif</p>
                        <p class="font-display font-semibold text-2xl text-danger mt-1 tabular-nums">{{ $inactiveOrganizations }}</p>
                        <p class="text-[11px] text-danger mt-0.5">Akses login ditangguhkan</p>
                    </div>

                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-ice-200 shadow-sm">
                        <p class="text-xs text-navy-500">Pengajuan Pending</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums flex items-center gap-2">
                            <span>{{ $pendingRequestsCount }}</span>
                            @if ($pendingRequestsCount > 0)
                                <span class="text-[11px] font-sans font-bold px-2 py-0.5 rounded-full bg-butter-100 text-navy-900 border border-amber-200">
                                    Butuh ACC
                                </span>
                            @endif
                        </p>
                        <p class="text-[11px] text-navy-500 mt-0.5">Menunggu verifikasi</p>
                    </div>
                </div>
            @endif

            <!-- Tab Navigasi (Hanya ditampilkan untuk Super Admin) -->
            @if (Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-2 pb-1 border-b border-ice-200">
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'organizations']) }}"
                        class="px-3.5 py-2 rounded-lg font-medium text-xs sm:text-sm transition-colors {{ $activeTab === 'organizations' ? 'bg-navy-900 text-white shadow-xs font-semibold' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/80' }}"
                    >
                        <span>Organisasi Terdaftar</span>
                        <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'organizations' ? 'bg-white/20 text-white' : 'bg-white text-navy-900 border border-ice-200/70' }}">
                            {{ $totalOrganizations }}
                        </span>
                    </a>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="px-3.5 py-2 rounded-lg font-medium text-xs sm:text-sm transition-colors {{ $activeTab === 'requests' ? 'bg-navy-900 text-white shadow-xs font-semibold' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/80' }}"
                    >
                        <span>Pengajuan Masuk (Pending)</span>
                        @if ($pendingRequestsCount > 0)
                            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs bg-butter-100 text-navy-900 font-bold border border-amber-200">
                                {{ $pendingRequestsCount }}
                            </span>
                        @else
                            <span class="ml-1.5 px-2 py-0.5 rounded-full text-xs {{ $activeTab === 'requests' ? 'bg-white/20 text-white' : 'bg-white text-navy-900 border border-ice-200/70' }}">
                                0
                            </span>
                        @endif
                    </a>
                </div>
            @endif

            <!-- TAMPILAN TABEL PENGAJUAN (Default untuk Admin Penerima atau tab=requests) -->
            @if (Auth::user()->isAdminPenerima() || $activeTab === 'requests')
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-semibold text-lg text-navy-900">
                                Daftar Pengajuan Pendaftaran Masuk
                            </h2>
                            <p class="text-xs sm:text-sm text-navy-500 mt-0.5">
                                Formulir pengajuan dari calon pengelola ormawa. Klik tombol <strong>Setujui (ACC)</strong> untuk mengaktifkan organisasi dan mengirimkan kredensial akun ke ketua/pemohon.
                            </p>
                        </div>
                    </div>

                    @if ($pendingRequests->isEmpty())
                        <div class="p-12 text-center space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-ice-100 border border-ice-200 text-navy-500 mx-auto flex items-center justify-center shadow-xs">
                                <svg class="w-7 h-7 text-navy-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-base font-bold text-navy-900">
                                Tidak ada pengajuan organisasi yang menunggu persetujuan
                            </p>
                            <p class="text-xs text-navy-500 max-w-md mx-auto">
                                Semua pengajuan yang masuk telah diproses. Formulir pengajuan baru yang diisi melalui landing page akan otomatis muncul di sini.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/50">
                                        <th class="py-3.5 px-4 font-semibold">Kode Diajukan</th>
                                        <th class="py-3.5 px-4 font-semibold">Nama Organisasi</th>
                                        <th class="py-3.5 px-4 font-semibold">Ketua / PIC Pemohon</th>
                                        <th class="py-3.5 px-4 font-semibold">Kontak Pemohon</th>
                                        <th class="py-3.5 px-4 font-semibold">Tanggal Diajukan</th>
                                        <th class="py-3.5 px-4 font-semibold text-right">Aksi Persetujuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-200 text-sm">
                                    @foreach ($pendingRequests as $req)
                                        <tr class="hover:bg-ice-100/40 transition-colors">
                                            <td class="py-4 px-4 font-semibold text-navy-900 font-display">
                                                <span class="inline-block px-2.5 py-1 rounded-md bg-ice-100 text-navy-900 text-xs tracking-wider border border-ice-200/80">
                                                    {{ $req->code }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-navy-900 font-medium">
                                                <div class="font-semibold">{{ $req->name }}</div>
                                                @if ($req->description)
                                                    <div class="text-xs text-navy-500 mt-0.5 max-w-[220px] truncate">{{ $req->description }}</div>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-navy-900 font-medium text-xs">
                                                <div class="font-semibold text-navy-900">{{ $req->pic_name }}</div>
                                                <div class="text-navy-500 font-mono mt-0.5">{{ '@' . $req->pic_username }}</div>
                                            </td>
                                            <td class="py-4 px-4 text-xs text-navy-500">
                                                <div class="font-mono text-navy-900 font-medium">{{ $req->pic_phone }}</div>
                                                <div class="text-navy-500">{{ $req->pic_email }}</div>
                                            </td>
                                            <td class="py-4 px-4 text-navy-500 tabular-nums text-xs">
                                                {{ $req->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td class="py-4 px-4 text-right whitespace-nowrap">
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
                                                            class="h-8 px-3 rounded-lg bg-navy-900 text-white font-semibold text-xs shadow-xs hover:bg-navy-700 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-1.5"
                                                            title="Setujui pengajuan organisasi ini"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            <span>Setujui (ACC)</span>
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
                                                            class="h-8 px-3 rounded-lg border border-red-200 text-danger bg-white hover:bg-red-50 text-xs font-medium transition-colors cursor-pointer"
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
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-semibold text-lg text-navy-900">
                                Daftar Seluruh Organisasi
                            </h2>
                            <p class="text-xs sm:text-sm text-navy-500 mt-0.5">
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
                                    class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-xs hover:bg-navy-700 transition-colors inline-flex items-center gap-2"
                                >
                                    Daftarkan Organisasi Pertama
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/50">
                                        <th class="py-3.5 px-4 font-semibold">Kode Organisasi</th>
                                        <th class="py-3.5 px-4 font-semibold">Nama Organisasi</th>
                                        <th class="py-3.5 px-4 font-semibold">Deskripsi</th>
                                        <th class="py-3.5 px-4 font-semibold">Status</th>
                                        <th class="py-3.5 px-4 font-semibold">Terdaftar Sejak</th>
                                        <th class="py-3.5 px-4 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-200 text-sm">
                                    @foreach ($organizations as $org)
                                        <tr class="hover:bg-ice-100/40 transition-colors">
                                            <td class="py-4 px-4 font-semibold text-navy-900 font-display">
                                                <span class="inline-block px-2.5 py-1 rounded-md bg-ice-100 text-navy-900 text-xs tracking-wider border border-ice-200/80">
                                                    {{ $org->code }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-4 text-navy-900 font-medium">
                                                <div class="font-semibold">{{ $org->name }}</div>
                                            </td>
                                            <td class="py-4 px-4 text-navy-500 text-xs max-w-[200px] truncate">
                                                {{ $org->description ?: '-' }}
                                            </td>
                                            <td class="py-4 px-4">
                                                @if ($org->status === 'active')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-danger border border-red-200 text-xs font-semibold">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-4 text-navy-500 tabular-nums text-xs">
                                                {{ $org->created_at->format('d M Y') }}
                                            </td>
                                            <td class="py-4 px-4 text-right whitespace-nowrap">
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
                                                                class="h-8 px-3 rounded-lg border border-ice-200 text-navy-700 bg-white hover:bg-ice-100 text-xs font-medium transition-colors cursor-pointer"
                                                                title="Nonaktifkan akses organisasi"
                                                            >
                                                                Nonaktifkan
                                                            </button>
                                                        @else
                                                            <button
                                                                type="submit"
                                                                class="h-8 px-3 rounded-lg bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold transition-colors cursor-pointer"
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
                                                            class="h-8 px-3 rounded-lg border border-red-200 text-danger bg-white hover:bg-red-50 text-xs font-medium transition-colors cursor-pointer"
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
