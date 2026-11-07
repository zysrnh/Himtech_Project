<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ajukan Pendaftaran Organisasi - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-neu bg-ice-100 text-navy-900 font-sans antialiased min-h-screen py-8 sm:py-12 px-4 selection:bg-ice-200 selection:text-navy-900">

    <div class="max-w-[760px] mx-auto space-y-6">

        <!-- Top Header & Brand -->
        <header class="flex items-center justify-between gap-4">
            <a href="/" class="neu-back-pill group" title="Kembali ke Beranda">
                <svg class="w-4 h-4 text-navy-500 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Beranda</span>
            </a>

            <a href="/" class="inline-flex items-center hover:scale-105 transition-transform" title="{{ config('app.name', 'Kasly') }}">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
            </a>

            <a href="{{ route('login') }}" class="neu-back-pill group text-xs hidden sm:inline-flex" title="Masuk Akun">
                <span class="text-navy-500">Punya Akun?</span>
                <strong class="text-navy-900 group-hover:underline">Masuk &rarr;</strong>
            </a>
        </header>

        <!-- Card Form Utama Neumorphic -->
        <main class="bg-white/90 rounded-[32px] p-6 sm:p-10 border border-white/80 shadow-[12px_16px_36px_rgba(122,163,214,0.35),-10px_-10px_28px_rgba(255,255,255,0.95)] space-y-8">

            <!-- Judul & Pengantar -->
            <div class="space-y-3">
                <h1 class="font-display font-extrabold text-2xl sm:text-3xl text-navy-900 tracking-tight">
                    Formulir Pengajuan Organisasi
                </h1>
                <p class="text-xs sm:text-sm text-navy-500 leading-relaxed">
                    Ajukan pendaftaran Himpunan, UKM, atau Organisasi Mahasiswa Anda. Setelah disetujui oleh Administrator, akun Pengelola dan sistem pembukuan kas Anda akan langsung aktif secara otomatis.
                </p>
            </div>

            <!-- Error Alerts -->
            @if ($errors->any())
                <div class="neu-alert-danger">
                    <svg class="w-4 h-4 text-danger shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="font-medium text-xs sm:text-sm">Ada beberapa kolom yang perlu diperbaiki. Silakan periksa formulir di bawah.</span>
                </div>
            @endif

            <form action="{{ route('ajukan-organisasi.submit') }}" method="POST" class="space-y-8">
                @csrf

                <!-- ======================================================== -->
                <!-- BAGIAN 1: IDENTITAS ORGANISASI (ORMAWA)                   -->
                <!-- ======================================================== -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-ice-200/70">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-ice-200/80 shadow-[3px_3px_8px_rgba(122,163,214,0.2),-3px_-3px_8px_#ffffff] flex items-center justify-center shrink-0 text-navy-900">
                            <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-base text-navy-900">
                                1. Identitas Organisasi (Ormawa)
                            </h2>
                            <p class="text-xs text-navy-500">
                                Data entitas organisasi yang akan didaftarkan ke platform Kasly
                            </p>
                        </div>
                    </div>

                    <!-- Nama Organisasi -->
                    <div>
                        <label for="name" class="neu-label flex items-center justify-between">
                            <span>Nama Organisasi / Ormawa <span class="text-danger">*</span></span>
                            <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                        </label>
                        <div class="neu-input-wrapper">
                            <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Himpunan Mahasiswa Informatika"
                                class="neu-input-field @error('name') neu-input--error @enderror"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usulan Kode Organisasi -->
                    <div>
                        <label for="code" class="neu-label flex items-center justify-between">
                            <span>Usulan Kode Organisasi (Huruf Kapital) <span class="text-danger">*</span></span>
                            <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                        </label>
                        <div class="neu-input-wrapper">
                            <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <input
                                type="text"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
                                required
                                placeholder="Contoh: HMIF"
                                class="neu-input-field uppercase font-mono @error('code') neu-input--error @enderror"
                            >
                        </div>
                        <p class="mt-1 text-xs text-navy-500 pl-1">
                            Kode unik yang nantinya dipakai seluruh pengurus dan anggota untuk login ke portal organisasi.
                        </p>
                        @error('code')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div>
                        <label for="description" class="neu-label">
                            Deskripsi Singkat / Profil Ormawa (Opsional)
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Deskripsi kegiatan, prodi yang dinaungi, atau visi ormawa..."
                            class="neu-textarea @error('description') neu-textarea--error @enderror"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- BAGIAN 2: DATA PEMOHON (CALON PENGELOLA / KETUA)          -->
                <!-- ======================================================== -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-ice-200/70">
                        <div class="w-10 h-10 rounded-2xl bg-white border border-ice-200/80 shadow-[3px_3px_8px_rgba(122,163,214,0.2),-3px_-3px_8px_#ffffff] flex items-center justify-center shrink-0 text-navy-900">
                            <svg class="w-5 h-5 text-navy-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display font-bold text-base text-navy-900">
                                2. Data Pemohon (Calon Pengelola / Ketua Ormawa)
                            </h2>
                            <p class="text-xs text-navy-500">
                                Akun utama yang berwenang mengelola pembukuan kas dan membuat akun staf di organisasinya
                            </p>
                        </div>
                    </div>

                    <!-- Nama Lengkap Pemohon -->
                    <div>
                        <label for="pic_name" class="neu-label flex items-center justify-between">
                            <span>Nama Lengkap Ketua / PIC Pemohon <span class="text-danger">*</span></span>
                            <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                        </label>
                        <div class="neu-input-wrapper">
                            <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <input
                                type="text"
                                id="pic_name"
                                name="pic_name"
                                value="{{ old('pic_name') }}"
                                required
                                placeholder="Contoh: Zaki Yusron"
                                class="neu-input-field @error('pic_name') neu-input--error @enderror"
                            >
                        </div>
                        @error('pic_name')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username Login yang Diinginkan -->
                    <div>
                        <label for="pic_username" class="neu-label flex items-center justify-between">
                            <span>Username Login yang Diinginkan <span class="text-danger">*</span></span>
                            <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                        </label>
                        <div class="neu-input-wrapper">
                            <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <input
                                type="text"
                                id="pic_username"
                                name="pic_username"
                                value="{{ old('pic_username') }}"
                                required
                                placeholder="Contoh: zaki_hmif"
                                class="neu-input-field @error('pic_username') neu-input--error @enderror"
                            >
                        </div>
                        @error('pic_username')
                            <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email & WhatsApp Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Email -->
                        <div>
                            <label for="pic_email" class="neu-label flex items-center justify-between">
                                <span>Alamat Email Aktif <span class="text-danger">*</span></span>
                                <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                            </label>
                            <div class="neu-input-wrapper">
                                <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <input
                                    type="email"
                                    id="pic_email"
                                    name="pic_email"
                                    value="{{ old('pic_email') }}"
                                    required
                                    placeholder="nama@email.com"
                                    class="neu-input-field @error('pic_email') neu-input--error @enderror"
                                >
                            </div>
                            @error('pic_email')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="pic_phone" class="neu-label flex items-center justify-between">
                                <span>Nomor WhatsApp Aktif <span class="text-danger">*</span></span>
                                <span class="text-[11px] text-navy-500/70 font-normal">Wajib</span>
                            </label>
                            <div class="neu-input-wrapper">
                                <svg class="neu-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <input
                                    type="text"
                                    id="pic_phone"
                                    name="pic_phone"
                                    value="{{ old('pic_phone') }}"
                                    required
                                    placeholder="Contoh: 081234567890"
                                    class="neu-input-field font-mono @error('pic_phone') neu-input--error @enderror"
                                >
                            </div>
                            <p class="mt-1 text-[11px] text-navy-500 pl-1">
                                Kredensial kata sandi awal akan otomatis dikirimkan ke WhatsApp ini.
                            </p>
                            @error('pic_phone')
                                <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ======================================================== -->
                <!-- PEMBERITAHUAN PRIVASI & ALUR NEUMORPHIC INSET           -->
                <!-- ======================================================== -->
                <div class="p-5 rounded-2xl bg-ice-100/70 border border-ice-200/80 shadow-[inset_2px_2px_5px_rgba(122,163,214,0.18),inset_-2px_-2px_5px_rgba(255,255,255,0.85)] text-xs text-navy-600 leading-relaxed space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-navy-900"></span>
                        <p class="font-bold text-navy-900 font-display text-xs">Alur Setelah Mengajukan:</p>
                    </div>
                    <p>
                        Permohonan ini akan ditinjau oleh Administrator Kasly. Begitu disetujui (ACC), sistem secara otomatis membuatkan database kas, akun Pengelola Organisasi Anda, dan mengirimkan kata sandi langsung ke WhatsApp dan Email Anda.
                    </p>
                </div>

                <!-- ======================================================== -->
                <!-- TOMBOL SUBMIT & BATAL NEUMORPHIC                          -->
                <!-- ======================================================== -->
                <div class="pt-4 border-t border-ice-200/70 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-4">
                    <a
                        href="/"
                        class="neu-back-pill justify-center text-center text-xs"
                    >
                        Batal dan Kembali
                    </a>
                    <button
                        type="submit"
                        class="neu-btn neu-btn--primary h-12 px-8 rounded-full font-bold text-xs sm:text-sm shadow-md hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Kirim Pengajuan Organisasi</span>
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

        </main>

        <!-- Footer kecil -->
        <footer class="text-center text-xs text-navy-500/70 pb-4">
            &copy; {{ date('Y') }} {{ config('app.name', 'Kasly') }}. Sistem Transparansi Kas Organisasi Mahasiswa.
        </footer>

    </div>

</body>
</html>
