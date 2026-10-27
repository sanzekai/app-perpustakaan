# Sistem Informasi Perpustakaan (app-perpustakaan)

Aplikasi manajemen perpustakaan modern berbasis **Laravel 12** yang mencakup sistem autentikasi, manajemen buku, kategori, anggota, transaksi sirkulasi (peminjaman & pengembalian buku), konsumsi internal REST API untuk dashboard & laporan, serta RESTful API lengkap untuk integrasi client eksternal.

---

## 🚀 Fitur Utama

- **Autentikasi & Autorisasi**:
  - Login dan logout berbasis session.
  - Role-based access control (Admin & Petugas) via Middleware.
- **Manajemen Data (CRUD)**:
  - **Kategori**: Pengelompokan buku dan relasi kategori.
  - **Buku**: Manajemen inventaris buku, nomor ISBN, stok dinamis, dan filter pencarian.
  - **Anggota**: Pendaftaran anggota perpustakaan dengan kode identitas unik.
- **Sirkulasi Perpustakaan**:
  - Form transaksi peminjaman multi-buku.
  - Pengurangan dan pengembalian stok otomatis.
  - Pencatatan tanggal pinjam, batas kembali, dan riwayat status transaksi.
- **Dashboard & Laporan (Internal API Consumption)**:
  - Dashboard statistik interaktif (Total Buku, Total Anggota, Peminjaman Aktif).
  - Laporan rekap transaksi peminjaman yang mengonsumsi endpoint internal API via Laravel HTTP Client (`Http::timeout()->get()`).
  - Mekanisme fallback ketahanan jaringan (`ConnectionException`) agar sistem tetap tampil meski server API offline.
- **Layanan RESTful API**:
  - Format response JSON standar menggunakan Laravel API Resource (`JsonResource`).
  - Mendukung endpoint Books, Members, Loans, dan Stats (lihat [API.md](./API.md) untuk dokumentasi lengkap).
- **Tampilan Bersih & Custom Pagination**:
  - UI sederhana dan responsif.
  - Pagination custom yang rapi menggantikan SVG default bawaan framework.

---

## 📋 Prasyarat Sistem

Pastikan environment Anda telah terpasang:
- **PHP** >= 8.2
- **Composer** >= 2.x
- **Ekstensi PHP**: `pdo_sqlite`, `curl`, `mbstring`, `openssl`, `tokenizer`, `xml`
- **Git**

---

## 🛠️ Panduan Instalasi & Setup

Ikuti langkah-langkah berikut untuk menjalankan aplikasi:

### 1. Clone Repository
```bash
git clone <url-repository-anda> app-perpustakaan
cd app-perpustakaan
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```

Pastikan variabel penting di `.env` sudah sesuai (menggunakan SQLite dan setting internal API URL):
```env
APP_NAME=Perpustakaan
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

# URL Target Internal API untuk Dashboard & Laporan
INTERNAL_API_URL=http://127.0.0.1:8011
```

Buat file database SQLite (jika belum ada):
```bash
touch database/database.sqlite
```

Generate application key:
```bash
php artisan key:generate
```

### 4. Migrasi & Seeding Database
Jalankan migrasi seluruh tabel beserta data awal contoh (User, Kategori, Buku, Anggota, dan Peminjaman):
```bash
php artisan migrate:fresh --seed
```

---

## 🖥️ Menjalankan Dua Server (PENTING)

Arsitektur aplikasi ini memisahkan konsumsi internal API pada Dashboard dan Laporan. Halaman web (port `8000`) melakukan request HTTP ke endpoint API (port `8011`).

Buka **dua terminal terpisah**:

### Terminal 1: Web Application Server (Port 8000)
```bash
php artisan serve --port=8000
```
> Server web utama dapat diakses di browser melalui: **`http://127.0.0.1:8000`**

### Terminal 2: API Service Server (Port 8011)
```bash
php artisan serve --port=8011
```
> Server API yang melayani request internal untuk `/api/stats` dan `/api/loans`.

---

## 👤 Akun Pengujian (Default Seeder)

| Role | Email | Password | Nama Pengguna |
|---|---|---|---|
| **Admin** | `admin@pens.ac.id` | `password` | Bambang Sutrisno |
| **Petugas** | `petugas1@pens.ac.id` | `password` | Siti Rahmawati |
| **Petugas** | `petugas2@pens.ac.id` | `password` | Ahmad Fauzi |

---

## 🧪 Pengujian Unit & Fitur

Jalankan test suite otomatis menggunakan PHPUnit / Artisan test:
```bash
php artisan test
```

---

## 📖 Dokumentasi Lanjutan

- **Skenario Demo UAS**: Lihat panduan langkah-langkah demo lengkap di [DEMO.md](./DEMO.md).
- **Dokumentasi REST API**: Spesifikasi 11 endpoint API perpustakaan lengkap dengan contoh payload dan response di [API.md](./API.md).
