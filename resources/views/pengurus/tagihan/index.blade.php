<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Tagihan Iuran Kas - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row">

    <!-- Sidebar Pengurus (Desktop) + Mobile Header & Bottom Toolbar (HP) -->
    <x-sidebar-pengurus active="tagihan" :organization="$organization" />

    <!-- Main Content Area: Tagihan Iuran -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6">

            <!-- Alerts Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-white border border-ice-200 text-navy-900 text-sm font-medium shadow-xs flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-500 hover:text-navy-900 text-xs font-bold cursor-pointer">Tutup</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-danger text-sm font-medium shadow-xs">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Top Header & Aksi Cepat -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900 leading-tight">
                        Tagihan Iuran Kas
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Kelola periode kas, verifikasi pelunasan anggota, dan pengaturan metode pembayaran.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a
                        href="{{ route('pengurus.pembayaran.index') }}"
                        class="h-9 px-3.5 rounded-lg bg-white border border-ice-200 text-navy-900 font-medium text-xs hover:bg-ice-100 transition-colors shadow-xs flex items-center gap-1.5"
                    >
                        <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Atur Metode Pembayaran</span>
                    </a>

                    <button
                        type="button"
                        onclick="openModal('modal-create-period')"
                        class="h-9 px-3.5 rounded-lg bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-xs flex items-center gap-1.5 cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Periode Kas</span>
                    </button>
                </div>
            </div>

            <!-- ======================================================== -->
            <!-- KALENDER JADWAL KAS MINGGUAN (INTERACTIVE CALENDAR VIEW)    -->
            <!-- ======================================================== -->
            <div class="bg-white rounded-xl p-5 sm:p-6 border border-ice-200 shadow-card space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-ice-200">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-ice-100 border border-ice-200 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display font-semibold text-base sm:text-lg text-navy-900">Kalender Jadwal Kas Mingguan</h2>
                            <p class="text-xs text-navy-500">Penanda tanggal & hari jatuh tempo kas mingguan dalam sebulan</p>
                        </div>
                    </div>

                    <!-- Navigasi Bulan Flat & Rapi -->
                    <div class="flex items-center gap-1.5">
                        @php
                            $prevMonth = $calendarDate->copy()->subMonth()->format('Y-m');
                            $nextMonth = $calendarDate->copy()->addMonth()->format('Y-m');
                        @endphp
                        <a
                            href="{{ route('pengurus.tagihan.index', ['month' => $prevMonth, 'period_id' => $selectedPeriod?->id]) }}"
                            class="w-8 h-8 rounded-lg bg-white border border-ice-200 text-navy-700 hover:bg-ice-100 flex items-center justify-center transition-colors cursor-pointer"
                            title="Bulan Sebelumnya"
                        >
                            <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div class="px-3.5 py-1.5 rounded-lg bg-ice-100 border border-ice-200 font-semibold text-xs text-navy-900 font-display flex items-center gap-2 select-none">
                            <svg class="w-3.5 h-3.5 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ $calendarDate->isoFormat('MMMM Y') }}</span>
                        </div>
                        <a
                            href="{{ route('pengurus.tagihan.index', ['month' => $nextMonth, 'period_id' => $selectedPeriod?->id]) }}"
                            class="w-8 h-8 rounded-lg bg-white border border-ice-200 text-navy-700 hover:bg-ice-100 flex items-center justify-center transition-colors cursor-pointer"
                            title="Bulan Berikutnya"
                        >
                            <svg class="w-4 h-4 text-navy-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Wrapper Responsif Kalender dengan Horizontal Scroll di HP -->
                <div class="overflow-x-auto pb-1 -mx-2 px-2 sm:mx-0 sm:px-0 custom-scrollbar">
                    <div class="min-w-[620px] sm:min-w-0">
                        <!-- Header Nama Hari (Senin s/d Minggu) Bersih -->
                        <div class="grid grid-cols-7 gap-2 text-center mb-2">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
                                <div class="py-1.5 text-[11px] font-semibold text-navy-500 uppercase tracking-wider">
                                    {{ $dayName }}
                                </div>
                            @endforeach
                        </div>

                        @php
                            $startDayOfWeek = $calendarDate->copy()->startOfMonth()->dayOfWeekIso; // 1 = Senin, 7 = Minggu
                            $daysInMonth = $calendarDate->daysInMonth;
                        @endphp

                        <!-- Grid Kalender Tanggal Rapi -->
                        <div class="grid grid-cols-7 gap-2">
                            <!-- Padding hari kosong sebelum tanggal 1 -->
                            @for ($i = 1; $i < $startDayOfWeek; $i++)
                                <div class="min-h-[105px] rounded-xl bg-ice-100/30 border border-dashed border-ice-200/50"></div>
                            @endfor

                            <!-- Tanggal 1 s/d Akhir Bulan -->
                            @for ($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateObj = $calendarDate->copy()->day($day);
                                    $dateStr = $dateObj->format('Y-m-d');
                                    $hasKas = isset($calendarPeriodsByDate[$dateStr]);
                                    $kasList = $hasKas ? $calendarPeriodsByDate[$dateStr] : [];
                                    $isToday = $dateObj->isToday();
                                @endphp

                                <div class="min-h-[105px] p-2 sm:p-2.5 rounded-xl border text-left transition-colors flex flex-col justify-between {{ $hasKas ? 'bg-butter-100/60 border-amber-300' : 'bg-white border-ice-200 hover:border-ice-400' }} {{ $isToday ? 'ring-2 ring-navy-900 bg-white' : '' }}">
                                    <!-- Baris Atas Cell: Angka Tanggal -->
                                    <div class="flex items-center justify-between">
                                        <span class="w-6 h-6 rounded-md flex items-center justify-center font-bold text-xs {{ $isToday ? 'bg-navy-900 text-white font-mono' : ($hasKas ? 'text-navy-900 font-display font-bold' : 'text-navy-500') }}">
                                            {{ $day }}
                                        </span>
                                        @if ($isToday)
                                            <span class="w-2 h-2 rounded-full bg-navy-900" title="Hari Ini"></span>
                                        @elseif ($hasKas)
                                            <span class="w-2 h-2 rounded-full bg-amber-500" title="Ada jadwal kas"></span>
                                        @endif
                                    </div>

                                    <!-- Daftar Kas / Tagihan pada Tanggal Ini -->
                                    @if ($hasKas)
                                        <div class="mt-2 space-y-1.5">
                                            @foreach ($kasList as $item)
                                                @php
                                                    $isSelected = $selectedPeriod && $selectedPeriod->id === $item['id'];
                                                @endphp
                                                <a
                                                    href="{{ route('pengurus.tagihan.index', ['period_id' => $item['id'], 'month' => $calendarDate->format('Y-m')]) }}"
                                                    class="block p-1.5 rounded-lg border text-left transition-all cursor-pointer {{ $isSelected ? 'bg-navy-900 text-white shadow-xs' : 'bg-white text-navy-900 border-ice-200 hover:border-navy-500' }}"
                                                    title="Klik untuk buka daftar tagihan periode ini"
                                                >
                                                    <div class="flex items-center justify-between gap-1">
                                                        <span class="font-semibold text-[10px] truncate leading-tight block {{ $isSelected ? 'text-white' : 'text-navy-900' }}">
                                                            {{ $item['title'] }}
                                                        </span>
                                                        @if ($item['is_all_paid'])
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0" title="Semua Lunas"></span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center justify-between mt-1 text-[9px] pt-1 border-t {{ $isSelected ? 'border-white/20 text-white/80' : 'border-ice-100 text-navy-500' }}">
                                                        <span class="tabular-nums font-medium">Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                                        <span class="font-mono text-[8.5px] px-1 rounded font-bold {{ $isSelected ? 'bg-white/20 text-white' : ($item['is_all_paid'] ? 'bg-emerald-100 text-emerald-800' : 'bg-butter-100 text-navy-900') }}">
                                                            {{ $item['paid_count'] }}/{{ $item['total_count'] }}
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="h-4"></div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Footer Legend Bersih -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 text-[11px] text-navy-500 border-t border-ice-200">
                    <div class="flex flex-wrap items-center gap-4">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-butter-100 border border-amber-300 inline-block"></span>
                            <span class="font-medium text-navy-700">Jadwal Kas Mingguan</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-sm bg-navy-900 inline-block"></span>
                            <span class="font-medium text-navy-700">Tagihan Terpilih</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-full bg-navy-900 ring-2 ring-navy-900/30 inline-block"></span>
                            <span class="font-medium text-navy-700">Hari Ini</span>
                        </span>
                    </div>
                    <span class="text-navy-500">Klik tanggal kas untuk memfilter & meninjau status pembayaran anggota</span>
                </div>
            </div>

            <!-- Pilih Periode Iuran Aktif -->
            <div class="bg-white rounded-xl p-4 border border-ice-200 shadow-card flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-navy-500 uppercase tracking-wider">Periode Kas:</span>
                    @if ($periods->isEmpty())
                        <span class="text-xs text-navy-500">Belum ada periode tagihan kas yang dibuat.</span>
                    @else
                        <form method="GET" action="{{ route('pengurus.tagihan.index') }}" class="inline-block">
                            <select
                                name="period_id"
                                onchange="this.form.submit()"
                                class="h-9 px-3 rounded-lg bg-ice-100 text-navy-900 font-semibold text-xs border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900 cursor-pointer"
                            >
                                @foreach ($periods as $p)
                                    <option value="{{ $p->id }}" {{ $selectedPeriod && $selectedPeriod->id === $p->id ? 'selected' : '' }}>
                                        {{ $p->title }} (Rp {{ number_format($p->amount, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>

                @if ($selectedPeriod)
                    <div class="flex items-center gap-3 text-xs">
                        <span class="text-navy-500">Jatuh Tempo:</span>
                        <span class="font-semibold text-navy-900">
                            {{ $selectedPeriod->due_date->format('d M Y') }}
                        </span>
                        <span class="text-navy-500">•</span>
                        <span class="text-navy-500">Nominal:</span>
                        <span class="font-semibold text-navy-900">Rp {{ number_format($selectedPeriod->amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            @if ($selectedPeriod)
                <!-- 4 Kartu Metrik Ringkas & Bersih -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Tagihan -->
                    <div class="bg-white rounded-xl p-5 border border-ice-200 shadow-card">
                        <p class="text-xs text-navy-500">Total Anggota</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">
                            {{ $metrics['total_bills'] }}
                        </p>
                        <p class="text-[11px] text-navy-500 mt-0.5">Target: Rp {{ number_format($metrics['total_amount'], 0, ',', '.') }}</p>
                    </div>

                    <!-- Terkumpul -->
                    <div class="bg-white rounded-xl p-5 border border-ice-200 shadow-card">
                        <p class="text-xs text-navy-500">Kas Terkumpul</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">
                            Rp {{ number_format($metrics['collected_amount'], 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-navy-700 mt-0.5">{{ $metrics['paid_count'] }} anggota lunas</p>
                    </div>

                    <!-- Belum Bayar -->
                    <div class="bg-white rounded-xl p-5 border border-ice-200 shadow-card">
                        <p class="text-xs text-navy-500">Sisa Tunggakan</p>
                        <p class="font-display font-semibold text-2xl text-danger mt-1 tabular-nums">
                            {{ $metrics['unpaid_count'] }}
                        </p>
                        <p class="text-[11px] text-danger mt-0.5">Rp {{ number_format($metrics['uncollected_amount'], 0, ',', '.') }} tertahan</p>
                    </div>

                    <!-- Kedisiplinan -->
                    <div class="bg-white rounded-xl p-5 border border-ice-200 shadow-card">
                        <p class="text-xs text-navy-500">Skor Disiplin</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1 tabular-nums">
                            {{ $metrics['ontime_rate'] }}%
                        </p>
                        <p class="text-[11px] text-navy-500 mt-0.5">{{ $metrics['ontime_count'] }} tepat waktu, {{ $metrics['late_count'] }} telat</p>
                    </div>
                </div>

                <!-- Tabel Tagihan Anggota & Filter -->
                <div class="bg-white rounded-xl p-6 border border-ice-200 shadow-card space-y-4">
                    <!-- Filter Status -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-ice-200">
                        <div class="flex flex-wrap items-center gap-1.5 text-xs">
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'all']) }}"
                                class="px-3 py-1.5 rounded-lg font-medium transition-colors {{ $statusFilter === 'all' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200' }}"
                            >
                                Semua ({{ $metrics['total_bills'] }})
                            </a>
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'paid_ontime']) }}"
                                class="px-3 py-1.5 rounded-lg font-medium transition-colors {{ $statusFilter === 'paid_ontime' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200' }}"
                            >
                                Tepat Waktu ({{ $metrics['ontime_count'] }})
                            </a>
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'paid_late']) }}"
                                class="px-3 py-1.5 rounded-lg font-medium transition-colors {{ $statusFilter === 'paid_late' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200' }}"
                            >
                                Terlambat ({{ $metrics['late_count'] }})
                            </a>
                            @if ($metrics['pending_count'] > 0)
                                <a
                                    href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'pending']) }}"
                                    class="px-3 py-1.5 rounded-lg font-medium transition-colors {{ $statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-amber-100 text-amber-900 hover:bg-amber-200' }}"
                                >
                                    Menunggu ACC ({{ $metrics['pending_count'] }})
                                </a>
                            @endif
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'unpaid']) }}"
                                class="px-3 py-1.5 rounded-lg font-medium transition-colors {{ $statusFilter === 'unpaid' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200' }}"
                            >
                                Belum Bayar ({{ $metrics['unpaid_count'] }})
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Anggota -->
                    @if ($bills->isEmpty())
                        <div class="py-12 text-center text-xs text-navy-500">
                            Tidak ada data tagihan yang sesuai dengan filter ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-ice-200 text-navy-500 font-medium text-xs">
                                        <th class="py-3 px-3">NIM / ID</th>
                                        <th class="py-3 px-3">Nama Anggota</th>
                                        <th class="py-3 px-3">Nominal</th>
                                        <th class="py-3 px-3">Status Pembayaran</th>
                                        <th class="py-3 px-3">Metode & Waktu</th>
                                        <th class="py-3 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-ice-100">
                                    @foreach ($bills as $bill)
                                        <tr class="hover:bg-ice-100/40 transition-colors">
                                            <td class="py-3 px-3 font-mono font-bold text-navy-900">
                                                {{ $bill->member->member_number }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <p class="font-semibold text-navy-900">{{ $bill->member->name }}</p>
                                                <p class="text-[11px] text-navy-500">{{ $bill->member->phone ?? '-' }}</p>
                                            </td>
                                            <td class="py-3 px-3 font-semibold tabular-nums text-navy-900">
                                                Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3 px-3">
                                                @if ($bill->status === 'paid')
                                                    @if ($bill->is_on_time)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-butter-100 text-navy-900 font-medium text-[11px]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                            Tepat Waktu
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-100 text-amber-900 font-medium text-[11px]">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                            Terlambat
                                                        </span>
                                                    @endif
                                                @elseif ($bill->status === 'pending')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-amber-100 text-amber-900 font-medium text-[11px] border border-amber-300">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                                        Klaim Tunai (Menunggu ACC)
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-red-100 text-danger font-medium text-[11px]">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                        Belum Bayar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 text-[11px] text-navy-700">
                                                @if ($bill->status === 'paid')
                                                    <p class="font-medium">
                                                        {{ strtoupper(str_replace('_', ' ', $bill->payment_channel ?? $bill->payment_method)) }}
                                                    </p>
                                                    <p class="text-[10px] text-navy-500">{{ $bill->paid_at?->format('d/m/Y H:i') ?? '-' }}</p>
                                                @elseif ($bill->status === 'pending')
                                                    <p class="font-medium text-amber-900">TUNAI (CASH)</p>
                                                    <p class="text-[10px] text-amber-700">Menunggu Uang Fisik</p>
                                                @else
                                                    <span class="text-navy-500">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                @if ($bill->status === 'paid')
                                                    <span class="text-[11px] text-navy-500">
                                                        {{ $bill->payment_method === 'midtrans' ? 'Midtrans' : ($bill->verifier ? 'acc by '.$bill->verifier->name : 'Lunas') }}
                                                    </span>
                                                @elseif ($bill->status === 'pending')
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <!-- Tombol Cepat ACC Tunai -->
                                                        <form action="{{ route('pengurus.tagihan.bills.verify', $bill->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <input type="hidden" name="payment_method" value="cash">
                                                            <button
                                                                type="submit"
                                                                onclick="return confirm('ACC pembayaran kas tunai Rp {{ number_format($bill->amount, 0, ',', '.') }} dari {{ $bill->member->name }}?')"
                                                                class="h-7 px-2.5 rounded-lg bg-emerald-600 text-white font-medium text-[11px] hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer flex items-center gap-1"
                                                                title="ACC Tunai Sekarang"
                                                            >
                                                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                <span>ACC Tunai</span>
                                                            </button>
                                                        </form>

                                                        <button
                                                            type="button"
                                                            onclick="openVerifyModal('{{ $bill->id }}', '{{ $bill->member->name }}', '{{ $bill->amount }}')"
                                                            class="h-7 px-2 rounded-lg bg-ice-100 text-navy-700 font-medium text-[11px] hover:bg-ice-200 transition-colors cursor-pointer"
                                                            title="Opsi Verifikasi Lain"
                                                        >
                                                            Ubah
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <!-- Tombol Verifikasi Manual -->
                                                        <button
                                                            type="button"
                                                            onclick="openVerifyModal('{{ $bill->id }}', '{{ $bill->member->name }}', '{{ $bill->amount }}')"
                                                            class="h-7 px-2.5 rounded-lg bg-navy-900 text-white font-medium text-[11px] hover:bg-navy-700 transition-colors shadow-xs cursor-pointer"
                                                        >
                                                            Verifikasi
                                                        </button>

                                                        <!-- Tombol Kirim Pengingat WhatsApp -->
                                                        <form action="{{ route('pengurus.tagihan.bills.remind', $bill->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button
                                                                type="submit"
                                                                title="Kirim Pengingat WhatsApp"
                                                                class="h-7 px-2 rounded-lg bg-emerald-600 text-white font-medium text-[11px] hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer"
                                                            >
                                                                WA
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="pt-3 border-t border-ice-200">
                            {{ $bills->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </main>

    <!-- Modal 1: Buat Jadwal Periode Tagihan Kas Baru (Mendukung Mingguan & Tunggal) -->
    <div id="modal-create-period" style="display: none;" onclick="if(event.target === this) closeModal('modal-create-period')" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl border border-ice-200 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                <div>
                    <h3 class="font-display font-semibold text-lg text-navy-900">Buat Jadwal Tagihan Kas</h3>
                    <p class="text-xs text-navy-500">Pilih mode kas mingguan rutin atau periode tunggal</p>
                </div>
                <button type="button" onclick="closeModal('modal-create-period')" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <!-- Tab Switcher Mode Jadwal -->
            <div class="grid grid-cols-2 p-1 bg-ice-100 rounded-lg gap-1">
                <button
                    type="button"
                    id="btn-tab-weekly"
                    onclick="switchScheduleMode('weekly')"
                    class="py-2 rounded-md text-xs font-semibold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center"
                >
                    Jadwal Mingguan (Rutin)
                </button>
                <button
                    type="button"
                    id="btn-tab-single"
                    onclick="switchScheduleMode('single')"
                    class="py-2 rounded-md text-xs font-semibold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center"
                >
                    Periode Tunggal
                </button>
            </div>

            <form action="{{ route('pengurus.tagihan.period.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="schedule_type" id="input-schedule-type" value="weekly">

                <!-- Mode 1: Weekly Fields -->
                <div id="container-weekly" class="space-y-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Nama / Label Kas Mingguan</label>
                        <input
                            type="text"
                            name="title"
                            id="field-weekly-title"
                            required
                            value="Kas Mingguan Ormawa"
                            placeholder="Contoh: Kas Rutin Mingguan"
                            class="w-full h-10 px-3.5 rounded-lg bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Pilih Bulan</label>
                            <input
                                type="month"
                                name="month"
                                id="field-weekly-month"
                                required
                                value="{{ $calendarDate->format('Y-m') }}"
                                class="w-full h-10 px-3 rounded-lg bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Hari Rutin Tiap Minggu</label>
                            <select
                                name="day_of_week"
                                id="field-weekly-day"
                                required
                                class="w-full h-10 px-3 rounded-lg bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                            >
                                <option value="5">Setiap Hari Jumat</option>
                                <option value="6">Setiap Hari Sabtu</option>
                                <option value="7">Setiap Hari Minggu</option>
                                <option value="1">Setiap Hari Senin</option>
                                <option value="2">Setiap Hari Selasa</option>
                                <option value="3">Setiap Hari Rabu</option>
                                <option value="4">Setiap Hari Kamis</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Nominal Kas per Minggu (Rp)</label>
                        <input
                            type="number"
                            name="amount"
                            id="field-weekly-amount"
                            required
                            min="1000"
                            step="1000"
                            value="5000"
                            placeholder="Contoh: 5000"
                            class="w-full h-10 px-3.5 rounded-lg bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                        >
                    </div>

                    <div class="text-[11px] text-navy-700 bg-butter-100/50 p-3 rounded-lg border border-amber-200 leading-relaxed">
                        <p class="font-bold text-navy-900 mb-0.5">Otomatis Terbit Sekaligus Sebulan:</p>
                        Sistem otomatis mencari semua hari yang dipilih dalam bulan tersebut (4-5 minggu), membuat jadwal periode terpisah per minggu, dan menerbitkan tagihan ke semua anggota aktif.
                    </div>
                </div>

                <!-- Mode 2: Single Period Fields -->
                <div id="container-single" class="space-y-3.5 hidden">
                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Nama Periode Tunggal</label>
                        <input
                            type="text"
                            id="field-single-title"
                            placeholder="Contoh: Iuran Kas Khusus / Acara"
                            class="w-full h-10 px-3.5 rounded-lg bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Nominal per Anggota</label>
                            <input
                                type="number"
                                id="field-single-amount"
                                min="1000"
                                step="1000"
                                placeholder="Contoh: 20000"
                                class="w-full h-10 px-3.5 rounded-lg bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Batas Jatuh Tempo</label>
                            <input
                                type="date"
                                id="field-single-date"
                                value="{{ now()->addDays(14)->format('Y-m-d') }}"
                                class="w-full h-10 px-3.5 rounded-lg bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 focus:outline-none focus:ring-1 focus:ring-navy-900"
                            >
                        </div>
                    </div>

                    <p class="text-[11px] text-navy-500 leading-relaxed bg-ice-100/60 p-3 rounded-lg border border-ice-200">
                        Membuat satu periode tagihan tunggal dengan tenggat waktu jatuh tempo spesifik.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button
                        type="button"
                        onclick="closeModal('modal-create-period')"
                        class="h-9 px-4 rounded-lg bg-ice-100 text-navy-700 font-medium text-xs hover:bg-ice-200 transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-9 px-5 rounded-lg bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer"
                    >
                        Simpan & Terbitkan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Quick Verify Pembayaran Kas Manual (Cash & Cashless) -->
    <div id="modal-verify-payment" style="display: none;" onclick="if(event.target === this) closeModal('modal-verify-payment')" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-xl max-w-sm w-full p-6 shadow-xl border border-ice-200 space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-ice-200">
                <h3 class="font-display font-semibold text-base text-navy-900">Verifikasi Pelunasan</h3>
                <button type="button" onclick="closeModal('modal-verify-payment')" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <p class="text-xs text-navy-700 leading-relaxed">
                Konfirmasi pelunasan iuran untuk <strong id="verify-member-name" class="text-navy-900">-</strong> sebesar <strong id="verify-amount" class="text-navy-900">-</strong>.
            </p>

            <form id="verify-form" method="POST" action="" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1">Metode Penerimaan</label>
                    <select name="payment_method" required class="w-full h-9 px-3 rounded-lg bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200">
                        <option value="cash">Metode Cash: Tunai Langsung ke Bendahara</option>
                        <option value="transfer">Metode Cashless: Transfer Bank (BCA / Mandiri / BRI / dll)</option>
                        <option value="qris">Metode Cashless: QRIS Statis Ormawa / E-Wallet</option>
                    </select>
                </div>

                <p class="text-[11px] text-navy-500 bg-ice-100/60 p-2.5 rounded-lg border border-ice-200">
                    Sistem otomatis mengevaluasi ketepatan waktu pembayaran berdasarkan tanggal jatuh tempo periode ini, dan otomatis mencatat mutasi kas masuk ke Buku Kas ormawa.
                </p>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('modal-verify-payment')" class="h-8 px-3 rounded-lg bg-ice-100 text-navy-700 font-medium text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="h-8 px-4 rounded-lg bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer">Konfirmasi Lunas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Modal & Switcher -->
    <script>
        window.openModal = function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.remove('hidden');
                el.style.display = 'flex';
            }
        };

        window.closeModal = function(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.add('hidden');
                el.style.display = 'none';
            }
        };

        window.openVerifyModal = function(billId, memberName, amount) {
            document.getElementById('verify-member-name').innerText = memberName;
            document.getElementById('verify-amount').innerText = 'Rp ' + Number(amount).toLocaleString('id-ID');
            document.getElementById('verify-form').action = '/pengurus/tagihan/bills/' + billId + '/verify';
            openModal('modal-verify-payment');
        };

        window.switchScheduleMode = function(mode) {
            const inputType = document.getElementById('input-schedule-type');
            const contWeekly = document.getElementById('container-weekly');
            const contSingle = document.getElementById('container-single');
            const btnWeekly = document.getElementById('btn-tab-weekly');
            const btnSingle = document.getElementById('btn-tab-single');

            const fWeeklyTitle = document.getElementById('field-weekly-title');
            const fWeeklyMonth = document.getElementById('field-weekly-month');
            const fWeeklyDay = document.getElementById('field-weekly-day');
            const fWeeklyAmount = document.getElementById('field-weekly-amount');

            const fSingleTitle = document.getElementById('field-single-title');
            const fSingleAmount = document.getElementById('field-single-amount');
            const fSingleDate = document.getElementById('field-single-date');

            if (mode === 'weekly') {
                inputType.value = 'weekly';
                contWeekly.classList.remove('hidden');
                contSingle.classList.add('hidden');

                btnWeekly.className = 'py-2 rounded-md text-xs font-semibold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center';
                btnSingle.className = 'py-2 rounded-md text-xs font-semibold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center';

                if (fWeeklyTitle) { fWeeklyTitle.setAttribute('name', 'title'); fWeeklyTitle.setAttribute('required', 'required'); }
                if (fWeeklyMonth) { fWeeklyMonth.setAttribute('name', 'month'); fWeeklyMonth.setAttribute('required', 'required'); }
                if (fWeeklyDay) { fWeeklyDay.setAttribute('name', 'day_of_week'); fWeeklyDay.setAttribute('required', 'required'); }
                if (fWeeklyAmount) { fWeeklyAmount.setAttribute('name', 'amount'); fWeeklyAmount.setAttribute('required', 'required'); }

                if (fSingleTitle) { fSingleTitle.removeAttribute('name'); fSingleTitle.removeAttribute('required'); }
                if (fSingleAmount) { fSingleAmount.removeAttribute('name'); fSingleAmount.removeAttribute('required'); }
                if (fSingleDate) { fSingleDate.removeAttribute('name'); fSingleDate.removeAttribute('required'); }
            } else {
                inputType.value = 'single';
                contWeekly.classList.add('hidden');
                contSingle.classList.remove('hidden');

                btnSingle.className = 'py-2 rounded-md text-xs font-semibold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center';
                btnWeekly.className = 'py-2 rounded-md text-xs font-semibold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center';

                if (fWeeklyTitle) { fWeeklyTitle.removeAttribute('name'); fWeeklyTitle.removeAttribute('required'); }
                if (fWeeklyMonth) { fWeeklyMonth.removeAttribute('name'); fWeeklyMonth.removeAttribute('required'); }
                if (fWeeklyDay) { fWeeklyDay.removeAttribute('name', 'day_of_week'); fWeeklyDay.removeAttribute('required', 'required'); }
                if (fWeeklyAmount) { fWeeklyAmount.removeAttribute('name'); fWeeklyAmount.removeAttribute('required'); }

                if (fSingleTitle) { fSingleTitle.setAttribute('name', 'title'); fSingleTitle.setAttribute('required', 'required'); }
                if (fSingleAmount) { fSingleAmount.setAttribute('name', 'amount'); fSingleAmount.setAttribute('required', 'required'); }
                if (fSingleDate) { fSingleDate.setAttribute('name', 'due_date'); fSingleDate.setAttribute('required', 'required'); }
            }
        };
    </script>

</body>
</html>
