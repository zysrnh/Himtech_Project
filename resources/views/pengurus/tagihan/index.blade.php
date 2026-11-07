<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tagihan Iuran Kas - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Pengurus Neumorphic Interaktif -->
    <x-sidebar-pengurus active="tagihan" :organization="$organization" />

    <!-- Main Content Area: Tagihan Iuran -->
    <main class="flex-1 min-w-0 p-6 lg:p-8">
        <div class="max-w-[1120px] mx-auto space-y-6">

            <!-- Alerts Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-white/90 border border-ice-200/80 text-navy-900 text-sm font-medium shadow-[3px_3px_10px_rgba(122,163,214,0.15)] flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-500 hover:text-navy-900 text-xs font-bold cursor-pointer">Tutup</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50/90 border border-red-200/80 text-danger text-sm font-medium shadow-[3px_3px_10px_rgba(192,82,74,0.15)]">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Top Header & Aksi Cepat -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        onclick="toggleAppSidebar()"
                        class="h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-navy-900 font-semibold text-xs shadow-xs hover:bg-ice-100 cursor-pointer transition-all flex items-center gap-2 shrink-0"
                        title="Buka / Tutup Sidebar"
                    >
                        <svg class="w-4 h-4 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Menu</span>
                    </button>
                    <div>
                        <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900 leading-tight">
                            Tagihan Iuran Kas
                        </h1>
                        <p class="text-xs sm:text-sm text-navy-500 mt-0.5">
                            Kelola periode kas, verifikasi pelunasan anggota, dan pengaturan metode pembayaran.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <a
                        href="{{ route('pengurus.pembayaran.index') }}"
                        class="h-10 px-4 rounded-xl bg-white text-navy-900 font-semibold text-xs border border-ice-200/70 shadow-[2px_2px_6px_rgba(122,163,214,0.2),-2px_-2px_6px_rgba(255,255,255,0.9)] hover:bg-ice-100/60 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3)] transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Atur Metode Pembayaran</span>
                    </a>

                    <button
                        type="button"
                        onclick="openModal('modal-create-period')"
                        class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.3)] hover:bg-navy-700 active:shadow-[inset_2px_2px_4px_rgba(0,0,0,0.4)] transition-all cursor-pointer flex items-center gap-2"
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
            <div class="bg-white/85 rounded-2xl p-5 border border-ice-200/80 shadow-[4px_4px_14px_rgba(122,163,214,0.15),-4px_-4px_14px_rgba(255,255,255,0.9)] space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-ice-200/70">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-ice-100 text-navy-900 flex items-center justify-center shadow-xs">
                            <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-base text-navy-900">Kalender Jadwal Kas Mingguan</h2>
                            <p class="text-xs text-navy-500">Penanda tanggal & hari jatuh tempo kas mingguan dalam sebulan</p>
                        </div>
                    </div>

                    <!-- Navigasi Bulan -->
                    <div class="flex items-center gap-2">
                        @php
                            $prevMonth = $calendarDate->copy()->subMonth()->format('Y-m');
                            $nextMonth = $calendarDate->copy()->addMonth()->format('Y-m');
                        @endphp
                        <a
                            href="{{ route('pengurus.tagihan.index', ['month' => $prevMonth, 'period_id' => $selectedPeriod?->id]) }}"
                            class="h-8 px-2.5 rounded-lg bg-ice-100 text-navy-900 hover:bg-ice-200 transition-colors flex items-center justify-center text-xs font-semibold cursor-pointer"
                            title="Bulan Sebelumnya"
                        >
                            &larr;
                        </a>
                        <span class="px-3 py-1 rounded-lg bg-ice-100 font-bold text-xs text-navy-900 font-display">
                            {{ $calendarDate->isoFormat('MMMM Y') }}
                        </span>
                        <a
                            href="{{ route('pengurus.tagihan.index', ['month' => $nextMonth, 'period_id' => $selectedPeriod?->id]) }}"
                            class="h-8 px-2.5 rounded-lg bg-ice-100 text-navy-900 hover:bg-ice-200 transition-colors flex items-center justify-center text-xs font-semibold cursor-pointer"
                            title="Bulan Berikutnya"
                        >
                            &rarr;
                        </a>
                    </div>
                </div>

                <!-- Grid Kalender 7 Kolom (Senin s/d Minggu) -->
                <div class="grid grid-cols-7 gap-1.5 text-center">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
                        <div class="py-1.5 text-[11px] font-bold text-navy-500 uppercase tracking-wider bg-ice-100/50 rounded-lg">
                            {{ $dayName }}
                        </div>
                    @endforeach

                    @php
                        $startDayOfWeek = $calendarDate->copy()->startOfMonth()->dayOfWeekIso; // 1 = Senin, 7 = Minggu
                        $daysInMonth = $calendarDate->daysInMonth;
                    @endphp

                    <!-- Padding hari kosong sebelum tanggal 1 -->
                    @for ($i = 1; $i < $startDayOfWeek; $i++)
                        <div class="min-h-16 rounded-xl bg-ice-100/20 border border-transparent"></div>
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

                        <div class="min-h-20 p-1.5 rounded-xl border text-left transition-all flex flex-col justify-between {{ $hasKas ? 'bg-butter-100/40 border-navy-900/30 shadow-xs' : 'bg-white/60 border-ice-200/60 hover:bg-white' }} {{ $isToday ? 'ring-2 ring-navy-900/40' : '' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold {{ $hasKas ? 'text-navy-900 font-display' : 'text-navy-500' }}">
                                    {{ $day }}
                                </span>
                                @if ($isToday)
                                    <span class="text-[8px] font-bold bg-navy-900 text-white px-1 py-0.2 rounded font-mono">HARI INI</span>
                                @endif
                            </div>

                            @if ($hasKas)
                                <div class="mt-1 space-y-1">
                                    @foreach ($kasList as $item)
                                        <a
                                            href="{{ route('pengurus.tagihan.index', ['period_id' => $item['id'], 'month' => $calendarDate->format('Y-m')]) }}"
                                            class="block p-1 rounded-md bg-navy-900 text-white text-[9.5px] leading-tight hover:bg-navy-700 transition-colors shadow-2xs {{ $selectedPeriod && $selectedPeriod->id === $item['id'] ? 'ring-2 ring-butter-100' : '' }}"
                                            title="Klik untuk buka daftar tagihan minggu ini"
                                        >
                                            <p class="font-bold truncate">{{ $item['title'] }}</p>
                                            <div class="flex items-center justify-between mt-0.5 text-[8.5px] text-ice-200">
                                                <span>Rp {{ number_format($item['amount'], 0, ',', '.') }}</span>
                                                <span class="{{ $item['is_all_paid'] ? 'text-green-300 font-bold' : 'text-yellow-200' }}">
                                                    {{ $item['paid_count'] }}/{{ $item['total_count'] }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>

                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 text-[11px] text-navy-500 border-t border-ice-200/60">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-butter-100/80 border border-navy-900/30 inline-block"></span>
                            <span>Jadwal Kas Mingguan</span>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded bg-navy-900 inline-block"></span>
                            <span>Badge Tagihan (Klik untuk Buka Tagihan)</span>
                        </span>
                    </div>
                    <span class="italic">Klik tanggal yang memiliki badge kas untuk membuka rincian tagihan anggota pada periode itu</span>
                </div>
            </div>

            <!-- Pilih Periode Iuran Aktif -->
            <div class="bg-white/80 rounded-2xl p-4 border border-ice-200/70 shadow-[3px_3px_10px_rgba(122,163,214,0.12),-3px_-3px_10px_rgba(255,255,255,0.85)] flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-navy-500 uppercase tracking-wider">Periode Kas:</span>
                    @if ($periods->isEmpty())
                        <span class="text-xs text-navy-500">Belum ada periode tagihan kas yang dibuat.</span>
                    @else
                        <form method="GET" action="{{ route('pengurus.tagihan.index') }}" class="inline-block">
                            <select
                                name="period_id"
                                onchange="this.form.submit()"
                                class="h-9 px-3 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400 cursor-pointer"
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
                        <span class="neu-badge-pill text-[11px] py-0.5 px-2.5 font-bold">
                            {{ $selectedPeriod->due_date->format('d M Y') }}
                        </span>
                        <span class="text-navy-500">Nominal:</span>
                        <span class="font-bold text-navy-900">Rp {{ number_format($selectedPeriod->amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>

            @if ($selectedPeriod)
                <!-- 4 Kartu Metrik Ringkas & Bersih -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Tagihan -->
                    <div class="bg-white/80 rounded-2xl p-4 border border-ice-200/70 shadow-[3px_3px_10px_rgba(122,163,214,0.12),-3px_-3px_10px_rgba(255,255,255,0.85)]">
                        <p class="text-xs text-navy-500">Total Anggota</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1">
                            {{ $metrics['total_bills'] }}
                        </p>
                        <p class="text-[11px] text-navy-500 mt-0.5">Target: Rp {{ number_format($metrics['total_amount'], 0, ',', '.') }}</p>
                    </div>

                    <!-- Terkumpul -->
                    <div class="bg-white/80 rounded-2xl p-4 border border-ice-200/70 shadow-[3px_3px_10px_rgba(122,163,214,0.12),-3px_-3px_10px_rgba(255,255,255,0.85)]">
                        <p class="text-xs text-navy-500">Kas Terkumpul</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1">
                            Rp {{ number_format($metrics['collected_amount'], 0, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-navy-700 mt-0.5">{{ $metrics['paid_count'] }} anggota lunas</p>
                    </div>

                    <!-- Belum Bayar -->
                    <div class="bg-white/80 rounded-2xl p-4 border border-ice-200/70 shadow-[3px_3px_10px_rgba(122,163,214,0.12),-3px_-3px_10px_rgba(255,255,255,0.85)]">
                        <p class="text-xs text-navy-500">Sisa Tunggakan</p>
                        <p class="font-display font-semibold text-2xl text-danger mt-1">
                            {{ $metrics['unpaid_count'] }}
                        </p>
                        <p class="text-[11px] text-danger mt-0.5">Rp {{ number_format($metrics['uncollected_amount'], 0, ',', '.') }} tertahan</p>
                    </div>

                    <!-- Kedisiplinan -->
                    <div class="bg-white/80 rounded-2xl p-4 border border-ice-200/70 shadow-[3px_3px_10px_rgba(122,163,214,0.12),-3px_-3px_10px_rgba(255,255,255,0.85)]">
                        <p class="text-xs text-navy-500">Skor Disiplin</p>
                        <p class="font-display font-semibold text-2xl text-navy-900 mt-1">
                            {{ $metrics['ontime_rate'] }}%
                        </p>
                        <p class="text-[11px] text-navy-500 mt-0.5">{{ $metrics['ontime_count'] }} tepat waktu, {{ $metrics['late_count'] }} telat</p>
                    </div>
                </div>

                <!-- Tabel Tagihan Anggota & Filter -->
                <div class="bg-white/90 rounded-2xl p-5 border border-ice-200/70 shadow-[4px_4px_14px_rgba(122,163,214,0.14),-4px_-4px_14px_rgba(255,255,255,0.9)] space-y-4">
                    <!-- Filter Status -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-ice-200/70">
                        <div class="flex items-center gap-1.5 text-xs">
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'all']) }}"
                                class="px-3 py-1.5 rounded-lg font-semibold transition-all {{ $statusFilter === 'all' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/60' }}"
                            >
                                Semua ({{ $metrics['total_bills'] }})
                            </a>
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'paid_ontime']) }}"
                                class="px-3 py-1.5 rounded-lg font-semibold transition-all {{ $statusFilter === 'paid_ontime' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/60' }}"
                            >
                                Tepat Waktu ({{ $metrics['ontime_count'] }})
                            </a>
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'paid_late']) }}"
                                class="px-3 py-1.5 rounded-lg font-semibold transition-all {{ $statusFilter === 'paid_late' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/60' }}"
                            >
                                Terlambat ({{ $metrics['late_count'] }})
                            </a>
                            <a
                                href="{{ route('pengurus.tagihan.index', ['period_id' => $selectedPeriod->id, 'status' => 'unpaid']) }}"
                                class="px-3 py-1.5 rounded-lg font-semibold transition-all {{ $statusFilter === 'unpaid' ? 'bg-navy-900 text-white shadow-xs' : 'bg-ice-100 text-navy-700 hover:bg-ice-200/60' }}"
                            >
                                Belum Bayar ({{ $metrics['unpaid_count'] }})
                            </a>
                        </div>
                    </div>

                    <!-- Tabel Anggota -->
                    @if ($bills->isEmpty())
                        <div class="py-12 text-center text-sm text-navy-500">
                            Tidak ada data tagihan yang sesuai dengan filter ini.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-ice-200 text-navy-500 font-semibold uppercase tracking-wider text-[11px]">
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
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 font-bold text-[10px] shadow-xs">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                            Tepat Waktu
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 font-bold text-[10px] shadow-xs">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                                            Terlambat
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-100 text-danger font-bold text-[10px]">
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
                                                @else
                                                    <span class="text-navy-500">-</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                @if ($bill->status === 'paid')
                                                    <span class="text-[11px] text-navy-500 italic">
                                                        {{ $bill->payment_method === 'midtrans' ? 'Auto Midtrans' : ($bill->verifier ? 'acc by '.$bill->verifier->name : 'Lunas') }}
                                                    </span>
                                                @else
                                                    <div class="flex items-center justify-end gap-1.5">
                                                        <!-- Tombol Verifikasi Manual -->
                                                        <button
                                                            type="button"
                                                            onclick="openVerifyModal('{{ $bill->id }}', '{{ $bill->member->name }}', '{{ $bill->amount }}')"
                                                            class="h-7 px-2.5 rounded-lg bg-navy-900 text-white font-semibold text-[11px] hover:bg-navy-700 transition-colors shadow-xs cursor-pointer"
                                                        >
                                                            Verifikasi
                                                        </button>

                                                        <!-- Tombol Kirim Pengingat WhatsApp -->
                                                        <form action="{{ route('pengurus.tagihan.bills.remind', $bill->id) }}" method="POST" class="inline-block">
                                                            @csrf
                                                            <button
                                                                type="submit"
                                                                title="Kirim Pengingat WhatsApp"
                                                                class="h-7 px-2 rounded-lg bg-emerald-600 text-white font-semibold text-[11px] hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer"
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
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-ice-200/80 space-y-4 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <div>
                    <h3 class="font-display font-semibold text-lg text-navy-900">Buat Jadwal Tagihan Kas</h3>
                    <p class="text-xs text-navy-500">Pilih mode kas mingguan rutin atau periode tunggal</p>
                </div>
                <button type="button" onclick="closeModal('modal-create-period')" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <!-- Tab Switcher Mode Jadwal -->
            <div class="grid grid-cols-2 p-1 bg-ice-100 rounded-xl gap-1">
                <button
                    type="button"
                    id="btn-tab-weekly"
                    onclick="switchScheduleMode('weekly')"
                    class="py-2 rounded-lg text-xs font-bold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center"
                >
                    Jadwal Mingguan (Rutin)
                </button>
                <button
                    type="button"
                    id="btn-tab-single"
                    onclick="switchScheduleMode('single')"
                    class="py-2 rounded-lg text-xs font-bold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center"
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
                            class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
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
                                class="w-full h-10 px-3 rounded-xl bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Hari Rutin Tiap Minggu</label>
                            <select
                                name="day_of_week"
                                id="field-weekly-day"
                                required
                                class="w-full h-10 px-3 rounded-xl bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none"
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
                            class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
                        >
                    </div>

                    <div class="text-[11px] text-navy-700 bg-butter-100/50 p-3 rounded-xl border border-butter-200 leading-relaxed">
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
                            class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
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
                                class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Batas Jatuh Tempo</label>
                            <input
                                type="date"
                                id="field-single-date"
                                value="{{ now()->addDays(14)->format('Y-m-d') }}"
                                class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
                            >
                        </div>
                    </div>

                    <p class="text-[11px] text-navy-500 leading-relaxed bg-ice-100/60 p-3 rounded-xl border border-ice-200/60">
                        Membuat satu periode tagihan tunggal dengan tenggat waktu jatuh tempo spesifik.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button
                        type="button"
                        onclick="closeModal('modal-create-period')"
                        class="h-9 px-4 rounded-xl bg-ice-100 text-navy-700 font-semibold text-xs hover:bg-ice-200/70 transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-9 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer"
                    >
                        Simpan & Terbitkan Tagihan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Quick Verify Pembayaran Kas Manual (Cash & Cashless) -->
    <div id="modal-verify-payment" style="display: none;" onclick="if(event.target === this) closeModal('modal-verify-payment')" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl border border-ice-200/80 space-y-4 animate-scale-up">
            <div class="flex items-center justify-between pb-2 border-b border-ice-200/70">
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
                    <select name="payment_method" required class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200">
                        <option value="cash">Metode Cash: Tunai Langsung ke Bendahara</option>
                        <option value="transfer">Metode Cashless: Transfer Bank (BCA / Mandiri / BRI / dll)</option>
                        <option value="qris">Metode Cashless: QRIS Statis Ormawa / E-Wallet</option>
                    </select>
                </div>

                <p class="text-[11px] text-navy-500 bg-ice-100/60 p-2.5 rounded-lg border border-ice-200/50">
                    Sistem otomatis mengevaluasi ketepatan waktu pembayaran berdasarkan tanggal jatuh tempo periode ini, dan otomatis mencatat mutasi kas masuk ke Buku Kas ormawa.
                </p>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('modal-verify-payment')" class="h-8 px-3 rounded-lg bg-ice-100 text-navy-700 font-semibold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="h-8 px-4 rounded-lg bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer">Konfirmasi Lunas</button>
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

                btnWeekly.className = 'py-2 rounded-lg text-xs font-bold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center';
                btnSingle.className = 'py-2 rounded-lg text-xs font-bold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center';

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

                btnSingle.className = 'py-2 rounded-lg text-xs font-bold transition-all bg-white text-navy-900 shadow-xs cursor-pointer text-center';
                btnWeekly.className = 'py-2 rounded-lg text-xs font-bold transition-all text-navy-500 hover:text-navy-900 cursor-pointer text-center';

                if (fWeeklyTitle) { fWeeklyTitle.removeAttribute('name'); fWeeklyTitle.removeAttribute('required'); }
                if (fWeeklyMonth) { fWeeklyMonth.removeAttribute('name'); fWeeklyMonth.removeAttribute('required'); }
                if (fWeeklyDay) { fWeeklyDay.removeAttribute('name'); fWeeklyDay.removeAttribute('required'); }
                if (fWeeklyAmount) { fWeeklyAmount.removeAttribute('name'); fWeeklyAmount.removeAttribute('required'); }

                if (fSingleTitle) { fSingleTitle.setAttribute('name', 'title'); fSingleTitle.setAttribute('required', 'required'); }
                if (fSingleAmount) { fSingleAmount.setAttribute('name', 'amount'); fSingleAmount.setAttribute('required', 'required'); }
                if (fSingleDate) { fSingleDate.setAttribute('name', 'due_date'); fSingleDate.setAttribute('required', 'required'); }
            }
        };
    </script>

</body>
</html>

