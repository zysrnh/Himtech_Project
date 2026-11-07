<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Overview Kas - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Pengurus Neumorphic (Desktop) + Mobile Header & Bottom Toolbar (HP) -->
    <x-sidebar-pengurus active="dashboard" :organization="$organization" />

    <!-- Main Content Area: Dashboard Overview Kas Bendahara -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 md:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header & Breadcrumb Info -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-white/80 border border-ice-200/80 text-navy-700 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Panel Bendahara
                        </span>
                        <span class="text-xs text-navy-500">•</span>
                        <span class="text-xs text-navy-500 font-medium">{{ $organization->name }}</span>
                    </div>
                    <h1 class="font-display font-bold text-2xl sm:text-3xl text-navy-900 tracking-tight">
                        Overview Kas Organisasi
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-0.5">
                        Ringkasan saldo, realisasi iuran, dan analitik arus kas bendahara terpusat.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('pengurus.buku-kas.index') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white text-navy-900 font-semibold text-xs border border-white/90 shadow-[2px_2px_6px_rgba(122,163,214,0.35),-2px_-2px_6px_rgba(255,255,255,0.9)] hover:bg-ice-100/60 active:scale-95 transition-all"
                    >
                        <svg class="w-3.5 h-3.5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Catat Mutasi Kas</span>
                    </a>
                    @if (session('active_role') === 'admin_organisasi' || Auth::user()->isSuperAdmin())
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="inline-flex items-center justify-center px-3.5 py-2 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-sm hover:bg-navy-700 active:scale-95 transition-all"
                        >
                            Kelola Staff
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kartu Utama Saldo Kas (Neumorphic Dark Card: NEUMORPHISM.md) -->
            <div class="rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-[#273B4F] to-[#182635] text-white shadow-[8px_8px_24px_rgba(122,163,214,0.45),-6px_-6px_20px_rgba(255,255,255,0.9)] relative overflow-hidden">
                <!-- Background subtle wave effect -->
                <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-white/[0.03] pointer-events-none blur-xl"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <!-- Sisi Kiri: Saldo Tersedia -->
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs uppercase tracking-wider text-ice-200/80 font-semibold">Total Saldo Kas Tersedia</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">Aktif</span>
                        </div>
                        <p class="font-display font-bold text-3xl sm:text-4xl md:text-5xl tabular-nums mt-2 tracking-tight text-white">
                            Rp {{ number_format($currentBalance, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-ice-200/70 mt-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span>Locked Ledger • Dihitung dari seluruh pemasukan dikurangi kas keluar.</span>
                        </p>
                    </div>

                    <!-- Sisi Kanan: Tiga Indikator Utama (Responsif di HP) -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-5 lg:pt-0 border-t lg:border-t-0 lg:border-l border-white/10 lg:pl-8 shrink-0">
                        <!-- Total Kas Masuk -->
                        <div class="p-3 sm:p-0 rounded-2xl bg-white/[0.04] sm:bg-transparent">
                            <span class="text-[11px] font-medium text-ice-200/80 block">Total Kas Masuk</span>
                            <span class="font-display font-bold text-lg sm:text-xl tabular-nums text-white block mt-1">
                                +Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-emerald-400 font-medium mt-0.5 block">Iuran & Dana Masuk</span>
                        </div>

                        <!-- Total Kas Keluar -->
                        <div class="p-3 sm:p-0 rounded-2xl bg-white/[0.04] sm:bg-transparent">
                            <span class="text-[11px] font-medium text-ice-200/80 block">Total Kas Keluar</span>
                            <span class="font-display font-bold text-lg sm:text-xl tabular-nums text-rose-300 block mt-1">
                                -Rp {{ number_format($totalExpense, 0, ',', '.') }}
                            </span>
                            <span class="text-[10px] text-rose-300/80 font-medium mt-0.5 block">Operasional & Belanja</span>
                        </div>

                        <!-- Total Anggota Ormawa -->
                        <div class="p-3 sm:p-0 rounded-2xl bg-white/[0.04] sm:bg-transparent">
                            <span class="text-[11px] font-medium text-ice-200/80 block">Total Anggota</span>
                            <span class="font-display font-bold text-lg sm:text-xl tabular-nums text-white block mt-1">
                                {{ $totalMembers }}
                            </span>
                            <span class="text-[10px] text-ice-200/70 font-medium mt-0.5 block">Wajib Iuran Aktif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION BARU: Statistik & Analitik Keuangan Bendahara (NEUMORPHISM.md) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Grafik Tren Arus Kas Bulanan (6 Bulan Terakhir) - 2 Kolom -->
                <div class="lg:col-span-2 rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200/80">
                            <div>
                                <h2 class="font-display font-bold text-lg text-navy-900 tracking-tight">
                                    Statistik Tren Arus Kas
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Perbandingan pergerakan kas masuk vs kas keluar selama 6 bulan terakhir.
                                </p>
                            </div>
                            <!-- Legend -->
                            <div class="flex items-center gap-4 text-xs font-semibold shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-navy-900">
                                    <span class="w-3 h-3 rounded-md bg-[#2F4862] shadow-xs"></span>
                                    Kas Masuk
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-navy-700">
                                    <span class="w-3 h-3 rounded-md bg-[#7AA3D6] shadow-xs"></span>
                                    Kas Keluar
                                </span>
                            </div>
                        </div>

                        <!-- Visual Dual-Bar Chart Neumorphic (CSS Murni & Responsif) -->
                        <div class="pt-6">
                            @php
                                $maxVal = max(
                                    100000,
                                    collect($monthlyTrends)->max(fn($m) => max($m['income'], $m['expense'])) ?? 100000
                                );
                            @endphp

                            <div class="grid grid-cols-6 gap-2 sm:gap-4 items-end h-44 sm:h-52 px-1 pb-2 border-b border-ice-200">
                                @foreach ($monthlyTrends as $trend)
                                    @php
                                        $hIn = $maxVal > 0 ? max(6, (int) round(($trend['income'] / $maxVal) * 100)) : 6;
                                        $hOut = $maxVal > 0 ? max(6, (int) round(($trend['expense'] / $maxVal) * 100)) : 6;
                                    @endphp
                                    <div class="flex flex-col items-center justify-end h-full gap-1 group">
                                        <div class="w-full flex items-end justify-center gap-1 sm:gap-1.5 h-full">
                                            <!-- Bar Pemasukan (Navy Deep Raised) -->
                                            <div
                                                class="w-3 sm:w-5 md:w-6 rounded-t-lg bg-[#2F4862] shadow-[2px_2px_4px_rgba(47,72,98,0.25)] transition-all duration-300 group-hover:brightness-110 relative"
                                                style="height: {{ $hIn }}%;"
                                                title="Kas Masuk {{ $trend['label'] }}: Rp {{ number_format($trend['income'], 0, ',', '.') }}"
                                            ></div>
                                            <!-- Bar Pengeluaran (Ice-400 Inset/Raised) -->
                                            <div
                                                class="w-3 sm:w-5 md:w-6 rounded-t-lg bg-[#7AA3D6] shadow-[2px_2px_4px_rgba(122,163,214,0.35)] transition-all duration-300 group-hover:brightness-110 relative"
                                                style="height: {{ $hOut }}%;"
                                                title="Kas Keluar {{ $trend['label'] }}: Rp {{ number_format($trend['expense'], 0, ',', '.') }}"
                                            ></div>
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-bold text-navy-500 mt-2 truncate">
                                            {{ $trend['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer Keterangan Chart -->
                            <div class="flex items-center justify-between text-[11px] text-navy-500 pt-3">
                                <span>Skala relatif: Max Rp {{ number_format($maxVal, 0, ',', '.') }}</span>
                                <span class="font-medium text-navy-900">Periode: {{ $monthlyTrends[0]['label'] ?? '' }} - {{ $monthlyTrends[count($monthlyTrends)-1]['label'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Summary Row -->
                    <div class="mt-4 pt-3.5 border-t border-ice-200/80 flex items-center justify-between text-xs">
                        <span class="text-navy-500">Pemasukan bulan ini:</span>
                        <span class="font-display font-bold text-navy-900 tabular-nums">
                            Rp {{ number_format($monthlyTrends[count($monthlyTrends)-1]['income'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- 2. Cincin Donut Metrik Kedisiplinan & Realisasi Kas (1 Kolom) -->
                <div class="rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] flex flex-col justify-between space-y-5">
                    <div>
                        <div class="pb-3 border-b border-ice-200/80">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-navy-500 block">Indikator Kunci</span>
                            <h2 class="font-display font-bold text-lg text-navy-900 mt-0.5">
                                Kepatuhan & Realisasi
                            </h2>
                        </div>

                        <!-- Donut Ring 1: Skor Kedisiplinan Bayar Tepat Waktu (Point 8 NEUMORPHISM.md) -->
                        <div class="pt-4 flex items-center gap-4">
                            <!-- Circular Donut SVG Neumorphic Timbul -->
                            <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                                <svg class="w-20 h-20 -rotate-90" viewBox="0 0 80 80">
                                    <!-- Inset Track Background -->
                                    <circle cx="40" cy="40" r="32" stroke="#B5CCEB" stroke-width="7" fill="none" class="opacity-60" />
                                    <!-- Dynamic Progress Ring -->
                                    <circle
                                        cx="40" cy="40" r="32"
                                        stroke="#2F4862" stroke-width="7"
                                        stroke-dasharray="201.06"
                                        stroke-dashoffset="{{ 201.06 - (201.06 * ($onTimeRate / 100)) }}"
                                        stroke-linecap="round"
                                        fill="none"
                                        class="transition-all duration-700 ease-out"
                                    />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="font-display font-bold text-base text-navy-900 tabular-nums leading-none">
                                        {{ $onTimeRate }}%
                                    </span>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-display font-bold text-sm text-navy-900 truncate">
                                    Ketepatan Waktu
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    {{ $onTimeBillsCount }} dari {{ $paidBillsCount }} iuran lunas sebelum/pada jatuh tempo.
                                </p>
                            </div>
                        </div>

                        <!-- Donut Ring 2: Realisasi Target Kas Iuran -->
                        <div class="pt-4 mt-4 border-t border-ice-200/80 flex items-center gap-4">
                            <!-- Circular Donut SVG Neumorphic Accent -->
                            <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                                <svg class="w-20 h-20 -rotate-90" viewBox="0 0 80 80">
                                    <!-- Inset Track Background -->
                                    <circle cx="40" cy="40" r="32" stroke="#B5CCEB" stroke-width="7" fill="none" class="opacity-60" />
                                    <!-- Dynamic Progress Ring Butter/Emerald -->
                                    <circle
                                        cx="40" cy="40" r="32"
                                        stroke="#7AA3D6" stroke-width="7"
                                        stroke-dasharray="201.06"
                                        stroke-dashoffset="{{ 201.06 - (201.06 * ($realizationRate / 100)) }}"
                                        stroke-linecap="round"
                                        fill="none"
                                        class="transition-all duration-700 ease-out"
                                    />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="font-display font-bold text-base text-navy-900 tabular-nums leading-none">
                                        {{ $realizationRate }}%
                                    </span>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-display font-bold text-sm text-navy-900 truncate">
                                    Target Terkumpul
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    Rp {{ number_format($paidBillsAmount, 0, ',', '.') }} terkumpul dari total tagihan aktif.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Tunggakan Alert Pill -->
                    <div class="pt-3 border-t border-ice-200/80 flex items-center justify-between text-xs">
                        <span class="text-navy-500">Tunggakan Belum Bayar:</span>
                        <span class="font-semibold text-danger tabular-nums">
                            {{ $unpaidBillsCount }} Tagihan
                        </span>
                    </div>
                </div>

            </div>

            <!-- SECTION: Breakdown Alokasi Belanja & Status Tunggakan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Breakdown Pengeluaran per Kategori (2 Kolom) -->
                <div class="md:col-span-2 rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200/80">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Alokasi Kas Keluar per Kategori
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Distribusi pengeluaran anggaran operasional ormawa bendahara.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.buku-kas.index') }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900 hover:underline">
                            Detail Mutasi &rarr;
                        </a>
                    </div>

                    @if ($expenseCategories->isEmpty())
                        <div class="py-8 text-center text-xs text-navy-500">
                            Belum ada riwayat pengeluaran yang dicatat.
                        </div>
                    @else
                        <div class="space-y-3.5 pt-1">
                            @foreach ($expenseCategories as $cat)
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1.5">
                                        <span class="font-bold text-navy-900">{{ $cat['name'] }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-display font-bold tabular-nums text-navy-900">
                                                Rp {{ number_format($cat['amount'], 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] font-semibold text-navy-500">({{ $cat['percentage'] }}%)</span>
                                        </div>
                                    </div>
                                    <!-- Inset Track Neumorphic -->
                                    <div class="w-full h-3 rounded-full bg-ice-100 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.5),inset_-2px_-2px_4px_rgba(255,255,255,0.95)] overflow-hidden p-0.5">
                                        <div
                                            class="h-full rounded-full bg-gradient-to-r from-[#7AA3D6] to-[#2F4862] transition-all duration-500"
                                            style="width: {{ max(4, $cat['percentage']) }}%;"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Kartu Status Tunggakan & Tombol Notifikasi WhatsApp (1 Kolom) -->
                <div class="rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200/80">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-navy-500">Tagihan Belum Bayar</span>
                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        </div>
                        <div class="mt-4">
                            <p class="font-display font-bold text-3xl tabular-nums text-danger">
                                {{ $unpaidBillsCount }} Tagihan
                            </p>
                            <p class="text-xs text-navy-500 mt-1">
                                Total nominal: <span class="font-bold text-navy-900">Rp {{ number_format($unpaidBillsAmount, 0, ',', '.') }}</span>
                            </p>
                        </div>
                        <p class="text-xs text-navy-600 mt-4 leading-relaxed bg-white/60 p-3 rounded-2xl border border-ice-200/80">
                            Fitur broadcast pengingat WhatsApp otomatis siap digunakan untuk menagih anggota yang belum membayar.
                        </p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-ice-200/80">
                        <a
                            href="{{ route('pengurus.tagihan.index') }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white text-navy-900 font-semibold text-xs border border-white/90 shadow-[2px_2px_6px_rgba(122,163,214,0.35),-2px_-2px_6px_rgba(255,255,255,0.9)] hover:bg-ice-100/60 active:scale-95 transition-all text-center"
                        >
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <span>Buka Jadwal & Blast Tagihan</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- SECTION: Mutasi Kas Terbaru & Periode Iuran Aktif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kolom Kiri (2 Span): Mutasi Kas Terakhir -->
                <div class="lg:col-span-2 rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200/80">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Mutasi Kas Terbaru
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Catatan kas masuk dan kas keluar organisasi secara transparan.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.buku-kas.index') }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if ($recentTransactions->isEmpty())
                        <div class="py-12 text-center text-xs text-navy-500">
                            Belum ada transaksi kas yang dicatat pada organisasi ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="border-b border-ice-200/80 text-xs text-navy-500">
                                        <th class="py-2.5 font-semibold">Tanggal</th>
                                        <th class="py-2.5 font-semibold">Kategori</th>
                                        <th class="py-2.5 font-semibold">Keterangan</th>
                                        <th class="py-2.5 font-semibold text-right">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-200/60">
                                    @foreach ($recentTransactions as $tx)
                                        <tr class="hover:bg-white/40 transition-colors">
                                            <td class="py-3 text-xs text-navy-500 tabular-nums">
                                                {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                            </td>
                                            <td class="py-3 text-xs font-bold text-navy-900">
                                                <span class="inline-block px-2.5 py-1 rounded-xl bg-white/80 border border-ice-200/80 shadow-2xs">
                                                    {{ $tx->category->name ?? 'Kas Umum' }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-xs text-navy-700">
                                                {{ $tx->description }}
                                            </td>
                                            <td class="py-3 text-xs sm:text-sm font-bold tabular-nums text-right {{ $tx->type === 'in' ? 'text-navy-900' : 'text-danger' }}">
                                                {{ $tx->type === 'in' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Kolom Kanan (1 Span): Periode Iuran Aktif -->
                <div class="rounded-3xl p-6 bg-gradient-to-br from-[#E6F0FA] to-[#D5E5F7] border border-white/80 shadow-[6px_6px_16px_rgba(122,163,214,0.35),-6px_-6px_16px_rgba(255,255,255,0.95)] space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200/80">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Periode Iuran Aktif
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Jadwal tagihan kas dengan batas jatuh tempo.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.tagihan.index') }}" class="text-xs font-semibold text-navy-700 hover:text-navy-900 hover:underline">
                            Kelola &rarr;
                        </a>
                    </div>

                    @if ($activePeriods->isEmpty())
                        <div class="py-12 text-center text-xs text-navy-500">
                            Belum ada jadwal periode iuran yang dibuat.
                        </div>
                    @else
                        <div class="space-y-3.5">
                            @foreach ($activePeriods as $period)
                                <div class="p-4 rounded-2xl bg-white/80 border border-white/90 shadow-[2px_2px_6px_rgba(122,163,214,0.25)] space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs sm:text-sm font-bold text-navy-900">
                                            {{ $period->title }}
                                        </p>
                                        <span class="text-xs font-bold tabular-nums text-navy-900 bg-ice-100 px-2 py-0.5 rounded-lg border border-ice-200/80">
                                            Rp {{ number_format($period->amount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-navy-500">
                                        <span>Jatuh Tempo:</span>
                                        <span class="font-semibold text-navy-900">
                                            {{ $period->due_date?->format('d M Y') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="pt-2 border-t border-ice-200/70 flex items-center justify-between text-xs">
                                        <span class="text-navy-700 font-medium">
                                            Terbayar: {{ $period->paid_bills_count }} / {{ $period->total_bills_count }}
                                        </span>
                                        <span class="text-danger font-semibold">
                                            {{ $period->unpaid_bills_count }} Belum Bayar
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

        </div>
    </main>

</body>
</html>
