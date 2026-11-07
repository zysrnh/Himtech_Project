# Design System: Ice & Butter (Fintech Mobile & Web)

Dokumen ini berisi panduan visual dan komponen untuk aplikasi fintech (mobile dan web), termasuk aplikasi uang kas. Gaya yang diadopsi: clean, lembut, rounded, dengan kartu berbentuk blob dan aksen bintang empat (sparkle) sebagai ciri khas.

---

## 1. Prinsip Desain

1. **Tenang dan terpercaya.** Fintech butuh rasa aman. Biru dominan memberi kesan stabil.
2. **Hangat di titik penting.** Warna butter dipakai sebagai highlight untuk hal yang perlu diperhatikan atau dirayakan, bukan untuk seluruh layar.
3. **Lapang.** Banyak whitespace, angka penting dibuat besar, dan hierarki jelas.
4. **Konsisten di mobile dan web.** Token warna, tipografi, dan radius sama. Yang berubah hanya layout.

---

## 2. Palet Warna

Hex di bawah adalah perkiraan dari referensi gambar. Sebelum rilis, ambil nilai pasti dengan color picker dan ganti jika perlu.

| Token | Nama | Hex (perkiraan) | Peran |
|---|---|---|---|
| `navy-900` | Deep Navy | `#2F4862` | Teks utama, ikon, navbar aktif, tombol primer |
| `white` | Pure White | `#FFFFFF` | Kartu, surface utama |
| `blue-400` | Ice Blue | `#7AA3D6` | Aksen, chart, ikon sekunder, tombol sekunder |
| `butter-100` | Butter Cream | `#FFF7CD` | Highlight: saldo positif, badge lunas, notifikasi hangat |
| `ice-100` | Frost Blue | `#DDEBFC` | Background layar, kartu sekunder, track input |

### Warna pendukung (turunan, untuk state)

| Token | Hex | Peran |
|---|---|---|
| `navy-700` | `#243A50` | Hover tombol primer |
| `navy-500` | `#4A6583` | Teks sekunder |
| `blue-200` | `#B5CCEB` | Border halus, divider |
| `butter-300` | `#F5E38F` | Hover highlight |
| `danger` | `#C0524A` | Tunggakan, error (hanya untuk status negatif) |
| `success` | `#3E8E6A` | Lunas, berhasil (opsional, jika butter tidak cukup jelas) |

### Aturan pemakaian kontras
- Teks di atas `white` atau `ice-100`: gunakan `navy-900`. Rasio kontras sekitar 9:1.
- Teks di atas `blue-400`: hanya `navy-900` dan hanya untuk teks besar (minimal 18px semi-bold). Jangan pakai putih di atas `blue-400` karena kontrasnya rendah (sekitar 2.6:1).
- Teks di atas `butter-100`: `navy-900`.
- Jangan letakkan teks kecil di atas `blue-400`.

### Proporsi pemakaian (perkiraan)
- 60% `ice-100` dan `white` (surface)
- 25% `navy-900` (teks, navigasi, tombol)
- 10% `blue-400` (aksen, chart)
- 5% `butter-100` (highlight)

---

## 3. Tipografi

| Peran | Font yang disarankan | Fallback | Ukuran mobile | Ukuran web |
|---|---|---|---|---|
| Display (judul besar, angka saldo) | Fraunces atau DM Serif Display (italic untuk aksen) | Georgia, serif | 32–40px | 40–56px |
| Heading | Manrope | system-ui, sans-serif | 20–24px | 24–32px |
| Body | Manrope | system-ui, sans-serif | 15–16px | 16px |
| Caption / label | Manrope | system-ui, sans-serif | 12–13px | 13–14px |

Catatan:
- Judul "Ice & Butter" di referensi bergaya serif tipis. Itu bisa dipakai untuk display dan angka saldo utama, sementara UI tetap sans-serif agar mudah dibaca.
- Angka uang pakai `font-variant-numeric: tabular-nums` supaya rapi di kolom.
- Berat font: 400 (body), 500 (label), 600 (heading), 700 (angka penting).

---

## 4. Spacing dan Grid

Skala spacing berbasis 4px:

`4 · 8 · 12 · 16 · 20 · 24 · 32 · 40 · 48 · 64`

- Padding layar mobile: 20px kiri dan kanan
- Padding layar web: 32px (container max-width 1200px)
- Jarak antar kartu: 16px (mobile), 24px (web)
- Grid web: 12 kolom, gutter 24px
- Grid mobile: 4 kolom, gutter 16px

---

## 5. Radius, Shadow, dan Bentuk

### Radius
| Token | Nilai | Dipakai untuk |
|---|---|---|
| `radius-sm` | 10px | Input, chip kecil |
| `radius-md` | 16px | Tombol, item list |
| `radius-lg` | 24px | Kartu utama |
| `radius-blob` | `28px 28px 28px 8px` atau asimetris | Kartu hero dan kartu saldo |
| `radius-pill` | 999px | Badge, toggle, filter chip |

### Shadow
Tipis dan halus. Jangan pakai shadow hitam pekat.
- `shadow-card`: `0 4px 16px rgba(47, 72, 98, 0.08)`
- `shadow-float`: `0 12px 32px rgba(47, 72, 98, 0.12)` (untuk bottom sheet, modal)

### Bentuk blob
Kartu saldo atau kartu hero dibuat dengan sudut asimetris dan "notch" kecil seperti di referensi. Di CSS bisa pakai `border-radius` empat nilai, atau SVG untuk bentuk yang lebih custom.

---

## 6. Motif Bintang (Sparkle)

Empat sudut bintang tipis (seperti di referensi) adalah ciri khas visual. Pemakaiannya:
- Sebagai ikon kecil di kartu kategori atau badge pencapaian
- Sebagai dekorasi di empty state dan halaman sukses (misal: "Semua tagihan lunas")
- Sebagai ornamen di ujung kartu hero (opacity 20–30%)

Jangan dipakai di setiap kartu. Batasi maksimal satu per layar agar tidak ramai.

Implementasi: simpan sebagai komponen SVG `<Sparkle />` dengan prop `color` dan `size`.

---

## 7. Komponen

### 7.1 Kartu Saldo (Hero Card)
- Background: `navy-900` dengan teks putih, atau `white` dengan teks navy (pilih salah satu per layar)
- Radius: `radius-blob`
- Isi: label kecil, angka saldo besar (display font), dan dua stat chip (Masuk dan Keluar)
- Stat chip Masuk: background `butter-100`, teks navy
- Stat chip Keluar: background `blue-400`, teks navy

### 7.2 Tombol
| Varian | Background | Teks | Border | Dipakai untuk |
|---|---|---|---|---|
| Primary | `navy-900` | white | none | Aksi utama (Bayar, Simpan) |
| Secondary | `blue-400` | navy-900 | none | Aksi sekunder |
| Highlight | `butter-100` | navy-900 | none | Aksi positif atau perayaan |
| Ghost | transparent | navy-900 | 1px `blue-200` | Batal, aksi ringan |

- Tinggi: 48px (mobile), 44px (web)
- Radius: `radius-md`
- Hover: naik ke `navy-700` (primary) atau turun satu tingkat opacity
- Disabled: opacity 40%

### 7.3 Input
- Background: `ice-100`
- Tanpa border tebal. Gunakan focus ring 2px `blue-400`
- Radius: `radius-sm`
- Tinggi: 52px (mobile), 48px (web)
- Label di atas input, ukuran 13px, warna `navy-500`

### 7.4 Quick Action
Lingkaran 56px dengan ikon di tengah.
- Background: `white` dengan `shadow-card`
- Ikon: `navy-900`
- Label di bawah, 12px
- Contoh: Bayar Kas, Catat, Tagihan, Lainnya

### 7.5 Item Transaksi
- Baris dengan ikon bulat 40px (background `ice-100`)
- Judul di baris pertama (navy-900, 15px semi-bold)
- Keterangan di baris kedua (navy-500, 13px)
- Nominal di kanan. Masuk: `navy-900` dengan prefix `+`. Keluar: `navy-900` dengan prefix `-`. Tunggakan: `danger`.
- Pemisah antar item: divider 1px `blue-200` atau tanpa divider dengan spacing 12px

### 7.6 Badge Status
- Lunas: background `butter-100`, teks navy-900
- Belum lunas: background `danger` dengan opacity 12%, teks `danger`
- Bentuk: `radius-pill`, padding 4px 10px, font 12px semi-bold

### 7.7 Chart
- Warna batang: `blue-400` untuk data biasa, `butter-100` (dengan outline tipis `navy-900` opacity 20%) untuk data tertinggi atau yang disorot
- Grid: garis tipis `blue-200`
- Label sumbu: 11px `navy-500`
- Library: ApexCharts atau Chart.js

### 7.8 Bottom Navigation (Mobile)
- Background `white`, shadow-float halus
- 4–5 item, ikon 24px
- Item aktif: ikon `navy-900` dengan pill background `ice-100`
- Item tidak aktif: ikon `blue-400`

### 7.9 Sidebar dan Navbar (Web)
- Sidebar: background `white`, lebar 256px, item aktif dengan pill `ice-100` dan teks `navy-900`
- Topbar: transparan atau `white`, berisi judul halaman, pencarian, dan avatar
- Avatar: lingkaran dengan border 2px `blue-400`

### 7.10 Modal dan Bottom Sheet
- Background `white`, radius atas 28px
- Overlay: `navy-900` dengan opacity 40%
- Shadow: `shadow-float`

### 7.11 Empty State
- Ilustrasi sederhana dengan motif bintang
- Judul singkat, deskripsi satu kalimat, satu tombol aksi

---

## 8. Layout Mobile dan Web

### Mobile (< 768px)
- Satu kolom
- Hero card di atas, quick action di bawahnya, lalu daftar transaksi
- Bottom navigation dengan 4–5 menu
- Safe area inset untuk iPhone (`env(safe-area-inset-bottom)`)

### Web (≥ 768px)
- Sidebar kiri, konten di kanan
- Dashboard dalam grid: hero card dan stat di baris atas, chart di tengah, daftar transaksi di bawah atau di kolom kanan
- Tabel untuk data banyak (laporan, daftar anggota) dengan sticky header

### Breakpoint
| Nama | Lebar |
|---|---|
| `sm` | 640px |
| `md` | 768px |
| `lg` | 1024px |
| `xl` | 1280px |

---

## 9. Motion

- Durasi: 150ms (hover), 250ms (transisi komponen), 400ms (page atau sheet)
- Easing: `cubic-bezier(0.2, 0, 0, 1)`
- Animasi angka saldo: count-up singkat saat halaman dibuka (maksimal 600ms)
- Hormati `prefers-reduced-motion`: nonaktifkan animasi jika user memintanya

---

## 10. Ikon

- Gunakan satu set ikon yang konsisten, misalnya Lucide atau Heroicons (outline, stroke 1.75)
- Ukuran: 20px (inline), 24px (navigasi), 40px (kontainer ikon)
- Warna mengikuti teks atau token `navy-900` / `blue-400`

---

## 11. Aksesibilitas

- Rasio kontras teks minimal 4.5:1 (body), 3:1 (teks besar dan ikon)
- Ukuran tap target minimal 44×44px
- Focus state terlihat (ring 2px `blue-400` atau `navy-900`)
- Status tidak hanya ditandai warna. Gunakan ikon atau teks tambahan (misal: "Lunas" dengan ikon centang)
- Semua input punya label

---

## 12. Token Tailwind (Siap Pakai)

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
        navy: {
          900: '#2F4862',
          700: '#243A50',
          500: '#4A6583',
        },
        ice: {
          400: '#7AA3D6',
          200: '#B5CCEB',
          100: '#DDEBFC',
        },
        butter: {
          300: '#F5E38F',
          100: '#FFF7CD',
        },
        surface: '#FFFFFF',
        danger: '#C0524A',
        success: '#3E8E6A',
      },
      fontFamily: {
        sans: ['Manrope', 'system-ui', 'sans-serif'],
        display: ['Fraunces', 'Georgia', 'serif'],
      },
      borderRadius: {
        sm: '10px',
        md: '16px',
        lg: '24px',
        blob: '28px 28px 28px 8px',
      },
      boxShadow: {
        card: '0 4px 16px rgba(47, 72, 98, 0.08)',
        float: '0 12px 32px rgba(47, 72, 98, 0.12)',
      },
      transitionTimingFunction: {
        emphasis: 'cubic-bezier(0.2, 0, 0, 1)',
      },
    },
  },
  plugins: [],
};
```

Contoh pemakaian:
```html
<div class="bg-ice-100 p-5">
  <div class="rounded-blob bg-navy-900 text-surface p-6 shadow-float">
    <p class="text-sm opacity-80">Saldo Kas</p>
    <p class="font-display text-4xl tabular-nums">Rp 1.250.000</p>
    <div class="mt-4 flex gap-2">
      <span class="rounded-full bg-butter-100 text-navy-900 px-3 py-1 text-xs">+ Rp 320.000</span>
      <span class="rounded-full bg-ice-400 text-navy-900 px-3 py-1 text-xs">- Rp 180.000</span>
    </div>
  </div>
</div>
```

Catatan: `font-display` memakai Fraunces. Pastikan font diload dari Google Fonts atau self-host.

---

## 13. Do dan Don't

**Do**
- Pakai `navy-900` untuk teks utama
- Pakai `butter-100` untuk highlight yang bermakna
- Jaga konsistensi radius dan spacing
- Pakai satu motif bintang per layar

**Don't**
- Jangan pakai teks putih di atas `blue-400`
- Jangan pakai butter sebagai background besar seluruh layar
- Jangan pakai shadow hitam atau border tebal
- Jangan campur lebih dari dua font family
- Jangan pakai banyak warna di satu komponen

---
