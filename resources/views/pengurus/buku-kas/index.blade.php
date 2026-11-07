<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Kas Mutasi - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Pengurus Universal -->
    <x-sidebar-pengurus active="buku-kas" :organization="$organization" />

    <!-- Main Content Area: Buku Kas Mutasi -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Alerts Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-white/95 border border-ice-200/80 text-navy-900 text-sm font-medium shadow-[4px_4px_16px_rgba(122,163,214,0.18),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-butter-100 text-navy-900 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">✓</div>
                        <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-400 hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-white/95 border border-danger/30 text-danger text-sm font-medium shadow-[4px_4px_16px_rgba(192,82,74,0.15),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-danger/10 text-danger flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">!</div>
                        <span class="text-xs sm:text-sm font-medium">{{ $errors->first() }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-danger hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- Top Header & Aksi Cepat -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">
                        Buku Kas & Mutasi Finansial
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Catatan transaksi pemasukan dan pengeluaran kas {{ $organization->name }} secara transparan.
                    </p>
                </div>

                <!-- Tombol Aksi: Ekspor Excel & Catat Transaksi Baru -->
                <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                    <a
                        href="{{ route('pengurus.buku-kas.export', ['type' => $typeFilter, 'month' => $monthFilter]) }}"
                        class="h-11 px-4 rounded-xl bg-white/90 hover:bg-white text-navy-900 font-semibold text-xs border border-ice-200/80 shadow-[3px_3px_8px_rgba(122,163,214,0.15),-3px_-3px_8px_#ffffff] hover:shadow-[4px_4px_12px_rgba(122,163,214,0.22),-4px_-4px_12px_#ffffff] active:scale-[0.99] transition-all flex items-center gap-2 cursor-pointer"
                        title="Unduh laporan buku kas format Excel"
                    >
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Ekspor Excel (.xlsx)</span>
                    </a>

                    <button
                        type="button"
                        onclick="openModal('modal-create-transaction')"
                        class="h-11 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Catat Transaksi Baru</span>
                    </button>
                </div>
            </div>

            <!-- 3 Kartu Metrik Kas Neumorphic -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- 1. Saldo Kas Aktif -->
                <div class="bg-gradient-to-br from-navy-900 via-navy-700 to-navy-900 text-white rounded-[28px] p-6 sm:p-7 shadow-[6px_6px_20px_rgba(47,72,98,0.28),-4px_-4px_16px_rgba(255,255,255,0.7)] flex flex-col justify-between relative overflow-hidden border border-navy-700/60">
                    <div class="relative z-10">
                        <span class="text-xs text-white/70 font-medium">Saldo Kas Aktif Saat Ini</span>
                        <p class="font-display font-bold text-3xl sm:text-4xl tabular-nums mt-3 tracking-tight text-white">
                            Rp {{ number_format($currentBalance, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-[11px] text-white/60 mt-5 pt-3 border-t border-white/10 relative z-10 leading-relaxed">
                        Dana riil tersedia dari seluruh pemasukan dikurangi pengeluaran.
                    </p>
                </div>

                <!-- 2. Total Pemasukan -->
                <div class="bg-white/90 rounded-[28px] p-6 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-navy-500">Total Kas Masuk</span>
                        <p class="font-display font-bold text-2xl sm:text-3xl tabular-nums text-navy-900 mt-3 tracking-tight">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-[11px] text-navy-500 mt-5 pt-3 border-t border-ice-200/60 leading-relaxed">
                        Iuran anggota, sponsor, donasi, dan usaha dana ormawa.
                    </p>
                </div>

                <!-- 3. Total Pengeluaran -->
                <div class="bg-white/90 rounded-[28px] p-6 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-semibold text-navy-500">Total Kas Keluar</span>
                        <p class="font-display font-bold text-2xl sm:text-3xl tabular-nums text-danger mt-3 tracking-tight">
                            Rp {{ number_format($totalExpense, 0, ',', '.') }}
                        </p>
                    </div>
                    <p class="text-[11px] text-navy-500 mt-5 pt-3 border-t border-ice-200/60 leading-relaxed">
                        Konsumsi, logistik, operasional, dan kepanitiaan acara ormawa.
                    </p>
                </div>
            </div>

            <!-- Panel Mutasi Kas & Filter -->
            <div class="bg-white/90 rounded-[28px] p-5 sm:p-7 border border-ice-200/80 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] space-y-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-ice-200/70">
                    <!-- Filter Tipe Tab (Pill Switcher) -->
                    <div class="flex items-center gap-1.5 p-1.5 bg-ice-100/80 rounded-2xl border border-ice-200/60 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.2)] text-xs overflow-x-auto neu-scroll">
                        <a
                            href="{{ route('pengurus.buku-kas.index', ['type' => 'all', 'month' => $monthFilter, 'search' => $search]) }}"
                            class="px-4 py-2 rounded-xl font-bold whitespace-nowrap transition-all {{ $typeFilter === 'all' ? 'bg-white text-navy-900 shadow-xs' : 'text-navy-500 hover:text-navy-900' }}"
                        >
                            Semua Mutasi
                        </a>
                        <a
                            href="{{ route('pengurus.buku-kas.index', ['type' => 'in', 'month' => $monthFilter, 'search' => $search]) }}"
                            class="px-4 py-2 rounded-xl font-bold whitespace-nowrap transition-all {{ $typeFilter === 'in' ? 'bg-white text-emerald-700 shadow-xs' : 'text-navy-500 hover:text-navy-900' }}"
                        >
                            + Pemasukan
                        </a>
                        <a
                            href="{{ route('pengurus.buku-kas.index', ['type' => 'out', 'month' => $monthFilter, 'search' => $search]) }}"
                            class="px-4 py-2 rounded-xl font-bold whitespace-nowrap transition-all {{ $typeFilter === 'out' ? 'bg-white text-danger shadow-xs' : 'text-navy-500 hover:text-navy-900' }}"
                        >
                            - Pengeluaran
                        </a>
                    </div>

                    <!-- Filter Pencarian & Bulan -->
                    <form method="GET" action="{{ route('pengurus.buku-kas.index') }}" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="type" value="{{ $typeFilter }}">
                        <input
                            type="month"
                            name="month"
                            value="{{ $monthFilter }}"
                            onchange="this.form.submit()"
                            class="h-10 px-3.5 rounded-xl bg-ice-100/70 text-navy-900 font-medium text-xs border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            title="Filter Berdasarkan Bulan"
                        >
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari transaksi..."
                            class="h-10 px-4 rounded-xl bg-ice-100/70 text-navy-900 font-medium text-xs border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900 w-36 sm:w-56"
                        >
                        <button
                            type="submit"
                            class="h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-colors shadow-xs cursor-pointer"
                        >
                            Cari
                        </button>
                        @if ($monthFilter || $search)
                            <a
                                href="{{ route('pengurus.buku-kas.index', ['type' => $typeFilter]) }}"
                                class="h-10 px-3 rounded-xl bg-ice-100 text-navy-500 hover:text-navy-900 font-semibold text-xs transition-colors flex items-center justify-center cursor-pointer border border-ice-200/70"
                                title="Reset Filter"
                            >
                                &times;
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Tabel Mutasi Kas Responsif Touch-Scroll -->
                @if ($transactions->isEmpty())
                    <div class="py-16 text-center text-sm text-navy-500">
                        <div class="w-12 h-12 rounded-2xl bg-ice-100 mx-auto mb-3 flex items-center justify-center text-navy-400 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <p class="font-bold text-navy-900">Belum Ada Transaksi</p>
                        <p class="text-xs text-navy-500 mt-1">Tidak ditemukan mutasi kas yang sesuai dengan filter pencarian ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto neu-scroll rounded-2xl border border-ice-200/80 shadow-xs">
                        <table class="w-full text-left text-xs min-w-[760px]">
                            <thead>
                                <tr class="bg-ice-100/70 border-b border-ice-200/80 text-navy-600 font-bold uppercase tracking-wider text-[11px]">
                                    <th class="py-3.5 px-4">Tanggal</th>
                                    <th class="py-3.5 px-4">Jenis & Kategori</th>
                                    <th class="py-3.5 px-4">Keterangan / Keperluan</th>
                                    <th class="py-3.5 px-4 text-right">Nominal</th>
                                    <th class="py-3.5 px-4 text-center">Nota / Bukti</th>
                                    <th class="py-3.5 px-4">Pencatat</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100/80 bg-white/60">
                                @foreach ($transactions as $tx)
                                    <tr class="hover:bg-ice-100/50 transition-colors">
                                        <!-- Tanggal -->
                                        <td class="py-3.5 px-4 font-mono font-medium text-navy-700 whitespace-nowrap">
                                            {{ $tx->transaction_date?->format('d/m/Y') ?? '-' }}
                                        </td>

                                        <!-- Kategori & Tipe -->
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            @if ($tx->type === 'in')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-[10px]">
                                                    + Masuk
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-red-50 text-danger border border-red-200 font-bold text-[10px]">
                                                    - Keluar
                                                </span>
                                            @endif
                                            <span class="ml-2 font-bold text-navy-900">
                                                {{ $tx->category->name ?? 'Kas Umum' }}
                                            </span>
                                        </td>

                                        <!-- Deskripsi -->
                                        <td class="py-3.5 px-4 text-navy-700 max-w-xs break-words font-medium">
                                            {{ $tx->description }}
                                        </td>

                                        <!-- Nominal -->
                                        <td class="py-3.5 px-4 text-right font-display font-bold tabular-nums whitespace-nowrap text-sm {{ $tx->type === 'in' ? 'text-emerald-700' : 'text-danger' }}">
                                            {{ $tx->type === 'in' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                        </td>

                                        <!-- Bukti Nota -->
                                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                            @if ($tx->proof_path)
                                                <button
                                                    type="button"
                                                    onclick="previewProof('{{ asset('storage/' . $tx->proof_path) }}', '{{ addslashes($tx->description) }}')"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white hover:bg-ice-100 text-navy-900 font-semibold text-[11px] border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.12),-2px_-2px_5px_#ffffff] transition-all cursor-pointer"
                                                >
                                                    <svg class="w-3.5 h-3.5 text-navy-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span>Lihat Nota</span>
                                                </button>
                                            @else
                                                <span class="text-navy-400 text-xs">-</span>
                                            @endif
                                        </td>

                                        <!-- Pencatat -->
                                        <td class="py-3.5 px-4 text-navy-500 whitespace-nowrap text-[11px] font-medium">
                                            {{ $tx->creator->name ?? 'Sistem Kasly' }}
                                        </td>

                                        <!-- Aksi -->
                                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                            @if ($tx->is_locked && str_starts_with($tx->description, 'Iuran '))
                                                <span class="text-[10.5px] text-navy-400 italic bg-ice-100 px-2 py-0.5 rounded-md" title="Terkunci otomatis dari sistem tagihan iuran kas">
                                                    Iuran Auto
                                                </span>
                                            @else
                                                <form action="{{ route('pengurus.buku-kas.destroy', $tx->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus transaksi ini dari Buku Kas? Saldo kas akan disesuaikan kembali.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="p-1.5 rounded-lg text-navy-400 hover:text-danger hover:bg-red-50 transition-colors cursor-pointer"
                                                        title="Hapus Transaksi"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="pt-3 border-t border-ice-200/70">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </main>

    <!-- ======================================================== -->
    <!-- MODAL 1: CATAT TRANSAKSI KAS BARU (PEMASUKAN/PENGELUARAN)-->
    <!-- ======================================================== -->
    <div id="modal-create-transaction" style="display: none;" onclick="if(event.target === this) closeModal('modal-create-transaction')" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/50 backdrop-blur-xs">
        <div class="bg-white/95 rounded-[28px] max-w-lg w-full p-6 sm:p-7 shadow-[10px_10px_35px_rgba(122,163,214,0.35),-10px_-10px_35px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5 animate-scale-up max-h-[92vh] overflow-y-auto custom-scrollbar">
            <div class="flex items-center justify-between pb-3.5 border-b border-ice-200/70">
                <div>
                    <h3 class="font-display font-bold text-lg text-navy-900">Catat Transaksi Kas</h3>
                    <p class="text-xs text-navy-500 mt-0.5">Pencatatan pengeluaran atau pemasukan dana kas ormawa</p>
                </div>
                <button type="button" onclick="closeModal('modal-create-transaction')" class="w-8 h-8 rounded-full bg-ice-100 text-navy-500 hover:text-navy-900 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>

            <form action="{{ route('pengurus.buku-kas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Pilihan Tipe: Pengeluaran vs Pemasukan -->
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1.5">Jenis Transaksi Kas</label>
                    <div class="grid grid-cols-2 p-1.5 bg-ice-100/80 rounded-2xl border border-ice-200/60 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.2)] gap-1.5">
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="out" checked onchange="switchTxType('out')" class="sr-only peer">
                            <div class="py-2.5 rounded-xl text-xs font-bold text-center transition-all peer-checked:bg-white peer-checked:text-danger peer-checked:shadow-xs text-navy-500">
                                - Pengeluaran Kas (Out)
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="type" value="in" onchange="switchTxType('in')" class="sr-only peer">
                            <div class="py-2.5 rounded-xl text-xs font-bold text-center transition-all peer-checked:bg-white peer-checked:text-emerald-700 peer-checked:shadow-xs text-navy-500">
                                + Pemasukan Kas (In)
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Kategori Transaksi -->
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1.5">Kategori Transaksi</label>
                    <div class="space-y-2">
                        <select
                            id="select-category"
                            onchange="handleCategorySelect(this)"
                            class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all cursor-pointer"
                        >
                            <optgroup id="optgroup-out" label="Kategori Pengeluaran">
                                @foreach ($defaultOutCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup id="optgroup-in" label="Kategori Pemasukan" class="hidden">
                                @foreach ($defaultInCategories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </optgroup>
                            <option value="__custom__">+ Tulis Kategori Baru Sendiri...</option>
                        </select>

                        <!-- Input teks kategori jika pilih custom -->
                        <input
                            type="text"
                            name="category_name"
                            id="input-category-name"
                            required
                            value="{{ $defaultOutCategories[0] }}"
                            placeholder="Ketik nama kategori..."
                            class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 hidden transition-all"
                        >
                    </div>
                </div>

                <!-- Nominal & Tanggal -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1.5">Nominal (Rp)</label>
                        <input
                            type="number"
                            name="amount"
                            required
                            min="1000"
                            step="1000"
                            placeholder="Contoh: 50000"
                            class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-sm text-navy-900 font-bold focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-navy-700 mb-1.5">Tanggal Transaksi</label>
                        <input
                            type="date"
                            name="transaction_date"
                            required
                            value="{{ now()->toDateString() }}"
                            class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all cursor-pointer"
                        >
                    </div>
                </div>

                <!-- Keterangan / Deskripsi -->
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1.5">Keterangan / Keperluan Dana</label>
                    <textarea
                        name="description"
                        required
                        rows="2"
                        placeholder="Contoh: Pembelian snack rapat evaluasi bulanan ormawa..."
                        class="w-full p-3.5 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium focus:outline-none focus:ring-1.5 focus:ring-navy-900 resize-none leading-relaxed transition-all"
                    ></textarea>
                </div>

                <!-- Upload Nota / Struk Bukti Fisik -->
                <div>
                    <label class="block text-xs font-semibold text-navy-700 mb-1.5">Foto Bukti / Nota Pembelian (Opsional)</label>
                    <input
                        type="file"
                        name="proof"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        class="w-full text-xs text-navy-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-white file:text-navy-900 file:shadow-xs hover:file:bg-ice-200 cursor-pointer"
                    >
                    <p class="text-[11px] text-navy-500 mt-1.5 leading-relaxed">Format foto struk/kwitansi: JPG, PNG, atau WebP (Maks 3 MB). Nota ini dapat dilihat secara transparan oleh seluruh anggota ormawa.</p>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-ice-200/70">
                    <button
                        type="button"
                        onclick="closeModal('modal-create-transaction')"
                        class="h-11 px-5 rounded-xl bg-ice-100 text-navy-700 font-semibold text-xs hover:bg-ice-200 transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-11 px-6 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-all shadow-[3px_4px_12px_rgba(47,72,98,0.25)] active:scale-[0.99] cursor-pointer"
                    >
                        Simpan ke Buku Kas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 2: PREVIEW FOTO NOTA BUKTI TRANSAKSI FISIK         -->
    <!-- ======================================================== -->
    <div id="modal-preview-proof" style="display: none;" onclick="if(event.target === this) closeModal('modal-preview-proof')" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/60 backdrop-blur-xs">
        <div class="bg-white/95 rounded-[28px] max-w-md w-full p-6 shadow-2xl border border-ice-200/80 space-y-4 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <h4 class="font-display font-bold text-base text-navy-900">Bukti Nota Transaksi Fisik</h4>
                <button type="button" onclick="closeModal('modal-preview-proof')" class="w-8 h-8 rounded-full bg-ice-100 text-navy-500 hover:text-navy-900 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">&times;</button>
            </div>
            <p id="proof-caption" class="text-xs text-navy-700 italic truncate"></p>
            <div class="rounded-2xl overflow-hidden bg-ice-100 border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.2)] flex items-center justify-center min-h-[220px] p-2">
                <img id="proof-img" src="" alt="Bukti Nota" class="max-h-[60vh] w-auto object-contain rounded-xl">
            </div>
            <div class="flex justify-end pt-1">
                <a id="proof-link" href="#" target="_blank" class="text-xs font-semibold text-navy-900 hover:underline inline-flex items-center gap-1.5">
                    <span>Buka Ukuran Asli</span>
                    <span>&rarr;</span>
                </a>
            </div>
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

        window.switchTxType = function(type) {
            const optOut = document.getElementById('optgroup-out');
            const optIn = document.getElementById('optgroup-in');
            const selectCat = document.getElementById('select-category');
            const inputName = document.getElementById('input-category-name');

            if (type === 'out') {
                optOut.classList.remove('hidden');
                optIn.classList.add('hidden');
                selectCat.value = optOut.children[0].value;
                inputName.value = selectCat.value;
            } else {
                optIn.classList.remove('hidden');
                optOut.classList.add('hidden');
                selectCat.value = optIn.children[0].value;
                inputName.value = selectCat.value;
            }
            inputName.classList.add('hidden');
        };

        window.handleCategorySelect = function(sel) {
            const inputName = document.getElementById('input-category-name');
            if (sel.value === '__custom__') {
                inputName.value = '';
                inputName.classList.remove('hidden');
                inputName.focus();
            } else {
                inputName.value = sel.value;
                inputName.classList.add('hidden');
            }
        };

        window.previewProof = function(url, caption) {
            document.getElementById('proof-img').src = url;
            document.getElementById('proof-link').href = url;
            document.getElementById('proof-caption').innerText = caption;
            openModal('modal-preview-proof');
        };
    </script>

</body>
</html>
