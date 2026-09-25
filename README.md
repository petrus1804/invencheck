# InvenCheck — Warehouse Management System

InvenCheck adalah sistem manajemen stok gudang berbasis web yang dibangun menggunakan Laravel. Sistem ini mendukung multi-role (Admin, Staff Gudang, dan User/Karyawan) dengan hak akses yang dapat dikustomisasi secara dinamis melalui halaman Manajemen Role.

## Fitur Utama

- **Dashboard** — ringkasan stok, barang masuk/keluar, dan peringatan stok menipis
- **Manajemen Stok Barang** — CRUD barang, catat barang masuk (restock)
- **Manajemen Kategori & Gudang** — pengelompokan barang dan lokasi penyimpanan
- **Laporan** — tren pergerakan stok, riwayat transaksi, export ke Excel & PDF
- **Ambil Barang** — karyawan dapat mengajukan permintaan pengambilan barang
- **Persetujuan** — Staff Gudang dapat menyetujui/menolak permintaan
- **Bukti Pengambilan** — cetak bukti PDF untuk permintaan yang disetujui
- **Manajemen Role & Permission** — sistem hak akses dinamis, role baru dapat dibuat tanpa mengubah kode
- **Manajemen Pengguna** — kelola akun dan role pengguna

## Tech Stack

- **Backend**: Laravel 12, PHP 8.2
- **Database**: MySQL
- **Frontend**: Blade Template, CSS custom
- **Export**: Maatwebsite Excel, Barryvdh DomPDF
- **Auth**: Laravel Breeze

## Instalasi Lokal

```bash
git clone https://github.com/username-kamu/invencheck.git
cd invencheck
composer install
cp .env.example .env
php artisan key:generate
```

Atur koneksi database di file `.env`, lalu jalankan:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

## Akun Demo

Gunakan akun berikut untuk mencoba sistem dengan role yang berbeda:

**Admin**  
`admin@contoh.com` | `Admin@123` | Akses penuh ke seluruh sistem, termasuk membuat akun dan membatasi fitur yang bisa diakses role user tertentu

**Catatan:** 
Sistem role di InvenCheck bersifat dinamis. Admin dapat membuat role baru dan mengatur hak aksesnya sendiri melalui halaman Manajemen Role. kemudian, user staff gudang hanya bisa dibuat oleh admin, jadi hanya user biasa dari department lain yang dapat membuat akun dengan cara register
## Struktur Role & Permission

- Admin: mengatur seluruh permission setiap user 
- Staff: mengecek dashboard, lihat stok barang di gudang (menambahkan, membuat label/tag, mengatur daftar barang)
- User: reservasi barang

Project ini dibuat untuk keperluan pembelajaran/internal.