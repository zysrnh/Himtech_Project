<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Metode Pembayaran Kas - {{ $organization->name }} - Kasly</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Pengurus Universal -->
    <x-sidebar-pengurus active="pembayaran" />

    <!-- Area Konten Utama -->
    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-y-auto custom-scrollbar">

        <!-- Topbar Sederhana & Notifikasi -->
        <header class="h-16 px-6 lg:px-8 border-b border-ice-200/70 flex items-center justify-between shrink-0 bg-white/40 backdrop-blur-xs sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="toggleAppSidebar()"
                    class="h-8 px-2.5 rounded-lg bg-white border border-ice-200/80 text-navy-900 font-semibold text-xs shadow-xs hover:bg-ice-100 cursor-pointer transition-all flex items-center gap-1.5 shrink-0"
                    title="Buka / Tutup Sidebar"
                >
                    <svg class="w-4 h-4 text-navy-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <span>Menu</span>
                </button>
                <a href="{{ route('pengurus.tagihan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Kembali ke Jadwal & Tagihan</span>
                </a>
            </div>

            <div class="flex items-center gap-2">
                <span class="neu-badge-ice text-xs">{{ $organization->code }}</span>
                <span class="text-xs font-medium text-navy-500 hidden sm:inline">Kas & Keuangan</span>
            </div>
        </header>

        <main class="p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">

            <!-- Banner Notifikasi Flash -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-white border border-ice-200/80 shadow-[4px_4px_12px_rgba(122,163,214,0.2),-4px_-4px_12px_rgba(255,255,255,0.9)] flex items-start gap-3 text-sm text-navy-900 animate-fade-in">
                    <div class="w-6 h-6 rounded-full bg-butter-100 text-navy-900 flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">✓</div>
                    <div class="flex-1 font-medium">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-white border border-danger/30 shadow-[4px_4px_12px_rgba(192,82,74,0.15),-4px_-4px_12px_rgba(255,255,255,0.9)] flex items-start gap-3 text-sm text-navy-900 animate-fade-in">
                    <div class="w-6 h-6 rounded-full bg-danger/10 text-danger flex items-center justify-center font-bold text-xs shrink-0 mt-0.5">!</div>
                    <div class="flex-1">
                        <ul class="list-disc pl-4 space-y-0.5 text-xs text-danger font-medium">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">Pengaturan Metode Pembayaran</h1>
                    <p class="text-sm text-navy-500 mt-1">Konfigurasi saluran penerimaan kas ormawa: Kas Tunai (Cash) dan Kas Non-Tunai (Cashless).</p>
                </div>
            </div>

            <!-- GRID 2 KATEGORI UTAMA: CASH & CASHLESS -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- ========================================== -->
                <!-- KATEGORI 1: METODE CASH (TUNAI) - 4 COLS -->
                <!-- ========================================== -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white/85 rounded-2xl p-6 shadow-[6px_6px_16px_rgba(122,163,214,0.25),-6px_-6px_16px_rgba(255,255,255,0.95)] border border-ice-200/70 space-y-5">
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-ice-100 flex items-center justify-center text-navy-900 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                                    <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-display font-bold text-base text-navy-900">Metode 1: Cash (Tunai)</h2>
                                    <p class="text-xs text-navy-500">Penerimaan fisik langsung ke Bendahara</p>
                                </div>
                            </div>
                            <span class="neu-badge-pill text-[10px] py-0.5 px-2.5 {{ $cashMethod->is_active ? 'bg-butter-100' : 'bg-ice-200/60 text-navy-500' }}">
                                {{ $cashMethod->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>

                        <form action="{{ route('pengurus.pembayaran.update-cash') }}" method="POST" class="space-y-4 text-xs">
                            @csrf

                            <!-- Saklar Aktifkan Penerimaan Tunai -->
                            <div class="p-3.5 rounded-xl bg-ice-100/60 border border-ice-200/60 flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-navy-900">Izinkan Pembayaran Tunai</p>
                                    <p class="text-[11px] text-navy-500">Anggota dapat melunasi secara fisik ke bendahara</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ $cashMethod->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-ice-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-ice-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-navy-900"></div>
                                </label>
                            </div>

                            <!-- PIC Bendahara Penerima -->
                            <div>
                                <label class="block font-semibold text-navy-900 mb-1">Nama Bendahara / PIC Penerima:</label>
                                <input
                                    type="text"
                                    name="account_holder"
                                    value="{{ old('account_holder', $cashMethod->account_holder) }}"
                                    required
                                    placeholder="Contoh: Siti Nurhaliza (Bendahara Umum)"
                                    class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900"
                                >
                            </div>

                            <!-- Petunjuk / Alur Pembayaran Tunai -->
                            <div>
                                <label class="block font-semibold text-navy-900 mb-1">Petunjuk Serah Terima Tunai:</label>
                                <textarea
                                    name="instructions"
                                    rows="3"
                                    placeholder="Contoh: Serahkan uang tunai langsung ke Bendahara saat rapat mingguan di Sekretariat atau hubungi WhatsApp Bendahara untuk janjian."
                                    class="w-full p-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900 resize-none leading-relaxed"
                                >{{ old('instructions', $cashMethod->instructions) }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full h-10 rounded-xl bg-gradient-to-r from-navy-500 to-navy-900 text-white font-semibold text-xs shadow-[4px_6px_14px_rgba(47,72,98,0.25)] hover:opacity-95 transition-opacity cursor-pointer flex items-center justify-center gap-1.5"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Pengaturan Tunai</span>
                            </button>
                        </form>
                    </div>

                    <!-- Kartu Info Verifikasi Kas Tunai -->
                    <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/70 text-xs text-navy-600 space-y-2">
                        <p class="font-bold text-navy-900 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-navy-900"></span>
                            Alur Verifikasi Pembayaran Tunai:
                        </p>
                        <p class="leading-relaxed">
                            Ketika anggota membayar tunai, Bendahara membuka halaman <strong>Jadwal & Tagihan</strong> lalu klik tombol <strong>Verifikasi</strong> pada nama anggota bersangkutan. Status otomatis berubah menjadi Lunas dan dana langsung tercatat ke Buku Kas ormawa.
                        </p>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- KATEGORI 2: METODE CASHLESS (NON-TUNAI) - 7 COLS -->
                <!-- ============================================== -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- SUB 2A: PAYMENT GATEWAY OTOMATIS (MIDTRANS SANDBOX) -->
                    <div class="bg-white/85 rounded-2xl p-6 shadow-[6px_6px_16px_rgba(122,163,214,0.25),-6px_-6px_16px_rgba(255,255,255,0.95)] border border-ice-200/70 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200/60">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-ice-100 flex items-center justify-center text-navy-900 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                                    <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-display font-bold text-base text-navy-900">Cashless A: Midtrans Gateway (Otomatis)</h2>
                                    <p class="text-xs text-navy-500">QRIS Dinamis, Virtual Account, & E-Wallet Instan</p>
                                </div>
                            </div>
                            <span class="neu-badge-pill text-[10px] py-0.5 px-2.5 {{ $organization->is_midtrans_enabled ? 'bg-butter-100' : 'bg-ice-200/60 text-navy-500' }}">
                                {{ $organization->is_midtrans_enabled ? 'Gateway Aktif' : 'Non-Aktif' }}
                            </span>
                        </div>

                        <div class="p-4 rounded-xl bg-ice-100/50 border border-ice-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-navy-900">Midtrans Sandbox Environment</span>
                                    @if ($midtransConfigured)
                                        <span class="text-[10px] font-bold text-navy-900 bg-butter-100 px-2 py-0.5 rounded-full">Server Key Terpasang</span>
                                    @else
                                        <span class="text-[10px] font-bold text-danger bg-danger/10 px-2 py-0.5 rounded-full">Kunci Belum Diisi</span>
                                    @endif
                                </div>
                                <p class="text-navy-500 leading-relaxed text-[11px]">
                                    Anggota membayar via popup Snap resmi (GoPay, ShopeePay, QRIS, BCA/BRI/Mandiri VA). Pelunasan terverifikasi otomatis seketika.
                                </p>
                            </div>

                            <form action="{{ route('pengurus.pembayaran.toggle-midtrans') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="h-9 px-4 rounded-xl font-bold text-xs shadow-xs transition-colors cursor-pointer shrink-0 {{ $organization->is_midtrans_enabled ? 'bg-ice-200/80 text-navy-900 hover:bg-ice-300' : 'bg-navy-900 text-white hover:bg-navy-700' }}"
                                >
                                    {{ $organization->is_midtrans_enabled ? 'Nonaktifkan Gateway' : 'Aktifkan Gateway' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- SALURAN PEMBAYARAN MIDTRANS OTOMATIS YANG TERHUBUNG -->
                    <div class="bg-white/85 rounded-2xl p-6 shadow-[6px_6px_16px_rgba(122,163,214,0.25),-6px_-6px_16px_rgba(255,255,255,0.95)] border border-ice-200/70 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200/60">
                            <div>
                                <h2 class="font-display font-bold text-base text-navy-900">Saluran Pembayaran Gateway Otomatis</h2>
                                <p class="text-xs text-navy-500">12 metode E-Wallet, QRIS, & Virtual Account siap pakai untuk anggota</p>
                            </div>
                            <span class="neu-badge-ice text-[10px] py-0.5 px-2 font-mono">12 SALURAN AKTIF</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                            <!-- GOPAY -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#00AED6] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    GOPAY
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">GoPay E-Wallet</p>
                                    <p class="text-[10px] text-navy-500 truncate">Deeplink App & Simulator</p>
                                </div>
                            </div>

                            <!-- QRIS -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#EE3124] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    QRIS
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">QRIS Dinamis</p>
                                    <p class="text-[10px] text-navy-500 truncate">GoPay, ShopeePay, DANA, dll</p>
                                </div>
                            </div>

                            <!-- BCA -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#003399] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    BCA
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">BCA Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">BCA Mobile, myBCA, ATM</p>
                                </div>
                            </div>

                            <!-- MANDIRI -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#003D79] text-[#F9A825] flex items-center justify-center font-black text-[9px] tracking-tighter shrink-0 shadow-xs">
                                    MANDIRI
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">Mandiri Bill Payment</p>
                                    <p class="text-[10px] text-navy-500 truncate">Livin' by Mandiri, ATM</p>
                                </div>
                            </div>

                            <!-- BRI -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#00529C] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    BRI
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">BRI VA (BRIVA)</p>
                                    <p class="text-[10px] text-navy-500 truncate">BRImo, ATM BRI</p>
                                </div>
                            </div>

                            <!-- BNI -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#005E6A] text-[#F37023] flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    BNI
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">BNI Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">BNI Mobile Banking, ATM</p>
                                </div>
                            </div>

                            <!-- PERMATA -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#008850] text-white flex items-center justify-center font-black text-[8px] tracking-tighter shrink-0 shadow-xs">
                                    PERMATA
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">Permata Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">PermataMobile X, ATM</p>
                                </div>
                            </div>

                            <!-- CIMB -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#ED1B24] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    CIMB
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">CIMB Niaga VA</p>
                                    <p class="text-[10px] text-navy-500 truncate">OCTO Mobile, OCTO Clicks</p>
                                </div>
                            </div>

                            <!-- DANAMON -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#FF6600] text-white flex items-center justify-center font-black text-[8px] tracking-tighter shrink-0 shadow-xs">
                                    DANAMON
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">Bank Danamon VA</p>
                                    <p class="text-[10px] text-navy-500 truncate">D-Bank PRO, ATM</p>
                                </div>
                            </div>

                            <!-- BSI -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#00A39D] text-white flex items-center justify-center font-black text-[10px] tracking-wider shrink-0 shadow-xs">
                                    BSI
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">BSI Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">BSI Mobile, ATM BSI</p>
                                </div>
                            </div>

                            <!-- SEABANK -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3">
                                <div class="w-14 h-7 rounded-md bg-[#FF5722] text-white flex items-center justify-center font-black text-[8px] tracking-tighter shrink-0 shadow-xs">
                                    SEABANK
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">SeaBank Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">Aplikasi SeaBank</p>
                                </div>
                            </div>

                            <!-- SAQU -->
                            <div class="p-3 rounded-xl bg-ice-100/60 border border-ice-200/70 flex items-center gap-3 sm:col-span-2">
                                <div class="w-14 h-7 rounded-md bg-[#6C5CE7] text-white flex items-center justify-center font-black text-[9px] tracking-wider shrink-0 shadow-xs">
                                    SAQU
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-navy-900 truncate">Bank Saqu Virtual Account</p>
                                    <p class="text-[10px] text-navy-500 truncate">Aplikasi Bank Saqu Digital</p>
                                </div>
                            </div>
                        </div>

                        <p class="text-[11px] text-navy-500 leading-relaxed pt-1">
                            Seluruh saluran di atas otomatis aktif begitu gateway dinyalakan. Anggota ormawa dapat memilih saluran transfer favorit mereka saat membayar tagihan kas.
                        </p>
                    </div>

                    @if (false)
                    <!-- SUB 2B: TRANSFER BANK & E-WALLET MANUAL ORMAWA (DISEMBUNYIKAN SEMENTARA) -->
                    <div class="bg-white/85 rounded-2xl p-6 shadow-[6px_6px_16px_rgba(122,163,214,0.25),-6px_-6px_16px_rgba(255,255,255,0.95)] border border-ice-200/70 space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-ice-200/60">
                            <div>
                                <h2 class="font-display font-bold text-base text-navy-900">Cashless B: Rekening Bank & E-Wallet Ormawa</h2>
                                <p class="text-xs text-navy-500">Nomor rekening tujuan transfer mandiri anggota</p>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

            </div>

        </main>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL NEUMORPHIC: TAMBAH REKENING BANK / E-WALLET        -->
    <!-- ======================================================== -->
    <div id="modal-add-bank" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-navy-900/40 backdrop-blur-xs hidden">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-ice-200/80 space-y-4 animate-scale-up">
            <div class="flex items-center justify-between pb-3 border-b border-ice-200/70">
                <div>
                    <h3 class="font-display font-semibold text-base text-navy-900">Tambah Saluran Transfer Cashless</h3>
                    <p class="text-xs text-navy-500">Nomor rekening bank atau e-wallet ormawa</p>
                </div>
                <button type="button" onclick="closeAddBankModal()" class="text-navy-500 hover:text-navy-900 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('pengurus.pembayaran.store-cashless') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf

                <div>
                    <label class="block font-semibold text-navy-900 mb-1">Kategori Saluran:</label>
                    <select name="type" required class="w-full h-10 px-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900">
                        <option value="bank">Rekening Bank (BCA, Mandiri, BRI, BNI, dll)</option>
                        <option value="ewallet">E-Wallet (DANA, GoPay, OVO, ShopeePay)</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-navy-900 mb-1">Nama Bank / E-Wallet:</label>
                    <input
                        type="text"
                        name="channel_name"
                        required
                        placeholder="Contoh: Bank BCA atau DANA"
                        class="w-full h-10 px-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-navy-900 mb-1">Nomor Rekening / Nomor HP:</label>
                    <input
                        type="text"
                        name="account_number"
                        required
                        placeholder="Contoh: 8830123456 atau 081234567890"
                        class="w-full h-10 px-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-navy-900 mb-1">Atas Nama (a.n Pemilik Rekening):</label>
                    <input
                        type="text"
                        name="account_holder"
                        required
                        placeholder="Contoh: Siti Nurhaliza atau Kas HIMTECH"
                        class="w-full h-10 px-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-navy-900 mb-1">Catatan Tambahan (Opsional):</label>
                    <input
                        type="text"
                        name="instructions"
                        placeholder="Contoh: Beri berita transfer NIM saat transfer"
                        class="w-full h-10 px-3 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.25),inset_-2px_-2px_5px_rgba(255,255,255,0.9)] text-xs text-navy-900 focus:outline-none focus:ring-1 focus:ring-navy-900"
                    >
                </div>

                <div class="pt-2 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAddBankModal()" class="h-9 px-4 rounded-xl text-navy-500 hover:text-navy-900 font-semibold cursor-pointer">Batal</button>
                    <button type="submit" class="h-9 px-5 rounded-xl bg-navy-900 text-white font-semibold shadow-xs hover:bg-navy-700 transition-colors cursor-pointer">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddBankModal() {
            document.getElementById('modal-add-bank').classList.remove('hidden');
        }
        function closeAddBankModal() {
            document.getElementById('modal-add-bank').classList.add('hidden');
        }
    </script>
</body>
</html>

