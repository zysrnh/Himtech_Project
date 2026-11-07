<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Induk Anggota & QR Registrasi - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex flex-col md:flex-row selection:bg-ice-200 selection:text-navy-900">

    <!-- Sidebar Pengurus Neumorphic Interaktif -->
    <x-sidebar-pengurus active="anggota" :organization="$organization" />

    <!-- Main Content Area: Data Anggota & QR Code Registrasi -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 pb-28 md:pb-8">
        <div class="max-w-[1140px] mx-auto space-y-6">

            <!-- Top Header & Aksi Cepat -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-bold text-2xl lg:text-3xl text-navy-900 tracking-tight">
                        Buku Induk Anggota & QR Registrasi
                    </h1>
                    <p class="text-xs sm:text-sm text-navy-500 mt-1">
                        Kelola data keanggotaan ormawa {{ $organization->name }} dan bagikan QR Code registrasi berbatas waktu & kuota ke WhatsApp grup.
                    </p>
                </div>
                <div class="flex items-center gap-2.5 shrink-0">
                    <button
                        type="button"
                        onclick="document.getElementById('manual-add-member-section').scrollIntoView({ behavior: 'smooth' })"
                        class="h-11 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs shadow-[3px_4px_12px_rgba(47,72,98,0.25)] hover:bg-navy-700 active:scale-[0.99] transition-all cursor-pointer flex items-center gap-2"
                    >
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Anggota Manual</span>
                    </button>
                </div>
            </div>

            <!-- Notifikasi Sukses / Error -->
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

            <!-- KARTU 1: QR CODE REGISTRASI MANDIRI ANGGOTA BERBATAS WAKTU & KUOTA -->
            <div class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-ice-200/70 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-display font-bold text-lg text-navy-900">
                                QR Code Registrasi Mandiri Anggota
                            </h2>
                            @if ($activeToken->isValid())
                                <span class="neu-badge-pill text-[10px] py-0.5 px-2.5 font-bold">
                                    Aktif
                                </span>
                            @elseif ($activeToken->isExpired())
                                <span class="px-2.5 py-0.5 rounded-full bg-red-100 text-danger text-[10px] font-bold">
                                    Kedaluwarsa
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full bg-red-100 text-danger text-[10px] font-bold">
                                    Kuota Habis
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-navy-500 mt-1">
                            Bagikan QR Code ini ke grup WhatsApp. Mahasiswa cukup scan/upload foto QR untuk langsung terdaftar sebagai anggota.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            onclick="toggleUpdateModal()"
                            class="h-10 px-4 rounded-xl bg-navy-900 text-white text-xs font-semibold hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer"
                        >
                            Ubah Pengaturan QR Aktif
                        </button>
                        <button
                            type="button"
                            onclick="toggleRenewModal()"
                            class="h-10 px-4 rounded-xl border border-ice-200/80 bg-white/90 text-navy-900 text-xs font-semibold hover:bg-white transition-all shadow-[2px_2px_6px_rgba(122,163,214,0.15),-2px_-2px_6px_#ffffff] active:scale-[0.99] cursor-pointer"
                        >
                            Reset / Buat QR Baru
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                    <!-- Kolom Kiri: Gambar QR Code Resmi -->
                    <div class="lg:col-span-4 text-center flex flex-col items-center space-y-3">
                        <div class="p-4 rounded-2xl bg-ice-100/70 border border-ice-200/80 inline-block shadow-[inset_2px_2px_5px_rgba(122,163,214,0.2),inset_-2px_-2px_5px_rgba(255,255,255,0.9)]">
                            <img
                                id="qr-image"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($registrationUrl) }}"
                                alt="QR Code Pendaftaran {{ $organization->name }}"
                                class="w-[190px] h-[190px] rounded-xl object-contain bg-white p-2.5 border border-ice-200/60 shadow-xs"
                            >
                        </div>
                        <p class="text-[11px] text-navy-500 font-mono truncate max-w-[240px]">
                            {{ $activeToken->token }}
                        </p>
                    </div>

                    <!-- Kolom Kanan: Telemetri Kuota, Waktu & Tombol Aksi -->
                    <div class="lg:col-span-8 space-y-4">
                        <!-- Indikator Kuota & Masa Berlaku -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15),inset_-1px_-1px_3px_rgba(255,255,255,0.9)]">
                                <span class="text-xs text-navy-500 block font-medium">Kuota Terpakai</span>
                                <p class="font-display font-bold text-2xl text-navy-900 mt-1 tabular-nums">
                                    {{ $activeToken->used_count }} <span class="text-xs text-navy-500 font-normal">/ {{ $activeToken->max_uses }} orang</span>
                                </p>
                                <div class="w-full bg-ice-200/80 h-2 rounded-full mt-2.5 overflow-hidden shadow-[inset_1px_1px_2px_rgba(122,163,214,0.25)]">
                                    <div
                                        class="bg-navy-900 h-2 rounded-full transition-all"
                                        style="width: {{ $activeToken->max_uses > 0 ? min(100, ($activeToken->used_count / $activeToken->max_uses) * 100) : 0 }}%"
                                    ></div>
                                </div>
                            </div>
                            <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15),inset_-1px_-1px_3px_rgba(255,255,255,0.9)]">
                                <span class="text-xs text-navy-500 block font-medium">Sisa Kuota</span>
                                <p class="font-display font-bold text-2xl text-navy-900 mt-1 tabular-nums">
                                    {{ $activeToken->remainingUses() }} <span class="text-xs text-navy-500 font-normal">anggota</span>
                                </p>
                                <span class="text-[11px] text-navy-500 block mt-1">Kuota bertambah otomatis</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-ice-100/60 border border-ice-200/80 shadow-[inset_1px_1px_3px_rgba(122,163,214,0.15),inset_-1px_-1px_3px_rgba(255,255,255,0.9)]">
                                <span class="text-xs text-navy-500 block font-medium">Masa Berlaku</span>
                                <p class="font-display font-bold text-sm text-navy-900 mt-1">
                                    {{ $activeToken->expires_at->format('d M Y H:i') }}
                                </p>
                                <span class="text-[11px] text-navy-500 block mt-0.5">
                                    ({{ $activeToken->expires_at->diffForHumans() }})
                                </span>
                            </div>
                        </div>

                        <!-- Baris Tautan & Aksi Cepat -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-navy-700">Tautan Pendaftaran Unik (Untuk WhatsApp):</label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    id="registration-url-input"
                                    readonly
                                    value="{{ $registrationUrl }}"
                                    class="flex-1 h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs font-mono text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                                >
                                <button
                                    type="button"
                                    onclick="copyRegistrationUrl()"
                                    class="h-10 px-4 rounded-xl bg-white/90 border border-ice-200/80 text-navy-900 hover:bg-white text-xs font-semibold transition-all shadow-[2px_2px_6px_rgba(122,163,214,0.15),-2px_-2px_6px_#ffffff] active:scale-[0.99] cursor-pointer shrink-0"
                                >
                                    Salin Tautan
                                </button>
                            </div>
                        </div>

                        <!-- Tombol Aksi Download & Share WhatsApp -->
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <a
                                href="{{ route('pengurus.anggota.token.download-qr', $activeToken) }}"
                                class="h-10 px-4 rounded-xl bg-navy-900 text-white text-xs font-semibold hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer flex items-center gap-2"
                            >
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Download QR (PNG)</span>
                            </a>
                            @php
                                $waShareText = "Halo rekan-rekan anggota {$organization->name}!\n\n"
                                    ."Silakan melakukan pendaftaran anggota resmi Kasly untuk transparansi kas kita melalui tautan berikut:\n"
                                    .$registrationUrl."\n\n"
                                    ."(Scan/Upload QR Code di atas menggunakan kamera atau unggah file foto dari galeri).";
                            @endphp
                            <a
                                href="https://api.whatsapp.com/send?text={{ urlencode($waShareText) }}"
                                target="_blank"
                                class="h-10 px-4 rounded-xl border border-ice-200/80 bg-white/90 text-navy-900 text-xs font-semibold hover:bg-white transition-all shadow-[2px_2px_6px_rgba(122,163,214,0.15),-2px_-2px_6px_#ffffff] active:scale-[0.99] cursor-pointer flex items-center gap-2"
                            >
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                <span>Bagikan ke Grup WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Modal: Ubah Pengaturan QR Code Aktif -->
                <div id="update-qr-modal" class="hidden p-5 rounded-2xl bg-ice-100/70 border border-ice-200/80 mt-4 space-y-4 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.15),inset_-2px_-2px_5px_rgba(255,255,255,0.9)]">
                    <div class="flex items-center justify-between border-b border-ice-200/70 pb-2.5">
                        <div>
                            <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">
                                Ubah Pengaturan QR Code Aktif
                            </h3>
                            <p class="text-[11px] text-navy-500 mt-0.5">
                                Perbarui kuota atau perpanjang masa berlaku tanpa mengubah tautan barcode yang sudah beredar.
                            </p>
                        </div>
                        <button type="button" onclick="toggleUpdateModal()" class="text-xs text-navy-500 hover:text-navy-900 font-semibold cursor-pointer">
                            Tutup
                        </button>
                    </div>
                    <form action="{{ route('pengurus.anggota.token.update', $activeToken) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3.5 items-end">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="update_max_uses" class="block text-xs font-semibold text-navy-700 mb-1">
                                Kuota Maksimal *
                            </label>
                            <input
                                type="number"
                                id="update_max_uses"
                                name="max_uses"
                                value="{{ $activeToken->max_uses }}"
                                min="{{ max(1, $activeToken->used_count) }}"
                                max="2000"
                                required
                                class="w-full h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-xs font-mono text-navy-900 shadow-xs focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                            <span class="text-[10px] text-navy-500 mt-1 block">Minimal {{ $activeToken->used_count }} (terpakai)</span>
                        </div>
                        <div>
                            <label for="update_extend_hours" class="block text-xs font-semibold text-navy-700 mb-1">
                                Perpanjang Waktu *
                            </label>
                            <select
                                id="update_extend_hours"
                                name="extend_hours"
                                required
                                class="w-full h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-xs text-navy-900 shadow-xs focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                                <option value="0" selected>+0 Jam (Tetap)</option>
                                <option value="12">+12 Jam</option>
                                <option value="24">+24 Jam (1 Hari)</option>
                                <option value="48">+48 Jam (2 Hari)</option>
                                <option value="72">+72 Jam (3 Hari)</option>
                                <option value="168">+168 Jam (7 Hari)</option>
                                <option value="720">+720 Jam (30 Hari)</option>
                            </select>
                            <span class="text-[10px] text-navy-500 mt-1 block">Ditambah ke waktu kedaluwarsa</span>
                        </div>
                        <div>
                            <label for="update_is_active" class="block text-xs font-semibold text-navy-700 mb-1">
                                Status Penerimaan *
                            </label>
                            <select
                                id="update_is_active"
                                name="is_active"
                                class="w-full h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-xs text-navy-900 shadow-xs focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                                <option value="1" {{ $activeToken->is_active ? 'selected' : '' }}>Aktif (Bisa Daftar)</option>
                                <option value="0" {{ ! $activeToken->is_active ? 'selected' : '' }}>Nonaktifkan Sementara</option>
                            </select>
                            <span class="text-[10px] text-navy-500 mt-1 block">Pause pendaftaran</span>
                        </div>
                        <div>
                            <button
                                type="submit"
                                class="w-full h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Form Modal Tersembunyi: Refresh / Buat QR Baru -->
                <div id="renew-qr-modal" class="hidden p-5 rounded-2xl bg-ice-100/70 border border-ice-200/80 mt-4 space-y-4 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.15),inset_-2px_-2px_5px_rgba(255,255,255,0.9)]">
                    <div class="flex items-center justify-between border-b border-ice-200/70 pb-2.5">
                        <h3 class="text-xs font-bold text-navy-900 uppercase tracking-wider">
                            Terbitkan QR Code Baru
                        </h3>
                        <button type="button" onclick="toggleRenewModal()" class="text-xs text-navy-500 hover:text-navy-900 font-semibold cursor-pointer">
                            Batal
                        </button>
                    </div>
                    <form action="{{ route('pengurus.anggota.renew-qr') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-end">
                        @csrf
                        <div>
                            <label for="max_uses" class="block text-xs font-semibold text-navy-700 mb-1">
                                Kuota Maksimal Anggota *
                            </label>
                            <input
                                type="number"
                                id="max_uses"
                                name="max_uses"
                                value="100"
                                min="1"
                                max="1000"
                                required
                                class="w-full h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-xs font-mono text-navy-900 shadow-xs focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                        </div>
                        <div>
                            <label for="valid_duration_hours" class="block text-xs font-semibold text-navy-700 mb-1">
                                Durasi Masa Berlaku *
                            </label>
                            <select
                                id="valid_duration_hours"
                                name="valid_duration_hours"
                                required
                                class="w-full h-10 px-3.5 rounded-xl bg-white border border-ice-200/80 text-xs text-navy-900 shadow-xs focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                                <option value="24">24 Jam (1 Hari)</option>
                                <option value="48" selected>48 Jam (2 Hari)</option>
                                <option value="72">72 Jam (3 Hari)</option>
                                <option value="168">168 Jam (7 Hari)</option>
                                <option value="720">720 Jam (30 Hari)</option>
                            </select>
                        </div>
                        <div>
                            <button
                                type="submit"
                                class="w-full h-10 px-4 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer"
                            >
                                Simpan & Terbitkan QR Baru
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KARTU 2: TABEL DATA ANGGOTA TERDAFTAR -->
            <div class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-ice-200/70">
                    <div>
                        <h2 class="font-display font-bold text-lg text-navy-900">
                            Daftar Seluruh Anggota Mahasiswa
                        </h2>
                        <p class="text-xs text-navy-500 mt-1">
                            Menampilkan seluruh anggota yang terdaftar di ormawa {{ $organization->name }}. Total: <strong class="text-navy-900">{{ $totalMembers }}</strong> anggota ({{ $activeMembers }} aktif).
                        </p>
                    </div>
                </div>

                @if ($members->isEmpty())
                    <div class="py-16 text-center text-sm text-navy-500">
                        <div class="w-12 h-12 rounded-2xl bg-ice-100 mx-auto mb-3 flex items-center justify-center text-navy-400 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="font-bold text-navy-900">Belum Ada Anggota Terdaftar</p>
                        <p class="text-xs text-navy-500 mt-1">Bagikan QR Code pendaftaran di atas ke grup WhatsApp atau gunakan formulir tambah manual di bawah.</p>
                    </div>
                @else
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead>
                                <tr class="border-b border-ice-200/70 text-navy-500 font-bold text-xs uppercase tracking-wider">
                                    <th class="py-3.5 px-3">NIM / No. Anggota</th>
                                    <th class="py-3.5 px-3">Nama Lengkap</th>
                                    <th class="py-3.5 px-3">Nomor WhatsApp</th>
                                    <th class="py-3.5 px-3">Akun Login</th>
                                    <th class="py-3.5 px-3">Status</th>
                                    <th class="py-3.5 px-3">Terdaftar Pada</th>
                                    <th class="py-3.5 px-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-100">
                                @foreach ($members as $m)
                                    <tr class="hover:bg-ice-100/50 transition-colors">
                                        <td class="py-3.5 px-3 font-mono font-bold text-navy-900">
                                            {{ $m->member_number }}
                                        </td>
                                        <td class="py-3.5 px-3 font-semibold text-navy-900">
                                            {{ $m->name }}
                                        </td>
                                        <td class="py-3.5 px-3 text-navy-500 font-mono">
                                            {{ $m->phone ?: '-' }}
                                        </td>
                                        <td class="py-3.5 px-3 text-navy-700">
                                            @if ($m->user)
                                                <span class="font-medium text-navy-900 font-mono">{{ '@' . $m->user->username }}</span>
                                            @else
                                                <span class="text-navy-400">Belum Login</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-3">
                                            @if ($m->is_active)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-butter-100 text-navy-900 font-bold text-[11px] shadow-xs">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-100 text-danger font-bold text-[11px] shadow-xs">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-3 text-navy-500 tabular-nums">
                                            {{ $m->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="py-3.5 px-3 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center gap-1.5">
                                                <!-- Edit Anggota -->
                                                <button
                                                    type="button"
                                                    onclick="openEditMemberModal('{{ $m->id }}', '{{ addslashes($m->member_number) }}', '{{ addslashes($m->name) }}', '{{ addslashes($m->phone ?? '') }}')"
                                                    class="h-8 px-3 rounded-xl bg-white/90 hover:bg-white text-navy-800 border border-ice-200/80 font-semibold text-xs shadow-[1px_1px_4px_rgba(122,163,214,0.12),-1px_-1px_4px_#ffffff] active:scale-[0.98] transition-all cursor-pointer"
                                                >
                                                    Edit
                                                </button>

                                                <!-- Toggle Aktif/Nonaktif -->
                                                <form action="{{ route('pengurus.anggota.toggle-status', $m) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="h-8 px-3 rounded-xl bg-white/90 hover:bg-white text-navy-700 border border-ice-200/80 font-semibold text-xs shadow-[1px_1px_4px_rgba(122,163,214,0.12),-1px_-1px_4px_#ffffff] active:scale-[0.98] transition-all cursor-pointer"
                                                    >
                                                        {{ $m->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>

                                                <!-- Hapus Anggota -->
                                                <form
                                                    action="{{ route('pengurus.anggota.destroy', $m) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Hapus data anggota {{ $m->name }} ({{ $m->member_number }}) dari organisasi?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="h-8 px-3 rounded-xl bg-red-50 text-danger hover:bg-red-100 border border-red-200/80 font-semibold text-xs shadow-[1px_1px_4px_rgba(192,82,74,0.12)] active:scale-[0.98] transition-all cursor-pointer"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($members->hasPages())
                        <div class="pt-3 border-t border-ice-200/70">
                            {{ $members->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- KARTU 3: FORM TAMBAH ANGGOTA MANUAL -->
            <div id="manual-add-member-section" class="bg-white/90 rounded-[28px] p-6 lg:p-7 shadow-[6px_6px_20px_rgba(122,163,214,0.18),-6px_-6px_20px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5">
                <div class="border-b border-ice-200/70 pb-3.5">
                    <h2 class="font-display font-bold text-lg text-navy-900">
                        Tambah Anggota Manual
                    </h2>
                    <p class="text-xs text-navy-500 mt-0.5">
                        Gunakan formulir ini jika terdapat anggota yang terkendala memindai QR Code pendaftaran mandiri.
                    </p>
                </div>

                <form action="{{ route('pengurus.anggota.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label for="manual_nim" class="block text-xs font-semibold text-navy-700 mb-1.5">
                                NIM / Nomor Anggota *
                            </label>
                            <input
                                type="text"
                                id="manual_nim"
                                name="member_number"
                                required
                                placeholder="Contoh: 20240105"
                                class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs font-mono text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                        </div>
                        <div>
                            <label for="manual_name" class="block text-xs font-semibold text-navy-700 mb-1.5">
                                Nama Lengkap Mahasiswa *
                            </label>
                            <input
                                type="text"
                                id="manual_name"
                                name="name"
                                required
                                placeholder="Contoh: Ahmad Fauzi"
                                class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                        </div>
                        <div>
                            <label for="manual_phone" class="block text-xs font-semibold text-navy-700 mb-1.5">
                                Nomor WhatsApp (Opsional)
                            </label>
                            <input
                                type="text"
                                id="manual_phone"
                                name="phone"
                                placeholder="Contoh: 081234567890"
                                class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs font-mono text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                            >
                        </div>
                    </div>
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="h-11 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer flex items-center gap-2"
                        >
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Simpan Data Anggota</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <!-- Modal Edit Data Anggota -->
    <div id="edit-member-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-navy-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white/95 rounded-[28px] max-w-md w-full p-6 sm:p-7 shadow-[10px_10px_35px_rgba(122,163,214,0.35),-10px_-10px_35px_rgba(255,255,255,0.95)] border border-ice-200/80 space-y-5 animate-scale-up" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between border-b border-ice-200/70 pb-3">
                <h3 class="font-display font-bold text-base text-navy-900">
                    Edit Data Anggota
                </h3>
                <button type="button" onclick="closeEditMemberModal()" class="w-8 h-8 rounded-full bg-ice-100 text-navy-500 hover:text-navy-900 flex items-center justify-center font-bold text-lg cursor-pointer transition-colors">
                    &times;
                </button>
            </div>

            <form id="edit-member-form" method="POST" action="" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_member_number" class="block text-xs font-semibold text-navy-700 mb-1.5">
                        NIM / Nomor Anggota *
                    </label>
                    <input
                        type="text"
                        id="edit_member_number"
                        name="member_number"
                        required
                        class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs font-mono text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                    >
                    <span class="text-[10px] text-navy-500 mt-1 block">Jika diubah, username login anggota otomatis disinkronkan.</span>
                </div>

                <div>
                    <label for="edit_member_name" class="block text-xs font-semibold text-navy-700 mb-1.5">
                        Nama Lengkap *
                    </label>
                    <input
                        type="text"
                        id="edit_member_name"
                        name="name"
                        required
                        class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                    >
                </div>

                <div>
                    <label for="edit_member_phone" class="block text-xs font-semibold text-navy-700 mb-1.5">
                        Nomor WhatsApp
                    </label>
                    <input
                        type="text"
                        id="edit_member_phone"
                        name="phone"
                        placeholder="08xxxxxxxxxx"
                        class="w-full h-10 px-3.5 rounded-xl bg-ice-100/70 text-xs font-mono text-navy-900 border border-ice-200/80 shadow-[inset_2px_2px_4px_rgba(122,163,214,0.2),inset_-2px_-2px_4px_rgba(255,255,255,0.9)] focus:outline-none focus:ring-1.5 focus:ring-navy-900"
                    >
                </div>

                <div class="pt-2 flex items-center justify-end gap-2.5">
                    <button
                        type="button"
                        onclick="closeEditMemberModal()"
                        class="h-10 px-4 rounded-xl bg-ice-100 text-navy-700 hover:bg-ice-200 text-xs font-semibold transition-colors cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="h-10 px-5 rounded-xl bg-navy-900 text-white font-semibold text-xs hover:bg-navy-700 transition-all shadow-[2px_3px_8px_rgba(47,72,98,0.2)] active:scale-[0.99] cursor-pointer"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleUpdateModal() {
            const modal = document.getElementById('update-qr-modal');
            modal.classList.toggle('hidden');
        }

        function toggleRenewModal() {
            const modal = document.getElementById('renew-qr-modal');
            modal.classList.toggle('hidden');
        }

        function openEditMemberModal(id, memberNumber, name, phone) {
            const modal = document.getElementById('edit-member-modal');
            const form = document.getElementById('edit-member-form');
            form.action = `/pengurus/anggota/${id}`;
            document.getElementById('edit_member_number').value = memberNumber;
            document.getElementById('edit_member_name').value = name;
            document.getElementById('edit_member_phone').value = phone || '';
            modal.classList.remove('hidden');
        }

        function closeEditMemberModal() {
            const modal = document.getElementById('edit-member-modal');
            modal.classList.add('hidden');
        }

        function copyRegistrationUrl() {
            const copyText = document.getElementById("registration-url-input");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Tautan pendaftaran berhasil disalin ke clipboard!");
        }
    </script>

</body>
</html>