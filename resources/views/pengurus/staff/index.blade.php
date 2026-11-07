<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Staff & Bendahara - {{ $organization->name }} - {{ config('app.name', 'Kasly') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ice-100 text-navy-900 font-sans antialiased min-h-screen flex">

    <!-- Sidebar Pengurus (240px) Sesuai Ice & Butter v2 -->
    <aside class="w-[240px] bg-white border-r border-ice-200 flex flex-col justify-between shrink-0 min-h-screen sticky top-0 h-screen">
        <div>
            <!-- Brand & Kode Organisasi -->
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
                <a
                    href="{{ route('pengurus.dashboard') }}"
                    class="flex items-center h-11 px-4 rounded-md text-navy-500 hover:text-navy-900 hover:bg-ice-100 font-medium text-sm transition-colors"
                >
                    Overview Kas
                </a>
                <a
                    href="{{ route('pengurus.staff.index') }}"
                    class="flex items-center h-11 px-4 rounded-md bg-ice-100 text-navy-900 font-medium text-sm transition-colors"
                >
                    Kelola Staff
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
                <p class="text-[13px] text-navy-500 truncate">
                    {{ Auth::user()->email ?? Auth::user()->username }}
                </p>
                <p class="text-[11px] font-semibold text-navy-900/70 uppercase tracking-wider mt-1">
                    {{ session('active_role') === 'admin_organisasi' ? 'Admin Organisasi' : (session('active_role') === 'bendahara' ? 'Bendahara' : 'Staff') }}
                </p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
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

    <!-- Main Content Area: Kelola Staff & Bendahara -->
    <main class="flex-1 min-w-0 p-8">
        <div class="max-w-[1120px] mx-auto space-y-8">

            <!-- Top Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-semibold text-[28px] text-navy-900">
                        Kelola Staff & Bendahara
                    </h1>
                    <p class="text-sm text-navy-500 mt-1">
                        Manajemen tim kepengurusan {{ $organization->name }}. Buatkan akun untuk bendahara atau staf operasional kas Anda.
                    </p>
                </div>
                <div>
                    <a
                        href="#form-tambah-staff"
                        class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm"
                    >
                        + Tambah Staff Baru
                    </a>
                </div>
            </div>

            <!-- Notifikasi Sukses / Error -->
            @if (session('success'))
                <div class="p-4 rounded-md bg-butter-100 text-navy-900 text-sm font-medium shadow-card flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-md bg-danger/10 text-danger text-sm font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Tabel Daftar Staff & Bendahara Ormawa -->
            <div class="bg-white rounded-lg shadow-card overflow-hidden border border-ice-200">
                <div class="p-6 border-b border-ice-200">
                    <h2 class="font-display font-medium text-lg text-navy-900">
                        Daftar Pengurus Ormawa
                    </h2>
                    <p class="text-sm text-navy-500 mt-0.5">
                        Seluruh akun yang memiliki hak akses mengelola pembukuan dan operasional kas {{ $organization->name }}.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-ice-200 text-xs font-medium text-navy-500 bg-ice-100/40">
                                <th class="py-3.5 px-6">Nama Pengurus</th>
                                <th class="py-3.5 px-6">Username Login</th>
                                <th class="py-3.5 px-6">Peran (Role)</th>
                                <th class="py-3.5 px-6">Nomor WhatsApp</th>
                                <th class="py-3.5 px-6">Email</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-ice-200 text-sm">
                            @foreach ($staffMembers as $staff)
                                <tr class="hover:bg-ice-100/50 transition-colors">
                                    <td class="py-4 px-6 font-semibold text-navy-900">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $staff->name }}</span>
                                            @if ($staff->id === Auth::id())
                                                <span class="px-2 py-0.5 rounded text-[11px] bg-butter-100 text-navy-900 font-semibold font-sans">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-navy-900">
                                        {{ '@' . $staff->username }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($staff->pivot->role === 'admin_organisasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-butter-100 text-navy-900 text-xs font-semibold">
                                                Admin Organisasi
                                            </span>
                                        @elseif ($staff->pivot->role === 'bendahara')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-ice-100 text-navy-900 text-xs font-semibold">
                                                Bendahara
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-ice-100/60 text-navy-500 text-xs font-medium">
                                                Staff
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs text-navy-900">
                                        {{ $staff->phone ?: '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-navy-500 text-xs">
                                        {{ $staff->email }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($staff->pivot->is_active)
                                            <span class="text-xs font-semibold text-navy-900">Aktif</span>
                                        @else
                                            <span class="text-xs font-medium text-danger">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right whitespace-nowrap">
                                        @if ($staff->id !== Auth::id())
                                            <div class="inline-flex items-center gap-2">
                                                <form
                                                    action="{{ route('pengurus.staff.toggle-status', $staff) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Ubah status keaktifan staf {{ $staff->name }}?');"
                                                >
                                                    @csrf
                                                    @method('PATCH')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-ice-200 text-navy-900 bg-white hover:bg-ice-100 text-xs font-medium transition-colors cursor-pointer"
                                                    >
                                                        {{ $staff->pivot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                    </button>
                                                </form>

                                                <form
                                                    action="{{ route('pengurus.staff.destroy', $staff) }}"
                                                    method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Hapus akun staf {{ $staff->name }} dari kepengurusan?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center h-8 px-3 rounded-md border border-danger/30 text-danger bg-white hover:bg-danger/10 text-xs font-medium transition-colors cursor-pointer"
                                                    >
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-navy-500/50 italic">Sesi Anda</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form Tambah Staff / Bendahara Baru -->
            <div id="form-tambah-staff" class="bg-white rounded-lg p-8 shadow-card border border-ice-200">
                <div class="pb-4 border-b border-ice-200 mb-6">
                    <h2 class="font-display font-medium text-xl text-navy-900">
                        Tambah Akun Staff / Bendahara Baru
                    </h2>
                    <p class="text-sm text-navy-500 mt-1">
                        Daftarkan bendahara atau staf yang membantu pembukuan kas {{ $organization->name }}. Kredensial akan otomatis dikirimkan ke WhatsApp mereka.
                    </p>
                </div>

                <form action="{{ route('pengurus.staff.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Nama Lengkap Pengurus <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Contoh: Rian Anggara"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="username" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Username Login <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                placeholder="Contoh: rian_bendahara"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Alamat Email <span class="text-danger">*</span>
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="rian@email.com"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="phone" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Nomor WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                id="phone"
                                name="phone"
                                value="{{ old('phone') }}"
                                required
                                placeholder="081234567890"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>

                        <div>
                            <label for="role" class="block text-[13px] text-navy-500 font-medium mb-2">
                                Peran / Jabatan di Ormawa <span class="text-danger">*</span>
                            </label>
                            <select
                                id="role"
                                name="role"
                                required
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors cursor-pointer"
                            >
                                <option value="bendahara" {{ old('role') === 'bendahara' ? 'selected' : '' }}>Bendahara (Kelola Kas, Tagihan & Pembukuan)</option>
                                <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Operasional (Bantu Catat Kas)</option>
                                <option value="admin_organisasi" {{ old('role') === 'admin_organisasi' ? 'selected' : '' }}>Admin Organisasi (Ketua / Koordinator)</option>
                            </select>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="text-[13px] text-navy-500 font-medium">
                                    Kata Sandi Awal
                                </label>
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        id="btn-gen-pwd"
                                        class="text-xs font-semibold text-navy-900 underline decoration-ice-400 underline-offset-4 cursor-pointer"
                                    >
                                        Acak Kuat
                                    </button>
                                </div>
                            </div>
                            <input
                                type="text"
                                id="staff-password"
                                name="password"
                                value="{{ old('password') }}"
                                placeholder="Biarkan kosong untuk generate otomatis (10 karakter)"
                                class="w-full h-12 px-4 rounded-md bg-ice-100 text-navy-900 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-ice-400 transition-colors"
                            >
                        </div>
                    </div>

                    <div class="p-4 rounded-md bg-ice-100/60 border border-ice-200 text-xs text-navy-500 space-y-1">
                        <p class="font-medium text-navy-900">Pengiriman Kredensial Otomatis:</p>
                        <p>Setelah tombol simpan ditekan, sistem gateway Fonnte akan otomatis mengirimkan Kode Organisasi (<strong>{{ $organization->code }}</strong>), Username, dan Kata Sandi ke nomor WhatsApp di atas.</p>
                    </div>

                    <div class="pt-4 border-t border-ice-200 flex items-center justify-end">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center h-11 px-6 rounded-md bg-navy-900 text-white font-medium text-sm hover:bg-navy-700 transition-colors shadow-sm cursor-pointer"
                        >
                            Daftarkan Staff & Kirim Kredensial via WA
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pwdInput = document.getElementById('staff-password');
            const btnGen = document.getElementById('btn-gen-pwd');

            function generatePassword(length = 10) {
                const chars = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$%^&*";
                let res = "";
                for (let i = 0; i < length; i++) {
                    res += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return res;
            }

            if (btnGen && pwdInput) {
                btnGen.addEventListener('click', function () {
                    pwdInput.value = generatePassword(10);
                });
            }
        });
    </script>
</body>
</html>
