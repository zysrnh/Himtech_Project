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
                <div>
                    <h1 class="font-display font-semibold text-2xl lg:text-[28px] text-navy-900">
                        Tagihan Iuran Kas
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Kelola periode kas, verifikasi pelunasan anggota, dan pengaturan metode pembayaran.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    <button
                        type="button"
                        onclick="openModal('modal-payment-methods')"
                        class="h-10 px-4 rounded-xl bg-white text-navy-900 font-semibold text-xs border border-ice-200/70 shadow-[2px_2px_6px_rgba(122,163,214,0.2),-2px_-2px_6px_rgba(255,255,255,0.9)] hover:bg-ice-100/60 active:shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3)] transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Metode Pembayaran</span>
                    </button>

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

    <!-- Modal 1: Buat Periode Tagihan Kas Baru -->
    <div id="modal-create-period" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-ice-200/80 space-y-5 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <h3 class="font-display font-semibold text-lg text-navy-900">Buat Periode Tagihan Baru</h3>
                <button type="button" onclick="closeModal('modal-create-period')" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('pengurus.tagihan.period.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1">Nama Periode Tagihan</label>
                    <input
                        type="text"
                        name="title"
                        required
                        placeholder="Contoh: Iuran Kas Desember 2026"
                        class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
                    >
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Nominal per Anggota</label>
                        <input
                            type="number"
                            name="amount"
                            required
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
                            name="due_date"
                            required
                            value="{{ now()->addDays(14)->format('Y-m-d') }}"
                            class="w-full h-10 px-3.5 rounded-xl bg-ice-100 text-navy-900 font-medium text-sm border border-ice-200 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.25)] focus:outline-none focus:ring-1 focus:ring-ice-400"
                        >
                    </div>
                </div>

                <p class="text-[11px] text-navy-500 leading-relaxed bg-ice-100/60 p-3 rounded-xl border border-ice-200/60">
                    Sistem otomatis menerbitkan tagihan ke seluruh anggota aktif organisasi saat periode dibuat. Anggota yang membayar sebelum atau pada tanggal jatuh tempo akan dinilai <strong>Tepat Waktu</strong>.
                </p>

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
                        Simpan & Terbitkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: Pengaturan Metode Pembayaran Ormawa (Bank, E-Wallet, QRIS, Midtrans) -->
    <div id="modal-payment-methods" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-ice-200/80 space-y-5 max-h-[90vh] overflow-y-auto custom-scrollbar animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <div>
                    <h3 class="font-display font-semibold text-lg text-navy-900">Metode Pembayaran Ormawa</h3>
                    <p class="text-xs text-navy-500 mt-0.5">Atur rekening bank, e-wallet, gambar QRIS, dan saklar Midtrans.</p>
                </div>
                <button type="button" onclick="closeModal('modal-payment-methods')" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <!-- Saklar Midtrans Sandbox -->
            <div class="p-4 rounded-xl bg-ice-100/70 border border-ice-200/80 flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-navy-900">Midtrans Sandbox (QRIS & VA Otomatis)</span>
                        <span class="neu-badge-pill text-[9px] py-0.2 px-1.5">OTOMATIS</span>
                    </div>
                    <p class="text-[11px] text-navy-500 mt-0.5">Izinkan anggota membayar instan via QRIS Dinamis & Virtual Account yang otomatis lunas seketika.</p>
                </div>

                <form action="{{ route('pengurus.tagihan.toggle-midtrans') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="h-8 px-3 rounded-lg font-bold text-xs cursor-pointer transition-colors {{ $organization->is_midtrans_enabled ? 'bg-emerald-600 text-white' : 'bg-ice-200 text-navy-700' }}"
                    >
                        {{ $organization->is_midtrans_enabled ? 'Aktif' : 'Nonaktif' }}
                    </button>
                </form>
            </div>

            <!-- Daftar Rekening & QRIS yang Sedang Aktif -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-navy-900 uppercase tracking-wider">Rekening & QRIS Terdaftar</h4>

                @if ($paymentMethods->isEmpty())
                    <p class="text-xs text-navy-500 italic bg-ice-100/40 p-3 rounded-xl text-center">Belum ada rekening bank atau QRIS yang didaftarkan.</p>
                @else
                    <div class="space-y-2">
                        @foreach ($paymentMethods as $pm)
                            <div class="p-3 rounded-xl bg-ice-100/50 border border-ice-200/60 flex items-center justify-between gap-3">
                                <div class="min-w-0 flex-1 text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-navy-900">{{ $pm->channel_name }}</span>
                                        <span class="neu-badge-ice text-[9px] py-0 px-1.5">{{ strtoupper($pm->type) }}</span>
                                    </div>
                                    @if ($pm->account_number)
                                        <p class="font-mono text-navy-700 font-semibold mt-0.5">{{ $pm->account_number }} (a.n. {{ $pm->account_holder }})</p>
                                    @endif
                                    @if ($pm->instructions)
                                        <p class="text-[10px] text-navy-500 mt-0.5">{{ $pm->instructions }}</p>
                                    @endif
                                </div>

                                <form action="{{ route('pengurus.tagihan.payment-methods.destroy', $pm->id) }}" method="POST" onsubmit="return confirm('Hapus metode pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger hover:underline text-xs font-bold cursor-pointer">Hapus</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Form Tambah Rekening / QRIS Baru -->
            <div class="pt-3 border-t border-ice-200/70 space-y-3">
                <h4 class="text-xs font-bold text-navy-900 uppercase tracking-wider">+ Tambah Rekening / E-Wallet / QRIS</h4>

                <form action="{{ route('pengurus.tagihan.payment-methods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Jenis</label>
                            <select name="type" required class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200">
                                <option value="bank">Rekening Bank</option>
                                <option value="ewallet">E-Wallet (DANA/GoPay/OVO)</option>
                                <option value="qris">QRIS Statis Ormawa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Nama Channel / Bank</label>
                            <input type="text" name="channel_name" required placeholder="Contoh: Bank BCA / DANA" class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 text-xs border border-ice-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Nomor Rekening / No. HP</label>
                            <input type="text" name="account_number" placeholder="Contoh: 1234567890" class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 text-xs border border-ice-200">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-navy-700 mb-1">Atas Nama (A.N.)</label>
                            <input type="text" name="account_holder" placeholder="Contoh: Siti Nurhaliza" class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 text-xs border border-ice-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Petunjuk Pembayaran (Opsional)</label>
                        <input type="text" name="instructions" placeholder="Contoh: Beri berita transfer NIM Anda" class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 text-xs border border-ice-200">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1">Upload Gambar QRIS (Khusus QRIS, Maks 2MB)</label>
                        <input type="file" name="qris_image" accept="image/*" class="w-full text-xs text-navy-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-ice-200 file:text-navy-900 hover:file:bg-ice-300">
                    </div>

                    <div class="pt-2 text-right">
                        <button type="submit" class="h-8 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors cursor-pointer">
                            Simpan Metode Bayar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 3: Quick Verify Pembayaran Kas Manual -->
    <div id="modal-verify-payment" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs hidden">
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
                    <label class="block text-xs font-semibold text-navy-700 mb-1">Metode Diterima</label>
                    <select name="payment_method" required class="w-full h-9 px-3 rounded-xl bg-ice-100 text-navy-900 font-medium text-xs border border-ice-200">
                        <option value="cash">Tunai / Cash (Diterima Langsung)</option>
                        <option value="transfer">Transfer Bank (BCA/Mandiri/BRI)</option>
                        <option value="qris">QRIS Statis Ormawa / E-Wallet</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <p class="text-[11px] text-navy-500 bg-ice-100/60 p-2.5 rounded-lg border border-ice-200/50">
                    Sistem otomatis mengevaluasi ketepatan waktu pembayaran berdasarkan tanggal jatuh tempo periode ini, dan otomatis mencatat transaksi kas masuk.
                </p>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('modal-verify-payment')" class="h-8 px-3 rounded-lg bg-ice-100 text-navy-700 font-semibold text-xs cursor-pointer">Batal</button>
                    <button type="submit" class="h-8 px-4 rounded-lg bg-emerald-600 text-white font-semibold text-xs hover:bg-emerald-700 transition-colors shadow-xs cursor-pointer">Konfirmasi Lunas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Modal -->
    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.remove('hidden');
        }

        function closeModal(id) {
            const el = document.getElementById(id);
            if (el) el.classList.add('hidden');
        }

        function openVerifyModal(billId, memberName, amount) {
            document.getElementById('verify-member-name').innerText = memberName;
            document.getElementById('verify-amount').innerText = 'Rp ' + Number(amount).toLocaleString('id-ID');
            document.getElementById('verify-form').action = '/pengurus/tagihan/bills/' + billId + '/verify';
            openModal('modal-verify-payment');
        }
    </script>

</body>
</html>

