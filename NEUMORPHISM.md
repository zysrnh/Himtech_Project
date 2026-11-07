# NEUMORPHISM.md — Style Guide (mengikuti 3 gambar referensi, palet Ice & Butter)

Patokan gaya UI neumorphism / soft UI. **Bentuk, bayangan, gradasi, dan susunan komponen mengikuti 3 gambar referensi** (dashboard biru, task manager, dan UI kit lavender). **Warna** diambil dari palet Ice & Butter.

> Saat meminta AI / rekan membuat UI: *"Ikuti NEUMORPHISM.md"*.

Aturan prioritas: **gambar referensi > aturan lain**. Jadi gradasi pada tombol/progress/permukaan boleh dipakai, karena memang ada di referensi.

---

## 1. Ciri Khas Referensi

1. Background dan permukaan hampir satu warna; card sedikit lebih terang dengan **gradasi halus 145°**.
2. **Bayangan ganda**: gelap kanan-bawah, putih kiri-atas. Cahaya selalu dari kiri-atas.
3. **Tombol utama bergradasi** (terang → pekat, 135°), teks putih, bayangan berwarna lembut (gambar 1: "Sign Up", "+", "ON").
4. **Elemen cekung (inset)**: input, track slider, track progress, tombol toggle, parit cincin.
5. **Thumb slider putih bulat** dengan cincin transparan; ada **tooltip** kecil di bawah slider.
6. **Sudut besar**: card 32–44px, tombol pill, ikon dalam lingkaran/kotak membulat.
7. **Nav ikon**: item non-aktif hanya outline tipis, item aktif timbul dengan ikon berwarna (gambar 1).
8. **Cincin progress / donut** timbul dengan angka di tengah, cincin aksen di dalam (gambar 1, 2, 3).
9. **Timeline** bergaris gradasi dengan titik putih dan label pill tahun aktif (gambar 1).
10. **Chart area** bergradasi aksen dengan slider di bawahnya (gambar 1).
11. **Kartu yang sedang di-drag** lebih tinggi, sedikit miring, dengan bayangan lebih besar (gambar 2).
12. **Badge pill** kecil untuk prioritas, **sidebar** panel besar dengan item aktif "tenggelam" (gambar 2).
13. **Media player, vertical slider, grid cincin DATA %, tombol power** (gambar 3).

| State | Efek |
|---|---|
| Default | `raised` |
| Hover | `raised` lebih besar + naik 1px |
| Active / dipilih | `inset` |
| Disabled | tanpa bayangan, opacity .5 |
| Input, track, parit | `inset` |

---

## 2. Design Tokens

### 2.1 Palet (Ice & Butter)

Hex masih perkiraan; ambil nilai pasti dengan color picker sebelum rilis.

| Token | Hex | Pemakaian |
|---|---|---|
| `navy-900` | `#2F4862` | Teks utama, ujung gelap gradasi tombol |
| `navy-500` | `#4A6583` | Teks sekunder, ikon non-aktif, awal gradasi tombol |
| `white` | `#FFFFFF` | Bayangan terang, thumb, teks di tombol gradasi |
| `ice-400` | `#7AA3D6` | Aksen: progress, slider, chart, switch, cincin |
| `ice-200` | `#B5CCEB` | Track kosong cincin, divider, outline nav |
| `ice-100` | `#DDEBFC` | Background layar dan dasar permukaan card |
| `butter-100` | `#FFF7CD` | Status lunas / satu angka penting |
| `danger` | `#C0524A` | Tunggakan, error |

### 2.2 Turunan (dari palet, bukan warna baru)

| Token | Nilai |
|---|---|
| `--neu-bg` | `ice-100` |
| `--neu-surface-1` | campuran 40% `ice-100` + 60% `white` (sisi terang card) |
| `--neu-surface-2` | campuran 85% `ice-100` + 15% `ice-200` (sisi gelap card) |
| `--neu-dark` | `ice-400` alpha 55% |
| `--neu-light` | `white` alpha 95% |
| `--grad-fill` | `linear-gradient(90deg, ice-400, navy-500)` → progress, slider, switch, chart, garis timeline |
| `--grad-primary` | `linear-gradient(135deg, navy-500, navy-900)` → tombol utama (teks putih) |

Catatan kontras: gradasi tombol dibuat navy-500 → navy-900 (bukan ice-400) supaya teks putih tetap terbaca. Teks di atas `ice-400` memakai `navy-900`. Gradasi `--grad-fill` hanya untuk elemen tanpa teks.

### 2.3 Bayangan

```
raised-xs : 3px 3px 6px dark, -3px -3px 6px light
raised-sm : 5px 5px 12px dark, -5px -5px 12px light
raised    : 8px 8px 20px dark, -8px -8px 20px light
raised-lg : 14px 14px 32px dark, -14px -14px 32px light
inset-sm  : inset 3px 3px 6px dark, inset -3px -3px 6px light
inset     : inset 6px 6px 12px dark, inset -6px -6px 12px light
glow-btn  : 6px 8px 18px rgba(47,72,98,.28), -4px -4px 12px light   (tombol gradasi)
```

### 2.4 Radius, spasi, tipografi

| Token | Nilai |
|---|---|
| `radius-sm` | 14px (tooltip, chip) |
| `radius-md` | 22px (ikon kotak, item list, tombol kotak) |
| `radius-lg` | 32px (card) |
| `radius-xl` | 44px (panel besar, sidebar) |
| `radius-pill` | 999px |
| Spasi | kelipatan 4; padding card 24–32px; jarak antar komponen 16–32px |

Font: **Outfit** (angka besar, judul) dan **Plus Jakarta Sans** (body, keterangan `navy-500`). Angka uang `tabular-nums`. Ukuran: caption 12–13, body 14–15, judul card 18–20, angka statistik 28–48.

---

## 3. CSS Murni

Simpan sebagai `neumorphism.css`.

```css
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap');

:root {
  /* Palet Ice & Butter */
  --navy-900: #2F4862; --navy-500: #4A6583; --white: #FFFFFF;
  --ice-400: #7AA3D6;  --ice-200: #B5CCEB; --ice-100: #DDEBFC;
  --butter-100: #FFF7CD; --danger: #C0524A;

  /* Turunan neumorphism */
  --neu-bg: var(--ice-100);
  --neu-surface-1: color-mix(in srgb, var(--ice-100) 40%, #fff);
  --neu-surface-2: color-mix(in srgb, var(--ice-100) 85%, var(--ice-200));
  --neu-surface: linear-gradient(145deg, var(--neu-surface-1), var(--neu-surface-2));
  --neu-dark: rgba(122, 163, 214, .55);
  --neu-light: rgba(255, 255, 255, .95);
  --grad-fill: linear-gradient(90deg, var(--ice-400), var(--navy-500));
  --grad-primary: linear-gradient(135deg, var(--navy-500), var(--navy-900));

  --raised-xs: 3px 3px 6px var(--neu-dark), -3px -3px 6px var(--neu-light);
  --raised-sm: 5px 5px 12px var(--neu-dark), -5px -5px 12px var(--neu-light);
  --raised:    8px 8px 20px var(--neu-dark), -8px -8px 20px var(--neu-light);
  --raised-lg: 14px 14px 32px var(--neu-dark), -14px -14px 32px var(--neu-light);
  --inset-sm:  inset 3px 3px 6px var(--neu-dark), inset -3px -3px 6px var(--neu-light);
  --inset:     inset 6px 6px 12px var(--neu-dark), inset -6px -6px 12px var(--neu-light);
  --glow-btn:  6px 8px 18px rgba(47, 72, 98, .28), -4px -4px 12px var(--neu-light);

  --radius-sm: 14px; --radius-md: 22px; --radius-lg: 32px; --radius-xl: 44px;
  --ease: cubic-bezier(.2, 0, 0, 1);
}

* { box-sizing: border-box; }
body {
  margin: 0; background: var(--neu-bg); color: var(--navy-900);
  font: 400 14px/1.5 'Plus Jakarta Sans', system-ui, sans-serif;
}
h1, h2, h3, .neu-amount { font-family: 'Outfit', system-ui, sans-serif; font-weight: 600; font-variant-numeric: tabular-nums; }
.neu-muted { color: var(--navy-500); }

/* ===== Card / Panel ===== */
.neu-card { background: var(--neu-surface); border-radius: var(--radius-lg); box-shadow: var(--raised); padding: 28px; }
.neu-card--panel { border-radius: var(--radius-xl); box-shadow: var(--raised-lg); padding: 32px; }
.neu-card--inset { background: var(--neu-bg); box-shadow: var(--inset); }
.neu-card--drag { transform: rotate(-1.5deg) translateY(-6px); box-shadow: var(--raised-lg); }   /* gambar 2: kartu di-drag */

/* ===== Button ===== */
.neu-btn {
  display: inline-flex; align-items: center; gap: 12px; padding: 14px 28px; border: 0; border-radius: 999px;
  background: var(--neu-surface); color: var(--navy-500); font: 600 14px 'Plus Jakarta Sans', sans-serif;
  box-shadow: var(--raised-sm); cursor: pointer;
  transition: box-shadow 200ms var(--ease), transform 200ms var(--ease);
}
.neu-btn:hover { box-shadow: var(--raised); transform: translateY(-1px); }
.neu-btn:active, .neu-btn.is-active { box-shadow: var(--inset-sm); transform: none; }
.neu-btn:disabled { box-shadow: none; opacity: .5; cursor: not-allowed; }
.neu-btn:focus-visible { outline: 2px solid var(--ice-400); outline-offset: 3px; }

.neu-btn--primary { background: var(--grad-primary); color: var(--white); box-shadow: var(--glow-btn); padding: 16px 40px; font-size: 16px; }
.neu-btn--primary:active { box-shadow: inset 3px 3px 8px rgba(0, 0, 0, .3); }

/* Tombol "+" kotak membulat berisi gradasi (Add Friend) */
.neu-icon-fill { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 16px; background: var(--grad-primary); color: var(--white); box-shadow: var(--glow-btn); }

.neu-icon-btn { width: 48px; height: 48px; padding: 0; justify-content: center; border-radius: 50%; }

/* ===== Input ===== */
.neu-input {
  width: 100%; height: 48px; padding: 0 22px; border: 0; border-radius: 999px;
  background: var(--neu-bg); color: var(--navy-900); font: 400 14px 'Plus Jakarta Sans', sans-serif; box-shadow: var(--inset);
}
.neu-input::placeholder { color: var(--navy-500); opacity: .7; }
.neu-input:focus { outline: 2px solid var(--ice-400); outline-offset: 2px; }
.neu-input--line { border-radius: 0; box-shadow: none; background: transparent; border-bottom: 1.5px solid var(--ice-200); padding-inline: 0; } /* login gambar 1 */
.neu-label { display: block; margin-bottom: 8px; font-size: 13px; color: var(--navy-500); }

/* ===== Select (Select Category) ===== */
.neu-select { appearance: none; height: 48px; padding: 0 48px 0 24px; border: 0; border-radius: 999px; background: var(--neu-surface); color: var(--navy-500); box-shadow: var(--raised-sm); }

/* ===== Toggle ON/OFF ===== */
.neu-switch { position: relative; display: inline-block; width: 88px; height: 44px; }
.neu-switch input { opacity: 0; width: 0; height: 0; }
.neu-switch__track { position: absolute; inset: 0; border-radius: 999px; background: var(--neu-bg); box-shadow: var(--inset); transition: background 250ms var(--ease); }
.neu-switch__track::after {
  content: ""; position: absolute; top: 5px; left: 5px; width: 34px; height: 34px; border-radius: 50%;
  background: var(--neu-surface); box-shadow: var(--raised-xs); transition: transform 250ms var(--ease);
}
.neu-switch input:checked + .neu-switch__track { background: var(--grad-primary); }
.neu-switch input:checked + .neu-switch__track::after { transform: translateX(44px); }
.neu-switch input:focus-visible + .neu-switch__track { outline: 2px solid var(--ice-400); outline-offset: 3px; }

/* ===== Slider (thumb putih + cincin) ===== */
.neu-range {
  -webkit-appearance: none; appearance: none; width: 100%; height: 10px; border-radius: 999px; --val: 50%;
  background: var(--grad-fill) no-repeat var(--neu-bg); background-size: var(--val) 100%; box-shadow: var(--inset-sm);
}
.neu-range::-webkit-slider-thumb {
  -webkit-appearance: none; width: 22px; height: 22px; border-radius: 50%; border: 0; cursor: pointer;
  background: var(--white); box-shadow: 0 2px 8px var(--neu-dark), 0 0 0 5px rgba(122, 163, 214, .25);
}
.neu-range::-moz-range-thumb { width: 22px; height: 22px; border-radius: 50%; border: 0; background: var(--white); box-shadow: 0 2px 8px var(--neu-dark); }
/* JS: range.style.setProperty('--val', (range.value / range.max * 100) + '%') */

.neu-tooltip { display: inline-block; padding: 8px 14px; border-radius: var(--radius-sm); background: var(--neu-surface); box-shadow: var(--raised-sm); font-size: 11px; color: var(--navy-500); }

/* ===== Progress linear ===== */
.neu-progress { height: 10px; border-radius: 999px; background: var(--neu-bg); box-shadow: var(--inset-sm); overflow: hidden; }
.neu-progress__bar { height: 100%; border-radius: 999px; background: var(--grad-fill); }
/* Progress unduh: <div class="neu-card"> ikon + "Downloading" + .neu-progress + "40%" </div> */

/* ===== Cincin progress / donut ===== */
.neu-ring {
  --p: 75; --size: 120px; --stroke: 9px;
  position: relative; width: var(--size); height: var(--size); border-radius: 50%;
  background: var(--neu-surface); box-shadow: var(--raised); display: grid; place-items: center;
}
.neu-ring::before {
  content: ""; position: absolute; inset: 8px; border-radius: 50%;
  background: conic-gradient(var(--ice-400), var(--navy-500) calc(var(--p) * 1%), var(--ice-200) 0);
  -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - var(--stroke)), #000 calc(100% - var(--stroke) + 1px));
          mask: radial-gradient(farthest-side, transparent calc(100% - var(--stroke)), #000 calc(100% - var(--stroke) + 1px));
}
.neu-ring__label { position: relative; font: 600 22px 'Outfit', sans-serif; text-align: center; line-height: 1.1; }
.neu-ring--lg { --size: 160px; --stroke: 12px; }           /* donut besar dengan inti timbul (gambar 1) */
.neu-ring--lg .neu-ring__label { font-size: 28px; }
/* <div class="neu-ring" style="--p:63"><span class="neu-ring__label">63%</span></div> */

/* ===== Nav ikon ===== */
.neu-nav { display: flex; gap: 16px; }
.neu-nav__item {
  width: 64px; height: 64px; display: grid; place-items: center; border-radius: var(--radius-md);
  border: 1.5px solid var(--ice-200); background: transparent; color: var(--navy-500); cursor: pointer;
  transition: all 200ms var(--ease);
}
.neu-nav__item:hover { box-shadow: var(--raised-sm); }
.neu-nav__item.is-active { background: var(--neu-surface); border-color: transparent; box-shadow: var(--raised-sm); color: var(--navy-900); }

/* Menu sidebar (gambar 2) */
.neu-menu__item { padding: 10px 14px; border-radius: var(--radius-sm); color: var(--navy-500); cursor: pointer; }
.neu-menu__item.is-active { color: var(--navy-900); font-weight: 600; box-shadow: var(--inset-sm); }

/* ===== Timeline (gambar 1) ===== */
.neu-timeline { position: relative; display: flex; justify-content: space-between; align-items: center; padding: 8px 24px 40px; }
.neu-timeline::before { content: ""; position: absolute; left: 24px; right: 24px; top: 19px; height: 3px; border-radius: 3px; background: var(--grad-fill); }
.neu-timeline__dot { position: relative; width: 22px; height: 22px; border-radius: 50%; background: var(--white); box-shadow: 0 2px 8px var(--neu-dark), 0 0 0 5px rgba(122, 163, 214, .25); }
.neu-timeline__year { position: absolute; top: 34px; left: 50%; transform: translateX(-50%); padding: 6px 16px; border-radius: 12px; background: var(--neu-surface); box-shadow: var(--raised-xs); font-size: 12px; color: var(--navy-500); }
.neu-timeline__dot.is-active .neu-timeline__year { background: var(--grad-primary); color: var(--white); }

/* ===== Badge / status ===== */
.neu-badge { display: inline-block; padding: 6px 16px; border-radius: 999px; font: 600 12px/1 'Plus Jakarta Sans', sans-serif; background: var(--neu-bg); color: var(--navy-500); box-shadow: var(--inset-sm); }
.neu-badge--accent { color: var(--navy-900); background: var(--ice-200); box-shadow: none; }      /* "Important" gambar 2 */
.neu-status--paid { padding: 4px 14px; border-radius: 999px; background: var(--butter-100); color: var(--navy-900); font-weight: 600; }
.neu-status--due { color: var(--danger); font-weight: 600; }

/* ===== Media player & vertical slider (gambar 3) ===== */
.neu-player { display: flex; align-items: center; gap: 16px; padding: 14px 28px; border-radius: 999px; background: var(--neu-surface); box-shadow: var(--raised); color: var(--ice-400); }
.neu-vslider { width: 56px; height: 220px; padding: 16px 0; border-radius: 999px; background: var(--neu-surface); box-shadow: var(--raised-sm); display: flex; justify-content: center; }
.neu-vslider__track { position: relative; width: 6px; height: 100%; border-radius: 6px; background: var(--neu-bg); box-shadow: var(--inset-sm); }
.neu-vslider__fill { position: absolute; bottom: 0; width: 100%; border-radius: 6px; background: linear-gradient(0deg, var(--ice-400), var(--navy-500)); }
.neu-power { width: 96px; height: 96px; border-radius: 50%; display: grid; place-items: center; background: var(--neu-surface); box-shadow: var(--raised); color: var(--ice-400); }
.neu-power:active { box-shadow: var(--inset); }

/* ===== Chart ===== */
.neu-chart-line { stroke: var(--ice-400); stroke-width: 3; fill: none; stroke-linecap: round; }
.neu-chart-area { fill: url(#areaGrad); }  /* <linearGradient id="areaGrad" x2="0" y2="1"><stop offset="0" stop-color="#7AA3D6"/><stop offset="1" stop-color="#7AA3D6" stop-opacity="0"/></linearGradient> */
.neu-chart-grid { stroke: var(--ice-200); stroke-width: 1; }

@media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
```

Contoh card login (gambar 1):

```html
<section class="neu-card" style="max-width:340px; position:relative; padding-bottom:56px">
  <h2>Login</h2>
  <input class="neu-input neu-input--line" placeholder="username@mail.com">
  <input class="neu-input neu-input--line" type="password" placeholder="••••••••">
  <a class="neu-muted" href="#">Forget Password?</a>
  <button class="neu-btn neu-btn--primary" style="position:absolute; left:50%; bottom:-24px; transform:translateX(-50%)">Sign Up</button>
</section>
```

---

## 4. Tailwind CSS (v3)

```js
// tailwind.config.js
/** @type {import('tailwindcss').Config} */
const plugin = require('tailwindcss/plugin');

module.exports = {
  content: ['./resources/**/*.{blade.php,js,vue,jsx,tsx}', './**/*.html'],
  theme: {
    extend: {
      colors: {
        navy: { 900: '#2F4862', 500: '#4A6583' },
        ice: { 400: '#7AA3D6', 200: '#B5CCEB', 100: '#DDEBFC' },
        butter: { 100: '#FFF7CD' },
        danger: '#C0524A',
      },
      fontFamily: { sans: ['Plus Jakarta Sans', 'system-ui', 'sans-serif'], display: ['Outfit', 'system-ui', 'sans-serif'] },
      borderRadius: { sm: '14px', md: '22px', lg: '32px', xl: '44px' },
      boxShadow: {
        'neu-xs': 'var(--raised-xs)', 'neu-sm': 'var(--raised-sm)', neu: 'var(--raised)', 'neu-lg': 'var(--raised-lg)',
        'neu-in-sm': 'var(--inset-sm)', 'neu-in': 'var(--inset)', 'neu-glow': 'var(--glow-btn)',
      },
    },
  },
  plugins: [
    plugin(({ addComponents }) => addComponents({
      '.neu-surface': { background: 'var(--neu-surface)' },
      '.neu-grad-fill': { background: 'var(--grad-fill)' },
      '.neu-grad-primary': { background: 'var(--grad-primary)' },
    })),
  ],
};
```

Pastikan bagian `:root` dari `neumorphism.css` dimuat agar variabel tersedia.

| Komponen | Class |
|---|---|
| Halaman | `bg-ice-100 text-navy-900 font-sans min-h-screen` |
| Card | `neu-surface rounded-lg shadow-neu p-7` |
| Panel besar | `neu-surface rounded-xl shadow-neu-lg p-8` |
| Area cekung | `bg-ice-100 rounded-lg shadow-neu-in p-6` |
| Tombol biasa | `neu-surface rounded-full px-7 py-3.5 font-semibold text-navy-500 shadow-neu-sm transition hover:shadow-neu hover:-translate-y-px active:shadow-neu-in-sm active:translate-y-0` |
| Tombol utama | `neu-grad-primary text-white rounded-full px-10 py-4 text-base font-semibold shadow-neu-glow active:shadow-neu-in-sm` |
| Ikon "+" gradasi | `neu-grad-primary grid place-items-center w-11 h-11 rounded-2xl text-white shadow-neu-glow` |
| Tombol ikon bulat | `neu-surface grid place-items-center w-12 h-12 rounded-full shadow-neu-sm active:shadow-neu-in-sm` |
| Input | `w-full h-12 px-5 rounded-full bg-ice-100 shadow-neu-in placeholder:text-navy-500/70 outline-none focus:ring-2 focus:ring-ice-400` |
| Select | `appearance-none neu-surface rounded-full shadow-neu-sm h-12 pl-6 pr-12 text-navy-500` |
| Track progress | `h-2.5 rounded-full bg-ice-100 shadow-neu-in-sm overflow-hidden` |
| Isi progress | `h-full rounded-full neu-grad-fill` |
| Badge cekung | `inline-block rounded-full bg-ice-100 px-4 py-1.5 text-xs font-semibold text-navy-500 shadow-neu-in-sm` |
| Badge aksen | `inline-block rounded-full bg-ice-200 px-4 py-1.5 text-xs font-semibold text-navy-900` |
| Status lunas | `rounded-full bg-butter-100 px-3.5 py-1 font-semibold text-navy-900` |
| Nav aktif | `neu-surface grid place-items-center w-16 h-16 rounded-md shadow-neu-sm text-navy-900` |
| Nav non-aktif | `grid place-items-center w-16 h-16 rounded-md border border-ice-200 text-navy-500 hover:shadow-neu-sm` |
| Tooltip | `neu-surface rounded-sm shadow-neu-sm px-3.5 py-2 text-[11px] text-navy-500` |
| Kartu di-drag | `neu-surface rounded-lg shadow-neu-lg p-5 -rotate-1 -translate-y-1.5` |

---

## 5. Bootstrap 5.3

Muat setelah `bootstrap.css` dan `neumorphism.css`.

```css
/* bootstrap-neu.css */
:root {
  --bs-body-bg: var(--ice-100); --bs-body-color: var(--navy-900);
  --bs-body-font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
  --bs-secondary-color: var(--navy-500);
  --bs-primary: var(--navy-900); --bs-primary-rgb: 47, 72, 98;
  --bs-danger: var(--danger); --bs-danger-rgb: 192, 82, 74;
  --bs-border-color: transparent;
  --bs-border-radius: 22px; --bs-border-radius-lg: 32px; --bs-border-radius-pill: 999px;
}

.card { background: var(--neu-surface); border: 0; border-radius: 32px; box-shadow: var(--raised); }

.btn {
  padding: .85rem 1.75rem; border: 0; border-radius: 999px; font-weight: 600;
  background: var(--neu-surface); color: var(--navy-500); box-shadow: var(--raised-sm);
  transition: box-shadow 200ms, transform 200ms;
}
.btn:hover { box-shadow: var(--raised); transform: translateY(-1px); color: var(--navy-500); }
.btn:active, .btn.active { box-shadow: var(--inset-sm) !important; transform: none; }
.btn-primary { background: var(--grad-primary); color: #fff; box-shadow: var(--glow-btn); }
.btn-primary:hover, .btn-primary:active { background: var(--grad-primary); color: #fff; }
.btn:disabled, .btn.disabled { box-shadow: none; opacity: .5; }
.btn:focus-visible { outline: 2px solid var(--ice-400); outline-offset: 3px; }

.form-label { font-size: 13px; color: var(--navy-500); }
.form-control, .form-select {
  height: 48px; padding: 0 1.4rem; border: 0; border-radius: 999px;
  background-color: var(--ice-100); color: var(--navy-900); box-shadow: var(--inset);
}
.form-control:focus, .form-select:focus { background-color: var(--ice-100); color: var(--navy-900); box-shadow: var(--inset), 0 0 0 2px var(--ice-400); }
.form-control::placeholder { color: var(--navy-500); opacity: .7; }
.form-select { background-color: var(--ice-100); }

.form-switch .form-check-input {
  width: 5.5rem; height: 2.75rem; border: 0; background-color: var(--ice-100); box-shadow: var(--inset);
  background-position: left .35rem center;
  --bs-form-switch-bg: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23ffffff'/%3e%3c/svg%3e");
}
.form-switch .form-check-input:checked { background-image: var(--bs-form-switch-bg), var(--grad-primary); background-position: right .35rem center, 0 0; }
.form-check-input:focus { border: 0; box-shadow: var(--inset), 0 0 0 2px var(--ice-400); }

.form-range::-webkit-slider-runnable-track { height: 10px; border-radius: 999px; background: var(--ice-100); box-shadow: var(--inset-sm); }
.form-range::-webkit-slider-thumb { width: 22px; height: 22px; margin-top: -6px; background: #fff; border: 0; box-shadow: 0 2px 8px var(--neu-dark), 0 0 0 5px rgba(122, 163, 214, .25); }
.form-range::-moz-range-track { height: 10px; border-radius: 999px; background: var(--ice-100); box-shadow: var(--inset-sm); }
.form-range::-moz-range-thumb { width: 22px; height: 22px; background: #fff; border: 0; box-shadow: 0 2px 8px var(--neu-dark); }

.progress { height: 10px; border-radius: 999px; background: var(--ice-100); box-shadow: var(--inset-sm); }
.progress-bar { border-radius: 999px; background: var(--grad-fill); }

.nav-pills .nav-link { color: var(--navy-500); border: 1.5px solid var(--ice-200); border-radius: 22px; }
.nav-pills .nav-link.active { color: var(--navy-900); background: var(--neu-surface); border-color: transparent; box-shadow: var(--raised-sm); }

.dropdown-menu, .modal-content { background: var(--neu-surface); border: 0; border-radius: 22px; box-shadow: var(--raised); }
.dropdown-item { border-radius: 14px; color: var(--navy-900); }
.dropdown-item:hover, .dropdown-item.active { background: var(--ice-100); color: var(--navy-900); box-shadow: var(--inset-sm); }

.badge { padding: .5rem 1rem; border-radius: 999px; font-weight: 600; background: var(--ice-100) !important; color: var(--navy-500); box-shadow: var(--inset-sm); }
.badge.badge-important { background: var(--ice-200) !important; color: var(--navy-900); box-shadow: none; }
.badge.badge-paid { background: var(--butter-100) !important; color: var(--navy-900); box-shadow: none; }
.text-danger { color: var(--danger) !important; }
```

---

## 6. Peta Referensi → Class

| Elemen di gambar | Gambar | Class |
|---|---|---|
| Card login + tombol "Sign Up" menimpa tepi bawah | 1 | `.neu-card` + `.neu-input--line` + `.neu-btn--primary` (posisi absolute) |
| Nav Home/Calendar/Notification/Setting | 1 | `.neu-nav` + `.neu-nav__item(.is-active)` |
| Line chart "Last 7 days" | 1 | `.neu-card` + `.neu-chart-line` |
| Toggle ON | 1 | `.neu-switch` |
| Slider 71% + tooltip | 1 | `.neu-range` + `.neu-tooltip` |
| Add Friend / Share / Download | 1 | `.neu-btn` (+ `.neu-icon-fill` untuk "+") |
| Select Category | 1 | `.neu-select` |
| Donut 75% + legend | 1 | `.neu-card` + `.neu-ring--lg` |
| Area chart + slider bawah | 1 | `.neu-card` + `.neu-chart-area` + `.neu-range` |
| Timeline 2019–2022 | 1 | `.neu-timeline` |
| Sidebar "Tasks Menu" | 2 | `.neu-card--panel` + `.neu-menu__item` |
| Banner "Welcome to Edit Mode" | 2 | `.neu-card` |
| Task card + badge "Important" | 2 | `.neu-card` (padding 20px) + `.neu-badge--accent` |
| Task card yang di-drag | 2 | `.neu-card--drag` |
| Profil + ring 75% / 25% | 2 | `.neu-card--panel` + `.neu-ring` |
| Search, player, tombol media | 3 | `.neu-input`, `.neu-player`, `.neu-icon-btn` |
| Kartu Downloading 40% | 3 | `.neu-card` + `.neu-progress` |
| Subscribe email | 3 | `.neu-card` + `.neu-input` + `.neu-btn` |
| Ikon bulat (kamera, musik, lonceng, dst.) | 3 | `.neu-icon-btn` |
| Vertical slider | 3 | `.neu-vslider` |
| Grid cincin DATA % | 3 | `.neu-ring` (`--p`) |
| Tombol power | 3 | `.neu-power` |

---

## 7. Responsive (Mobile-First)

Tulis gaya dasar untuk layar kecil, lalu tambah di `min-width`. Semua komponen di bagian 3 tetap dipakai; bagian ini hanya menyesuaikan ukuran, tata letak, dan perilaku sentuh.

### 7.1 Breakpoint

| Nama | Lebar | Perangkat | Tailwind | Bootstrap |
|---|---|---|---|---|
| base | < 768px | HP | (tanpa prefix) | (tanpa infix) |
| `md` | ≥ 768px | Tablet | `md:` | `md` (768px) |
| `lg` | ≥ 1024px | Desktop | `lg:` | `lg` bernilai 992px (bukan 1024px); pakai `xl` 1200px bila perlu selisih ini dihindari |

### 7.2 Perilaku komponen per breakpoint

| Komponen | HP (< 768) | Tablet (≥ 768) | Desktop (≥ 1024) |
|---|---|---|---|
| Layout | 1 kolom, padding 16px | grid widget 2 kolom, padding 24px | 3 kolom: sidebar 260px / konten / panel 300px |
| Navigasi | **Bottom nav** melayang, ikon + label | Bottom nav | Sidebar kiri (bottom nav disembunyikan) |
| Card | radius 24px, padding 20px | radius 32px, padding 28px | sama dengan tablet |
| Panel / sidebar | radius 28px | radius 44px | radius 44px |
| Bayangan | blur lebih kecil (14px) | blur penuh (20px) | blur penuh |
| Cincin progress | `clamp(88px, 26vw, 120px)` | 120px | 120px |
| Timeline | scroll horizontal | tampil penuh | tampil penuh |
| Input & select | tinggi 48px, font **16px** | 48px, 14px | 48px, 14px |
| Hover | tidak ada efek lift (layar sentuh) | ikut perangkat | lift + bayangan besar |

### 7.3 `neumorphism.responsive.css`

Muat **setelah** `neumorphism.css`.

```css
/* ===== 1. Token mobile-first ===== */
:root {
  --radius-md: 18px; --radius-lg: 24px; --radius-xl: 28px;
  --card-pad: 20px; --gap: 16px;
  --raised-sm: 4px 4px 10px var(--neu-dark), -4px -4px 10px var(--neu-light);
  --raised:    6px 6px 14px var(--neu-dark), -6px -6px 14px var(--neu-light);
  --raised-lg: 10px 10px 22px var(--neu-dark), -10px -10px 22px var(--neu-light);
  --inset:     inset 4px 4px 9px var(--neu-dark), inset -4px -4px 9px var(--neu-light);
}
@media (min-width: 768px) {
  :root {
    --radius-md: 22px; --radius-lg: 32px; --radius-xl: 44px;
    --card-pad: 28px; --gap: 24px;
    --raised-sm: 5px 5px 12px var(--neu-dark), -5px -5px 12px var(--neu-light);
    --raised:    8px 8px 20px var(--neu-dark), -8px -8px 20px var(--neu-light);
    --raised-lg: 14px 14px 32px var(--neu-dark), -14px -14px 32px var(--neu-light);
    --inset:     inset 6px 6px 12px var(--neu-dark), inset -6px -6px 12px var(--neu-light);
  }
}

/* ===== 2. Ukuran fluid ===== */
.neu-card { padding: var(--card-pad); }
.neu-card--panel { padding: calc(var(--card-pad) + 4px); }
.neu-amount { font-size: clamp(28px, 6vw, 48px); }
.neu-ring { --size: clamp(88px, 26vw, 120px); }
.neu-ring--lg { --size: clamp(120px, 36vw, 160px); }
.neu-ring__label { font-size: calc(var(--size) * .19); }
.neu-btn { min-height: 48px; }
@media (max-width: 767px) {
  .neu-btn--primary { padding: 14px 32px; font-size: 15px; }
  .neu-input, .neu-select, .form-control, .form-select { font-size: 16px; } /* cegah auto-zoom iOS */
}

/* ===== 3. Layout app ===== */
.neu-app {
  display: grid; gap: var(--gap); align-items: start;
  padding: 16px 16px calc(96px + env(safe-area-inset-bottom));
}
.neu-grid {
  display: grid; gap: var(--gap);
  grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
}
.neu-sidebar { display: none; }

@media (min-width: 768px) { .neu-app { padding: 24px 24px calc(104px + env(safe-area-inset-bottom)); } }
@media (min-width: 1024px) {
  .neu-app { grid-template-columns: 260px minmax(0, 1fr) 300px; padding: 32px; }
  .neu-sidebar { display: block; position: sticky; top: 32px; }
  .neu-bottomnav { display: none !important; }
}

/* ===== 4. Bottom nav (HP & tablet) ===== */
.neu-bottomnav {
  position: fixed; z-index: 20; left: 12px; right: 12px; bottom: calc(12px + env(safe-area-inset-bottom));
  display: flex; justify-content: space-around; gap: 4px; padding: 8px;
  border-radius: 28px; background: var(--neu-surface); box-shadow: var(--raised);
}
.neu-bottomnav .neu-nav__item { flex: 1; width: auto; height: 56px; border-color: transparent; }
.neu-bottomnav .neu-nav__item.is-active { box-shadow: var(--inset-sm); }

/* ===== 5. Scroll horizontal tanpa memotong bayangan ===== */
.neu-scroll {
  overflow-x: auto; padding: 16px; margin: -16px;            /* ruang untuk bayangan */
  scroll-snap-type: x proximity; -webkit-overflow-scrolling: touch;
}
.neu-scroll > * { scroll-snap-align: start; flex-shrink: 0; }
.neu-scroll .neu-timeline { min-width: 480px; }

/* ===== 6. Perangkat sentuh: tanpa hover lengket ===== */
@media (hover: none) {
  .neu-btn:hover { transform: none; box-shadow: var(--raised-sm); }
  .neu-btn--primary:hover { box-shadow: var(--glow-btn); }
  .neu-nav__item:not(.is-active):hover { box-shadow: none; }
  .neu-btn:active { transform: scale(.97); }
}
```

### 7.4 Contoh halaman lengkap (responsive + interaktif)

```html
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Dashboard</title>
  <link rel="stylesheet" href="neumorphism.css">
  <link rel="stylesheet" href="neumorphism.responsive.css">
  <link rel="stylesheet" href="neumorphism.interactive.css">
</head>
<body>
  <div class="neu-app">
    <aside class="neu-sidebar neu-card neu-card--panel" data-nav>
      <div class="neu-menu__item is-active" role="button" tabindex="0">Overview</div>
      <div class="neu-menu__item" role="button" tabindex="0">Tugas</div>
      <div class="neu-menu__item" role="button" tabindex="0">Kalender</div>
    </aside>

    <main>
      <div class="neu-grid">
        <section class="neu-card">
          <h3>Selesai</h3>
          <div class="neu-ring" data-value="75"><span class="neu-ring__label">0%</span></div>
        </section>

        <section class="neu-card" data-range>
          <h3>Volume <span data-range-out>50%</span></h3>
          <input class="neu-range" type="range" min="0" max="100" value="50" aria-label="Volume">
        </section>

        <section class="neu-card">
          <h3>Notifikasi</h3>
          <label class="neu-switch">
            <input type="checkbox" checked aria-label="Notifikasi">
            <span class="neu-switch__track"></span>
          </label>
        </section>
      </div>
    </main>

    <aside class="neu-card neu-card--panel">
      <h3>Halo!</h3>
      <p class="neu-muted">Ringkasan minggu ini</p>
    </aside>
  </div>

  <nav class="neu-bottomnav" data-nav aria-label="Navigasi utama">
    <button class="neu-nav__item is-active" aria-current="page" aria-label="Beranda">⌂</button>
    <button class="neu-nav__item" aria-label="Tugas">✓</button>
    <button class="neu-nav__item" aria-label="Kalender">▦</button>
    <button class="neu-nav__item" aria-label="Pengaturan">⚙</button>
  </nav>

  <script src="neumorphism.js" defer></script>
</body>
</html>
```

### 7.5 Tailwind

```html
<!-- Shell -->
<div class="grid items-start gap-4 p-4 pb-24 md:gap-6 md:p-6 md:pb-28
            lg:grid-cols-[260px_minmax(0,1fr)_300px] lg:gap-6 lg:p-8">
  <aside class="hidden lg:block sticky top-8 neu-surface rounded-xl shadow-neu-lg p-8">…</aside>

  <main class="grid gap-4 md:gap-6 [grid-template-columns:repeat(auto-fit,minmax(min(100%,260px),1fr))]">
    <!-- Card: radius & bayangan mengecil di HP -->
    <section class="neu-surface rounded-[24px] shadow-neu-sm p-5 md:rounded-lg md:shadow-neu md:p-7">…</section>
  </main>

  <aside class="neu-surface rounded-[28px] shadow-neu p-6 md:rounded-xl md:shadow-neu-lg md:p-8">…</aside>
</div>

<!-- Bottom nav -->
<nav class="fixed inset-x-3 bottom-3 z-20 flex gap-1 rounded-[28px] neu-surface p-2 shadow-neu lg:hidden"
     style="bottom:calc(.75rem + env(safe-area-inset-bottom))">
  <button class="flex-1 h-14 grid place-items-center rounded-md text-navy-500 aria-[current=page]:text-navy-900 aria-[current=page]:shadow-neu-in-sm" aria-current="page">⌂</button>
  …
</nav>

<!-- Input: 16px di HP, 14px mulai md -->
<input class="w-full h-12 px-5 rounded-full bg-ice-100 shadow-neu-in text-base md:text-sm outline-none focus:ring-2 focus:ring-ice-400">
```

Tambahkan di `tailwind.config.js` agar hover tidak lengket di layar sentuh:

```js
module.exports = {
  future: { hoverOnlyWhenSupported: true },
  // …
};
```

Container scroll horizontal tanpa memotong bayangan: `overflow-x-auto p-4 -m-4 snap-x`.

### 7.6 Bootstrap

```html
<div class="container-fluid p-3 p-md-4 p-xl-5 pb-5 mb-5 mb-xl-0">
  <div class="row g-3 g-md-4">
    <aside class="col-xl-3 d-none d-xl-block"><div class="card p-4">…</div></aside>
    <main class="col-12 col-xl-6">
      <div class="row g-3 g-md-4">
        <section class="col-12 col-md-6"><div class="card p-3 p-md-4">…</div></section>
        <section class="col-12 col-md-6"><div class="card p-3 p-md-4">…</div></section>
      </div>
    </main>
    <aside class="col-12 col-xl-3"><div class="card p-4">…</div></aside>
  </div>
</div>

<nav class="neu-bottomnav d-xl-none" data-nav>…</nav>
```

Karena `lg` Bootstrap = 992px, pakai `xl` (1200px) untuk layout 3 kolom agar kolom tengah tidak terlalu sempit. Ubah media query `1024px` di `neumorphism.responsive.css` menjadi `1200px` bila memakai Bootstrap.

### 7.7 Catatan penting neumorphism di mobile

1. **Bayangan terpotong.** `overflow: hidden/auto` memotong bayangan di tepi. Beri `padding` + `margin` negatif (lihat `.neu-scroll`) atau jangan pakai overflow pada pembungkus card.
2. **Performa.** Bayangan besar pada banyak elemen membuat scroll berat di HP murah. Batasi blur ≤ 22px di mobile (sudah di token) dan hindari menganimasikan `box-shadow` pada daftar panjang; animasikan `transform`/`opacity`.
3. **Sentuhan.** Target minimal 44×44px (komponen di sini 48px). Beri jarak ≥ 8px antar target.
4. **Zoom iOS.** Font input kurang dari 16px memicu zoom otomatis.
5. **Area aman.** Gunakan `viewport-fit=cover` dan `env(safe-area-inset-*)` untuk bottom nav.
6. **Teks panjang.** Kartu tidak boleh meluber di 360px: pakai `min-width: 0` pada anak grid dan `overflow-wrap: anywhere` pada teks bebas.

---

## 8. Interaktif

### 8.1 Peta state dan atribut

| Interaksi | Visual | Atribut / pemicu |
|---|---|---|
| Hover (hanya perangkat dengan kursor) | raised membesar, naik 1px | `:hover` di dalam `@media (hover: hover)` |
| Ditekan | inset | `:active` |
| Terpilih / aktif (nav, tab, menu) | inset + ikon `navy-900` | `.is-active` + `aria-current` atau `aria-selected` |
| Toggle tombol | inset saat ON | `aria-pressed="true"` |
| Switch | track bergradasi, thumb bergeser | `input:checked` |
| Fokus keyboard | outline 2px `ice-400` | `:focus-visible` |
| Memuat | skeleton cekung + shimmer | `.neu-skeleton` |
| Disabled | flat, opacity .5 | `disabled` / `aria-disabled` |
| Cincin & angka | terisi dan count-up saat terlihat | `data-value` + IntersectionObserver |
| Kartu di-drag | miring, bayangan besar | `.neu-card--drag` |

### 8.2 `neumorphism.interactive.css`

```css
/* ===== Toggle button ===== */
.neu-btn[aria-pressed="true"] { box-shadow: var(--inset-sm); color: var(--navy-900); }

/* ===== Nav aktif via aria-current ===== */
.neu-nav__item[aria-current="page"] { background: var(--neu-surface); border-color: transparent; box-shadow: var(--raised-sm); color: var(--navy-900); }
.neu-bottomnav .neu-nav__item[aria-current="page"] { box-shadow: var(--inset-sm); }
.neu-menu__item[aria-current="page"] { color: var(--navy-900); font-weight: 600; box-shadow: var(--inset-sm); }
.neu-nav__item:focus-visible, .neu-menu__item:focus-visible { outline: 2px solid var(--ice-400); outline-offset: 3px; }

/* ===== Hover hanya untuk perangkat berkursor ===== */
@media (hover: hover) {
  .neu-menu__item:not([aria-current="page"]):hover { box-shadow: var(--inset-sm); }
}

/* ===== Cincin beranimasi ===== */
@property --p { syntax: '<number>'; inherits: false; initial-value: 0; }
.neu-ring { transition: --p 800ms var(--ease); }

/* ===== Timeline interaktif ===== */
button.neu-timeline__dot { border: 0; padding: 0; cursor: pointer; transition: transform 200ms var(--ease); }
button.neu-timeline__dot:hover { transform: scale(1.1); }
button.neu-timeline__dot:focus-visible { outline: 2px solid var(--ice-400); outline-offset: 4px; }

/* ===== Accordion (details) ===== */
.neu-details { background: var(--neu-surface); border-radius: var(--radius-lg); box-shadow: var(--raised); padding: 0 var(--card-pad); transition: box-shadow 200ms var(--ease); }
.neu-details summary { list-style: none; cursor: pointer; padding: 18px 0; font-weight: 600; display: flex; justify-content: space-between; }
.neu-details summary::-webkit-details-marker { display: none; }
.neu-details summary::after { content: "+"; color: var(--ice-400); }
.neu-details[open] { box-shadow: var(--inset); }
.neu-details[open] summary::after { content: "–"; }
.neu-details > :not(summary) { padding-bottom: 18px; }

/* ===== Skeleton loading ===== */
.neu-skeleton { position: relative; overflow: hidden; min-height: 16px; border-radius: var(--radius-md); background: var(--neu-bg); box-shadow: var(--inset-sm); }
.neu-skeleton::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, transparent, rgba(255,255,255,.7), transparent); transform: translateX(-100%); animation: neu-shimmer 1.4s infinite; }
@keyframes neu-shimmer { to { transform: translateX(100%); } }

/* ===== Drag & drop ===== */
[data-sortable] .neu-card { cursor: grab; }
[data-sortable] .neu-card--drag { cursor: grabbing; opacity: .95; }

@media (prefers-reduced-motion: reduce) {
  .neu-skeleton::after { animation: none; }
  .neu-ring { transition: none; }
}
```

### 8.3 `neumorphism.js`

Tanpa library, sekitar 90 baris. Muat dengan `defer`.

```js
(() => {
  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* 1. Slider: isi gradasi + angka */
  document.querySelectorAll('.neu-range').forEach((r) => {
    const sync = () => {
      const min = Number(r.min || 0), max = Number(r.max || 100);
      r.style.setProperty('--val', ((r.value - min) / (max - min)) * 100 + '%');
      const out = r.closest('[data-range]')?.querySelector('[data-range-out]');
      if (out) out.textContent = r.value + '%';
    };
    r.addEventListener('input', sync);
    sync();
  });

  /* 2. Tombol toggle: <button class="neu-btn" data-toggle aria-pressed="false"> */
  document.addEventListener('click', (e) => {
    const b = e.target.closest('[data-toggle]');
    if (b) b.setAttribute('aria-pressed', String(b.getAttribute('aria-pressed') !== 'true'));
  });

  /* 3. Navigasi: <nav data-nav> berisi .neu-nav__item / .neu-menu__item */
  document.querySelectorAll('[data-nav]').forEach((nav) => {
    const items = () => [...nav.querySelectorAll('.neu-nav__item, .neu-menu__item')];
    const go = (item) => {
      items().forEach((i) => {
        const on = i === item;
        i.classList.toggle('is-active', on);
        on ? i.setAttribute('aria-current', 'page') : i.removeAttribute('aria-current');
      });
      nav.dispatchEvent(new CustomEvent('nav:change', { detail: item }));
    };
    nav.addEventListener('click', (e) => { const i = e.target.closest('.neu-nav__item, .neu-menu__item'); if (i) go(i); });
    nav.addEventListener('keydown', (e) => {
      const i = e.target.closest('.neu-menu__item');
      if (i && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); go(i); }
    });
  });

  /* 4. Tab: role="tablist" > role="tab" aria-controls="panel-id" */
  document.querySelectorAll('[role="tablist"]').forEach((list) => {
    const tabs = [...list.querySelectorAll('[role="tab"]')];
    const select = (t) => {
      tabs.forEach((x) => {
        const on = x === t;
        x.classList.toggle('is-active', on);
        x.setAttribute('aria-selected', String(on));
        x.tabIndex = on ? 0 : -1;
        const panel = document.getElementById(x.getAttribute('aria-controls'));
        if (panel) panel.hidden = !on;
      });
      t.focus();
    };
    list.addEventListener('click', (e) => { const t = e.target.closest('[role="tab"]'); if (t) select(t); });
    list.addEventListener('keydown', (e) => {
      const i = tabs.indexOf(document.activeElement);
      if (i < 0) return;
      const step = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
      if (step) { e.preventDefault(); select(tabs[(i + step + tabs.length) % tabs.length]); }
    });
  });

  /* 5. Cincin: terisi + count-up saat terlihat. <div class="neu-ring" data-value="75"> */
  const countUp = (el, to, ms = 800) => {
    if (!el) return;
    if (reduceMotion) { el.textContent = to + '%'; return; }
    const t0 = performance.now();
    const step = (t) => {
      const k = Math.min((t - t0) / ms, 1);
      el.textContent = Math.round(to * k) + '%';
      if (k < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  };
  const io = new IntersectionObserver((entries) => {
    entries.forEach((en) => {
      if (!en.isIntersecting) return;
      const el = en.target, v = Number(el.dataset.value);
      el.style.setProperty('--p', v);
      countUp(el.querySelector('.neu-ring__label'), v);
      io.unobserve(el);
    });
  }, { threshold: 0.4 });
  document.querySelectorAll('.neu-ring[data-value]').forEach((el) => {
    el.style.setProperty('--p', 0);
    void el.offsetWidth; // paksa reflow agar transisi berjalan
    io.observe(el);
  });

  /* 6. Timeline: <button class="neu-timeline__dot" data-year="2019"> */
  document.querySelectorAll('.neu-timeline').forEach((tl) => {
    tl.addEventListener('click', (e) => {
      const d = e.target.closest('.neu-timeline__dot');
      if (!d) return;
      tl.querySelectorAll('.neu-timeline__dot').forEach((x) => x.classList.toggle('is-active', x === d));
      tl.dispatchEvent(new CustomEvent('timeline:change', { detail: d.dataset.year }));
    });
  });

  /* 7. Urutkan kartu (desktop): <div data-sortable> berisi .neu-card draggable="true" */
  document.querySelectorAll('[data-sortable]').forEach((list) => {
    let drag = null;
    list.addEventListener('dragstart', (e) => {
      drag = e.target.closest('.neu-card');
      if (!drag) return;
      drag.classList.add('neu-card--drag');
      e.dataTransfer.effectAllowed = 'move';
    });
    list.addEventListener('dragover', (e) => {
      e.preventDefault();
      const over = e.target.closest('.neu-card');
      if (!drag || !over || over === drag) return;
      const r = over.getBoundingClientRect();
      over.parentNode.insertBefore(drag, e.clientY - r.top > r.height / 2 ? over.nextSibling : over);
    });
    list.addEventListener('dragend', () => { drag?.classList.remove('neu-card--drag'); drag = null; });
  });
})();
```

Catatan: drag & drop HTML5 tidak bekerja di layar sentuh. Untuk HP sediakan tombol "naik / turun" pada tiap kartu sebagai pengganti.

### 8.4 Markup pendamping

```html
<!-- Tab -->
<div class="neu-nav" role="tablist" aria-label="Tampilan">
  <button class="neu-nav__item is-active" role="tab" aria-selected="true"  aria-controls="p1">Aktif</button>
  <button class="neu-nav__item"           role="tab" aria-selected="false" aria-controls="p2" tabindex="-1">Selesai</button>
</div>
<section id="p1" role="tabpanel">…</section>
<section id="p2" role="tabpanel" hidden>…</section>

<!-- Timeline -->
<div class="neu-scroll">
  <div class="neu-timeline">
    <button class="neu-timeline__dot is-active" data-year="2019" aria-label="2019"><span class="neu-timeline__year">2019</span></button>
    <button class="neu-timeline__dot" data-year="2020" aria-label="2020"><span class="neu-timeline__year">2020</span></button>
    <button class="neu-timeline__dot" data-year="2021" aria-label="2021"><span class="neu-timeline__year">2021</span></button>
  </div>
</div>

<!-- Tombol toggle -->
<button class="neu-btn" data-toggle aria-pressed="false">Favorit</button>

<!-- Daftar kartu yang bisa diurutkan -->
<div data-sortable>
  <article class="neu-card" draggable="true">Beli makanan kucing</article>
  <article class="neu-card" draggable="true">Kembalikan lampu</article>
</div>

<!-- Skeleton saat memuat -->
<div class="neu-card"><div class="neu-skeleton" style="height:20px;width:60%"></div></div>
```

### 8.5 Tailwind

Tailwind v3.2+ punya varian `aria-*` dan `data-*`:

```html
<!-- Tombol toggle -->
<button data-toggle aria-pressed="false"
  class="neu-surface rounded-full px-7 py-3.5 font-semibold text-navy-500 shadow-neu-sm transition
         aria-pressed:shadow-neu-in-sm aria-pressed:text-navy-900 active:scale-[.97]">Favorit</button>

<!-- Switch tanpa JS -->
<label class="relative inline-block h-11 w-[88px]">
  <input type="checkbox" class="peer sr-only">
  <span class="absolute inset-0 rounded-full bg-ice-100 shadow-neu-in transition
               peer-checked:neu-grad-primary peer-focus-visible:ring-2 peer-focus-visible:ring-ice-400"></span>
  <span class="absolute left-[5px] top-[5px] h-[34px] w-[34px] rounded-full neu-surface shadow-neu-xs
               transition-transform peer-checked:translate-x-11"></span>
</label>

<!-- Item nav: aktif via aria-current -->
<button class="grid h-16 w-16 place-items-center rounded-md border border-ice-200 text-navy-500
               aria-[current=page]:border-transparent aria-[current=page]:neu-surface
               aria-[current=page]:text-navy-900 aria-[current=page]:shadow-neu-sm">⌂</button>
```

`neumorphism.js` dan `neumorphism.interactive.css` tetap dipakai untuk slider, cincin, tab, timeline, dan drag.

### 8.6 Bootstrap

Gunakan JS bawaan Bootstrap (`bootstrap.bundle.min.js`) untuk komponen yang sudah ada, ditambah `neumorphism.js` untuk sisanya.

```html
<!-- Toggle button: Bootstrap menambah .active + aria-pressed (sudah inset lewat .btn.active) -->
<button class="btn" data-bs-toggle="button">Favorit</button>

<!-- Tab -->
<ul class="nav nav-pills gap-2" role="tablist">
  <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#p1">Aktif</button></li>
  <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#p2">Selesai</button></li>
</ul>
<div class="tab-content mt-3">
  <div class="tab-pane fade show active" id="p1">…</div>
  <div class="tab-pane fade" id="p2">…</div>
</div>

<!-- Accordion -->
<div class="accordion" id="acc">…</div>
```

Tambahan CSS agar slider Bootstrap punya isi gradasi (pakai JS slider yang sama; beri `class="form-range neu-range-sync"`):

```css
.neu-range-sync { --val: 50%; }
.neu-range-sync::-webkit-slider-runnable-track { background: var(--grad-fill) no-repeat var(--ice-100); background-size: var(--val) 100%; }
.neu-range-sync::-moz-range-track { background: var(--ice-100); }
.neu-range-sync::-moz-range-progress { height: 10px; border-radius: 999px; background: var(--ice-400); }
```

Tambahkan `.neu-range-sync` ke selektor `.neu-range` di bagian 1 JS (`document.querySelectorAll('.neu-range, .neu-range-sync')`). Tab, accordion, dan modal Bootstrap sudah mendapat gaya neumorphism dari `bootstrap-neu.css` (bagian 5).

### 8.7 Aturan interaksi

1. **Umpan balik instan.** Setiap elemen yang bisa diklik harus berubah dalam ≤ 150ms (bayangan inset saat `:active`).
2. **Durasi.** Hover/fokus 150–200ms; geser switch 250ms; cincin 800ms. Easing `cubic-bezier(.2, 0, 0, 1)`.
3. **Animasikan yang murah.** Transisi `transform`, `opacity`, dan `--p`; hindari menganimasikan `box-shadow` pada banyak elemen sekaligus.
4. **Keyboard.** Semua kontrol bisa difokus dan dioperasikan: Enter/Space untuk tombol dan menu, panah kiri/kanan untuk tab, Space untuk switch dan checkbox.
5. **Jangan hanya bayangan.** State aktif harus juga berubah warna ikon/teks, dan membawa `aria-current`, `aria-selected`, atau `aria-pressed`.
6. **Reduced motion.** Count-up, transisi cincin, dan shimmer dimatikan saat `prefers-reduced-motion: reduce` (sudah ditangani di CSS dan JS).
7. **Alternatif sentuh.** Drag & drop selalu disertai tombol naik/turun untuk HP dan pengguna keyboard.
8. **Label aksesibel.** Kontrol berupa ikon wajib punya `aria-label`; nilai slider diumumkan lewat label dan `aria-valuenow` bawaan `<input type="range">`.

---

## 9. Do & Don't

**Do**
- Ikuti gambar: permukaan gradasi halus, tombol utama bergradasi, bayangan ganda, cahaya kiri-atas.
- Pakai `inset` untuk yang cekung: input, track, toggle, item menu aktif, tombol ditekan.
- Tandai state aktif juga dengan warna ikon/teks (`navy-900` vs `navy-500`), bukan bayangan saja.
- Pakai `butter-100` hanya untuk satu status/angka penting per layar.

**Don't**
- Warna di luar palet, kecuali turunan di 2.2.
- Teks putih di atas `ice-400` atau `ice-100`; teks putih hanya di atas gradasi tombol (navy-500 → navy-900).
- Border tebal atau warna latar kontras pada card.
- Arah cahaya campur-aduk.
- Menumpuk lebih dari 2 level elemen `raised` dalam satu card.
- Blur bayangan di bawah 6px.

---

## 10. Aksesibilitas

1. Teks utama `navy-900`, teks sekunder `navy-500` di atas `ice-100`/surface; tidak ada teks penting dengan warna lebih muda.
2. Fokus keyboard terlihat: outline 2px `ice-400`, offset 3px.
3. Target sentuh minimal 44×44px.
4. State tidak hanya dari bayangan: sertakan perubahan warna ikon/teks.
5. Hormati `prefers-reduced-motion` (sudah ada).
6. Fallback `color-mix()` untuk browser lama: tulis `--neu-surface-1: #F4F9FE; --neu-surface-2: #D6E4F6;` sebelum deklarasi `color-mix`.

---

## 11. Template Prompt

```
Buat [komponen] bergaya neumorphism mengikuti NEUMORPHISM.md.
- Framework: [CSS murni / Tailwind / Bootstrap 5.3]
- Bentuk dan efek mengikuti gambar referensi (permukaan gradasi halus, bayangan ganda, tombol utama bergradasi)
- Warna hanya dari palet Ice & Butter dan turunannya
- State: default=raised, hover=lift, active=inset, disabled=flat
- Teks putih hanya di atas gradasi navy-500 → navy-900
- Mobile-first responsive (bagian 7) dan interaktif: state hover/active/focus, ARIA, keyboard (bagian 8)
```

---

## 12. Checklist

- [ ] Warna hanya dari token palet dan turunannya
- [ ] Permukaan card bergradasi halus, bukan datar
- [ ] Tombol utama bergradasi, bayangan `glow-btn`
- [ ] Cahaya kiri-atas konsisten
- [ ] Ada state hover, active, focus-visible, disabled
- [ ] Kontras teks terpenuhi
- [ ] Responsif di lebar 360px, 768px, dan 1024px (token mobile-first, bottom nav di HP, sidebar di desktop)
- [ ] Tidak ada scroll horizontal pada body; bayangan tidak terpotong di container scroll
- [ ] Input berfont 16px di HP; target sentuh ≥ 44px
- [ ] Setiap kontrol punya state hover (hanya perangkat kursor), active, focus-visible, disabled
- [ ] Tab, nav, toggle memakai `aria-selected` / `aria-current` / `aria-pressed`
- [ ] Animasi mati saat `prefers-reduced-motion`
