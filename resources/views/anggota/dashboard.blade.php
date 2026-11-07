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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Anggota (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand & Organization Code -->
            <div class="h-18 px-6 flex items-center justify-between border-b border-ice-200">
                <a href="{{ route('anggota.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
                </a>
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-ice-100 text-navy-900 text-xs font-bold tracking-wider font-display">
                    {{ $organization->code }}
                </span>
            </div>

            <!-- Organisasi Aktif -->
            <div class="px-6 py-3 border-b border-ice-200/50 bg-ice-100/30">
                <p class="text-[11px] text-navy-500 uppercase tracking-wider font-medium">Organisasi Aktif</p>
                <p class="text-xs font-semibold text-navy-900 truncate mt-0.5" title="{{ $organization->name }}">
                    {{ $organization->name }}
                </p>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('anggota.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Overview Kas
                </a>
                <a
                    href="#tagihan"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Tagihan Iuran Saya
                </a>
                <a
                    href="#buku-kas"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Buku Kas Ormawa
                </a>
                <a
                    href="{{ route('anggota.profile') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Profil Saya
                </a>
                <a
                    href="/"
                    target="_blank"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Lihat Beranda Web
                </a>
            </nav>
        </div>

        <!-- User Info & Logout -->
        <div class="p-4 border-t border-ice-200">
            <div class="px-2 mb-3">
                <p class="text-sm font-medium text-navy-900 truncate" title="{{ $member->name }}">
                    {{ $member->name }}
                </p>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="inline-block text-[11px] px-2 py-0.5 rounded bg-butter-100 text-navy-900 font-semibold uppercase">
                        Anggota
                    </span>
                    <span class="text-xs text-navy-500 font-mono truncate">
                        {{ $member->member_number }}
                    </span>
                </div>
            </div>
            <form action="{{ route('anggota.logout') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center justify-center h-10 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm hover:bg-ice-200 transition-colors cursor-pointer"
                >
                    Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

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

</body>
</html>
