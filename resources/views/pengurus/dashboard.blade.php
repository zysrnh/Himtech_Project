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
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Pengurus (Desktop) + Mobile Header & Bottom Toolbar (HP) -->
    <x-sidebar-pengurus active="dashboard" :organization="$organization" />

    <!-- Main Content Area: Dashboard Overview Kas Bendahara -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 md:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-extrabold text-2xl sm:text-[28px] text-navy-900 leading-tight tracking-tight">
                        Overview Kas {{ $organization->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Pantau saldo kas, tingkat kepatuhan bayar tepat waktu, dan mutasi keuangan terpusat.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('pengurus.buku-kas.index') }}"
                        class="neu-btn h-10 px-4 rounded-full font-bold text-xs bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.18),-2px_-2px_6px_#ffffff] text-navy-900 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Catat Mutasi Kas</span>
                    </a>
                    @if (session('active_role') === 'admin_organisasi' || Auth::user()->isSuperAdmin())
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="neu-btn neu-btn--primary h-10 px-5 rounded-full font-bold text-xs shadow-sm hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Kelola Staff</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kartu Saldo Kas Aktif Neumorphic Hero Balance Card -->
            <div class="bg-gradient-to-br from-navy-700 via-navy-900 to-[#182635] text-white rounded-[28px] p-6 sm:p-8 shadow-[8px_14px_32px_rgba(47,72,98,0.36),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-white/10 flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-ice-400/10 blur-2xl pointer-events-none"></div>

                <div class="relative z-1">
                    <span class="text-xs text-ice-200 font-semibold tracking-wider uppercase block">Total Saldo Kas Tersedia</span>
                    <p class="font-display font-extrabold text-3xl sm:text-4xl md:text-5xl tabular-nums mt-2 tracking-tight text-white">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-ice-200/80 mt-2 font-medium">
                        Dihitung dari seluruh transaksi kas masuk dikurangi kas keluar ormawa.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-5 lg:pt-0 border-t lg:border-t-0 lg:border-l border-white/15 lg:pl-8 shrink-0 relative z-1">
                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Total Kas Masuk</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums text-white block mt-1">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-ice-200/60 block mt-0.5">Iuran & Dana Masuk</span>
                    </div>

                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Total Kas Keluar</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums text-white block mt-1">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-ice-200/60 block mt-0.5">Operasional Kas</span>
                    </div>

                    <div>
                        <span class="text-xs text-ice-200/90 font-medium block">Total Anggota</span>
                        <span class="font-display font-bold text-lg md:text-xl tabular-nums text-white block mt-1">
                            {{ $totalMembers }}
                        </span>
                        <span class="text-[11px] text-ice-200/60 block mt-0.5">Anggota Terdaftar</span>
                    </div>
                </div>
            </div>

            <!-- Statistik Keuangan Bendahara & Indikator Kepatuhan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Grafik Tren Arus Kas Bulanan (6 Bulan Terakhir) - 2 Kolom -->
                <div class="lg:col-span-2 bg-white/90 rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.2),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200/70">
                            <div>
                                <h2 class="font-display font-bold text-lg text-navy-900 tracking-tight">
                                    Statistik Tren Arus Kas
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Perbandingan kas masuk dan kas keluar selama 6 bulan terakhir.
                                </p>
                            </div>
                            <!-- Legend -->
                            <div class="flex items-center gap-4 text-xs font-semibold shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-navy-900">
                                    <span class="w-3 h-3 rounded-md bg-navy-900 shadow-2xs"></span>
                                    Kas Masuk
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-navy-700">
                                    <span class="w-3 h-3 rounded-md bg-ice-400 shadow-2xs"></span>
                                    Kas Keluar
                                </span>
                            </div>
                        </div>

                        <!-- Visual Dual-Bar Chart Bersih -->
                        <div class="pt-6">
                            @php
                                $maxVal = max(
                                    100000,
                                    collect($monthlyTrends)->max(fn($m) => max($m['income'], $m['expense'])) ?? 100000
                                );
                            @endphp

                            <div class="grid grid-cols-6 gap-2 sm:gap-4 items-end h-44 sm:h-52 px-1 pb-2 border-b border-ice-200/70">
                                @foreach ($monthlyTrends as $trend)
                                    @php
                                        $hIn = $trend['income'] > 0 ? max(4, (int) round(($trend['income'] / $maxVal) * 100)) : 0;
                                        $hOut = $trend['expense'] > 0 ? max(4, (int) round(($trend['expense'] / $maxVal) * 100)) : 0;
                                    @endphp
                                    <div class="flex flex-col items-center justify-end h-full gap-1 group">
                                        <div class="w-full flex items-end justify-center gap-1 sm:gap-1.5 h-full">
                                            <!-- Bar Pemasukan -->
                                            @if ($hIn > 0)
                                                <div
                                                    class="w-3.5 sm:w-5 md:w-6 rounded-t-lg bg-navy-900 shadow-2xs transition-all duration-300 group-hover:scale-y-105"
                                                    style="height: {{ $hIn }}%;"
                                                    title="Kas Masuk {{ $trend['label'] }}: Rp {{ number_format($trend['income'], 0, ',', '.') }}"
                                                ></div>
                                            @else
                                                <div class="w-3.5 sm:w-5 md:w-6 h-1 rounded-full bg-ice-200"></div>
                                            @endif

                                            <!-- Bar Pengeluaran -->
                                            @if ($hOut > 0)
                                                <div
                                                    class="w-3.5 sm:w-5 md:w-6 rounded-t-lg bg-ice-400 shadow-2xs transition-all duration-300 group-hover:scale-y-105"
                                                    style="height: {{ $hOut }}%;"
                                                    title="Kas Keluar {{ $trend['label'] }}: Rp {{ number_format($trend['expense'], 0, ',', '.') }}"
                                                ></div>
                                            @else
                                                <div class="w-3.5 sm:w-5 md:w-6 h-1 rounded-full bg-ice-200"></div>
                                            @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-semibold text-navy-500 mt-2 truncate">
                                            {{ $trend['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer Keterangan Chart -->
                            <div class="flex items-center justify-between text-[11px] text-navy-500 pt-3">
                                <span>Maksimal skala: Rp {{ number_format($maxVal, 0, ',', '.') }}</span>
                                <span class="font-bold text-navy-900">Periode: {{ $monthlyTrends[0]['label'] ?? '' }} - {{ $monthlyTrends[count($monthlyTrends)-1]['label'] ?? '' }} {{ $monthlyTrends[count($monthlyTrends)-1]['year'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Bulan Berjalan -->
                    <div class="mt-4 pt-3.5 border-t border-ice-200/70 flex items-center justify-between text-xs">
                        <span class="text-navy-500 font-medium">Pemasukan bulan berjalan:</span>
                        <span class="font-display font-bold text-navy-900 tabular-nums">
                            Rp {{ number_format($monthlyTrends[count($monthlyTrends)-1]['income'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- 2. Metrik Kepatuhan & Realisasi Kas - 1 Kolom -->
                <div class="bg-white/90 rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.2),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="pb-3 border-b border-ice-200/70">
                            <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono">INDIKATOR KUNCI</span>
                            <h2 class="font-display font-bold text-lg text-navy-900 mt-1.5">
                                Kepatuhan & Target Kas
                            </h2>
                        </div>

                        <!-- Metrik 1: Ketepatan Waktu Pembayaran Kas -->
                        <div class="pt-4 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-display font-bold text-sm text-navy-900">
                                    Ketepatan Waktu
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    {{ $onTimeBillsCount }} dari {{ $paidBillsCount }} tagihan lunas tepat waktu.
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-display font-extrabold text-2xl tabular-nums text-navy-900 block">
                                    {{ $onTimeRate }}%
                                </span>
                                <span class="text-[10px] font-semibold text-navy-500">Skor Disiplin</span>
                            </div>
                        </div>

                        <!-- Progress Bar Ketepatan Waktu (Inset Track) -->
                        <div class="w-full h-2.5 rounded-full bg-ice-100 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.3),inset_-1px_-1px_3px_rgba(255,255,255,0.9)] overflow-hidden mt-2 p-0.5">
                            <div class="h-full rounded-full bg-navy-900 transition-all duration-500" style="width: {{ $onTimeRate }}%;"></div>
                        </div>

                        <!-- Metrik 2: Realisasi Target Kas Iuran -->
                        <div class="pt-5 mt-5 border-t border-ice-200/70 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-display font-bold text-sm text-navy-900">
                                    Realisasi Iuran
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    Rp {{ number_format($paidBillsAmount, 0, ',', '.') }} dari total target kas.
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-display font-extrabold text-2xl tabular-nums text-navy-900 block">
                                    {{ $realizationRate }}%
                                </span>
                                <span class="text-[10px] font-semibold text-navy-500">Terkumpul</span>
                            </div>
                        </div>

                        <!-- Progress Bar Realisasi Iuran (Inset Track) -->
                        <div class="w-full h-2.5 rounded-full bg-ice-100 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.3),inset_-1px_-1px_3px_rgba(255,255,255,0.9)] overflow-hidden mt-2 p-0.5">
                            <div class="h-full rounded-full bg-ice-400 transition-all duration-500" style="width: {{ $realizationRate }}%;"></div>
                        </div>
                    </div>

                    <!-- Info Tunggakan Kas -->
                    <div class="pt-3 border-t border-ice-200/70 flex items-center justify-between text-xs">
                        <span class="text-navy-500 font-medium">Tunggakan Belum Bayar:</span>
                        <span class="font-bold text-danger tabular-nums">
                            {{ $unpaidBillsCount }} Tagihan (Rp {{ number_format($unpaidBillsAmount, 0, ',', '.') }})
                        </span>
                    </div>
                </div>

            </div>

            <!-- SECTION: Alokasi Kas Keluar & Periode Iuran Aktif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Alokasi Kas Keluar per Kategori (2 Kolom) -->
                <div class="lg:col-span-2 bg-white/90 rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.2),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Alokasi Kas Keluar per Kategori
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Distribusi pengeluaran anggaran operasional ormawa bendahara.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.buku-kas.index') }}" class="neu-back-pill text-xs py-1 px-3">
                            Detail Mutasi &rarr;
                        </a>
                    </div>

                    @if ($expenseCategories->isEmpty())
                        <div class="py-8 text-center text-xs text-navy-500">
                            Belum ada riwayat pengeluaran yang dicatat.
                        </div>
                    @else
                        <div class="space-y-4 pt-1">
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
                                    <div class="w-full h-2.5 rounded-full bg-ice-100 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.3),inset_-1px_-1px_3px_rgba(255,255,255,0.9)] overflow-hidden p-0.5">
                                        <div
                                            class="h-full rounded-full bg-navy-900 transition-all duration-300"
                                            style="width: {{ max(2, $cat['percentage']) }}%;"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Periode Iuran Aktif (1 Kolom) -->
                <div class="bg-white/90 rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.2),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                        <div>
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                Periode Iuran Aktif
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Jadwal tagihan kas dengan batas jatuh tempo.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.tagihan.index') }}" class="neu-back-pill text-xs py-1 px-3">
                            Kelola &rarr;
                        </a>
                    </div>

                    @if ($activePeriods->isEmpty())
                        <div class="py-10 text-center text-xs text-navy-500">
                            Belum ada jadwal periode iuran yang dibuat.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($activePeriods as $period)
                                <div class="p-4 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_10px_rgba(122,163,214,0.18),-3px_-3px_10px_#ffffff] transition-all duration-200 space-y-2.5">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs sm:text-sm font-bold text-navy-900">
                                            {{ $period->title }}
                                        </p>
                                        <span class="text-xs font-bold tabular-nums text-navy-900">
                                            Rp {{ number_format($period->amount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-navy-500">
                                        <span>Jatuh Tempo:</span>
                                        <span class="font-bold text-navy-900">
                                            {{ $period->due_date?->format('d M Y') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="pt-2 border-t border-ice-200/70 flex items-center justify-between text-xs font-semibold">
                                        <span class="text-navy-700">
                                            Terbayar: {{ $period->paid_bills_count }} / {{ $period->total_bills_count }}
                                        </span>
                                        <span class="text-danger">
                                            {{ $period->unpaid_bills_count }} Belum Bayar
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- SECTION: Mutasi Kas Terbaru (Tabel) -->
            <div class="bg-white/90 rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.2),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                    <div>
                        <h2 class="font-display font-bold text-lg text-navy-900">
                            Mutasi Kas Terbaru
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Catatan kas masuk dan kas keluar organisasi secara transparan.
                        </p>
                    </div>
                    <a href="{{ route('pengurus.buku-kas.index') }}" class="neu-back-pill text-xs py-1 px-3">
                        Lihat Semua &rarr;
                    </a>
                </div>

                @if ($recentTransactions->isEmpty())
                    <div class="py-12 text-center text-xs text-navy-500">
                        Belum ada transaksi kas yang dicatat pada organisasi ini.
                    </div>
                @else
                    <div class="overflow-x-auto neu-scroll rounded-2xl border border-ice-200/70 shadow-xs">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-ice-200/80 text-xs text-navy-600 bg-ice-100/60 font-semibold">
                                    <th class="py-3 px-4">Tanggal</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Keterangan</th>
                                    <th class="py-3 px-4 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100 bg-white">
                                @foreach ($recentTransactions as $tx)
                                    <tr class="hover:bg-ice-100/30 transition-colors">
                                        <td class="py-3.5 px-4 text-xs text-navy-500 tabular-nums">
                                            {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs font-bold text-navy-900">
                                            {{ $tx->category->name ?? 'Kas Umum' }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs text-navy-700">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="py-3.5 px-4 text-xs sm:text-sm font-bold tabular-nums text-right {{ $tx->type === 'in' ? 'text-emerald-700' : 'text-danger' }}">
                                            {{ $tx->type === 'in' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
