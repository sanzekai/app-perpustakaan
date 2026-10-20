# Dokumentasi REST API Perpustakaan (Pertemuan 9)

Dokumentasi resmi seluruh endpoint REST API aplikasi `app-perpustakaan` yang dibangun pada **Pertemuan 9**. Seluruh endpoint mengembalikan format response JSON yang konsisten menggunakan Laravel **API Resource** (`JsonResource`).

---

## 📌 Konvensi & Header Request

- **Base URL**: `http://127.0.0.1:8000/api`
- **Header Wajib**:
  - `Accept: application/json` *(Wajib pada setiap request agar response error terformat sebagai JSON)*
  - `Content-Type: application/json` *(Wajib pada request yang menyertakan body: POST & PUT)*
- **Autentikasi**: Seluruh endpoint pada pertemuan ini bersifat publik (*stateless REST API dasar*).

---

## 📋 Tabel Ringkasan Endpoint

Berikut adalah rekapitulasi seluruh **11 endpoint** REST API:

| Method | URL | Body (jika ada) | Contoh Response |
|---|---|---|---|
| GET | `/api/books` | - | `200 OK` (Array data buku terpaginasi) |
| GET | `/api/books/{id}` | - | `200 OK` (Satu data buku) / `404 Not Found` |
| POST | `/api/books` | JSON (`judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `category_id`) | `201 Created` (Buku baru) / `422 Unprocessable` |
| PUT | `/api/books/{id}` | JSON (`judul`, `penulis`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `category_id`) | `200 OK` (Buku terupdate) / `404 Not Found` / `422 Unprocessable` |
| DELETE | `/api/books/{id}` | - | `200 OK` (`{"message":"Buku berhasil dihapus."}`) / `404 Not Found` |
| GET | `/api/members` | - | `200 OK` (Array data anggota terpaginasi) |
| GET | `/api/members/{id}` | - | `200 OK` (Satu data anggota) / `404 Not Found` |
| GET | `/api/loans` | - | `200 OK` (Daftar peminjaman beserta relasi nested) |
| POST | `/api/loans` | JSON (`member_id`, `user_id`, `tanggal_pinjam`, `tanggal_kembali`, `book_ids[]`) | `201 Created` (Peminjaman baru) / `422 Unprocessable` |
| PUT | `/api/loans/{id}/kembalikan` | - | `200 OK` (Status jadi `"dikembalikan"`) / `404 Not Found` |
| GET | `/api/stats` | - | `200 OK` (Objek statistik total buku, anggota, pinjam aktif) |

---

## 📖 Rincian Endpoint & Contoh Response Sungguhan

### 1. GET `/api/books`
Mengambil daftar buku yang terpaginasi (10 item per halaman) dengan eager loading relasi kategori (`category`). Mendukung filter pencarian parsial judul (`?judul=`) dan filter kategori (`?category_id=`).

- **Method**: `GET`
- **URL**: `/api/books` (atau `/api/books?judul=laskar`, `/api/books?category_id=2`)
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": [
        {
            "id": 1,
            "judul": "Sangkuriang (Edisi Bergambar)",
            "penulis": "Gemini",
            "penerbit": "Google Books",
            "tahun_terbit": 1969,
            "isbn": "978-123-456-789-0",
            "stok": 3,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Dongeng"
            },
            "created_at": "2026-09-22T05:42:10.000000Z",
            "updated_at": "2026-10-20T13:50:36.000000Z"
        },
        {
            "id": 2,
            "judul": "Malin Kundang",
            "penulis": "GPT",
            "penerbit": "OpenAI",
            "tahun_terbit": 1945,
            "isbn": null,
            "stok": 3,
            "sampul": null,
            "category": {
                "id": 1,
                "nama_kategori": "Dongeng"
            },
            "created_at": "2026-09-22T18:37:06.000000Z",
            "updated_at": "2026-09-22T18:37:06.000000Z"
        },
        {
            "id": 7,
            "judul": "Laskar Pelangi",
            "penulis": "Andrea Hirata",
            "penerbit": "Bentang Pustaka",
            "tahun_terbit": 2005,
            "isbn": "978-979-3062-79-2",
            "stok": 5,
            "sampul": null,
            "category": {
                "id": 2,
                "nama_kategori": "Novel"
            },
            "created_at": "2026-10-20T13:50:02.000000Z",
            "updated_at": "2026-10-20T13:50:02.000000Z"
        },
        {
            "id": 8,
            "judul": "Filosofi Teras",
            "penulis": "Henry Manampiring",
            "penerbit": "Kompas Gramedia",
            "tahun_terbit": 2018,
            "isbn": "978-602-412-518-9",
            "stok": 7,
            "sampul": null,
            "category": {
                "id": 2,
                "nama_kategori": "Novel"
            },
            "created_at": "2026-10-20T13:50:36.000000Z",
            "updated_at": "2026-10-20T13:50:36.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/books?page=1",
        "last": "http://localhost/api/books?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/books?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/books",
        "per_page": 10,
        "to": 4,
        "total": 4
    }
}
```

#### Response Sukses dengan Filter `?judul=laskar`: `200 OK`
```json
{
    "data": [
        {
            "id": 7,
            "judul": "Laskar Pelangi",
            "penulis": "Andrea Hirata",
            "penerbit": "Bentang Pustaka",
            "tahun_terbit": 2005,
            "isbn": "978-979-3062-79-2",
            "stok": 5,
            "sampul": null,
            "category": {
                "id": 2,
                "nama_kategori": "Novel"
            },
            "created_at": "2026-10-20T13:50:02.000000Z",
            "updated_at": "2026-10-20T13:50:02.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/books?page=1",
        "last": "http://localhost/api/books?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/books?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/books",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

### 2. GET `/api/books/{id}`
Mengambil detail informasi satu buku beserta relasi kategori.

- **Method**: `GET`
- **URL**: `/api/books/{id}` (Contoh: `/api/books/1`)
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": {
        "id": 1,
        "judul": "Sangkuriang (Edisi Bergambar)",
        "penulis": "Gemini",
        "penerbit": "Google Books",
        "tahun_terbit": 1969,
        "isbn": "978-123-456-789-0",
        "stok": 3,
        "sampul": null,
        "category": {
            "id": 1,
            "nama_kategori": "Dongeng"
        },
        "created_at": "2026-09-22T05:42:10.000000Z",
        "updated_at": "2026-10-20T13:50:36.000000Z"
    }
}
```

#### Response Gagal: `404 Not Found` (ID buku tidak ditemukan)
```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

### 3. POST `/api/books`
Menambahkan data buku baru ke dalam katalog. Validasi menggunakan `StoreBookRequest`.

- **Method**: `POST`
- **URL**: `/api/books`
- **Headers**: `Accept: application/json`, `Content-Type: application/json`
- **Request Body (JSON)**:
```json
{
  "judul": "Bumi Manusia",
  "penulis": "Pramoedya Ananta Toer",
  "penerbit": "Hasta Mitra",
  "tahun_terbit": 1980,
  "isbn": "978-979-973-123-4",
  "stok": 8,
  "category_id": 2
}
```

#### Response Sukses: `201 Created`
```json
{
    "data": {
        "id": 10,
        "judul": "Bumi Manusia",
        "penulis": "Pramoedya Ananta Toer",
        "penerbit": "Hasta Mitra",
        "tahun_terbit": 1980,
        "isbn": "978-979-973-123-4",
        "stok": 8,
        "sampul": null,
        "category": {
            "id": 2,
            "nama_kategori": "Novel"
        },
        "created_at": "2026-10-20T13:51:44.000000Z",
        "updated_at": "2026-10-20T13:51:44.000000Z"
    }
}
```

#### Response Gagal: `422 Unprocessable Content` (Validasi Gagal)
```json
{
    "message": "Judul buku wajib diisi. (and 5 more errors)",
    "errors": {
        "judul": [
            "Judul buku wajib diisi."
        ],
        "penulis": [
            "Nama penulis wajib diisi."
        ],
        "penerbit": [
            "Nama penerbit wajib diisi."
        ],
        "tahun_terbit": [
            "Tahun terbit wajib diisi."
        ],
        "stok": [
            "Stok wajib diisi."
        ],
        "category_id": [
            "Kategori wajib dipilih."
        ]
    }
}
```

### 4. PUT `/api/books/{id}`
Memperbarui data buku yang telah ada berdasarkan ID.

- **Method**: `PUT`
- **URL**: `/api/books/{id}` (Contoh: `/api/books/1`)
- **Headers**: `Accept: application/json`, `Content-Type: application/json`
- **Request Body (JSON)**:
```json
{
  "judul": "Bumi Manusia (Edisi Khusus)",
  "penulis": "Pramoedya Ananta Toer",
  "penerbit": "Lentera Dipantara",
  "tahun_terbit": 1980,
  "isbn": "978-979-973-123-4",
  "stok": 12,
  "category_id": 2
}
```

#### Response Sukses: `200 OK`
```json
{
    "data": {
        "id": 10,
        "judul": "Bumi Manusia (Edisi Khusus)",
        "penulis": "Pramoedya Ananta Toer",
        "penerbit": "Lentera Dipantara",
        "tahun_terbit": 1980,
        "isbn": "978-979-973-123-4",
        "stok": 12,
        "sampul": null,
        "category": {
            "id": 2,
            "nama_kategori": "Novel"
        },
        "created_at": "2026-10-20T13:51:44.000000Z",
        "updated_at": "2026-10-20T13:51:44.000000Z"
    }
}
```

#### Response Gagal: `422 Unprocessable Content` (Validasi Gagal)
```json
{
    "message": "The judul field is required. (and 5 more errors)",
    "errors": {
        "judul": [
            "The judul field is required."
        ],
        "penulis": [
            "The penulis field is required."
        ],
        "penerbit": [
            "The penerbit field is required."
        ],
        "tahun_terbit": [
            "The tahun terbit field is required."
        ],
        "stok": [
            "The stok field is required."
        ],
        "category_id": [
            "The category id field is required."
        ]
    }
}
```

#### Response Gagal: `404 Not Found` (ID Tidak Ditemukan)
```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

### 5. DELETE `/api/books/{id}`
Menghapus data buku dari sistem perpustakaan berdasarkan ID.

- **Method**: `DELETE`
- **URL**: `/api/books/{id}`
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "message": "Buku berhasil dihapus."
}
```

#### Response Gagal: `404 Not Found`
```json
{
    "message": "No query results for model [App\\Models\\Book] 999"
}
```

### 6. GET `/api/members`
Mengambil daftar anggota perpustakaan terpaginasi (10 per halaman) dibungkus `MemberResource`.

- **Method**: `GET`
- **URL**: `/api/members`
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": [
        {
            "id": 1,
            "nama": "Mohammad Ihsan Septiano Firdaus",
            "nim": "31256000441",
            "email": "tiancraft51@gmail.com",
            "nomor_telepon": "081217893314",
            "alamat": "Ngabetan, Cerme District",
            "status": "aktif",
            "created_at": "2026-09-22T19:06:03.000000Z",
            "updated_at": "2026-09-22T19:06:03.000000Z"
        }
    ],
    "links": {
        "first": "http://localhost/api/members?page=1",
        "last": "http://localhost/api/members?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/members?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/members",
        "per_page": 10,
        "to": 1,
        "total": 1
    }
}
```

### 7. GET `/api/members/{id}`
Mengambil informasi lengkap satu anggota perpustakaan.

- **Method**: `GET`
- **URL**: `/api/members/{id}` (Contoh: `/api/members/1`)
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": {
        "id": 1,
        "nama": "Mohammad Ihsan Septiano Firdaus",
        "nim": "31256000441",
        "email": "tiancraft51@gmail.com",
        "nomor_telepon": "081217893314",
        "alamat": "Ngabetan, Cerme District",
        "status": "aktif",
        "created_at": "2026-09-22T19:06:03.000000Z",
        "updated_at": "2026-09-22T19:06:03.000000Z"
    }
}
```

#### Response Gagal: `404 Not Found`
```json
{
    "message": "No query results for model [App\\Models\\Member] 999"
}
```

### 8. GET `/api/loans`
Mengambil seluruh riwayat peminjaman buku terpaginasi dengan eager loading relasi `member`, `user` (sebagai `petugas`), dan `loanItems.book` (sebagai daftar `books`). Menghindari N+1 Query Problem.

- **Method**: `GET`
- **URL**: `/api/loans`
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": [
        {
            "id": 3,
            "member": {
                "id": 1,
                "nama": "Mohammad Ihsan Septiano Firdaus"
            },
            "petugas": {
                "id": 1,
                "name": "Admin Perpustakaan"
            },
            "tanggal_pinjam": "2026-10-15",
            "tanggal_kembali": "2026-10-22",
            "tanggal_dikembalikan": "2026-10-20",
            "status": "dikembalikan",
            "books": [
                {
                    "id": 1,
                    "judul": "Sangkuriang (Edisi Bergambar)"
                },
                {
                    "id": 2,
                    "judul": "Malin Kundang"
                }
            ]
        },
        {
            "id": 4,
            "member": {
                "id": 1,
                "nama": "Mohammad Ihsan Septiano Firdaus"
            },
            "petugas": {
                "id": 2,
                "name": "Petugas Satu"
            },
            "tanggal_pinjam": "2026-10-18",
            "tanggal_kembali": "2026-10-25",
            "tanggal_dikembalikan": "2026-10-20",
            "status": "dikembalikan",
            "books": [
                {
                    "id": 7,
                    "judul": "Laskar Pelangi"
                }
            ]
        },
        {
            "id": 5,
            "member": {
                "id": 1,
                "nama": "Mohammad Ihsan Septiano Firdaus"
            },
            "petugas": {
                "id": 2,
                "name": "Petugas Satu"
            },
            "tanggal_pinjam": "2026-10-21",
            "tanggal_kembali": "2026-10-28",
            "tanggal_dikembalikan": null,
            "status": "dipinjam",
            "books": [
                {
                    "id": 1,
                    "judul": "Sangkuriang (Edisi Bergambar)"
                },
                {
                    "id": 2,
                    "judul": "Malin Kundang"
                }
            ]
        }
    ],
    "links": {
        "first": "http://localhost/api/loans?page=1",
        "last": "http://localhost/api/loans?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://localhost/api/loans?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "path": "http://localhost/api/loans",
        "per_page": 10,
        "to": 3,
        "total": 3
    }
}
```

### 9. POST `/api/loans`
Mencatat transaksi peminjaman baru untuk seorang anggota dengan satu atau lebih buku (`book_ids[]`). Petugas dicatat melalui `user_id`.

- **Method**: `POST`
- **URL**: `/api/loans`
- **Headers**: `Accept: application/json`, `Content-Type: application/json`
- **Request Body (JSON)**:
```json
{
  "member_id": 1,
  "user_id": 2,
  "tanggal_pinjam": "2026-10-20",
  "tanggal_kembali": "2026-10-27",
  "book_ids": [1, 2]
}
```

#### Response Sukses: `201 Created`
```json
{
    "data": {
        "id": 6,
        "member": {
            "id": 1,
            "nama": "Mohammad Ihsan Septiano Firdaus"
        },
        "petugas": {
            "id": 2,
            "name": "Petugas Satu"
        },
        "tanggal_pinjam": "2026-10-20",
        "tanggal_kembali": "2026-10-27",
        "tanggal_dikembalikan": null,
        "status": "dipinjam",
        "books": [
            {
                "id": 1,
                "judul": "Sangkuriang (Edisi Bergambar)"
            },
            {
                "id": 2,
                "judul": "Malin Kundang"
            }
        ]
    }
}
```

#### Response Gagal: `422 Unprocessable Content` (Validasi Gagal)
```json
{
    "message": "Anggota wajib dipilih. (and 4 more errors)",
    "errors": {
        "member_id": [
            "Anggota wajib dipilih."
        ],
        "user_id": [
            "Petugas (user_id) wajib diisi."
        ],
        "tanggal_pinjam": [
            "Tanggal pinjam wajib diisi."
        ],
        "tanggal_kembali": [
            "Tanggal kembali wajib diisi."
        ],
        "book_ids": [
            "Pilih minimal satu buku."
        ]
    }
}
```

### 10. PUT `/api/loans/{id}/kembalikan`
Memproses pengembalian peminjaman buku. Mengubah kolom `status` menjadi `"dikembalikan"` dan mencatat `tanggal_dikembalikan` dengan tanggal hari ini.

- **Method**: `PUT`
- **URL**: `/api/loans/{id}/kembalikan`
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "data": {
        "id": 6,
        "member": {
            "id": 1,
            "nama": "Mohammad Ihsan Septiano Firdaus"
        },
        "petugas": {
            "id": 2,
            "name": "Petugas Satu"
        },
        "tanggal_pinjam": "2026-10-20",
        "tanggal_kembali": "2026-10-27",
        "tanggal_dikembalikan": "2026-10-20",
        "status": "dikembalikan",
        "books": [
            {
                "id": 1,
                "judul": "Sangkuriang (Edisi Bergambar)"
            },
            {
                "id": 2,
                "judul": "Malin Kundang"
            }
        ]
    }
}
```

#### Response Gagal: `404 Not Found`
```json
{
    "message": "No query results for model [App\\Models\\Loan] 999"
}
```

### 11. GET `/api/stats`
Mengambil ringkasan statistik aplikasi: total judul buku, total anggota, dan total transaksi peminjaman yang masih aktif (`dipinjam`). Endpoint ini digunakan untuk kartu ringkasan dashboard.

- **Method**: `GET`
- **URL**: `/api/stats`
- **Headers**: `Accept: application/json`
- **Body**: `-`

#### Response Sukses: `200 OK`
```json
{
    "total_buku": 4,
    "total_anggota": 1,
    "peminjaman_aktif": 1
}
```

---

## 🛠️ Catatan Desain Arsitektur & Best Practices

1. **API Resource & Konsistensi Output**:
   - `BookResource`: Memformat data buku sekaligus melakukan nested transformer pada relasi `category` menggunakan null-safe operator (`$this->category?->...`).
   - `LoanResource`: Melakukan transformasi nested untuk `member`, `user` (diekspos sebagai `petugas`), dan koleksi item buku (`books`), mengisolasi format internal model dari konsumsi klien luar.
   - `MemberResource`: Mengontrol atribut anggota yang diekspos ke publik secara terstruktur.
2. **Pencegahan N+1 Query Problem**:
   - Pada `Api\BookController@index` dan `show`, relasi `category` di-eager load melalui `Book::with('category')`.
   - Pada `Api\LoanController@index`, ketiga relasi di-eager load melalui `Loan::with(['member', 'user', 'loanItems.book'])`.
3. **Database Consistency & `$model->refresh()`**:
   - Pada `Api\LoanController@store`, pemanggilan `$loan->refresh()` memastikan nilai default database (`status = 'dipinjam'`) terbaca sebelum dimasukkan ke dalam `LoanResource`.
4. **Pagination Metadata**:
   - Pemanggilan `Resource::collection($paginator)` otomatis membungkus metadata (`links` dan `meta`) sehingga klien tidak perlu menghitung manual jumlah total halaman maupun navigasi URL berikutnya.


