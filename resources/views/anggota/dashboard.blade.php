<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Anggota - {{ $member->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('logo/icon-kasly.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/icon-32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/icon-192.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen">

    <!-- Top Navigation Bar Khusus Anggota -->
    <header class="w-full bg-white border-b border-ice-200 sticky top-0 z-40">
        <div class="max-w-[1120px] mx-auto px-6 h-18 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-8 w-auto">
                </a>
                <span class="text-xs px-2.5 py-1 rounded bg-ice-100 text-navy-900 font-semibold font-mono border border-ice-200">
                    {{ $organization->code }} &middot; {{ $organization->name }}
                </span>
            </div>

            <!-- Identitas Anggota & Logout -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-navy-900 leading-tight">
                        {{ $member->name }}
                    </p>
                    <p class="text-xs text-navy-500 font-mono">
                        NIM: {{ $member->member_number }}
                    </p>
                </div>
                <form action="{{ route('anggota.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-xs hover:bg-ice-200 transition-colors cursor-pointer"
                    >
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-[1120px] mx-auto p-6 md:p-8 space-y-8">

        <!-- Welcome Header -->
        <div>
            <h1 class="font-display font-semibold text-2xl md:text-3xl text-navy-900">
                Halo, {{ $member->name }}
            </h1>
            <p class="text-sm text-navy-500 mt-1">
                Pantau status iuran kas pribadi dan keterbukaan saldo keuangan {{ $organization->name }}.
            </p>
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
                        <span class="font-medium text-navy-900 bg-butter-100 px-2 py-0.5 rounded">
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

        <!-- Tabel Riwayat & Tagihan Iuran Saya -->
        <div class="bg-white rounded-lg p-6 shadow-card border border-ice-200 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                <div>
                    <h2 class="font-display font-medium text-lg text-navy-900">
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
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-ice-200 text-xs text-navy-500">
                                <th class="py-2.5 font-normal">Periode Tagihan</th>
                                <th class="py-2.5 font-normal">Batas Jatuh Tempo</th>
                                <th class="py-2.5 font-normal">Nominal</th>
                                <th class="py-2.5 font-normal">Status</th>
                                <th class="py-2.5 font-normal">Ketepatan Waktu</th>
                                <th class="py-2.5 font-normal">Tanggal Bayar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ice-100">
                            @foreach ($myBills as $bill)
                                <tr>
                                    <td class="py-3 font-medium text-navy-900 text-sm">
                                        {{ $bill->duesPeriod->title ?? 'Iuran Kas' }}
                                    </td>
                                    <td class="py-3 text-xs text-navy-500">
                                        {{ $bill->duesPeriod?->due_date?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="py-3 font-semibold tabular-nums text-navy-900 text-sm">
                                        Rp {{ number_format($bill->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-xs">
                                        @if ($bill->status === 'paid')
                                            <span class="inline-block px-2.5 py-1 rounded bg-butter-100 text-navy-900 font-medium">
                                                Lunas
                                            </span>
                                        @elseif ($bill->status === 'pending')
                                            <span class="inline-block px-2.5 py-1 rounded bg-ice-200 text-navy-900 font-medium">
                                                Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span class="inline-block px-2.5 py-1 rounded bg-danger/10 text-danger font-medium">
                                                Belum Dibayar
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-xs">
                                        @if ($bill->status === 'paid')
                                            @if ($bill->is_on_time)
                                                <span class="font-medium text-navy-900">
                                                    Tepat Waktu
                                                </span>
                                            @else
                                                <span class="text-danger font-medium">
                                                    Terlambat
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-navy-500">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-xs text-navy-500">
                                        {{ $bill->paid_at?->format('d/m/Y H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Tabel Mutasi Kas Terbuka Organisasi (Transparansi) -->
        <div class="bg-white rounded-lg p-6 shadow-card border border-ice-200 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                <div>
                    <h2 class="font-display font-medium text-lg text-navy-900">
                        Mutasi Kas Terbuka Organisasi
                    </h2>
                    <p class="text-xs text-navy-500 mt-0.5">
                        Transparansi pengeluaran dan pemasukan kas {{ $organization->name }}.
                    </p>
                </div>
            </div>

            @if ($publicTransactions->isEmpty())
                <div class="py-12 text-center text-sm text-navy-500">
                    Belum ada riwayat transaksi kas yang dibukukan.
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
                            @foreach ($publicTransactions as $tx)
                                <tr>
                                    <td class="py-3 text-xs text-navy-500">
                                        {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="py-3 text-xs font-medium text-navy-900">
                                        {{ $tx->category->name ?? 'Kas Umum' }}
                                    </td>
                                    <td class="py-3 text-xs text-navy-700">
                                        {{ $tx->description }}
                                    </td>
                                    <td class="py-3 text-sm font-semibold tabular-nums text-right {{ $tx->type === 'in' ? 'text-navy-900' : 'text-danger' }}">
                                        {{ $tx->type === 'in' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </main>

</body>
</html>
