<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Metode Pembayaran Kas - {{ $organization->name }} - Kasly</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Pengurus Universal -->
    <x-sidebar-pengurus active="pembayaran" :organization="$organization" />

    <!-- Area Konten Utama -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Banner Notifikasi Flash Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-2xl bg-white/95 border border-ice-200/80 shadow-[4px_4px_16px_rgba(122,163,214,0.18),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-center justify-between gap-3 text-sm text-navy-900 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-butter-100 text-navy-900 flex items-center justify-center font-bold text-sm shrink-0 shadow-xs">✓</div>
                        <span class="font-medium text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-navy-400 hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-2xl bg-white/95 border border-danger/30 shadow-[4px_4px_16px_rgba(192,82,74,0.15),-4px_-4px_16px_rgba(255,255,255,0.95)] flex items-start gap-3 text-sm text-navy-900 animate-fade-in">
                    <div class="w-8 h-8 rounded-xl bg-danger/10 text-danger flex items-center justify-center font-bold text-sm shrink-0 shadow-xs mt-0.5">!</div>
                    <div class="flex-1">
                        <ul class="list-disc pl-4 space-y-0.5 text-xs text-danger font-medium">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-danger hover:text-navy-900 text-xs font-bold px-2 py-1 cursor-pointer">&times;</button>
                </div>
            @endif

            <!-- Header Halaman -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">Metode Pembayaran Kas</h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">Konfigurasi saluran penerimaan kas ormawa: Kas Tunai (Cash) dan Kas Non-Tunai Otomatis (Cashless).</p>
                </div>
            </div>

            <!-- GRID 2 KATEGORI UTAMA: CASH & CASHLESS -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- ========================================== -->
                <!-- KATEGORI 1: METODE CASH (TUNAI) - 5 COLS   -->
                <!-- ========================================== -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5">
                        <div class="flex items-center justify-between pb-3.5 border-b border-ice-200/70">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-ice-100 flex items-center justify-center text-navy-900 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                                    <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-display font-bold text-base text-navy-900">Metode 1: Cash (Tunai)</h2>
                                    <p class="text-[11px] text-navy-500">Penerimaan fisik langsung ke Bendahara</p>
                                </div>
                            </div>
                            @if ($cashMethod->is_active)
                                <span class="neu-badge-pill text-[10px] py-0.5 px-3">Aktif</span>
                            @else
                                <span class="neu-badge-ice text-[10px] py-0.5 px-3 text-navy-400">Non-Aktif</span>
                            @endif
                        </div>

                        <form action="{{ route('pengurus.pembayaran.update-cash') }}" method="POST" class="space-y-4 text-xs">
                            @csrf

                            <!-- Saklar Aktifkan Penerimaan Tunai -->
                            <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/70 flex items-center justify-between gap-3 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15)]">
                                <div>
                                    <p class="font-bold text-navy-900 text-xs">Izinkan Pembayaran Tunai</p>
                                    <p class="text-[11px] text-navy-500 mt-0.5">Anggota dapat melunasi secara fisik ke bendahara</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                    <input type="checkbox" name="is_active" value="1" {{ $cashMethod->is_active ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-ice-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-ice-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-navy-900"></div>
                                </label>
                            </div>

                            <!-- PIC Bendahara Penerima -->
                            <div>
                                <label class="block font-semibold text-navy-900 mb-1.5 text-xs">Nama Bendahara / PIC Penerima:</label>
                                <input
                                    type="text"
                                    name="account_holder"
                                    value="{{ old('account_holder', $cashMethod->account_holder) }}"
                                    required
                                    placeholder="Contoh: Siti Nurhaliza (Bendahara Umum)"
                                    class="w-full h-11 px-4 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.22),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium placeholder:text-navy-400 focus:outline-none focus:ring-1.5 focus:ring-navy-900 transition-all"
                                >
                            </div>

                            <!-- Petunjuk / Alur Pembayaran Tunai -->
                            <div>
                                <label class="block font-semibold text-navy-900 mb-1.5 text-xs">Petunjuk Serah Terima Tunai:</label>
                                <textarea
                                    name="instructions"
                                    rows="3"
                                    placeholder="Contoh: Serahkan uang tunai langsung ke Bendahara saat rapat mingguan di Sekretariat atau hubungi WhatsApp Bendahara untuk janjian."
                                    class="w-full p-3.5 rounded-xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.22),inset_-2px_-2px_5px_rgba(255,255,255,0.95)] text-xs text-navy-900 font-medium placeholder:text-navy-400 focus:outline-none focus:ring-1.5 focus:ring-navy-900 resize-none leading-relaxed transition-all"
                                >{{ old('instructions', $cashMethod->instructions) }}</textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full h-11 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all cursor-pointer flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Simpan Pengaturan Tunai</span>
                            </button>
                        </form>
                    </div>

                    <!-- Kartu Info Verifikasi Kas Tunai -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-white/70 border border-ice-200/80 shadow-[3px_3px_10px_rgba(122,163,214,0.1),-3px_-3px_10px_#ffffff] text-xs text-navy-600 space-y-2">
                        <p class="font-bold text-navy-900 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-navy-900"></span>
                            <span>Alur Verifikasi Pembayaran Tunai:</span>
                        </p>
                        <p class="leading-relaxed text-[11px] text-navy-500">
                            Ketika anggota membayar tunai, Bendahara membuka halaman <strong class="text-navy-900">Jadwal & Tagihan</strong> lalu klik tombol <strong class="text-navy-900">Verifikasi</strong> pada nama anggota bersangkutan. Status otomatis berubah menjadi Lunas dan dana langsung tercatat ke Buku Kas ormawa.
                        </p>
                    </div>
                </div>

                <!-- ============================================== -->
                <!-- KATEGORI 2: METODE CASHLESS (NON-TUNAI) - 7 COLS -->
                <!-- ============================================== -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- SUB 2A: PAYMENT GATEWAY OTOMATIS (MIDTRANS SANDBOX) -->
                    <div class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-4">
                        <div class="flex items-center justify-between pb-3.5 border-b border-ice-200/70">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-ice-100 flex items-center justify-center text-navy-900 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.3),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                                    <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-display font-bold text-base text-navy-900">Cashless: Midtrans Gateway (Otomatis)</h2>
                                    <p class="text-[11px] text-navy-500">QRIS Dinamis, Virtual Account, & E-Wallet Instan</p>
                                </div>
                            </div>
                            @if ($organization->is_midtrans_enabled)
                                <span class="neu-badge-pill text-[10px] py-0.5 px-3">Gateway Aktif</span>
                            @else
                                <span class="neu-badge-ice text-[10px] py-0.5 px-3 text-navy-400">Non-Aktif</span>
                            @endif
                        </div>

                        <div class="p-4 sm:p-5 rounded-2xl bg-ice-100/60 border border-ice-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15)] text-xs">
                            <div class="space-y-1.5">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="font-bold text-navy-900 text-sm">Midtrans Sandbox Environment</span>
                                    @if ($midtransConfigured)
                                        <span class="neu-badge-pill text-[10px] py-0.5 px-2.5">Server Key Terpasang</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full bg-danger/10 text-danger border border-danger/20 font-bold text-[10px]">Kunci Belum Diisi</span>
                                    @endif
                                </div>
                                <p class="text-navy-500 leading-relaxed text-[11px]">
                                    Anggota membayar via popup Snap resmi (GoPay, QRIS, BCA, BRI, Mandiri VA, dll). Pelunasan terverifikasi otomatis seketika.
                                </p>
                            </div>

                            <form action="{{ route('pengurus.pembayaran.toggle-midtrans') }}" method="POST" class="shrink-0">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full sm:w-auto h-10 px-5 rounded-xl font-bold text-xs shadow-[3px_3px_10px_rgba(47,72,98,0.18)] transition-all cursor-pointer {{ $organization->is_midtrans_enabled ? 'bg-ice-200 text-navy-900 hover:bg-ice-300' : 'bg-navy-900 text-white hover:bg-navy-700' }}"
                                >
                                    {{ $organization->is_midtrans_enabled ? 'Nonaktifkan Gateway' : 'Aktifkan Gateway' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- SALURAN PEMBAYARAN MIDTRANS OTOMATIS YANG TERHUBUNG -->
                    <div class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3.5 border-b border-ice-200/70">
                            <div>
                                <h2 class="font-display font-bold text-base text-navy-900">Saluran Pembayaran Gateway Otomatis</h2>
                                <p class="text-xs text-navy-500 mt-0.5">12 metode E-Wallet, QRIS, & Virtual Account siap pakai untuk anggota</p>
                            </div>
                            <span class="neu-badge-ice text-[10px] py-1 px-3 font-mono shrink-0 self-start sm:self-auto">12 SALURAN AKTIF</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <!-- 1. GOPAY -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/gopaylogo.svg') }}" alt="GoPay" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">GoPay E-Wallet</p>
                                        <p class="text-[10px] text-navy-500 truncate">Deeplink App & Simulator</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">E-WALLET</span>
                            </div>

                            <!-- 2. QRIS -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/qrislogo-removebg-preview.png') }}" alt="QRIS" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">QRIS Dinamis</p>
                                        <p class="text-[10px] text-navy-500 truncate">GoPay, ShopeePay, DANA, dll</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">SCAN QR</span>
                            </div>

                            <!-- 3. BCA -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/bcalogo-removebg-preview.png') }}" alt="BCA" class="h-5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">BCA Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">BCA Mobile, myBCA, ATM</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">BCA VA</span>
                            </div>

                            <!-- 4. MANDIRI -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/mandirilogo-removebg-preview.png') }}" alt="Mandiri" class="h-5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">Mandiri Bill Payment</p>
                                        <p class="text-[10px] text-navy-500 truncate">Livin' by Mandiri, ATM</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">MANDIRI</span>
                            </div>

                            <!-- 5. BRI -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/brivalogo-removebg-preview.png') }}" alt="BRI" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">BRI VA (BRIVA)</p>
                                        <p class="text-[10px] text-navy-500 truncate">BRImo, ATM BRI</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">BRIVA</span>
                            </div>

                            <!-- 6. BNI -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/bnilogo-removebg-preview.png') }}" alt="BNI" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">BNI Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">BNI Mobile Banking, ATM</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">BNI VA</span>
                            </div>

                            <!-- 7. PERMATA -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/permatalogo.svg') }}" alt="Permata Bank" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">Permata Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">PermataMobile X, ATM</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">PERMATA VA</span>
                            </div>

                            <!-- 8. CIMB -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/niagalogo-removebg-preview.png') }}" alt="CIMB Niaga" class="h-5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">CIMB Niaga VA</p>
                                        <p class="text-[10px] text-navy-500 truncate">OCTO Mobile, OCTO Clicks</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">CIMB VA</span>
                            </div>

                            <!-- 9. DANAMON -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/danamonlogo-removebg-preview.png') }}" alt="Danamon" class="h-4.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">Bank Danamon VA</p>
                                        <p class="text-[10px] text-navy-500 truncate">D-Bank PRO, ATM</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">DANAMON VA</span>
                            </div>

                            <!-- 10. BSI -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/bsilogo-removebg-preview.png') }}" alt="BSI" class="h-5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">BSI Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">BSI Mobile, ATM BSI</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">BSI VA</span>
                            </div>

                            <!-- 11. SEABANK -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/seabank_logo-removebg-preview.png') }}" alt="SeaBank" class="h-5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">SeaBank Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">Aplikasi SeaBank</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">SEABANK</span>
                            </div>

                            <!-- 12. SAQU -->
                            <div class="p-3.5 rounded-2xl bg-white border border-ice-200/80 shadow-[2px_2px_6px_rgba(122,163,214,0.12),-2px_-2px_6px_#ffffff] hover:shadow-[3px_3px_12px_rgba(122,163,214,0.2),-3px_-3px_12px_#ffffff] hover:-translate-y-0.5 transition-all flex items-center justify-between gap-3 sm:col-span-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-14 h-9 rounded-xl bg-white border border-ice-200/80 shadow-[2px_2px_5px_rgba(122,163,214,0.15),-2px_-2px_5px_#ffffff] flex items-center justify-center p-1.5 shrink-0 overflow-hidden">
                                        <img src="{{ asset('logobank/banksaqulogo-removebg-preview.png') }}" alt="Bank Saqu" class="h-5.5 w-auto max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-navy-900 truncate text-xs">Bank Saqu Virtual Account</p>
                                        <p class="text-[10px] text-navy-500 truncate">Aplikasi Bank Saqu Digital</p>
                                    </div>
                                </div>
                                <span class="neu-badge-ice text-[9px] py-0.5 px-2 font-mono shrink-0">SAQU VA</span>
                            </div>
                        </div>

                        <p class="text-[11px] text-navy-500 leading-relaxed pt-1">
                            Seluruh saluran di atas otomatis aktif begitu gateway dinyalakan. Anggota ormawa dapat memilih saluran transfer favorit mereka saat membayar tagihan kas.
                        </p>
                    </div>

                </div>

            </div>

        </div>
    </main>

</body>
</html>
