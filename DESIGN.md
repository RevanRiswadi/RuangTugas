# Design Specification — RuangTugas

RuangTugas adalah hub projek dan praktikum dengan identitas emerald dan dark mode sebagai tampilan awal. Dokumen ini adalah **Single Source of Truth** visual untuk proyek ini.
Semua token, komponen, layout, dan aturan diterapkan melalui `resources/css/app.css` dan view Blade.
Jangan ubah CSS atau Blade tanpa merujuk dokumen ini terlebih dahulu.

---

## 1. Prinsip Desain

| Prinsip | Aturan konkret |
|---|---|
| **Brand** | Nama produk `RuangTugas`, badge `RT`, tagline `Projek & Praktikum Hub`. |
| **High-density** | Padding card: `px-4 py-3` (`sm:px-5`). Gap grid: `gap-4`. Baris tabel: `py-2.5`. Tidak ada padding `p-8` ke atas. |
| **Depth lewat warna** | Dark mode menjadi tema awal: `--page: #0b131e`, `--surface: #0f1e2e`, `--border: #1e3148`. `--page` selalu lebih gelap dari `--surface`. |
| **Mono untuk data** | Tanggal, deadline, angka statistik, dan badge memakai `ui-monospace`. Teks narasi tetap sans-serif. |
| **Satu warna aksen** | Hanya Emerald: `#047857` (light) / `#34d399` (dark). Tidak ada warna highlight kedua. |
| **No Slop** | Lihat Seksi 7 untuk daftar lengkap larangan. |

---

## 2. Token Warna (CSS Custom Properties)

### 2.1 Light Mode — `:root`

```css
:root {
  color-scheme: light;

  /* Surfaces */
  --page:           #eef2f7;   /* background halaman, lebih gelap dari surface */
  --surface:        #ffffff;   /* card, form, tabel */
  --surface-muted:  #f1f5f9;   /* thead, hover row, disabled */

  /* Typography */
  --text-heading:   #0f172a;   /* h1–h3, label form */
  --text-primary:   #1e293b;   /* body text */
  --text-muted:     #64748b;   /* deskripsi, meta, placeholder */

  /* Borders */
  --border:         #e2e8f0;   /* divider, border card tipis */
  --border-strong:  #cbd5e1;   /* border input, button-secondary */
  --border-hover:   #94a3b8;   /* hover state card/row */

  /* Accent — Emerald */
  --accent:         #047857;
  --accent-strong:  #065f46;   /* hover button primer */
  --accent-soft:    #ecfdf5;   /* background badge done, pagination aktif */
  --accent-border:  #059669;   /* focus ring input, border-left stat */
  --accent-contrast:#ffffff;   /* teks di atas button primer */

  /* Danger */
  --danger:         #b91c1c;

  /* Elevation */
  --card-shadow:    0 1px 3px rgb(0 0 0 / 6%), 0 1px 2px -1px rgb(0 0 0 / 4%);

  /* Focus */
  --focus-color:    #047857;
  --focus-ring:     0 0 0 3px rgb(4 120 87 / 18%);

  /* Sidebar */
  --sidebar:        #0f172a;
  --sidebar-active: #1e293b;

  /* Badge — Priority */
  --badge-high-bg:      #fef2f2;
  --badge-high-text:    #991b1b;
  --badge-high-border:  #fecaca;
  --badge-medium-bg:    #fffbeb;
  --badge-medium-text:  #92400e;
  --badge-medium-border:#fde68a;
  --badge-low-bg:       #f8fafc;
  --badge-low-text:     #475569;
  --badge-low-border:   #e2e8f0;

  /* Badge — Status */
  --badge-pending-bg:     #f8fafc;
  --badge-pending-text:   #475569;
  --badge-pending-border: #e2e8f0;
  --badge-progress-bg:    #eff6ff;
  --badge-progress-text:  #1d4ed8;
  --badge-progress-border:#bfdbfe;
  --badge-done-bg:        #ecfdf5;
  --badge-done-text:      #065f46;
  --badge-done-border:    #a7f3d0;
}
```

### 2.2 Dark Mode — `[data-theme="dark"]`

```css
[data-theme="dark"] {
  color-scheme: dark;

  /* Surfaces */
  --page:           #0b131e;   /* latar halaman */
  --surface:        #0f1e2e;   /* card terangkat di atas page */
  --surface-muted:  #162236;   /* thead, hover */

  /* Typography */
  --text-heading:   #f8fafc;   /* maksimal kontras */
  --text-primary:   #e2e8f0;
  --text-muted:     #94a3b8;

  /* Borders */
  --border:         #1e3148;
  --border-strong:  #334155;
  --border-hover:   #3d5a78;

  /* Accent — Emerald terang */
  --accent:         #34d399;
  --accent-strong:  #059669;
  --accent-soft:    #064e3b;
  --accent-border:  #34d399;
  --accent-contrast:#052e24;

  /* Danger */
  --danger:         #f87171;

  /* Elevation */
  --card-shadow:    0 1px 3px rgb(0 0 0 / 40%), 0 1px 2px -1px rgb(0 0 0 / 30%);

  /* Focus */
  --focus-color:    #34d399;
  --focus-ring:     0 0 0 3px rgb(52 211 153 / 22%);

  /* Sidebar */
  --sidebar:        #050c18;
  --sidebar-active: #162236;

  /* Badge — Priority */
  --badge-high-bg:      #2d0a0a;
  --badge-high-text:    #fca5a5;
  --badge-high-border:  #7f1d1d;
  --badge-medium-bg:    #2d1a04;
  --badge-medium-text:  #fcd34d;
  --badge-medium-border:#78350f;
  --badge-low-bg:       #162236;
  --badge-low-text:     #94a3b8;
  --badge-low-border:   #1e3148;

  /* Badge — Status */
  --badge-pending-bg:     #162236;
  --badge-pending-text:   #94a3b8;
  --badge-pending-border: #1e3148;
  --badge-progress-bg:    #0d1f40;
  --badge-progress-text:  #93c5fd;
  --badge-progress-border:#1e3a8a;
  --badge-done-bg:        #052e24;
  --badge-done-text:      #6ee7b7;
  --badge-done-border:    #065f46;
}
```

---

## 3. Komponen CSS

### 3.1 Badge

Digunakan untuk status tugas dan level prioritas.

```css
.badge {
  display: inline-flex;
  align-items: center;
  border-radius: 0.375rem;        /* bukan pill */
  border: 1px solid transparent;
  padding: 0.125rem 0.4375rem;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
  font-size: 10.5px;
  font-weight: 600;
  line-height: 1.1rem;
  letter-spacing: 0.01em;
  white-space: nowrap;
}
```

| Class | Konteks | Token |
|---|---|---|
| `.badge-priority-high` | Prioritas Tinggi | `--badge-high-*` |
| `.badge-priority-medium` | Prioritas Sedang | `--badge-medium-*` |
| `.badge-priority-low` | Prioritas Rendah | `--badge-low-*` |
| `.badge-status-pending` | Belum Dimulai | `--badge-pending-*` |
| `.badge-status-progress` | Dalam Proses | `--badge-progress-*` |
| `.badge-status-done` | Selesai | `--badge-done-*` |

### 3.2 `.surface-card`

Container utama untuk section, form, list wrapper.

```css
.surface-card {
  border: 1px solid var(--border);
  border-radius: 0.75rem;
  background: var(--surface);
  box-shadow: var(--card-shadow);
  transition: background-color 180ms ease, border-color 180ms ease;
}
```

### 3.3 `.summary-strip`

Container stat ringkasan di dashboard. Grid 4 kolom dengan `divide-x`.

```css
.summary-strip {
  overflow: hidden;
  border: 1px solid var(--border);
  border-radius: 0.75rem;
  background: var(--surface);
  box-shadow: var(--card-shadow);
}

.summary-stat {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 0.75rem;
  padding: 0.875rem 1rem;
  min-width: 0;
}

/* Stat pertama mendapat border aksen kiri */
.summary-stat--accent {
  border-left: 3px solid var(--accent-border);
  padding-left: calc(1rem - 3px);
}
```

### 3.4 `.field-control`

Semua input, select, textarea.

```css
.field-control {
  min-height: 2.75rem;
  width: 100%;
  border: 1px solid var(--border-strong);
  border-radius: 0.5rem;
  background: var(--surface);
  padding: 0.625rem 0.75rem;
  color: var(--text-primary);
  font-size: 0.875rem;
  transition: border-color 160ms ease, background-color 160ms ease, box-shadow 160ms ease;
}

.field-control:focus {
  outline: none;
  border-color: var(--accent-border);
  box-shadow: var(--focus-ring);
}
```

### 3.5 `.button`, `.button-primary`, `.button-secondary`

```css
.button {
  display: inline-flex;
  min-height: 2.75rem;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  border-radius: 0.625rem;
  padding: 0.625rem 1rem;
  font-size: 0.875rem;
  font-weight: 600;
  transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease;
}

/* Active press */
.button:not(:disabled):active { transform: translateY(1px); }
.button:disabled { cursor: wait; opacity: 0.75; }

.button-primary {
  background: var(--accent);
  color: var(--accent-contrast);
}
.button-primary:hover { background: var(--accent-strong); }

.button-secondary {
  border: 1px solid var(--border-strong);
  background: var(--surface);
  color: var(--text-primary);
}
.button-secondary:hover {
  border-color: var(--border-hover);
  background: var(--surface-muted);
}
```

### 3.6 `.task-table`

Tabel tugas di dashboard dan halaman Tugas.

```css
/* Wrapper: overflow-x-auto pada container */

.task-table { font-size: 0.8125rem; }

.task-table th {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.07em;
  text-transform: uppercase;
  color: var(--text-muted);
  background: var(--surface-muted);
  white-space: nowrap;
  user-select: none;
}

.task-table-row { transition: background-color 120ms ease; }
.task-table-row:hover { background-color: var(--surface-muted); }
```

### 3.7 `.project-list-card` / `.task-list-card`

Card mobile untuk list proyek dan tugas (breakpoint < `xl`).

```css
.task-list-card,
.project-list-card {
  transition: border-color 160ms ease, background-color 160ms ease, box-shadow 160ms ease;
}
.task-list-card:hover,
.project-list-card:hover {
  border-color: var(--border-hover);
  box-shadow: 0 2px 8px rgb(0 0 0 / 8%);
}
```

---

## 4. Layout & Responsivitas

### 4.1 Layout Utama

```
<body>
  <aside>          w-56, hanya xl:flex, bg: --sidebar, logo RT + nama dan tagline RuangTugas
  <div.flex-1>
    <header>       hanya < xl, logo RT + nama dan tagline RuangTugas, nav 3 kolom
    <main>         max-w-screen-2xl, px-4 sm:px-6 lg:px-8
```

Tema awal dark; tombol tema tetap menyediakan light mode dan menghormati preferensi yang tersimpan.

### 4.2 Dashboard (`/`)

```
[Page header]
[Summary strip — grid 2 kolom mobile / 4 kolom ≥ sm]
[Main grid]
  ├── lg:col-span-2  → section "Tugas mendatang" (tabel)
  └── lg:col-span-1  → section "Proyek aktif" (list)
```

Grid: `grid grid-cols-1 lg:grid-cols-3 gap-4 items-start`

### 4.3 Halaman Tugas & Proyek (`/tasks`, `/projects`)

- **≥ xl**: tabel HTML `<table>` full dengan thead/tbody
- **< xl**: grid card `<article class="surface-card task-list-card">`

### 4.4 Halaman Form (`/tasks/create`, `/projects/create`, edit)

```
<div class="max-w-3xl">
  [← Kembali link]
  [h1 + deskripsi]
  <form class="surface-card mt-5 space-y-4 p-4 sm:p-5">
```

---

## 5. Tipografi

| Elemen | Class Tailwind |
|---|---|
| `<h1>` halaman | `text-xl sm:text-2xl font-semibold tracking-tight text-ink-900` |
| `<h2>` section/card | `text-sm font-semibold tracking-tight text-ink-900` |
| Deskripsi halaman | `text-xs leading-5 text-slate-600` |
| Label form | `text-sm font-semibold text-ink-900` |
| Teks opsional label | `font-normal text-slate-600` (span dalam label) |
| Tanggal / deadline | `font-mono text-xs tabular-nums` |
| Thead tabel | `text-[10px] font-bold uppercase tracking-widest text-slate-600` |
| Angka stat besar | `text-2xl font-semibold tabular-nums tracking-tight text-ink-900` |
| Label stat kecil | `text-xs font-medium text-slate-600` |

> `text-ink-900` dan `text-slate-600` di-override via CSS ke `--text-heading` dan `--text-muted` masing-masing, sehingga otomatis responsif terhadap dark mode.

---

## 6. Motion & Aksesibilitas

### Motion

- Halaman selain dashboard menggunakan transisi `160ms` atau `180ms ease`, tanpa animasi masuk/keluar.
- Dashboard memakai dial `ENERGY 2 / RHYTHM 1 / MOTION 2`. Entry fade/slide satu kali mengikuti urutan baca, sedangkan hover lift menandai panel interaktif.
- Satu ambient emerald glow di pojok kanan atas mengikat area ringkasan tanpa menutupi teks. Titik di samping judul menandai halaman aktif, bukan status real-time.
- Identitas RuangTugas memakai badge inisial RT dan tagline "Projek & Praktikum Hub". Dot judul tetap statis karena dashboard tidak memperbarui data secara live.
- Item proyek terangkat 2px dengan border emerald halus saat pointer hover untuk menandai item yang dapat dibuka.
- Progress proyek dihitung dari tugas selesai terhadap jumlah tugas; proyek tanpa tugas menampilkan `0%`.
- `@media (prefers-reduced-motion: reduce)` menonaktifkan animasi dan transform.

### Aksesibilitas

- Setiap `<input>`, `<select>`, `<textarea>` punya `<label for>` eksplisit.
- `<table>` menggunakan `scope="col"` pada header dan `scope="row"` pada sel identitas tugas.
- Tombol hapus menggunakan `data-confirm` (dikonfirmasi via JS sebelum submit).
- Focus ring: `outline: 2px solid var(--focus-color)` + `box-shadow: var(--focus-ring)`.
- Skip link: `href="#konten-utama"` di awal `<body>`.
- Status flash: `role="status" aria-live="polite"`.

---

## 7. Aturan No Slop (Larangan Keras)

Aturan ini berlaku untuk semua kontributor dan AI agent.

| Larangan | Alasan |
|---|---|
| ❌ `shadow-xl`, `shadow-2xl`, `drop-shadow-*` besar | Terlalu berat, merusak kesan flat-depth |
| ❌ Gradien dekoratif (`bg-gradient-*`) | Tidak sesuai tema SaaS minimal; ambient glow dashboard adalah pengecualian untuk menambatkan area ringkasan |
| ❌ `text-3xl` ke atas untuk heading halaman | Heading maks `text-2xl` (`sm:text-2xl`) |
| ❌ `rounded-2xl` ke atas untuk card | Card maks `rounded-xl` (`0.75rem`) |
| ❌ `p-8`, `p-10`, `p-12` pada card/section | Padding maks `p-5` untuk card |
| ❌ Lebih dari satu warna aksen | Hanya Emerald |
| ❌ Animasi `animate-bounce`, `animate-spin` dekoratif | Hanya transisi fungsional |
| ❌ Komponen ditampilkan saat tidak ada data | Hanya tampilkan empty state, bukan tabel kosong |
| ❌ Lorem ipsum atau teks placeholder di production | Hapus sebelum commit |
| ❌ Inline `style=""` kecuali untuk CSS variable override | Semua visual lewat class atau CSS file |

---

## 8. Instruksi AI Agent

Saat mengerjakan task yang melibatkan UI proyek ini:

1. **Baca DESIGN.md ini terlebih dahulu** sebelum menyentuh file apapun.
2. **Semua token warna** harus diambil dari Seksi 2, bukan dari nilai hardcode.
3. **Setiap komponen baru** harus mengikuti pola dari Seksi 3.
4. **Heading halaman** selalu `text-xl sm:text-2xl` — tidak boleh lebih besar.
5. **Badge** selalu `border-radius: 0.375rem` + `font-mono` — bukan pill.
6. **Padding form** maksimal `p-5` (`sm:p-5`), field gap `space-y-4`.
7. **Label error** selalu `text-xs font-medium text-clay-700`.
8. **Jangan tambahkan** library UI baru, framework CSS kedua, atau font external.
9. **Verifikasi** bahwa tidak ada aturan No Slop (Seksi 7) yang dilanggar sebelum selesai.
10. Jika ada konflik antara DESIGN.md dan kode yang ada — **DESIGN.md yang menang**.
