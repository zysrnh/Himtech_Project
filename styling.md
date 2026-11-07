# Design System: Ice & Butter (v2)

Aturan utama: UI menampilkan data, bukan dekorasi. Tidak ada label kecil di atas judul, tidak ada teks uppercase berjarak lebar, tidak ada tag seperti "01 —" atau "NEW", dan tidak ada ornamen yang tidak membawa informasi.

---

## 1. Palet

Hex dari referensi, masih perkiraan. Ambil nilai pasti dengan color picker sebelum rilis.

| Token | Hex | Pemakaian |
|---|---|---|
| `navy-900` | `#2F4862` | Teks utama, ikon, tombol primer |
| `navy-500` | `#4A6583` | Teks sekunder |
| `white` | `#FFFFFF` | Kartu, surface |
| `ice-400` | `#7AA3D6` | Chart, tombol sekunder, fokus |
| `ice-200` | `#B5CCEB` | Border, divider |
| `ice-100` | `#DDEBFC` | Background layar, input |
| `butter-100` | `#FFF7CD` | Saldo positif, status lunas |
| `danger` | `#C0524A` | Tunggakan, error |

Kontras:
- Teks di atas `white` atau `ice-100`: `navy-900`
- Teks di atas `ice-400`: `navy-900`, minimal 18px semi-bold
- Jangan pakai teks putih di atas `ice-400`

Butter hanya dipakai untuk satu hal per layar, yaitu status atau angka yang perlu diperhatikan. Jangan jadikan background besar.

---

## 2. Tipografi

| Peran | Font | Ukuran mobile | Ukuran web |
|---|---|---|---|
| Angka utama (saldo) | Outfit 600 | 36px | 48px |
| Judul halaman | Outfit 600 | 22px | 28px |
| Judul kartu | Outfit 500 | 16px | 18px |
| Body | Plus Jakarta Sans 400 | 15px | 16px |
| Keterangan | Plus Jakarta Sans 400, `navy-500` | 13px | 14px |

Tidak ada teks uppercase, tidak ada letter-spacing lebar, dan tidak ada label kecil di atas judul. Judul langsung berdiri sendiri.

Angka uang memakai `tabular-nums`.

---

## 3. Spacing dan Radius

Spacing kelipatan 4: `4 · 8 · 12 · 16 · 24 · 32 · 48`

| Token | Nilai | Pemakaian |
|---|---|---|
| `radius-sm` | 10px | Input |
| `radius-md` | 16px | Tombol, item list |
| `radius-lg` | 24px | Kartu |
| `radius-blob` | `28px 28px 28px 8px` | Kartu saldo utama (satu per layar) |

Shadow hanya satu: `0 4px 16px rgba(47, 72, 98, 0.08)`. Dipakai di kartu dan bottom nav.

---

## 4. Komponen

### Kartu saldo
Satu per halaman. Isi: angka saldo besar, dan dua angka kecil untuk masuk dan keluar. Tidak ada label dekoratif. Keterangan "Saldo" boleh ditulis sebagai teks biasa `navy-500` di atas angka.

### Tombol
- Primer: background `navy-900`, teks putih
- Sekunder: background `ice-100`, teks `navy-900`
- Tinggi 48px, radius `radius-md`
- Maksimal satu tombol primer per layar

### Input
- Background `ice-100`, tanpa border
- Label ditulis di atas input dengan ukuran 13px, warna `navy-500`
- Focus ring 2px `ice-400`
- Tinggi 48px

### Item transaksi
- Baris: judul, keterangan di bawahnya, nominal di kanan
- Nominal masuk tanpa warna khusus, dengan prefix `+`
- Nominal keluar dengan prefix `-`
- Tidak ada ikon hiasan. Kalau perlu kategori, pakai ikon 20px saja.

### Status
Hanya teks dengan warna, tanpa background pill kecuali untuk status tunggakan atau lunas di tabel.
- Lunas: teks `navy-900` dengan latar `butter-100`
- Belum lunas: teks `danger`

### Chart
- Batang `ice-400`, batang tertinggi `navy-900`
- Garis grid `ice-200`, sangat tipis
- Tanpa judul di dalam chart, judul ditaruh di luar

### Navigasi mobile
- Bottom nav 4 menu, ikon 24px, label 12px
- Item aktif: ikon `navy-900`
- Item lain: ikon `navy-500`

### Sidebar web
- Lebar 240px, background `white`
- Item aktif: background `ice-100`
- Tanpa kelompok judul seperti "MENU" atau "LAINNYA"

### Empty state
Satu kalimat dan satu tombol. Tidak ada ilustrasi wajib.

---

## 5. Motif Bintang

Sparkle boleh dipakai satu kali per aplikasi, misalnya di halaman sukses setelah semua tagihan lunas. Jangan dipakai di kartu, tombol, atau header.

---

## 6. Layout

Mobile: satu kolom, padding 20px, kartu saldo di atas, lalu daftar.

Web: sidebar kiri 240px, konten maksimal 1120px, gutter 24px. Tabel untuk data banyak, dengan header sticky.

Breakpoint: `md` 768px, `lg` 1024px.

---

## 7. Motion

- Hover dan fokus: 150ms
- Transisi komponen: 250ms
- Easing: `cubic-bezier(0.2, 0, 0, 1)`
- Angka saldo boleh count-up, maksimal 600ms
- Hormati `prefers-reduced-motion`

---

## 8. Aturan yang Dilarang

- Label uppercase di atas judul atau di dalam kartu
- Tag atau badge yang tidak membawa status
- Nomor dekoratif seperti "01", "02"
- Ikon tanpa fungsi
- Gradient di tombol atau kartu
- Lebih dari satu warna aksen per layar
- Teks kecil di atas `ice-400`

---

## 9. Tailwind Config

```js
// tailwind.config.js
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.jsx',
    './resources/**/*.tsx',
  ],
  theme: {
    extend: {
      colors: {
        navy: { 900: '#2F4862', 500: '#4A6583' },
        ice: { 400: '#7AA3D6', 200: '#B5CCEB', 100: '#DDEBFC' },
        butter: { 100: '#FFF7CD' },
        surface: '#FFFFFF',
        danger: '#C0524A',
      },
      fontFamily: {
        sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'],
        display: ['Outfit', 'system-ui', 'sans-serif'],
      },
      borderRadius: {
        sm: '10px',
        md: '16px',
        lg: '24px',
        blob: '28px 28px 28px 8px',
      },
      boxShadow: {
        card: '0 4px 16px rgba(47, 72, 98, 0.08)',
      },
    },
  },
  plugins: [],
};
```

---
