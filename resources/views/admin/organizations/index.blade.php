<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Organisasi - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Web (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand -->
            <div class="h-18 px-6 flex items-center border-b border-ice-200">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                    <img src="{{ asset('logo/logo-light.svg') }}" alt="{{ config('app.name', 'Kasly') }}" class="h-9 w-auto">
                </a>
            </div>

            <!-- Nav Items (Tanpa kelompok judul sesuai aturan v2) -->
            <nav class="p-4 space-y-1">
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Overview
                </a>
                <a
                    href="{{ route('admin.organizations.index') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Organisasi
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
                    {{ Auth::user()->name ?? 'Super Admin' }}
                </p>
                <p class="text-[13px] text-navy-500 truncate">
                    {{ Auth::user()->email ?? 'admin@kasly.id' }}
                </p>
            </div>
            <form action="{{ route('admin.logout') }}" method="POST">
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

    <!-- Main Content Area: Manajemen Organisasi -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Manajemen Organisasi
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftar seluruh entitas ormawa yang terdaftar dan formulir pendaftaran organisasi baru.
                    </p>
                </div>
                <div>
                    <a
                        href="#form-tambah-organisasi"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors"
                    >
                        Tambah Organisasi Baru
                    </a>
                </div>
            </div>

            <!-- Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-md bg-butter-100 text-navy-900 text-sm font-medium shadow-card">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-md bg-danger/10 text-danger text-sm font-medium">
                    Ada kesalahan saat pengisian form. Silakan periksa kembali input Anda.
                </div>
            @endif

            <!-- Bagian Tabel Organisasi -->
            <div class="bg-white rounded-lg shadow-card overflow-hidden">
                <div class="p-6 border-b border-ice-200 flex items-center justify-between">
                    <div>
                        <h2 class="font-display font-medium text-lg text-navy-900">
                            Daftar Seluruh Organisasi
                        </h2>
                        <p class="text-sm text-navy-500 mt-0.5">
                            Total {{ $totalOrganizations }} organisasi terdaftar ({{ $activeOrganizations }} aktif).
                        </p>
                    </div>
                </div>

                @if ($organizations->isEmpty())
                    <!-- Empty State Sesuai v2: 1 Kalimat & 1 Tombol -->
                    <div class="p-12 text-center">
                        <p class="text-sm text-navy-500">
                            Belum ada organisasi yang terdaftar di dalam sistem Kasly.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-ice-200 text-xs font-medium text-navy-500">
                                    <th class="py-3 px-6">Kode Organisasi</th>
                                    <th class="py-3 px-6">Nama Organisasi</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6">Terdaftar Sejak</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-ice-200 text-sm">
                                @foreach ($organizations as $org)
                                    <tr class="hover:bg-ice-100/50 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-navy-900 font-display">
                                            {{ $org->code }}
                                        </td>
                                        <td class="py-4 px-6 text-navy-900 font-medium">
                                            {{ $org->name }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if ($org->status === 'active')
                                                <span class="inline-block px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-semibold">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="text-danger font-medium text-xs">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-navy-500 tabular-nums">
                                            {{ $org->created_at->format('d M Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($organizations->hasPages())
                        <div class="p-4 border-t border-ice-200">
                            {{ $organizations->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Bagian Form Tambah Organisasi Baru (Akun Staff Awal) -->
            <div id="form-tambah-organisasi" class="bg-white rounded-lg p-8 shadow-card">
                <div>
                    <h2 class="font-display font-medium text-xl text-navy-900">
                        Tambah Organisasi Baru
                    </h2>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftarkan entitas ormawa baru sekaligus buatkan akun Staff pertamanya.
                    </p>
                </div>

                <form action="{{ route('admin.organizations.store') }}" method="POST" class="mt-8 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri: Data Organisasi -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Nama Organisasi
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Contoh: Himpunan Mahasiswa Teknologi"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('name')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Kode Organisasi (Huruf Kapital, Tanpa Spasi)
                                </label>
                                <input
                                    type="text"
                                    id="code"
                                    name="code"
                                    value="{{ old('code') }}"
                                    required
                                    placeholder="Contoh: HIMTECH"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('code')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Deskripsi Singkat (Opsional)
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="3"
                                    placeholder="Deskripsi kegiatan atau ormawa..."
                                    class="w-full p-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan: Data Akun Staff Awal -->
                        <div class="space-y-4">
                            <div>
                                <label for="staff_name" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Nama Lengkap Staff Awal
                                </label>
                                <input
                                    type="text"
                                    id="staff_name"
                                    name="staff_name"
                                    value="{{ old('staff_name') }}"
                                    required
                                    placeholder="Contoh: Zaki Yusron"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_name')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_email" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Email Staff (Untuk Login Pengurus)
                                </label>
                                <input
                                    type="email"
                                    id="staff_email"
                                    name="staff_email"
                                    value="{{ old('staff_email') }}"
                                    required
                                    placeholder="Contoh: staff@himtech.id"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_email')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_phone" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Nomor WhatsApp Staff (Kirim Kredensial via Fonnte)
                                </label>
                                <input
                                    type="text"
                                    id="staff_phone"
                                    name="staff_phone"
                                    value="{{ old('staff_phone') }}"
                                    required
                                    placeholder="Contoh: 081234567890"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_phone')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="staff_password" class="block text-[13px] text-navy-500 font-normal mb-2">
                                    Password Awal (Kosongkan jika default: password)
                                </label>
                                <input
                                    type="password"
                                    id="staff_password"
                                    name="staff_password"
                                    placeholder="Minimal 6 karakter"
                                    class="w-full h-12 px-4 rounded-sm bg-ice-100 text-navy-900 placeholder:text-navy-500/50 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                                >
                                @error('staff_password')
                                    <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="pt-4 flex justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center h-12 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors cursor-pointer"
                        >
                            Daftarkan Organisasi & Akun Staff
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

</body>
</html>
