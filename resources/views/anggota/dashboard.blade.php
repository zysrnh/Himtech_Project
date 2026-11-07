<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Anggota - {{ $member->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Midtrans Snap SDK Sandbox -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Anggota Neumorphic Interaktif -->
    <x-sidebar-anggota active="dashboard" :organization="$organization" :member="$member" />

    <!-- Main Content Area: Portal Anggota -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Banner Notifikasi Selamat Datang / Lengkapi Profil -->
            @if (session('success'))
                <div class="p-4 rounded-md bg-butter-100 text-navy-900 text-sm font-medium shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <span>{{ session('success') }}</span>
                    <a
                        href="{{ route('anggota.profile') }}"
                        class="inline-flex items-center justify-center h-8 px-3 rounded bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shrink-0"
                    >
                        Sesuaikan Profil Saya &rarr;
                    </a>
                </div>
            @elseif (str_starts_with($member->member_number, $organization->code.'-'))
                <div class="p-4 rounded-md bg-white border border-ice-200 text-navy-900 text-xs shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <span class="font-semibold text-navy-900">ID Anggota Default: {{ $member->member_number }}</span>
                        <p class="text-navy-500 mt-0.5">Anda bergabung menggunakan QR Code. Anda dapat melengkapi nama asli, NIM kampus, dan kata sandi di menu profil.</p>
                    </div>
                    <a
                        href="{{ route('anggota.profile') }}"
                        class="inline-flex items-center justify-center h-8 px-3.5 rounded bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shrink-0"
                    >
                        Lengkapi Profil
                    </a>
                </div>
            @endif

            <!-- Welcome Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Halo, {{ $member->name }}
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Pantau status iuran kas pribadi dan keterbukaan saldo keuangan {{ $organization->name }}.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('anggota.profile') }}"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-md border border-ice-200 bg-white text-navy-900 font-medium text-xs hover:bg-ice-100 transition-colors shadow-sm"
                    >
                        Kelola Profil Saya
                    </a>
                </div>
            </div>

            <!-- Kartu Transparansi Finansial Bersama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Saldo Kas Ormawa (Transparansi Terbuka) -->
                <div class="bg-navy-900 text-white rounded-blob p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-white/70">Saldo Kas Organisasi (Transparan)</span>
                        <p class="font-display font-semibold text-3xl tabular-nums mt-2">
                            Rp {{ number_format($currentBalance, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-white/10 flex items-center justify-between text-xs text-white/70">
                        <span>Masuk: Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                        <span>Keluar: Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Status Tunggakan Iuran Saya -->
                <div class="bg-white border border-ice-200 rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-navy-500 font-normal">Tunggakan Iuran Saya</span>
                        <p class="font-display font-semibold text-3xl tabular-nums {{ $unpaidBills->isNotEmpty() ? 'text-danger' : 'text-navy-900' }} mt-2">
                            {{ $unpaidBills->count() }} Tagihan
                        </p>
                        <p class="text-sm text-navy-500 mt-1">
                            Total yang harus dibayar: <span class="font-semibold text-navy-900">Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-ice-200 text-xs">
                        @if ($unpaidBills->isEmpty())
                            <span class="font-semibold text-navy-900 bg-butter-100 px-2 py-0.5 rounded">
                                Semua tagihan kas Anda sudah lunas tepat waktu.
                            </span>
                        @else
                            <span class="text-danger font-medium">
                                Harap selesaikan pembayaran sebelum batas jatuh tempo.
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Info Kontak Pengurus -->
                <div class="bg-white border border-ice-200 rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <span class="text-xs text-navy-500 font-normal">Bantuan & Konfirmasi Kas</span>
                        <h2 class="font-display font-medium text-base text-navy-900 mt-2">
                            Bendahara / Staff Ormawa
                        </h2>
                        <p class="text-xs text-navy-500 mt-1 leading-relaxed">
                            Jika sudah melakukan pembayaran kas tunai atau transfer, konfirmasikan ke pengurus ormawa untuk diverifikasi.
                        </p>
                    </div>
                    <div class="pt-4 mt-4 border-t border-ice-200 text-xs text-navy-500">
                        Organisasi: <span class="font-semibold text-navy-900">{{ $organization->code }}</span>
                    </div>
                </div>

            </div>

            <!-- Bagian 1: Tabel Riwayat & Tagihan Iuran Saya -->
            <div id="tagihan" class="bg-white rounded-lg p-6 sm:p-8 shadow-card border border-ice-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                    <div>
                        <h2 class="font-display font-semibold text-lg text-navy-900">
                            Tagihan & Riwayat Pembayaran Kas Saya
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Daftar seluruh kewajiban kas pada periode berjalan beserta catatan ketepatan waktu.
                        </p>
                    </div>
                </div>

                @if ($myBills->isEmpty())
                    <div class="py-12 text-center text-sm text-navy-500">
                        Belum ada tagihan kas yang diterbitkan untuk akun anggota Anda.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-ice-200 text-xs text-navy-500 bg-ice-100/40">
                                    <th class="py-3 px-4 font-medium">Periode Tagihan</th>
                                    <th class="py-3 px-4 font-medium">Batas Jatuh Tempo</th>
                                    <th class="py-3 px-4 font-medium">Nominal</th>
                                    <th class="py-3 px-4 font-medium">Status</th>
                                    <th class="py-3 px-4 font-medium">Ketepatan Waktu</th>
                                    <th class="py-3 px-4 font-medium">Tanggal Bayar</th>
                                    <th class="py-3 px-4 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100 text-sm">
                                @foreach ($myBills as $bill)
                                    <tr class="hover:bg-ice-100/30 transition-colors">
                                        <td class="py-3 px-4 font-medium text-navy-900 text-sm">
                                            {{ $bill->duesPeriod->title ?? 'Iuran Kas' }}
                                        </td>
                                        <td class="py-3 px-4 text-xs text-navy-500">
                                            {{ $bill->duesPeriod?->due_date?->format('d M Y') ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 font-semibold tabular-nums text-navy-900 text-sm">
                                            Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3 px-4 text-xs">
                                            @if ($bill->status === 'paid')
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-butter-100 text-navy-900 font-semibold text-xs">
                                                    Lunas
                                                </span>
                                            @elseif ($bill->status === 'pending')
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-ice-200 text-navy-900 font-semibold text-xs">
                                                    Menunggu Verifikasi
                                                </span>
                                            @else
                                                <span class="inline-block px-2.5 py-0.5 rounded-full bg-danger/10 text-danger font-semibold text-xs">
                                                    Belum Dibayar
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-xs">
                                            @if ($bill->status === 'paid')
                                                @if ($bill->is_on_time)
                                                    <span class="font-semibold text-navy-900">
                                                        Tepat Waktu
                                                    </span>
                                                @else
                                                    <span class="text-danger font-semibold">
                                                        Terlambat
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-navy-500">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-xs text-navy-500 tabular-nums">
                                            {{ $bill->paid_at?->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            @if ($bill->status === 'unpaid')
                                                <button
                                                    type="button"
                                                    onclick="openPaymentModal('{{ $bill->id }}', '{{ $bill->duesPeriod->title ?? 'Iuran Kas' }}', '{{ $bill->amount }}', '{{ $bill->duesPeriod?->due_date?->format('d M Y') ?? '-' }}')"
                                                    class="h-8 px-3.5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer inline-flex items-center gap-1.5"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <span>Bayar</span>
                                                </button>
                                            @else
                                                <span class="text-xs text-navy-500 italic">Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Bagian 2: Tabel Mutasi Kas Terbuka Organisasi (Transparansi) -->
            <div id="buku-kas" class="bg-white rounded-lg p-6 sm:p-8 shadow-card border border-ice-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                    <div>
                        <h2 class="font-display font-semibold text-lg text-navy-900">
                            Buku Kas Mutasi Terbuka Organisasi
                        </h2>
                        <p class="text-xs text-navy-500 mt-0.5">
                            Transparansi mutasi pengeluaran dan pemasukan kas ormawa {{ $organization->name }}.
                        </p>
                    </div>
                </div>

                @if ($publicTransactions->isEmpty())
                    <div class="py-12 text-center text-sm text-navy-500">
                        Belum ada riwayat transaksi kas yang dibukukan.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm border-collapse">
                            <thead>
                                <tr class="border-b border-ice-200 text-xs text-navy-500 bg-ice-100/40">
                                    <th class="py-3 px-4 font-medium">Tanggal</th>
                                    <th class="py-3 px-4 font-medium">Kategori</th>
                                    <th class="py-3 px-4 font-medium">Keterangan</th>
                                    <th class="py-3 px-4 font-medium text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100 text-sm">
                                @foreach ($publicTransactions as $tx)
                                    <tr class="hover:bg-ice-100/30 transition-colors">
                                        <td class="py-3 px-4 text-xs text-navy-500 tabular-nums">
                                            {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-xs font-semibold text-navy-900">
                                            {{ $tx->category->name ?? 'Kas Umum' }}
                                        </td>
                                        <td class="py-3 px-4 text-xs text-navy-700">
                                            {{ $tx->description }}
                                        </td>
                                        <td class="py-3 px-4 text-sm font-semibold tabular-nums text-right {{ $tx->type === 'in' ? 'text-navy-900' : 'text-danger' }}">
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

    <!-- Modal Pembayaran Tagihan Kas (Midtrans Snap & Bank Ormawa) -->
    <div id="modal-pay-bill" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-ice-200/80 space-y-5 max-h-[90vh] overflow-y-auto custom-scrollbar animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <div>
                    <h3 class="font-display font-semibold text-lg text-navy-900">Pembayaran Iuran Kas</h3>
                    <p id="pay-bill-title" class="text-xs text-navy-500 mt-0.5">Iuran Kas Ormawa</p>
                </div>
                <button type="button" onclick="closePaymentModal()" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <!-- Kartu Nominal & Jatuh Tempo -->
            <div class="p-4 rounded-xl bg-ice-100/70 border border-ice-200/80 flex items-center justify-between">
                <div>
                    <p class="text-xs text-navy-500">Nominal yang Harus Dibayar:</p>
                    <p id="pay-bill-amount" class="font-display font-bold text-2xl text-navy-900 mt-0.5">Rp 0</p>
                </div>
                <div class="text-right">
                    <p class="text-[11px] text-navy-500">Jatuh Tempo:</p>
                    <span id="pay-bill-due" class="neu-badge-pill text-[10px] py-0.5 px-2">15 Nov 2026</span>
                </div>
            </div>

            @php
                $cashMethod = $paymentMethods->firstWhere('type', 'cash');
                $cashlessMethods = $paymentMethods->whereIn('type', ['bank', 'ewallet', 'qris']);
            @endphp

            <!-- Switcher Tab 2 Metode: Cashless vs Cash -->
            <div class="flex items-center p-1 rounded-xl bg-ice-100/80 border border-ice-200/70 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.2)]">
                <button
                    type="button"
                    onclick="switchPaymentTab('cashless')"
                    id="tab-btn-cashless"
                    class="flex-1 h-8 rounded-lg font-bold text-xs transition-all cursor-pointer bg-white text-navy-900 shadow-xs"
                >
                    Metode Cashless (Non-Tunai)
                </button>
                <button
                    type="button"
                    onclick="switchPaymentTab('cash')"
                    id="tab-btn-cash"
                    class="flex-1 h-8 rounded-lg font-bold text-xs transition-all cursor-pointer text-navy-500 hover:text-navy-900"
                >
                    Metode Cash (Tunai)
                </button>
            </div>

            <!-- KONTEN TAB CASHLESS -->
            <div id="tab-content-cashless" class="space-y-4">
                @if ($organization->is_midtrans_enabled)
                    <!-- Opsi 1: Pembayaran Instan Otomatis (Midtrans Sandbox) -->
                    <div class="p-4 rounded-xl bg-white border border-ice-200/80 shadow-[3px_3px_10px_rgba(122,163,214,0.12)] space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-navy-900">1. Pembayaran Instan Otomatis</span>
                            <span class="neu-badge-pill text-[9px] py-0.2 px-1.5">QRIS & VA</span>
                        </div>
                        <p class="text-xs text-navy-500 leading-relaxed">
                            Bayar instan via QRIS Dinamis (GoPay, ShopeePay, DANA) atau Virtual Account. Status otomatis diverifikasi lunas seketika.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-2 pt-1">
                            <button
                                type="button"
                                id="btn-pay-midtrans"
                                onclick="payWithMidtrans()"
                                class="flex-1 h-9 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                <span>Bayar via Midtrans (QRIS/VA)</span>
                            </button>

                            <button
                                type="button"
                                onclick="simulateSandboxSuccess()"
                                title="Khusus Demo: Verifikasi lunas instan di sandbox"
                                class="h-9 px-3 rounded-xl bg-ice-100 text-navy-900 font-semibold text-xs border border-ice-200/80 hover:bg-ice-200/70 transition-colors cursor-pointer shrink-0"
                            >
                                Simulasi Lunas
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Opsi 2: Transfer Bank / E-Wallet / QRIS Statis Ormawa -->
                <div class="p-4 rounded-xl bg-ice-100/50 border border-ice-200/70 space-y-3">
                    <span class="text-xs font-bold text-navy-900">2. Transfer Manual ke Rekening Bendahara</span>

                    @if ($cashlessMethods->isEmpty())
                        <p class="text-xs text-navy-500 italic">Bendahara belum menambahkan nomor rekening bank ormawa.</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($cashlessMethods as $pm)
                                <div class="p-3 rounded-xl bg-white border border-ice-200/70 flex items-center justify-between gap-3 text-xs">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-navy-900">{{ $pm->channel_name }}</span>
                                            <span class="neu-badge-ice text-[9px] py-0 px-1">{{ strtoupper($pm->type) }}</span>
                                        </div>
                                        @if ($pm->account_number)
                                            <p class="font-mono font-bold text-navy-900 mt-0.5 text-sm select-all">{{ $pm->account_number }}</p>
                                            <p class="text-[11px] text-navy-500">a.n. {{ $pm->account_holder }}</p>
                                        @endif
                                        @if ($pm->qris_image_path)
                                            <a href="{{ $pm->qrisUrl() }}" target="_blank" class="inline-block mt-1 text-[11px] text-navy-900 underline font-semibold">
                                                Lihat Gambar QRIS Ormawa &rarr;
                                            </a>
                                        @endif
                                    </div>

                                    @if ($pm->account_number)
                                        <button
                                            type="button"
                                            onclick="copyToClipboard('{{ $pm->account_number }}', this)"
                                            class="h-7 px-2.5 rounded-lg bg-ice-100 text-navy-900 font-semibold text-[11px] border border-ice-200/70 hover:bg-ice-200 transition-colors cursor-pointer shrink-0"
                                        >
                                            Salin
                                        </button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-navy-500">Setelah transfer, silakan konfirmasikan bukti pembayaran ke Bendahara untuk diverifikasi.</p>
                    @endif
                </div>
            </div>

            <!-- KONTEN TAB CASH (TUNAI) -->
            <div id="tab-content-cash" class="space-y-4 hidden">
                <div class="p-4 rounded-xl bg-ice-100/60 border border-ice-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-navy-900">Pembayaran Kas Tunai Langsung</span>
                        <span class="neu-badge-pill text-[9px] py-0.2 px-1.5">FISIK</span>
                    </div>

                    <div class="p-3 rounded-lg bg-white border border-ice-200/70 space-y-1.5 text-xs">
                        <p class="text-navy-500">PIC / Bendahara Penerima:</p>
                        <p class="font-bold text-navy-900">{{ $cashMethod?->account_holder ?? 'Bendahara Ormawa' }}</p>
                    </div>

                    <div class="p-3 rounded-lg bg-white border border-ice-200/70 space-y-1.5 text-xs">
                        <p class="text-navy-500">Petunjuk Serah Terima:</p>
                        <p class="text-navy-700 leading-relaxed">{{ $cashMethod?->instructions ?? 'Silakan serahkan uang tunai langsung ke Bendahara saat pertemuan/kumpul rutin ormawa.' }}</p>
                    </div>

                    <p class="text-[11px] text-navy-500 leading-relaxed">
                        Bendahara akan langsung memverifikasi pelunasan Anda pada aplikasi Kasly begitu uang tunai diterima secara fisik.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Midtrans & Modal Helper -->
    <script>
        let currentPayingBillId = null;

        function openPaymentModal(billId, title, amount, dueDate) {
            currentPayingBillId = billId;
            document.getElementById('pay-bill-title').innerText = title;
            document.getElementById('pay-bill-amount').innerText = 'Rp ' + Number(amount).toLocaleString('id-ID');
            document.getElementById('pay-bill-due').innerText = dueDate;
            document.getElementById('modal-pay-bill').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('modal-pay-bill').classList.add('hidden');
            currentPayingBillId = null;
        }

        function switchPaymentTab(tab) {
            const btnCashless = document.getElementById('tab-btn-cashless');
            const btnCash = document.getElementById('tab-btn-cash');
            const contentCashless = document.getElementById('tab-content-cashless');
            const contentCash = document.getElementById('tab-content-cash');

            if (tab === 'cashless') {
                btnCashless.classList.add('bg-white', 'text-navy-900', 'shadow-xs');
                btnCashless.classList.remove('text-navy-500');
                btnCash.classList.remove('bg-white', 'text-navy-900', 'shadow-xs');
                btnCash.classList.add('text-navy-500');
                contentCashless.classList.remove('hidden');
                contentCash.classList.add('hidden');
            } else {
                btnCash.classList.add('bg-white', 'text-navy-900', 'shadow-xs');
                btnCash.classList.remove('text-navy-500');
                btnCashless.classList.remove('bg-white', 'text-navy-900', 'shadow-xs');
                btnCashless.classList.add('text-navy-500');
                contentCash.classList.remove('hidden');
                contentCashless.classList.add('hidden');
            }
        }

        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const orig = btn.innerText;
                btn.innerText = 'Tersalin!';
                setTimeout(() => { btn.innerText = orig; }, 2000);
            });
        }

        async function payWithMidtrans() {
            if (!currentPayingBillId) return;
            const btn = document.getElementById('btn-pay-midtrans');
            const origText = btn.innerHTML;
            btn.innerHTML = '<span>Memuat Midtrans...</span>';
            btn.disabled = true;

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const res = await fetch(`/anggota/bills/${currentPayingBillId}/snap-token`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    }
                });

                const data = await res.json();

                if (data.status === 'success') {
                    if (window.snap && data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                alert('Pembayaran berhasil diverifikasi!');
                                window.location.reload();
                            },
                            onPending: function(result) {
                                alert('Menunggu pembayaran diselesaikan...');
                                window.location.reload();
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal: ' + (result.status_message || 'Terjadi kesalahan'));
                            },
                            onClose: function() {
                                btn.innerHTML = origText;
                                btn.disabled = false;
                            }
                        });
                    } else {
                        // Fallback jika mode mock/sandbox dummy
                        alert(data.message || 'Membuka simulasi pembayaran...');
                        await simulateSandboxSuccess();
                    }
                } else {
                    alert(data.message || 'Gagal memproses pembayaran via gateway.');
                }
            } catch (e) {
                alert('Kendala koneksi ke server gateway: ' + e.message);
            } finally {
                btn.innerHTML = origText;
                btn.disabled = false;
            }
        }

        async function simulateSandboxSuccess() {
            if (!currentPayingBillId) return;
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch(`/anggota/bills/${currentPayingBillId}/simulate-success`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    }
                });
                const data = await res.json();
                if (data.status === 'success') {
                    alert('Simulasi Midtrans Berhasil: Tagihan otomatis terverifikasi LUNAS!');
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal simulasi.');
                }
            } catch (e) {
                alert('Kendala koneksi: ' + e.message);
            }
        }
    </script>

</body>
</html>
