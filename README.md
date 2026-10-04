# Dashboard Manajemen Proyek & Tugas Praktikum

Aplikasi Laravel untuk mencatat proyek praktikum dan memantau tugas berdasarkan deadline, mata pelajaran, prioritas, dan status. Antarmuka menggunakan Blade, Tailwind CSS 4, dan Vite.

## Fitur

- Ringkasan proyek aktif dan tugas belum selesai.
- Daftar tugas mendatang berdasarkan deadline terdekat.
- CRUD proyek dan tugas dengan validasi.
- Perubahan status tugas dari daftar tugas.
- Filter tugas berdasarkan mata pelajaran dan prioritas.
- Penghapusan proyek turut menghapus tugas yang terhubung.
- Pilihan tema terang atau gelap mengikuti preferensi perangkat dan dapat diubah.

## Menjalankan aplikasi

Pastikan PHP 8.3+, Composer, dan Node.js tersedia.

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Jika file `.env` sudah tersedia, lewati perintah `Copy-Item`. Database bawaan menggunakan SQLite. Untuk mengembangkan CSS/JavaScript sambil menjalankan Laravel, gunakan `npm run dev` di terminal terpisah dari `php artisan serve`.

## Struktur data

- **Proyek**: nama proyek, mata pelajaran, deskripsi opsional, deadline, dan status `Aktif` atau `Selesai`.
- **Tugas**: proyek terkait, judul, anggota tim opsional, deadline, prioritas `Rendah`, `Sedang`, atau `Tinggi`, dan status `Belum Dimulai`, `Dalam Proses`, atau `Selesai`.

## Pengujian

```powershell
php artisan test
```
