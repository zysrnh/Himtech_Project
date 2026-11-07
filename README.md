# Kasly — Sistem Tata Kelola Kas & Transparansi Iuran Ormawa Multi-Tenant

> **Tema Lomba:** *Bayar Kas Tepat Waktu: Meningkatkan Kedisiplinan dan Transparansi Pembayaran Iuran/Kas*

Kasly adalah platform web *Software-as-a-Service* (SaaS) multi-tenant modern yang dirancang untuk mendigitalkan pembukuan kas, mengotomatiskan penerbitan tagihan iuran berkala, mengukur kepatuhan bayar tepat waktu (*on-time payment rate*), dan menyediakan transparansi mutasi kas secara terbuka bagi organisasi mahasiswa (Ormawa, Himpunan, BEM, UKM, dan Komunitas Kampus).

---

## 🚀 Fitur Unggulan Sistem

- **Arsitektur Multi-Tenant Terisolasi:** Satu sistem terpusat yang aman untuk puluhan organisasi dengan pemisahan database berbasis `organization_id`.
- **Portal Login Tunggal Universal (`/login`):** Deteksi otomatis peran pengguna (Admin Organisasi, Bendahara, Staf, atau Anggota Mahasiswa via NIM).
- **Penilaian Kedisiplinan Iuran Otomatis:** Menghitung rasio tagihan terbayar tepat waktu (*is_on_time*) terhadap batas tanggal jatuh tempo (*due_date*).
- **Multi-Channel Pembayaran Fleksibel:**
  - Pembayaran Tunai (*Cash*) langsung ke bendahara.
  - Transfer Bank Manual ke rekening resmi ormawa.
  - Pembayaran Otomatis via **Midtrans Sandbox** (Virtual Account BCA, Mandiri, BRI, BNI, Permata & QRIS Dinamis).
- **Registrasi Mandiri Anggota via QR Code (`/daftar-anggota`):** Scan QR Code berbatas waktu dan kuota dengan konfirmasi organisasi dan auto-login instan.
- **Buku Kas Anti-Manipulasi (*Locked Ledger*):** Mutasi kas terkunci permanen (`is_locked = true`) dilengkapi bukti nota fisik dan rekam jejak audit (*audit trail*).
- **Integrasi Alert & Notifikasi WhatsApp Gateway (Fonnte API) & Email SMTP:** Pengiriman kredensial akun otomatis ke pengurus baru.

---

## 🛠️ Stack Teknologi

- **Backend Framework:** Laravel 12 / 13 (PHP 8.3+)
- **Frontend & Styling:** Blade Templating, Tailwind CSS v4 (*Design System Ice & Butter v2 Neumorphism*), Vite
- **Database:** SQLite (default lokal) / MySQL / MariaDB
- **Payment Gateway:** Midtrans Sandbox (Core API & Snap)
- **WhatsApp Gateway:** Fonnte API (`https://api.fonnte.com/send`)
- **Mail Transport:** Laravel Mail SMTP (SSL port 465)

---

## 📋 Prasyarat Sistem

Sebelum memulai instalasi, pastikan perangkat Anda telah terpasang:

- **PHP:** Versi `>= 8.2` (disarankan PHP 8.3) dengan ekstensi `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`
- **Composer:** Versi `>= 2.0`
- **Node.js & NPM:** Node.js `>= 18.0` dan npm `>= 9.0`
- **Git**

---

## 📦 Panduan Instalasi Langkah Demi Langkah

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan proyek di komputer lokal Anda:

### 1. Clone Repositori
Buka terminal / PowerShell dan clone repositori ini:
```bash
git clone https://github.com/zysrnh/Himtech_Project.git
cd Himtech_Project
```

### 2. Pasang Dependensi Backend (Composer)
```bash
composer install
```

### 3. Pasang Dependensi Frontend (NPM)
```bash
npm install
```

### 4. Konfigurasi File Environment (`.env`)
Salin file konfigurasi dari template `.env.example`:
- **Linux / macOS / Bash:**
  ```bash
  cp .env.example .env
  ```
- **Windows PowerShell:**
  ```powershell
  Copy-Item .env.example .env
  ```

### 5. Generate Application Encryption Key
```bash
php artisan key:generate
```

### 6. Setup Database & Jalankan Migrasi + Seeder Utama
Secara default, Laravel dikonfigurasi menggunakan SQLite untuk kemudahan pengujian lokal.

Jika menggunakan SQLite, pastikan file database tersedia (Laravel akan otomatis menawarkannya saat migrasi) atau buat secara manual:
- **Windows PowerShell:**
  ```powershell
  New-Item -ItemType File -Path database\database.sqlite -Force
  ```
- **Linux / macOS:**
  ```bash
  touch database/database.sqlite
  ```

Jalankan migrasi tabel dan muat data seeder awal:
```bash
php artisan migrate --seed
```

> **Catatan:** Perintah `--seed` di atas akan memuat akun Administrator Kasly (`SuperAdminSeeder`) dan data ormawa demo utama HIMTECH (`OrganizationSeeder`).

### 7. Buat Symbolic Link Storage (Untuk Upload Bukti & Avatar)
```bash
php artisan storage:link
```

### 8. Kompilasi Aset Frontend (Tailwind CSS & Vite)
Untuk mode pengembangan (*hot reload*):
```bash
npm run dev
```
Atau untuk melakukan build aset produksi sekali jalan:
```bash
npm run build
```

### 9. Jalankan Web Server Lokal
Di jendela terminal baru, jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Aplikasi kini aktif dan dapat diakses melalui browser pada alamat:
👉 **http://localhost:8000**

---

## 🔑 Rekapitulasi Akun Login Demo

Sistem menyediakan beberapa tingkatan peran untuk simulasi pengujian:

### 1. Portal Administrator Platform Kasly (`/admin/login`)
Gunakan portal ini untuk mengelola organisasi se-kampus dan menyetujui pendaftaran baru:

| Peran | Email / Username | Password | Wewenang Utama |
| :--- | :--- | :--- | :--- |
| **Super Admin Kasly** | `admin_kasly` / `admin@kasly.id` | `password` | Kendali master platform, kelola ormawa, toggle status, kelola admin |
| **Admin Penerima Organisasi** | `admin_organisasi` / `admin.organisasi@kasly.id` | `password` | Spesialisasi review & ACC/Tolak pengajuan ormawa baru dari landing page |

---

### 2. Ormawa Demo Utama — HIMTECH (`/login`)
Gunakan form login universal `/login` dengan memasukkan **Kode Organisasi: `HIMTECH`**:

| Peran | Username / Identitas | Password | Nama Akun | Keterangan Akses |
| :--- | :--- | :--- | :--- | :--- |
| **Admin Organisasi / PIC** | `bendahara_himtech` | `password` | Muhammad Zaki | Kelola ormawa, buat akun staf/bendahara baru |
| **Bendahara Ormawa** | `siti_bendahara` | `password` | Siti Nurhaliza | Kelola iuran kas, verifikasi bukti bayar, pembukuan |
| **Anggota Mahasiswa #1** | `2026001` | `2026001` | Aditya Pratama | Simulasi: Lunas Tepat Waktu |
| **Anggota Mahasiswa #2** | `2026002` | `2026002` | Budi Santoso | Simulasi: Lunas Tepat Waktu |
| **Anggota Mahasiswa #7** | `2026007` | `2026007` | Gita Permata | Simulasi: Lunas Terlambat |
| **Anggota Mahasiswa #9** | `2026009` | `2026009` | Indah Wahyuni | Simulasi: Belum Bayar (Menunggak) |

*(Tersedia 10 data anggota sampel: NIM `2026001` s/d `2026010`, password default sama dengan NIM masing-masing).*

---

### 3. Akun Seeder Tambahan Baru — BEM FASILKOM (Opsional)

Kami telah menyiapkan seeder dataset mandiri kedua di dalam file `database/seeders/NewAccountSeeder.php`. 

> ⚠️ **Catatan Penting:** Seeder ini **TIDAK dijalankan secara otomatis** pada migrasi bawaan agar database Anda tetap bersih. Anda dapat menjalankannya kapan saja secara opsional jika ingin menguji skenario multi-organisasi dengan perintah berikut:

```bash
php artisan db:seed --class=NewAccountSeeder
```

Setelah dijalankan, Anda dapat login di `/login` dengan **Kode Organisasi: `BEMFASILKOM`**:

| Peran | Username / NIM | Password | Nama Pengguna | Status Pembayaran |
| :--- | :--- | :--- | :--- | :--- |
| **Admin Organisasi (Ketua BEM)** | `rizky_bem` | `password` | Rizky Fauzan | Akses Pengurus BEM |
| **Bendahara BEM** | `nabila_bendahara` | `password` | Nabila Putri | Akses Kas & Tagihan BEM |
| **Staf Operasional BEM** | `dimas_staff` | `password` | Dimas Pratama | Akses Staf Pengurus |
| **Anggota Mahasiswa #1** | `2026101` | `password` | Ahmad Fauzi | Lunas Tepat Waktu |
| **Anggota Mahasiswa #2** | `2026102` | `password` | Bella Safitri | Lunas Tepat Waktu |
| **Anggota Mahasiswa #3** | `2026103` | `password` | Chandra Wijaya | Lunas Tepat Waktu |
| **Anggota Mahasiswa #4** | `2026104` | `password` | Dini Anggraini | Lunas Terlambat |
| **Anggota Mahasiswa #5** | `2026105` | `password` | Erlangga Putra | Belum Bayar (Menunggak) |

---

## ⚙️ Konfigurasi Tambahan (Opsional)

### Integrasi WhatsApp Gateway (Fonnte)
Untuk mengaktifkan blast pesan WhatsApp dan dispatch kredensial secara langsung ke nomor WhatsApp pengguna, tambahkan API token Fonnte pada file `.env`:
```env
FONNTE_TOKEN=token_fonnte_anda_disini
```
*(Jika token dikosongkan, sistem akan mengaktifkan mode graceful fallback: notifikasi tetap dicatat di log sistem tanpa menyebabkan crash).*

### Integrasi Payment Gateway Midtrans (Sandbox)
Untuk menguji simulasi pembayaran Virtual Account & QRIS dinamis:
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false
```

---

## 🗂️ Struktur Direktori Utama

```
Himtech_Project/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/             # Pengendali Super Admin & Admin Penerima
│   │   ├── Anggota/           # Dashboard & Portal Transparansi Mahasiswa
│   │   ├── Auth/              # Autentikasi Universal & Deteksi Peran
│   │   ├── Payment/           # Integrasi Midtrans Core API & Webhook
│   │   └── Pengurus/          # Tagihan, Anggota, Metode Bayar, Staf
│   └── Models/                # Model Eloquent (User, Organization, Member, dll)
├── database/
│   ├── migrations/            # Skema tabel database multi-tenant
│   └── seeders/               # Dataset pengujian (SuperAdmin, HIMTECH, NewAccount)
├── resources/
│   ├── views/                 # Template UI Blade (Landing page, Pengurus, Anggota, Admin)
│   └── css/app.css            # Desain Neumorphic Tailwind CSS v4
├── routes/
│   ├── web.php                # Rute aplikasi web
│   └── api.php                # Endpoint webhook Midtrans
└── laporan/                   # Dokumen PRD, ERD, dan rekapitulasi fitur lomba
```

---

## 👥 Tim Pengembang

- **Tim Himtech / Kasly Project Team**
- Dikembangkan untuk Kompetisi Inovasi Tata Kelola Keuangan Organisasi Mahasiswa 2026.
