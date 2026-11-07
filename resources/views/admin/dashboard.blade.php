<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Overview Sistem - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Super Admin Neumorphic Interaktif -->
    <x-sidebar-admin active="dashboard" :pending-requests-count="$pendingRequestsCount ?? null" />

    <!-- Main Content Area: Overview -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900 leading-tight">
                        Overview Sistem
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Ringkasan telemetri, metrik global, dan status operasional sistem multi-tenant Kasly.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('admin.organizations.index') }}"
                        class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:shadow-[inset_2px_2px_4px_rgba(0,0,0,0.4)] transition-all cursor-pointer inline-flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Kelola Organisasi</span>
                    </a>
                </div>
            </div>

            <!-- Notifikasi Pengajuan Baru (Banner Alert) -->
            @if ($pendingRequestsCount > 0)
                <div class="p-4 sm:p-5 rounded-xl bg-white border border-amber-200 text-navy-900 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-butter-100 text-amber-900 border border-amber-200 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">
                            {{ $pendingRequestsCount }}
                        </span>
                        <div>
                            <p class="text-sm font-semibold text-navy-900">
                                Ada {{ $pendingRequestsCount }} pengajuan organisasi baru yang menunggu persetujuan Anda!
                            </p>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Segera tinjau identitas pemohon dan lakukan persetujuan (ACC) agar akun pengelola ormawa aktif.
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                        class="h-9 px-4 rounded-lg bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shrink-0 inline-flex items-center justify-center shadow-xs"
                    >
                        Tinjau & Setujui (ACC) Sekarang
                    </a>
                </div>
            @endif

            <!-- Kartu Hero Stat Telemetri (Sesuai Gaya Kartu Saldo Kas Bendahara) -->
            <div class="bg-gradient-to-br from-navy-700 via-navy-900 to-[#182635] text-white rounded-[28px] p-6 sm:p-8 shadow-[8px_14px_32px_rgba(47,72,98,0.36),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-white/10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-ice-400/10 blur-2xl pointer-events-none"></div>

                <div class="relative z-1">
                    <span class="text-xs text-ice-200 font-semibold tracking-wider uppercase block">Total Organisasi Terdaftar</span>
                    <p class="font-display font-extrabold text-3xl sm:text-4xl md:text-5xl tabular-nums mt-2 tracking-tight text-white">
                        {{ $totalOrganizations }}
                    </p>
                    <p class="text-xs text-ice-200/80 mt-2 font-medium">
                        Ekosistem multi-tenant terisolasi aktif untuk seluruh ormawa kampus.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-5 lg:pt-0 border-t lg:border-t-0 lg:border-l border-white/15 lg:pl-8 shrink-0 relative z-1">
                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Organisasi Aktif</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums text-white block mt-1">
                            {{ $activeOrganizations }}
                        </span>
                        <span class="text-[11px] text-emerald-400 block mt-0.5 font-medium">● Beroperasi Penuh</span>
                    </div>

                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Total Anggota Terdata</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums text-white block mt-1">
                            {{ $totalMembers }}
                        </span>
                        <span class="text-[11px] text-ice-200/60 block mt-0.5">Mahasiswa Terdata</span>
                    </div>

                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Pengajuan Pending</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums block mt-1 {{ $pendingRequestsCount > 0 ? 'text-butter-100' : 'text-white' }}">
                            {{ $pendingRequestsCount }}
                        </span>
                        <span class="text-[11px] {{ $pendingRequestsCount > 0 ? 'text-butter-200 font-medium' : 'text-ice-200/60' }} block mt-0.5">
                            {{ $pendingRequestsCount > 0 ? 'Menunggu Review' : 'Semua Bersih' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Widget Antrean Pengajuan Masuk (Jika Ada) -->
            @if ($pendingRequests->isNotEmpty())
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-semibold text-base sm:text-lg text-navy-900">
                                Pengajuan Organisasi Terbaru yang Butuh ACC
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Formulir permohonan pendaftaran yang baru masuk dari calon pengelola ormawa.
                            </p>
                        </div>
                        <a href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}" class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                            Lihat Semua ({{ $pendingRequestsCount }})
                        </a>
                    </div>

                    <div class="divide-y divide-ice-200">
                        @foreach ($pendingRequests as $req)
                            <div class="py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-sm text-navy-900">{{ $req->name }}</span>
                                        <span class="font-mono text-xs px-2 py-0.5 rounded-md bg-ice-100 text-navy-900 border border-ice-200/80">{{ $req->code }}</span>
                                    </div>
                                    <p class="text-xs text-navy-500 mt-1">
                                        Pemohon: <strong class="text-navy-900 font-medium">{{ $req->pic_name }}</strong> (WA: {{ $req->pic_phone }}) · Diajukan {{ $req->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <form
                                        action="{{ route('admin.organizations.requests.approve', $req) }}"
                                        method="POST"
                                        onsubmit="return confirm('SETUJUI (ACC) pengajuan organisasi {{ $req->name }}?');"
                                    >
                                        @csrf
                                        <button
                                            type="submit"
                                            class="h-8 px-3 rounded-lg bg-navy-900 text-white font-semibold text-xs shadow-xs hover:bg-navy-700 active:scale-95 transition-all cursor-pointer inline-flex items-center gap-1.5"
                                        >
                                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Setujui (ACC)</span>
                                        </button>
                                    </form>
                                    <a
                                        href="{{ route('admin.organizations.index', ['tab' => 'requests']) }}"
                                        class="h-8 px-3 rounded-lg border border-ice-200 text-navy-700 hover:text-navy-900 hover:bg-ice-100 text-xs font-medium transition-colors inline-flex items-center"
                                    >
                                        Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2 Kolom Ringkasan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Kolom 1: Organisasi Terbaru -->
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                            <div>
                                <h2 class="font-display font-semibold text-base sm:text-lg text-navy-900">
                                    Organisasi Terdaftar
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">Daftar ormawa yang telah diverifikasi</p>
                            </div>
                            <a href="{{ route('admin.organizations.index') }}" class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                                Kelola Semua
                            </a>
                        </div>

                        @if ($recentOrganizations->isEmpty())
                            <div class="py-10 text-center">
                                <p class="text-xs text-navy-500">
                                    Belum ada organisasi yang terdaftar.
                                </p>
                            </div>
                        @else
                            <div class="divide-y divide-ice-200">
                                @foreach ($recentOrganizations as $org)
                                    <div class="py-3.5 flex items-center justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-navy-900 truncate">
                                                {{ $org->name }}
                                            </p>
                                            <p class="text-xs text-navy-500 mt-0.5 font-mono">
                                                Kode: {{ $org->code }} · {{ $org->created_at->format('d M Y') }}
                                            </p>
                                        </div>
                                        <div class="shrink-0">
                                            @if ($org->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-red-50 text-danger border border-red-200 text-xs font-medium">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-ice-200 mt-4">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs hover:bg-ice-200 transition-colors border border-ice-200/80"
                        >
                            Ke Manajemen Organisasi
                        </a>
                    </div>
                </div>

                <!-- Kolom 2: Status & Aktivitas Sistem -->
                <div class="bg-white rounded-xl p-4 sm:p-6 border border-ice-200 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="pb-3 border-b border-ice-200">
                            <h2 class="font-display font-semibold text-base sm:text-lg text-navy-900">
                                Status & Aktivitas Sistem
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Log audit trail dan status telemetri keamanan multi-tenant.
                            </p>
                        </div>

                        <!-- 2 Status Card Kecil -->
                        <div class="grid grid-cols-2 gap-3 my-4">
                            <div class="p-3.5 rounded-xl bg-ice-100/60 border border-ice-200/80">
                                <span class="text-xs text-navy-500 block">Isolasi Multi-Tenant</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-semibold text-navy-900">Aktif Normal</span>
                                </div>
                            </div>
                            <div class="p-3.5 rounded-xl bg-ice-100/60 border border-ice-200/80">
                                <span class="text-xs text-navy-500 block">Integritas Audit Log</span>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span class="text-xs font-semibold text-navy-900">Terkunci</span>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Log Aktivitas -->
                        <div>
                            <p class="text-xs font-semibold text-navy-700 mb-2">Log Terakhir</p>
                            @if ($recentActivities->isEmpty())
                                <p class="text-xs text-navy-500 italic py-4">Belum ada aktivitas tercatat.</p>
                            @else
                                <div class="divide-y divide-ice-200 text-xs">
                                    @foreach ($recentActivities as $act)
                                        <div class="py-2.5 flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <p class="font-mono text-navy-900 font-semibold truncate">{{ $act->action }}</p>
                                                <p class="text-navy-500 text-[11px] mt-0.5 truncate">
                                                    {{ $act->payload['organization_name'] ?? ($act->organization->name ?? 'Sistem') }}
                                                </p>
                                            </div>
                                            <span class="text-navy-500 tabular-nums text-[11px] shrink-0">
                                                {{ $act->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-ice-200 mt-4">
                        <a
                            href="{{ route('admin.organizations.index') }}"
                            class="w-full flex items-center justify-center h-10 px-4 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs hover:bg-ice-200 transition-colors border border-ice-200/80"
                        >
                            Lihat Log Lengkap
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </main>

</body>
</html>
