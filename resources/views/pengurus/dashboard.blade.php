<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Pengurus - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Pengurus (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand & Nama Organisasi -->
            <div class="h-18 px-6 flex items-center justify-between border-b border-ice-200">
                <a href="{{ route('pengurus.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-8 w-auto">
                </a>
                <span class="text-xs px-2 py-0.5 rounded bg-ice-100 text-navy-900 font-semibold font-mono">
                    {{ $organization->code }}
                </span>
            </div>

            <!-- Identitas Organisasi Aktif -->
            <div class="px-6 py-4 bg-ice-100/40 border-b border-ice-200">
                <p class="text-xs text-navy-500 font-normal">Organisasi Aktif</p>
                <p class="text-sm font-semibold text-navy-900 truncate mt-0.5" title="{{ $organization->name }}">
                    {{ $organization->name }}
                </p>
            </div>

            <!-- Nav Items -->
            <nav class="p-4 space-y-1">
                @if (Auth::user()->is_super_admin)
                    <a
                        href="{{ Auth::user()->isAdminPenerima() ? route('admin.organizations.index', ['tab' => 'requests']) : route('admin.dashboard') }}"
                        class="flex items-center h-11 px-4 rounded-md bg-butter-100 text-navy-900 font-semibold text-sm transition-colors mb-2"
                    >
                        &larr; Ke Panel Admin Kasly
                    </a>
                @endif
                <a
                    href="{{ route('pengurus.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Overview Kas
                </a>
                @if (session('active_role') === 'admin_organisasi' || Auth::user()->isSuperAdmin())
                    <a
                        href="{{ route('pengurus.staff.index') }}"
                        class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                    >
                        Kelola Staff
                    </a>
                @endif
                <a
                    href="{{ route('pengurus.anggota.index') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Data Anggota
                </a>
                <a
                    href="#tagihan"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Tagihan Iuran
                </a>
                <a
                    href="#buku-kas"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Buku Kas Mutasi
                </a>
                <a
                    href="{{ route('pengurus.profile') }}"
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
                <p class="text-sm font-medium text-navy-900 truncate">
                    {{ Auth::user()->name }}
                </p>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="inline-block text-[11px] px-1.5 py-0.5 rounded bg-butter-100 text-navy-900 font-medium">
                        {{ ucfirst(session('active_role', 'staff')) }}
                    </span>
                    <span class="text-xs text-navy-500 truncate">
                        {{ Auth::user()->email }}
                    </span>
                </div>
            </div>
            <form action="{{ route('pengurus.logout') }}" method="POST">
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

    <!-- Main Content Area: Dashboard Pengurus -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Overview Kas {{ $organization->name }}
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Pantau saldo kas, tingkat kepatuhan bayar tepat waktu, dan mutasi keuangan terpusat.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if (session('active_role') === 'admin_organisasi' || Auth::user()->isSuperAdmin())
                        <a
                            href="{{ route('pengurus.staff.index') }}"
                            class="inline-flex items-center justify-center h-10 px-4 rounded-md bg-navy-900 text-white font-medium text-xs hover:bg-navy-700 transition-colors shadow-sm"
                        >
                            Kelola Staff & Bendahara
                        </a>
                    @endif
                    <span class="inline-flex items-center px-3 py-2 rounded-md bg-white border border-ice-200 text-xs font-medium text-navy-900">
                        Status: Organisasi Aktif
                    </span>
                </div>
            </div>

            <!-- Kartu Hero Saldo Kas (radius-blob khas Ice & Butter v2) -->
            <div class="bg-navy-900 text-white rounded-blob p-8 shadow-card flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="text-sm text-white/70">Saldo Kas Aktif Saat Ini</p>
                    <p class="font-display font-semibold text-4xl md:text-5xl tabular-nums mt-2">
                        Rp {{ number_format($currentBalance, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-white/60 mt-2">
                        Dihitung dari seluruh transaksi kas masuk dikurangi kas keluar.
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-6 pt-6 lg:pt-0 border-t lg:border-t-0 lg:border-l border-white/10 lg:pl-8 shrink-0">
                    <div>
                        <p class="text-xs text-white/70">Total Kas Masuk</p>
                        <p class="font-display font-semibold text-lg md:text-xl tabular-nums mt-1 text-white">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-white/70">Total Kas Keluar</p>
                        <p class="font-display font-semibold text-lg md:text-xl tabular-nums mt-1 text-white">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-white/70">Total Anggota</p>
                        <p class="font-display font-semibold text-lg md:text-xl tabular-nums mt-1 text-white">
                            {{ $totalMembers }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Kartu Disiplin Bayar Kas Tepat Waktu (Fokus Tema Lomba) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Metrik Persentase Ketepatan Waktu -->
                <div class="md:col-span-2 bg-butter-100 border border-ice-200 rounded-lg p-6 shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <span class="inline-block text-xs font-semibold text-navy-900 bg-white/70 px-2.5 py-1 rounded">
                            Indikator Kedisiplinan Iuran
                        </span>
                        <h2 class="font-display font-semibold text-xl text-navy-900 mt-2">
                            Ketepatan Waktu Pembayaran Kas
                        </h2>
                        <p class="text-sm text-navy-700 mt-1 leading-relaxed max-w-[460px]">
                            {{ $onTimeBillsCount }} dari {{ $paidBillsCount }} tagihan terbayar lunas sebelum atau tepat pada tanggal jatuh tempo.
                        </p>
                    </div>
                    <div class="text-center sm:text-right shrink-0 bg-white/80 p-4 rounded-lg border border-navy-900/10">
                        <p class="text-xs text-navy-500 font-medium">Skor Disiplin</p>
                        <p class="font-display font-semibold text-4xl tabular-nums text-navy-900 mt-1">
                            {{ $onTimeRate }}%
                        </p>
                    </div>
                </div>

                <!-- Kartu Status Tagihan Belum Dibayar -->
                <div class="bg-white border border-ice-200 rounded-lg p-6 shadow-card flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-navy-500 font-normal">Tunggakan Iuran Belum Dibayar</p>
                        <p class="font-display font-semibold text-2xl tabular-nums text-danger mt-2">
                            {{ $unpaidBillsCount }} Tagihan
                        </p>
                        <p class="text-sm text-navy-500 mt-1">
                            Total nominal: <span class="font-medium text-navy-900">Rp {{ number_format($unpaidBillsAmount, 0, ',', '.') }}</span>
                        </p>
                    </div>
                    <div class="mt-4 pt-4 border-t border-ice-200">
                        <span class="text-xs text-navy-500">
                            Fitur blast WhatsApp pengingat otomatis siap digunakan.
                        </span>
                    </div>
                </div>
            </div>

            <!-- Dua Kolom: Mutasi Terakhir & Periode Iuran Aktif -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Kolom Kiri (2 Span): Mutasi Kas Terakhir -->
                <div class="lg:col-span-2 bg-white rounded-lg p-6 shadow-card border border-ice-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Mutasi Kas Terbaru
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Catatan kas masuk dan kas keluar organisasi secara transparan.
                            </p>
                        </div>
                    </div>

                    @if ($recentTransactions->isEmpty())
                        <div class="py-12 text-center text-sm text-navy-500">
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

                <!-- Kolom Kanan (1 Span): Periode Iuran Aktif -->
                <div class="bg-white rounded-lg p-6 shadow-card border border-ice-200 space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-ice-200">
                        <div>
                            <h2 class="font-display font-medium text-lg text-navy-900">
                                Periode Iuran Aktif
                            </h2>
                            <p class="text-xs text-navy-500 mt-0.5">
                                Jadwal tagihan kas dengan batas jatuh tempo.
                            </p>
                        </div>
                    </div>

                    @if ($activePeriods->isEmpty())
                        <div class="py-12 text-center text-sm text-navy-500">
                            Belum ada jadwal periode iuran yang dibuat.
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($activePeriods as $period)
                                <div class="p-4 rounded-md bg-ice-100 border border-ice-200 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-navy-900">
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

        </div>
    </main>

</body>
</html>

