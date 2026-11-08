# Panduan Instalasi Kasly dari File ZIP (Metode Cepat Tanpa Dependensi Tambahan)

Panduan ini ditujukan bagi penilai, juri, penguji, atau pengguna yang menerima dan mengekstrak proyek Kasly dalam bentuk file arsip ZIP.

Paket ZIP Kasly telah dikemas agar siap dijalankan secara mandiri (*standalone*) dengan ketentuan:
- Dependensi PHP (folder `vendor/`) sudah disertakan di dalam ZIP sehingga **TIDAK PERLU** menginstal Composer atau menjalankan `composer install`.
- Aset frontend (folder `public/build/`) sudah dikompilasi siap pakai sehingga **TIDAK PERLU** menginstal Node.js atau NPM (`npm install` / `npm run build`).
- Basis data default telah mendukung SQLite sehingga **TIDAK PERLU** menginstal atau menyalakan service MySQL, MariaDB, ataupun XAMPP.

---

## 1. Persyaratan Minimum Sistem

Anda hanya membutuhkan satu syarat utama pada perangkat laptop / PC:
- **PHP 8.2 atau PHP 8.3** (dapat menggunakan PHP bawaan XAMPP, Laragon, Herd, atau binary PHP standalone).
- Ekstensi PHP aktif di file `php.ini`:
  - `pdo_sqlite`
  - `sqlite3`
  - `mbstring`
  - `openssl`
  - `curl`
  - `fileinfo`

---

## 2. Langkah-Langkah Menjalankan Aplikasi

### Langkah 1: Ekstrak File ZIP
Ekstrak arsip ZIP Kasly ke folder pilihan Anda di komputer (contoh: `D:\Kasly` atau `C:\Kasly`).

### Langkah 2: Buka Terminal / Command Prompt
Buka terminal (Command Prompt, PowerShell, atau Terminal macOS/Linux) dan arahkan direktori aktif ke folder hasil ekstrak proyek:
```bash
cd path/ke/folder/kasly
```

### Langkah 3: Siapkan File Konfigurasi (.env)
Salin file konfigurasi template `.env.example` menjadi `.env`:

- **Untuk Windows (Command Prompt / CMD):**
  ```cmd
  copy .env.example .env
  ```
- **Untuk Windows (PowerShell):**
  ```powershell
  Copy-Item .env.example .env
  ```
- **Untuk Linux / macOS:**
  ```bash
  cp .env.example .env
  ```

*(Catatan: Konfigurasi default `.env.example` sudah siap pakai untuk pengujian lokal).*

### Langkah 4: Generate Application Key
Jalankan perintah berikut untuk mengenerate encryption key aplikasi:
```bash
php artisan key:generate
```

### Langkah 5: Setup Database SQLite & Muat Data Demo
Secara default, aplikasi dapat langsung menggunakan database SQLite tanpa perlu membuat database di phpMyAdmin / MySQL.

Jalankan perintah migrasi tabel dan seeding data demo:
```bash
php artisan migrate --seed
```

> **Info:** Perintah di atas akan otomatis membuat file basis data `database/database.sqlite` dan mengisi akun Super Admin, Admin Penerima, Organisasi HIMTECH, data tagihan kas, dan data anggota mahasiswa demo.

### Langkah 6: Buat Symbolic Link Storage (Untuk Nota & Foto)
Jalankan perintah ini agar file nota transaksi dan foto profil dapat diakses publik:
```bash
php artisan storage:link
```

### Langkah 7: Jalankan Web Server Lokal
Jalankan server bawaan Laravel:
```bash
php artisan serve
```

Aplikasi telah aktif dan siap diakses melalui peramban (browser) pada tautan:
**http://localhost:8000** atau **http://127.0.0.1:8000**

---

## 3. Cara 1-Klik Otomatis Khusus Pengguna Windows

Bagi pengguna sistem operasi Windows, Anda juga dapat menjalankan file batch yang telah disediakan:
1. Klik ganda (double-click) pada file `jalankan-kasly.bat`.
2. File batch akan otomatis mengecek konfigurasi `.env`, membuat database demo jika belum ada, dan langsung menyalakan server lokal pada port 8000.
3. Buka browser dan buka alamat: `http://localhost:8000`.

---

## 4. Rekapitulasi Akun Login Demo

Setelah server berjalan, Anda dapat menguji seluruh alur kerja sistem menggunakan data akun berikut:

### A. Portal Super Administrator Kasly
- **URL Akses:** `http://localhost:8000/admin/login`
- **Fungsi:** Mengelola seluruh organisasi kampus, memverifikasi pendaftaran ormawa baru, dan manajemen administrator platform.

| Peran Akun | Username / Email | Password | Wewenang |
| :--- | :--- | :--- | :--- |
| **Super Admin Kasly** | `admin@kasly.id` (atau `admin_kasly`) | `password` | Master kontrol platform, kelola semua ormawa, kelola admin |
| **Admin Penerima Organisasi** | `admin.organisasi@kasly.id` (atau `admin_organisasi`) | `password` | Spesialisasi verifikasi & persetujuan pengajuan ormawa baru |

---

### B. Portal Bendahara / Pengurus Ormawa (HIMTECH)
- **URL Akses:** `http://localhost:8000/login`
- **Kode Organisasi:** `HIMTECH`
- **Fungsi:** Pembukuan buku kas, jadwal tagihan iuran, mutasi kas, metode pembayaran (Cash/Cashless/Midtrans), QR Code pendaftaran anggota, dan rekap kepatuhan bayar.

| Peran Akun | Username | Password | Nama Pengguna | Keterangan |
| :--- | :--- | :--- | :--- | :--- |
| **Admin Organisasi / PIC** | `bendahara_himtech` | `password` | Muhammad Zaki | Kelola profil ormawa, kelola staf pengurus |
| **Bendahara Utama** | `siti_bendahara` | `password` | Siti Nurhaliza | Kelola kas, buat tagihan, verifikasi mutasi |

---

### C. Portal Anggota Mahasiswa (HIMTECH)
- **URL Akses:** `http://localhost:8000/login`
- **Kode Organisasi:** `HIMTECH`
- **Fungsi:** Melihat riwayat tagihan kas pribadi, persentase kedisiplinan bayar tepat waktu, melakukan pembayaran kas, upload bukti transfer, dan melihat transparansi kas ormawa.

| Peran Akun | NIM (Username) | Password | Nama Mahasiswa | Status Pembayaran Demo |
| :--- | :--- | :--- | :--- | :--- |
| **Anggota Mahasiswa #1** | `2026001` | `2026001` | Aditya Pratama | Simulasi: Lunas Tepat Waktu |
| **Anggota Mahasiswa #2** | `2026002` | `2026002` | Budi Santoso | Simulasi: Lunas Tepat Waktu |
| **Anggota Mahasiswa #7** | `2026007` | `2026007` | Gita Permata | Simulasi: Lunas Terlambat |
| **Anggota Mahasiswa #9** | `2026009` | `2026009` | Indah Wahyuni | Simulasi: Belum Bayar (Menunggak) |

*(Tersedia 10 data anggota sampel dari NIM `2026001` sampai `2026010` dengan password default sama dengan masing-masing NIM).*

---

## 5. Opsi Menggunakan MySQL / XAMPP (Opsional)

Jika Anda lebih memilih menggunakan MySQL daripada SQLite:
1. Pastikan modul MySQL pada XAMPP / Laragon sudah menyala.
2. Buat database baru bernama `Himtech_Kas` melalui phpMyAdmin atau MySQL CLI.
3. Buka file `.env` dan pastikan konfigurasi database disesuaikan:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=Himtech_Kas
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Jalankan:
   ```bash
   php artisan migrate --seed
   ```

---

## 6. Solusi Kendala Umum (Troubleshooting)

1. **Kendala Driver SQLite Tidak Ditemukan (`could not find driver`):**
   - Buka file `php.ini` pada instalasi PHP Anda.
   - Hilangkan tanda titik koma (`;`) pada baris:
     ```ini
     extension=pdo_sqlite
     extension=sqlite3
     ```
   - Simpan file `php.ini` lalu jalankan kembali perintah `php artisan migrate --seed`.

2. **Port 8000 Sudah Terpakai:**
   - Jalankan server pada port alternatif:
     ```bash
     php artisan serve --port=8080
     ```
   - Buka browser pada `http://localhost:8080`.

3. **Gambar Bukti Nota Transaksi Tidak Muncul:**
   - Jalankan perintah:
     ```bash
     php artisan storage:link
     ```

---

Dokumen ini disusun untuk memudahkan pengujian sistem tanpa hambatan instalasi dependensi tambahan.

