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

    <!-- Sidebar Pengurus (Desktop) + Mobile Header & Bottom Toolbar (HP) -->
    <x-sidebar-pengurus active="dashboard" :organization="$organization" />

    <!-- Main Content Area: Dashboard Overview Kas Bendahara -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 md:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6 sm:space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-2xl sm:text-[28px] text-navy-900 leading-tight">
                        Overview Kas {{ $organization->name }}
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Pantau saldo kas, tingkat kepatuhan bayar tepat waktu, dan mutasi keuangan terpusat.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <a
                        href="{{ route('pengurus.buku-kas.index') }}"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg bg-white border border-ice-200 text-navy-900 font-medium text-xs hover:bg-ice-100 transition-colors shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Catat Mutasi Kas</span>
                    </a>
                    @if (session('active_role') === 'admin_organisasi' || Auth::user()->isSuperAdmin())
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="inline-flex items-center justify-center h-9 px-3.5 rounded-lg bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-xs"
                        >
                            Kelola Staff
                        </a>
                    @endif
                </div>
            </div>

            <!-- Kartu Saldo Kas Aktif -->
            <div class="bg-navy-900 text-white rounded-xl p-6 sm:p-8 shadow-card flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <span class="text-xs text-white/70 font-medium block">Total Saldo Kas Tersedia</span>
                    <p class="font-display font-semibold text-3xl sm:text-4xl md:text-5xl tabular-nums mt-2 tracking-tight text-white">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-white/60 mt-2">
                        Dihitung dari seluruh transaksi kas masuk dikurangi kas keluar.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-5 lg:pt-0 border-t lg:border-t-0 lg:border-l border-white/10 lg:pl-8 shrink-0">
                    <div>
                        <span class="text-xs text-white/70 block">Total Kas Masuk</span>
                        <span class="font-display font-semibold text-lg md:text-xl tabular-nums text-white block mt-1">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-white/50 block mt-0.5">Iuran & Dana Masuk</span>
                    </div>

                    <div>
                        <span class="text-xs text-white/70 block">Total Kas Keluar</span>
                        <span class="font-display font-semibold text-lg md:text-xl tabular-nums text-white block mt-1">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </span>
                        <span class="text-[11px] text-white/50 block mt-0.5">Operasional Kas</span>
                    </div>

                    <div>
                        <span class="text-xs text-white/70 block">Total Anggota</span>
                        <span class="font-display font-semibold text-lg md:text-xl tabular-nums text-white block mt-1">
                            {{ $totalMembers }}
                        </span>
                        <span class="text-[11px] text-white/50 block mt-0.5">Anggota Aktif</span>
                    </div>
                </div>
            </div>

            <!-- Statistik Keuangan Bendahara & Indikator Kepatuhan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Grafik Tren Arus Kas Bulanan (6 Bulan Terakhir) - 2 Kolom -->
                <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-card border border-ice-200 flex flex-col justify-between">
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200">
                            <div>
                                <h2 class="font-display font-semibold text-lg text-navy-900 tracking-tight">
                                    Statistik Tren Arus Kas
                                </h2>
                                <p class="text-xs text-navy-500 mt-0.5">
                                    Perbandingan kas masuk dan kas keluar selama 6 bulan terakhir.
                                </p>
                            </div>
                            <!-- Legend -->
                            <div class="flex items-center gap-4 text-xs font-medium shrink-0">
                                <span class="inline-flex items-center gap-1.5 text-navy-900">
                                    <span class="w-3 h-3 rounded-sm bg-navy-900"></span>
                                    Kas Masuk
                                </span>
                                <span class="inline-flex items-center gap-1.5 text-navy-700">
                                    <span class="w-3 h-3 rounded-sm bg-ice-400"></span>
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

                            <div class="grid grid-cols-6 gap-2 sm:gap-4 items-end h-44 sm:h-52 px-1 pb-2 border-b border-ice-200">
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
                                                    class="w-3 sm:w-5 md:w-6 rounded-t-sm bg-navy-900 transition-all duration-300 group-hover:opacity-80"
                                                    style="height: {{ $hIn }}%;"
                                                    title="Kas Masuk {{ $trend['label'] }}: Rp {{ number_format($trend['income'], 0, ',', '.') }}"
                                                ></div>
                                            @else
                                                <div class="w-3 sm:w-5 md:w-6 h-0.5 bg-ice-200"></div>
                                            @endif

                                            <!-- Bar Pengeluaran -->
                                            @if ($hOut > 0)
                                                <div
                                                    class="w-3 sm:w-5 md:w-6 rounded-t-sm bg-ice-400 transition-all duration-300 group-hover:opacity-80"
                                                    style="height: {{ $hOut }}%;"
                                                    title="Kas Keluar {{ $trend['label'] }}: Rp {{ number_format($trend['expense'], 0, ',', '.') }}"
                                                ></div>
                                            @else
                                                <div class="w-3 sm:w-5 md:w-6 h-0.5 bg-ice-200"></div>
                                            @endif
                                        </div>
                                        <span class="text-[10px] sm:text-xs font-medium text-navy-500 mt-2 truncate">
                                            {{ $trend['label'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Footer Keterangan Chart -->
                            <div class="flex items-center justify-between text-[11px] text-navy-500 pt-3">
                                <span>Maksimal skala: Rp {{ number_format($maxVal, 0, ',', '.') }}</span>
                                <span class="font-medium text-navy-900">Periode: {{ $monthlyTrends[0]['label'] ?? '' }} - {{ $monthlyTrends[count($monthlyTrends)-1]['label'] ?? '' }} {{ $monthlyTrends[count($monthlyTrends)-1]['year'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Bulan Berjalan -->
                    <div class="mt-4 pt-3.5 border-t border-ice-200 flex items-center justify-between text-xs">
                        <span class="text-navy-500">Pemasukan bulan berjalan:</span>
                        <span class="font-display font-semibold text-navy-900 tabular-nums">
                            Rp {{ number_format($monthlyTrends[count($monthlyTrends)-1]['income'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- 2. Metrik Kepatuhan & Realisasi Kas - 1 Kolom -->
                <div class="bg-white rounded-xl p-6 shadow-card border border-ice-200 flex flex-col justify-between space-y-6">
                    <div>
                        <div class="pb-3 border-b border-ice-200">
                            <span class="text-[10px] font-semibold uppercase tracking-wider text-navy-500 block">Indikator Kunci</span>
                            <h2 class="font-display font-semibold text-lg text-navy-900 mt-0.5">
                                Kepatuhan & Target Kas
                            </h2>
                        </div>

                        <!-- Metrik 1: Ketepatan Waktu Pembayaran Kas -->
                        <div class="pt-4 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-display font-semibold text-sm text-navy-900">
                                    Ketepatan Waktu
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    {{ $onTimeBillsCount }} dari {{ $paidBillsCount }} tagihan lunas tepat waktu.
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-display font-semibold text-2xl tabular-nums text-navy-900 block">
                                    {{ $onTimeRate }}%
                                </span>
                                <span class="text-[10px] font-medium text-navy-500">Skor Disiplin</span>
                            </div>
                        </div>

                        <!-- Progress Bar Ketepatan Waktu -->
                        <div class="w-full h-2 rounded-full bg-ice-100 overflow-hidden mt-2">
                            <div class="h-full rounded-full bg-navy-900" style="width: {{ $onTimeRate }}%;"></div>
                        </div>

                        <!-- Metrik 2: Realisasi Target Kas Iuran -->
                        <div class="pt-5 mt-5 border-t border-ice-200 flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-display font-semibold text-sm text-navy-900">
                                    Realisasi Iuran
                                </h3>
                                <p class="text-xs text-navy-500 mt-0.5 leading-relaxed">
                                    Rp {{ number_format($paidBillsAmount, 0, ',', '.') }} dari total target kas.
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-display font-semibold text-2xl tabular-nums text-navy-900 block">
                                    {{ $realizationRate }}%
                                </span>
                                <span class="text-[10px] font-medium text-navy-500">Terkumpul</span>
                            </div>
                        </div>

                        <!-- Progress Bar Realisasi Iuran -->
                        <div class="w-full h-2 rounded-full bg-ice-100 overflow-hidden mt-2">
                            <div class="h-full rounded-full bg-ice-400" style="width: {{ $realizationRate }}%;"></div>
                        </div>
                    </div>

                    <!-- Info Tunggakan Kas -->
                    <div class="pt-3 border-t border-ice-200 flex items-center justify-between text-xs">
                        <span class="text-navy-500">Tunggakan Belum Bayar:</span>
                        <span class="font-semibold text-danger tabular-nums">
                            {{ $unpaidBillsCount }} Tagihan (Rp {{ number_format($unpaidBillsAmount, 0, ',', '.') }})
                        </span>
                    </div>
                </div>

            </div>

            <!-- SECTION: Alokasi Kas Keluar & Periode Iuran Aktif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Alokasi Kas Keluar per Kategori (2 Kolom) -->
                <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-card border border-ice-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-semibold text-lg text-navy-900">
                                Alokasi Kas Keluar per Kategori
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Distribusi pengeluaran anggaran operasional ormawa bendahara.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.buku-kas.index') }}" class="text-xs font-medium text-navy-700 hover:text-navy-900 hover:underline">
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
                                        <span class="font-medium text-navy-900">{{ $cat['name'] }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="font-display font-semibold tabular-nums text-navy-900">
                                                Rp {{ number_format($cat['amount'], 0, ',', '.') }}
                                            </span>
                                            <span class="text-[11px] text-navy-500">({{ $cat['percentage'] }}%)</span>
                                        </div>
                                    </div>
                                    <div class="w-full h-2 rounded-full bg-ice-100 overflow-hidden">
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
                <div class="bg-white rounded-xl p-6 shadow-card border border-ice-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-semibold text-lg text-navy-900">
                                Periode Iuran Aktif
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Jadwal tagihan kas dengan batas jatuh tempo.
                            </p>
                        </div>
                        <a href="{{ route('pengurus.tagihan.index') }}" class="text-xs font-medium text-navy-700 hover:text-navy-900 hover:underline">
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
                                <div class="p-3.5 rounded-lg bg-ice-100 border border-ice-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-xs sm:text-sm font-semibold text-navy-900">
                                            {{ $period->title }}
                                        </p>
                                        <span class="text-xs font-semibold tabular-nums text-navy-900">
                                            Rp {{ number_format($period->amount, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-navy-500">
                                        <span>Jatuh Tempo:</span>
                                        <span class="font-medium text-navy-900">
                                            {{ $period->due_date?->format('d M Y') ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="pt-2 border-t border-ice-200 flex items-center justify-between text-xs">
                                        <span class="text-navy-700">
                                            Terbayar: {{ $period->paid_bills_count }} / {{ $period->total_bills_count }}
                                        </span>
                                        <span class="text-danger font-medium">
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
            <div class="bg-white rounded-xl p-6 shadow-card border border-ice-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                    <div>
                        <h2 class="font-display font-semibold text-lg text-navy-900">
                            Mutasi Kas Terbaru
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Catatan kas masuk dan kas keluar organisasi secara transparan.
                        </p>
                    </div>
                    <a href="{{ route('pengurus.buku-kas.index') }}" class="text-xs font-medium text-navy-700 hover:text-navy-900 hover:underline">
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
                                <tr class="border-b border-ice-200 text-xs text-navy-500">
                                    <th class="py-2.5 font-normal">Tanggal</th>
                                    <th class="py-2.5 font-normal">Kategori</th>
                                    <th class="py-2.5 font-normal">Keterangan</th>
                                    <th class="py-2.5 font-normal text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100">
                                @foreach ($recentTransactions as $tx)
                                    <tr class="hover:bg-ice-100/40 transition-colors">
                                        <td class="py-3 text-xs text-navy-500 tabular-nums">
                                            {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="py-3 text-xs font-medium text-navy-900">
                                            {{ $tx->category->name ?? 'Kas Umum' }}
                                        </td>
                                        <td class="py-3 text-xs text-navy-700">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="py-3 text-xs sm:text-sm font-semibold tabular-nums text-right {{ $tx->type === 'in' ? 'text-navy-900' : 'text-danger' }}">
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
