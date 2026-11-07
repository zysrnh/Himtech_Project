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
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen py-10 px-4">

    <div class="max-w-[760px] mx-auto space-y-6">

        <!-- Top Header & Brand -->
        <div class="flex items-center justify-between">
            <a href="/" class="inline-flex items-center">
                <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
            </a>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('login') }}"
                    class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors"
                >
                    Sudah Punya Akun? Masuk
                </a>
                <span class="text-ice-200">|</span>
                <a
                    href="/"
                    class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors"
                >
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Card Form Utama -->
        <div class="bg-white rounded-lg p-8 sm:p-10 shadow-card border border-ice-200 space-y-8">

            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-ice-100 text-navy-900 text-xs font-semibold mb-3">
                    Pendaftaran Ormawa Baru
                </span>
                <h1 class="font-display font-semibold text-2xl sm:text-3xl text-navy-900">
                    Formulir Pengajuan Organisasi
                </h1>
                <p class="text-sm text-navy-500 mt-2 leading-relaxed">
                    Ajukan pendaftaran Himpunan, UKM, atau Organisasi Mahasiswa Anda. Setelah disetujui oleh Administrator, akun Pengelola dan sistem pembukuan kas Anda akan langsung aktif secara otomatis.
                </p>
            </div>

            @if ($errors->any())
                <div class="p-4 rounded-md bg-danger/10 text-danger text-sm font-medium">
                    Ada beberapa kolom yang perlu diperbaiki. Silakan periksa formulir di bawah.
                </div>
            @endif

            <form action="{{ route('ajukan-organisasi.submit') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Bagian 1: Data Organisasi -->
                <div class="space-y-4">
                    <div class="pb-2 border-b border-ice-200">
                        <h2 class="font-display font-semibold text-base text-navy-900">
                            1. Identitas Organisasi (Ormawa)
                        </h2>
                        <p class="text-xs text-navy-500">
                            Data entitas organisasi yang akan didaftarkan ke platform Kasly.
                        </p>
                    </div>

                    <div>
                        <label for="name" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Nama Organisasi / Ormawa <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Contoh: Himpunan Mahasiswa Informatika"
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Usulan Kode Organisasi (Huruf Kapital, Singkat) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="code"
                            name="code"
                            value="{{ old('code') }}"
                            required
                            placeholder="Contoh: HMIF"
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        <p class="mt-1 text-xs text-navy-500">
                            Kode unik yang nantinya dipakai seluruh pengurus dan anggota untuk login ke portal organisasi.
                        </p>
                        @error('code')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Deskripsi Singkat / Profil Ormawa (Opsional)
                        </label>
                        <textarea
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Deskripsi kegiatan, prodi yang dinaungi, atau visi ormawa..."
                            class="w-full p-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bagian 2: Data Pemohon (Calon Pengelola / Ketua) -->
                <div class="space-y-4">
                    <div class="pb-2 border-b border-ice-200">
                        <h2 class="font-display font-semibold text-base text-navy-900">
                            2. Data Pemohon (Calon Pengelola / Ketua Ormawa)
                        </h2>
                        <p class="text-xs text-navy-500">
                            Akun utama yang berwenang mengelola pembukuan kas dan membuat akun staf di organisasinya.
                        </p>
                    </div>

                    <div>
                        <label for="pic_name" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Nama Lengkap Ketua / PIC Pemohon <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="pic_name"
                            name="pic_name"
                            value="{{ old('pic_name') }}"
                            required
                            placeholder="Contoh: Zaki Yusron"
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        @error('pic_name')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pic_username" class="block text-[13px] text-navy-500 font-medium mb-2">
                            Username Login yang Diinginkan <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            id="pic_username"
                            name="pic_username"
                            value="{{ old('pic_username') }}"
                            required
                            placeholder="Contoh: zaki_hmif"
                            class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                        >
                        @error('pic_username')
                            <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pic_email" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Alamat Email Aktif <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="pic_email"
                                name="pic_email"
                                value="{{ old('pic_email') }}"
                                required
                                placeholder="nama@email.com"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                            @error('pic_email')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="pic_phone" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Nomor WhatsApp Aktif <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="pic_phone"
                                name="pic_phone"
                                value="{{ old('pic_phone') }}"
                                required
                                placeholder="Contoh: 081234567890"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                            <p class="mt-1 text-xs text-navy-500">
                                Kredensial kata sandi awal akan otomatis dikirimkan ke WhatsApp ini setelah disetujui.
                            </p>
                            @error('pic_phone')
                                <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pemberitahuan Privasi & Alur -->
                <div class="p-4 rounded-md bg-ice-100/60 border border-ice-200 text-xs text-navy-500 leading-relaxed space-y-1">
                    <p class="font-medium text-navy-900">Alur Setelah Mengajukan:</p>
                    <p>Permohonan ini akan ditinjau oleh Administrator Kasly. Begitu disetujui (ACC), sistem secara otomatis membuatkan akun Pengelola Organisasi Anda dan mengirimkan kata sandi langsung ke WhatsApp dan Email Anda.</p>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-4 border-t border-ice-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <a
                        href="/"
                        class="text-xs font-semibold text-navy-500 hover:text-navy-900 transition-colors text-center sm:text-left"
                    >
                        Batal dan Kembali
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center h-12 px-8 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                    >
                        Kirim Pengajuan Organisasi
                    </button>
                </div>
            </form>

        </div>

    </div>

</body>
</html>

