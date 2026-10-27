# Panduan Skenario Demo Aplikasi Perpustakaan

Dokumen ini berisi panduan skenario demo aplikasi **Sistem Informasi Perpustakaan** untuk keperluan evaluasi/UAS (Sesuai skenario di Pertemuan 11).

---

## 🛠️ Persiapan Sebelum Demo

1. **Jalankan Migrasi dan Database Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan menyiapkan data awal: Akun User/Admin/Petugas, Kategori buku, 20 data buku, 15 anggota, dan 10 riwayat transaksi peminjaman.*

2. **Jalankan Dua Web Server**:
   Aplikasi perpustakaan menggunakan arsitektur konsumsi internal REST API untuk halaman **Dashboard** dan **Laporan Peminjaman**. Oleh karena itu, nyalakan dua instance server:

   - **Terminal 1 (Aplikasi Web & Frontend Client)**:
     ```bash
     php artisan serve --port=8000
     ```
   - **Terminal 2 (API Server Target)**:
     ```bash
     php artisan serve --port=8011
     ```

3. **Pastikan Konfigurasi `.env`**:
   Pastikan variabel `INTERNAL_API_URL` mengarah ke port 8011:
   ```env
   INTERNAL_API_URL=http://127.0.0.1:8011
   ```

4. **Kredensial Akun Pengujian**:
   - **Admin**: `admin@pens.ac.id` | Password: `password` (Nama: *Bambang Sutrisno*, Role: `admin`)
   - **Petugas**: `petugas1@pens.ac.id` | Password: `password` (Nama: *Siti Rahmawati*, Role: `petugas`)

---

## 📋 Langkah-Langkah Skenario Demo

### 1. Autentikasi (Login & Logout)
1. Buka browser dan akses `http://127.0.0.1:8000/login`.
2. Masukkan email `admin@pens.ac.id` dan password `password`.
3. Klik tombol **Login**.
4. Sistem berhasil memvalidasi kredensial dan melakukan redirect langsung ke halaman **Dashboard** (`/`).
5. Perhatikan pada navbar kanan atas, tertera nama pengguna yang sedang aktif (`Bambang Sutrisno`) dan role (`admin`).

---

### 2. Tampilkan Dashboard & Verifikasi Statistik
1. Pada halaman utama / Dashboard (`http://127.0.0.1:8000/`):
2. Tunjukkan 3 kartu statistik ringkasan:
   - **Total Buku**: Terisi otomatis via API (`/api/stats`) dengan jumlah data buku (default 20 buku dari seeder).
   - **Total Anggota**: Terisi otomatis dengan jumlah anggota terdaftar (default 15 anggota dari seeder).
   - **Peminjaman Aktif**: Menampilkan jumlah transaksi peminjaman yang berstatus `dipinjam`.
3. *(Opsional - Uji Ketahanan)*: Jika server port `8011` dihentikan sementara, dashboard akan fallback menampilkan `0` tanpa menyebabkan error 500 (`ConnectionException` tertangani).

---

### 3. Tambah Kategori Buku (Categories)
1. Klik menu **Kategori** pada navbar (`http://127.0.0.1:8000/categories`).
2. Klik tombol **Tambah Kategori** (`/categories/create`).
3. Masukkan data kategori baru:
   - **Nama Kategori**: `Sains & Astronomi`
   - **Deskripsi**: `Buku-buku tentang sains alam, fisika antariksa, dan tata surya`
4. Klik **Simpan**.
5. Muncul notifikasi sukses dan kategori baru muncul di daftar kategori.

---

### 4. Tambah Data Buku Baru (Books)
1. Klik menu **Buku** pada navbar (`http://127.0.0.1:8000/books`).
2. Perhatikan pagination di baris bawah tabel yang sudah terformat rapi (Clean navigation: `« Sebelumnya`, nomor halaman, `Berikutnya »`).
3. Klik tombol **Tambah Buku** (`/books/create`).
4. Isi formulir data buku:
   - **Judul**: `Kosmos: Menjelajahi Alam Semesta`
   - **Penulis**: `Carl Sagan`
   - **Penerbit**: `Kepustakaan Populer Gramedia`
   - **Tahun Terbit**: `2021`
   - **ISBN**: `9786024813589`
   - **Stok**: `5`
   - **Kategori**: Pilih kategori yang baru dibuat (`Sains & Astronomi`)
5. Klik **Simpan**.
6. Data buku baru berhasil tersimpan dan tampil pada daftar buku.

---

### 5. Tambah Data Anggota (Members)
1. Klik menu **Anggota** pada navbar (`http://127.0.0.1:8000/members`).
2. Klik tombol **Tambah Anggota** (`/members/create`).
3. Isi data anggota:
   - **Nomor Anggota**: `MBR-9901`
   - **Nama**: `Rian Pratama`
   - **Email**: `rian.pratama@example.com`
   - **Nomor Telepon**: `081234567890`
   - **Alamat**: `Jl. Sukolilo Raya No. 45, Surabaya`
4. Klik **Simpan**.
5. Anggota baru tersimpan dengan status aktif.

---

### 6. Transaksi Peminjaman Buku (Loans)
1. Klik menu **Peminjaman** pada navbar (`http://127.0.0.1:8000/loans`).
2. Klik tombol **Buat Peminjaman Baru** (`/loans/create`).
3. Isi formulir transaksi:
   - **Pilih Anggota**: Pilih `Rian Pratama` (`MBR-9901`).
   - **Pilih Buku**: Centang atau pilih buku yang ingin dipinjam (misal: `Kosmos: Menjelajahi Alam Semesta` atau buku lainnya).
   - **Tanggal Pinjam**: Tanggal hari ini.
   - **Tanggal Wajib Kembali**: 7 hari ke depan.
4. Klik **Simpan Transaksi**.
5. Sistem otomatis membuat nomor transaksi (kode peminjaman), mengurangi stok buku yang dipinjam, dan mencatat item buku terkait.

---

### 7. Tampilkan Daftar Peminjaman Aktif
1. Masuk ke halaman daftar peminjaman (`/loans`).
2. Filter atau perhatikan daftar transaksi yang berstatus **"Dipinjam"**.
3. Klik tombol **Detail / Show** pada transaksi yang baru saja dibuat.
4. Tunjukkan detail peminjam, petugas penanggung jawab, daftar buku yang dipinjam, tanggal peminjaman, serta badge status kuning/oranye bertuliskan **Dipinjam**.

---

### 8. Proses Pengembalian Buku
1. Pada daftar peminjaman atau detail transaksi yang masih dipinjam:
2. Klik tombol **Kembalikan Buku** (atau tombol aksi pengembalian).
3. Konfirmasi proses pengembalian.
4. Sistem akan:
   - Mengubah status peminjaman menjadi **"Dikembalikan"**.
   - Mencatat tanggal pengembalian aktual.
   - Mengembalikan (menambah kembali) stok buku ke inventaris secara otomatis.
5. Status pada tabel berubah menjadi badge hijau **Dikembalikan**.

---

### 9. Tampilkan Laporan Peminjaman (HTTP Client Report)
1. Dari halaman Dashboard atau menu navigasi, klik **Lihat Laporan Peminjaman** (`http://127.0.0.1:8000/loans/report`).
2. Halaman ini mendemokan integrasi Laravel HTTP Client yang mengonsumsi endpoint API `/api/loans` dari server port 8011.
3. Tunjukkan tabel data laporan peminjaman yang berisi daftar seluruh transaksi, nama peminjam, nama petugas, daftar buku yang dipinjam, tanggal transaksi, dan status peminjaman.

---

### 10. Pengujian & Demo REST API di Postman / cURL
Buka Postman atau terminal cURL untuk mendemokan endpoint API utama perpustakaan:

#### A. Ambil Statistik Perpustakaan (`GET /api/stats`)
- **Request**:
  ```bash
  curl -s -H "Accept: application/json" http://127.0.0.1:8000/api/stats
  ```
- **Hasil**: Mengembalikan total buku, total anggota, dan total peminjaman aktif.

#### B. Ambil Daftar Buku Terpaginasi & Filter (`GET /api/books`)
- **Request**:
  ```bash
  curl -s -H "Accept: application/json" "http://127.0.0.1:8000/api/books?per_page=5"
  ```
- **Hasil**: JSON dengan key `data`, pagination `links`, dan `meta`.

#### C. Ambil Daftar Anggota (`GET /api/members`)
- **Request**:
  ```bash
  curl -s -H "Accept: application/json" http://127.0.0.1:8000/api/members
  ```

#### D. Ambil Riwayat Peminjaman Lengkap (`GET /api/loans`)
- **Request**:
  ```bash
  curl -s -H "Accept: application/json" http://127.0.0.1:8000/api/loans
  ```
- **Hasil**: Array transaksi peminjaman lengkap dengan relasi `member`, `user`, dan `items.book`.

#### E. Pembuatan Peminjaman via API (`POST /api/loans`)
- **Headers**:
  - `Accept: application/json`
  - `Content-Type: application/json`
- **Body**:
  ```json
  {
    "member_id": 1,
    "user_id": 1,
    "tanggal_pinjam": "2026-10-27",
    "tanggal_kembali": "2026-11-03",
    "book_ids": [1, 2]
  }
  ```

#### F. Pengembalian Buku via API (`PUT /api/loans/{id}/kembalikan`)
- **Request**:
  ```bash
  curl -X PUT -H "Accept: application/json" http://127.0.0.1:8000/api/loans/1/kembalikan
  ```
- **Hasil**: Response pesan sukses pengembalian dan status berubah menjadi `dikembalikan`.

---

## 🎯 Kesimpulan Demo
Skenario di atas membuktikan bahwa seluruh fungsionalitas aplikasi telah terintegrasi dengan baik:
- Manajemen data CRUD (Kategori, Buku, Anggota) bekerja sesuai validasi.
- Alur transaksi sirkulasi (peminjaman dan pengembalian) berhasil mengelola stok buku.
- Tampilan Dashboard dan Laporan berhasil mengonsumsi data melalui internal REST API.
- Endpoints REST API siap diakses secara stateless oleh aplikasi eksternal maupun Postman.
