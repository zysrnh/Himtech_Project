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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="organizations" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area -->
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
                        {{ Auth::user()->isAdminPenerima() ? 'Pengajuan Organisasi Masuk' : 'Manajemen Organisasi' }}
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        {{ Auth::user()->isAdminPenerima()
                            ? 'Tinjau identitas pendaftar ormawa dan lakukan persetujuan (ACC) pengajuan yang masuk dari landing page.'
                            : 'Daftar seluruh entitas ormawa yang terdaftar dan persetujuan pengajuan baru.' }}
                    </p>
                </div>
                @if (Auth::user()->isSuperAdmin())
                    <div class="shrink-0">
                        <a
                            href="{{ route('admin.organizations.create') }}"
                            class="h-11 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all flex items-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Tambah Organisasi Manual</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Ringkasan Statistik Neumorphic -->
            @if (Auth::user()->isAdminPenerima())
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="bg-white/90 rounded-[28px] p-6 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold text-navy-500 uppercase tracking-wider">Pengajuan Masuk Butuh ACC</p>
                            <p class="font-display font-bold text-3xl sm:text-4xl text-navy-900 mt-2 tabular-nums">{{ $pendingRequestsCount }}</p>
                        </div>
                        @if ($pendingRequestsCount > 0)
                            <span class="neu-badge-pill text-xs py-1 px-3 bg-butter-100">
                                Perlu Ditinjau
                            </span>
                        @endif
                    </div>
                    <div class="bg-white/90 rounded-[28px] p-6 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)]">
                        <p class="text-xs font-semibold text-navy-500 uppercase tracking-wider">Peran Anda</p>
                        <p class="font-display font-bold text-xl text-navy-900 mt-2">Admin Penerima Organisasi</p>
                        <p class="text-xs text-navy-500 mt-1">Fokus menerima & memvalidasi ormawa baru</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white/90 rounded-[24px] p-5 border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.95)]">
                        <p class="text-[11px] font-bold text-navy-500 uppercase tracking-wider">Total Organisasi</p>
                        <p class="font-display font-bold text-2xl text-navy-900 mt-1.5 tabular-nums">{{ $totalOrganizations }}</p>
                    </div>
                    <div class="bg-white/90 rounded-[24px] p-5 border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.95)]">
                        <p class="text-[11px] font-bold text-navy-500 uppercase tracking-wider">Organisasi Aktif</p>
                        <p class="font-display font-bold text-2xl text-emerald-700 mt-1.5 tabular-nums">{{ $activeOrganizations }}</p>
                    </div>
                    <div class="bg-white/90 rounded-[24px] p-5 border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.95)]">
                        <p class="text-[11px] font-bold text-navy-500 uppercase tracking-wider">Organisasi Nonaktif</p>
                        <p class="font-display font-bold text-2xl text-danger mt-1.5 tabular-nums">{{ $inactiveOrganizations }}</p>
                    </div>
                    <div class="bg-white/90 rounded-[24px] p-5 border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.95)]">
                        <p class="text-[11px] font-bold text-navy-500 uppercase tracking-wider">Pengajuan Pending</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <p class="font-display font-bold text-2xl text-navy-900 tabular-nums">{{ $pendingRequestsCount }}</p>
                            @if ($pendingRequestsCount > 0)
                                <span class="neu-badge-pill text-[10px] py-0.5 px-2 bg-butter-100">
                                    Butuh ACC
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tab Navigasi (Hanya ditampilkan untuk Super Admin) -->
            @if (Auth::user()->isSuperAdmin())
                <div class="flex items-center gap-1.5 p-1.5 bg-ice-100/80 rounded-2xl border border-ice-200/60 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.2)] text-xs overflow-x-auto neu-scroll">
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'organizations']) }}"
                        class="px-4 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ $activeTab === 'organizations' ? 'bg-white text-navy-900 shadow-xs' : 'text-navy-500 hover:text-navy-900' }}"
                    >
                        <span>Organisasi Terdaftar</span>
                        <span class="neu-badge-ice text-[10px] py-0.2 px-2">
                            {{ $totalOrganizations }}
                        </span>
                    </a>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="px-4 py-2 rounded-xl font-bold whitespace-nowrap transition-all flex items-center gap-2 {{ $activeTab === 'requests' ? 'bg-white text-navy-900 shadow-xs' : 'text-navy-500 hover:text-navy-900' }}"
                    >
                        <span>Pengajuan Masuk (Pending)</span>
                        @if ($pendingRequestsCount > 0)
                            <span class="neu-badge-pill text-[10px] py-0.2 px-2 bg-butter-100">
                                {{ $pendingRequestsCount }}
                            </span>
                        @else
                            <span class="neu-badge-ice text-[10px] py-0.2 px-2">
                                0
                            </span>
                        @endif
                    </a>
                </div>
            @endif

            <!-- TAMPILAN TABEL PENGAJUAN (Default untuk Admin Penerima atau tab=requests) -->
            @if (Auth::user()->isAdminPenerima() || $activeTab === 'requests')
                <div class="bg-white/90 rounded-[28px] p-5 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200/70">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Daftar Pengajuan Pendaftaran Masuk
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Formulir pengajuan dari calon pengelola ormawa. Klik tombol <strong class="text-navy-900">Setujui (ACC)</strong> untuk mengaktifkan organisasi dan mengirimkan kredensial akun ke pemohon.
                            </p>
                        </div>
                    </div>

                    @if ($pendingRequests->isEmpty())
                        <div class="py-16 text-center space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-ice-100 mx-auto mb-3 flex items-center justify-center text-navy-400 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-navy-900">
                                Tidak ada pengajuan organisasi yang menunggu persetujuan.
                            </p>
                            <p class="text-xs text-navy-500">
                                Semua pengajuan yang masuk telah diproses. Formulir pengajuan baru akan otomatis tampil di sini.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto neu-scroll rounded-2xl border border-ice-200/80 shadow-xs">
                            <table class="w-full text-left text-xs min-w-[760px]">
                                <thead>
                                    <tr class="bg-ice-100/70 border-b border-ice-200/80 text-navy-600 font-bold uppercase tracking-wider text-[11px]">
                                        <th class="py-3.5 px-4">Kode Diajukan</th>
                                        <th class="py-3.5 px-4">Nama Organisasi</th>
                                        <th class="py-3.5 px-4">Ketua / PIC Pemohon</th>
                                        <th class="py-3.5 px-4">Kontak Pemohon</th>
                                        <th class="py-3.5 px-4">Tanggal Diajukan</th>
                                        <th class="py-3.5 px-4 text-right">Aksi Persetujuan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-100/80 bg-white/60">
                                    @foreach ($pendingRequests as $req)
                                        <tr class="hover:bg-ice-100/50 transition-colors">
                                            <td class="py-3.5 px-4 font-semibold text-navy-900 font-mono whitespace-nowrap">
                                                <span class="neu-badge-ice text-xs py-0.5 px-2.5 font-bold">
                                                    {{ $req->code }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-900 font-medium">
                                                <div class="font-bold text-navy-900 text-xs sm:text-sm">{{ $req->name }}</div>
                                                @if ($req->description)
                                                    <div class="text-[11px] text-navy-500 mt-0.5 max-w-xs truncate">{{ $req->description }}</div>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-900 font-medium whitespace-nowrap">
                                                <div class="font-semibold">{{ $req->pic_name }}</div>
                                                <div class="text-navy-500 font-mono text-[10.5px] mt-0.5">{{ '@' . $req->pic_username }}</div>
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-500 whitespace-nowrap">
                                                <div class="font-mono text-navy-900 font-semibold">{{ $req->pic_phone }}</div>
                                                <div class="text-[11px] text-navy-500">{{ $req->pic_email }}</div>
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-500 tabular-nums whitespace-nowrap font-mono text-[11px]">
                                                {{ $req->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
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
                                                            class="h-8 px-3.5 rounded-lg bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold shadow-xs transition-colors cursor-pointer"
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
                                                            class="h-8 px-3 rounded-lg border border-ice-200 text-danger bg-white hover:bg-danger/10 text-xs font-medium shadow-xs transition-colors cursor-pointer"
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
                            <div class="pt-3 border-t border-ice-200/70">
                                {{ $pendingRequests->links() }}
                            </div>
                        @endif
                    @endif
                </div>

            @else
                <!-- TAB 1: ORGANISASI TERDAFTAR (HANYA SUPER ADMIN) -->
                <div class="bg-white/90 rounded-[28px] p-5 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200/70">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Daftar Seluruh Organisasi
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Menampilkan data tenant ormawa terdaftar beserta status keaktifan dan kontrol akses.
                            </p>
                        </div>
                    </div>

                    @if ($organizations->isEmpty())
                        <div class="py-16 text-center space-y-4">
                            <p class="text-sm text-navy-500">
                                Belum ada organisasi yang terdaftar di dalam sistem Kasly.
                            </p>
                            <div>
                                <a
                                    href="{{ route('admin.organizations.create') }}"
                                    class="h-10 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs inline-flex items-center gap-2"
                                >
                                    Daftarkan Organisasi Pertama
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto neu-scroll rounded-2xl border border-ice-200/80 shadow-xs">
                            <table class="w-full text-left text-xs min-w-[760px]">
                                <thead>
                                    <tr class="bg-ice-100/70 border-b border-ice-200/80 text-navy-600 font-bold uppercase tracking-wider text-[11px]">
                                        <th class="py-3.5 px-4">Kode Organisasi</th>
                                        <th class="py-3.5 px-4">Nama Organisasi</th>
                                        <th class="py-3.5 px-4">Deskripsi</th>
                                        <th class="py-3.5 px-4">Status</th>
                                        <th class="py-3.5 px-4">Terdaftar Sejak</th>
                                        <th class="py-3.5 px-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-100/80 bg-white/60">
                                    @foreach ($organizations as $org)
                                        <tr class="hover:bg-ice-100/50 transition-colors">
                                            <td class="py-3.5 px-4 font-semibold text-navy-900 font-mono whitespace-nowrap">
                                                <span class="neu-badge-ice text-xs py-0.5 px-2.5 font-bold">
                                                    {{ $org->code }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-900 font-medium">
                                                <span class="font-bold text-navy-900 text-xs sm:text-sm">{{ $org->name }}</span>
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-500 text-xs max-w-xs truncate">
                                                {{ $org->description ?: '-' }}
                                            </td>
                                            <td class="py-3.5 px-4 whitespace-nowrap">
                                                @if ($org->status === 'active')
                                                    <span class="neu-badge-pill text-[10px] py-0.5 px-2.5 bg-butter-100">
                                                        Aktif
                                                    </span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-full bg-danger/10 text-danger border border-danger/20 text-[10px] font-bold">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3.5 px-4 text-navy-500 tabular-nums whitespace-nowrap font-mono text-[11px]">
                                                {{ $org->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
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
                                                                class="h-8 px-3 rounded-lg border border-ice-200 text-navy-900 bg-white hover:bg-ice-100 text-xs font-medium shadow-xs transition-colors cursor-pointer"
                                                                title="Nonaktifkan akses organisasi"
                                                            >
                                                                Nonaktifkan
                                                            </button>
                                                        @else
                                                            <button
                                                                type="submit"
                                                                class="h-8 px-3 rounded-lg bg-navy-900 text-white hover:bg-navy-700 text-xs font-semibold shadow-xs transition-colors cursor-pointer"
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
                                                            class="h-8 px-3 rounded-lg border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium shadow-xs transition-colors cursor-pointer"
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
                            <div class="pt-3 border-t border-ice-200/70">
                                {{ $organizations->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            @endif

        </main>
    </div>

</body>
</html>
