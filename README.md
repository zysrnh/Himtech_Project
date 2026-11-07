# Himtech Project - Sistem Manajemen Kas Transparan & Disiplin

Aplikasi web manajemen iuran kas organisasi berbasis Laravel dan Tailwind CSS. Dirancang untuk meningkatkan kedisiplinan anggota dalam membayar kas tepat waktu serta menyediakan transparansi pencatatan arus kas secara terbuka dan akuntabel.

## Tema Lomba
- Tema Utama: Bayar kas tepat waktu
- Fokus: Meningkatkan kedisiplinan dan transparansi pembayaran iuran/kas

## Persyaratan Sistem
Sebelum menjalankan aplikasi, pastikan perangkat telah terpasang:
- PHP >= 8.2 (ekstensi: pdo, mbstring, openssl, tokenizer, xml, ctype, json)
- Composer >= 2.0
- Node.js >= 18.0 & npm >= 9.0
- Git

## Stack Teknologi
- Framework: Laravel
- Styling & UI: Tailwind CSS, Vite
- Database: SQLite / MySQL

## Langkah Instalasi Proyek

1. Clone repositori ke lokal:
   ```bash
   git clone https://github.com/zysrnh/Himtech_Project.git
   cd Himtech_Project
   ```

2. Pasang dependensi PHP melalui Composer:
   ```bash
   composer install
   ```

3. Pasang dependensi Frontend (Node.js & Tailwind CSS):
   ```bash
   npm install
   ```

4. Konfigurasi Environment:
   Salin file environment dari template:
   ```bash
   cp .env.example .env
   ```
   Atau untuk pengguna Windows PowerShell:
   ```powershell
   Copy-Item .env.example .env
   ```

5. Generate Application Key:
   ```bash
   php artisan key:generate
   ```

6. Konfigurasi Database & Jalankan Migrasi:
   Pastikan pengaturan database di file .env sudah sesuai (secara bawaan Laravel dapat menggunakan SQLite).
   ```bash
   php artisan migrate
   ```

## Menjalankan Aplikasi di Lokal

Jalankan server backend Laravel:
```bash
php artisan serve
```

Di terminal terpisah, jalankan server kompilasi frontend (Vite / Tailwind CSS):
```bash
npm run dev
```

Untuk melakukan build aset produksi:
```bash
npm run build
```

Aplikasi dapat diakses melalui browser pada alamat:
http://localhost:8000

## Struktur Proyek Utama
- app/: Logika bisnis dan model aplikasi
- resources/views/: Tampilan antarmuka Blade
- resources/css/app.css: Konfigurasi dan direktif Tailwind CSS
- routes/web.php: Rute halaman web
- public/: Titik masuk aplikasi dan aset terkompilasi
