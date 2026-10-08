# SI Akademik - Acara 15: API dan JSON (Workshop SI Web Server TIF330805)

API Data Mahasiswa yang dapat dipakai aplikasi lain. Frontend meminta data lewat API, bukan mengakses database langsung:

```
Frontend -> Request -> API (MahasiswaApiController) -> Service -> Repository -> MySQL
Frontend <- Response JSON <-------------------------------------------------------
```

## Langkah Praktikum
| No | Tugas | Lokasi |
|---|---|---|
| 1 | Database dan data awal (23001 Budi, 23002 Siti, 23003 Andi) | `database/si_akademik.sql` + `database/api_data_awal.sql` |
| 2 | Endpoint API: `api/mahasiswa.php` | `public/api/mahasiswa.php` |
| 3 | Koneksi PDO | `app/Core/Database.php` |
| 4 | Ambil seluruh data mahasiswa (SELECT) | `MahasiswaRepository::all()` |
| 5 | Hasil query menjadi JSON (`Content-Type: application/json`, `json_encode`) | `app/Core/JsonResponse.php` |
| 6 | Response terstruktur `{success, message, data}` | `JsonResponse::send()` |
| 7 | Uji lewat browser dan Postman | di bawah |

## Endpoint
| Method | URL | Fungsi |
|---|---|---|
| GET | `/api/mahasiswa` | semua mahasiswa |
| GET | `/api/mahasiswa?id=1` | satu mahasiswa |
| POST | `/api/mahasiswa` | tambah mahasiswa (body JSON) |

Alamat versi file seperti di modul juga tersedia: `/api/mahasiswa.php` (hasilnya identik).
XAMPP: `http://localhost/si_akademik/public/api/mahasiswa.php`

## Contoh
**GET /api/mahasiswa**
```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        { "id": 4, "nim": "23001", "nama": "Budi", "email": "budi@gmail.com",
          "prodi": "Teknik Informatika", "angkatan": 2023, "status": "aktif" }
    ]
}
```

**POST /api/mahasiswa** (Postman: Body > raw > JSON, atau curl)
```
curl -X POST http://localhost:8000/api/mahasiswa \
     -H "Content-Type: application/json" \
     -d '{"nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com"}'
```
Response `201 Created`:
```json
{ "success": true, "message": "Data berhasil ditambahkan",
  "data": { "id": 7, "nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com",
            "prodi": "Teknik Informatika", "angkatan": 2023, "status": "aktif" } }
```

**Nilai bawaan POST.** Body di modul hanya berisi `nim`, `nama`, `email`, sedangkan tabel mahasiswa juga mewajibkan
`prodi_id` dan `angkatan`. Bila tidak dikirim: `angkatan` diambil dari 2 digit awal NIM (23004 -> 2023),
`status` = aktif, `prodi_id` = prodi berkode `api_default_prodi` di `config/app.php` (bawaan `TI`).
Ketiganya boleh dikirim sendiri. Isi `api_default_prodi` dengan `null` agar `prodi_id` wajib dikirim.

## Kode status HTTP
| Kode | Arti |
|---|---|
| 200 | berhasil (GET) |
| 201 | berhasil menambah (POST) |
| 400 | id tidak valid, atau body bukan objek JSON yang benar |
| 404 | mahasiswa / endpoint tidak ditemukan |
| 405 | method tidak diizinkan (header `Allow: GET, POST`) |
| 422 | data tidak valid, mis. `{"success": false, "message": "Data tidak valid", "data": null, "errors": {"nim": "NIM sudah terdaftar."}}` |
| 500 | kesalahan server (detail hanya di `storage/logs/app.log`) |

## Keamanan dan catatan
- Semua query memakai prepared statement; `id` divalidasi sebagai angka sebelum dipakai.
- Error teknis tidak pernah dikirim ke klien; hanya `"Terjadi kesalahan pada server"`, detail masuk `app.log`.
- API bersifat stateless (tanpa session/cookie).
- **API ini terbuka tanpa login, sesuai modul.** Siapa pun yang bisa mengakses alamatnya dapat membaca dan menambah data.
  Cukup untuk praktikum di localhost; sebelum dipasang di server publik perlu autentikasi (mis. API key/token).

## Perubahan dari Acara 14
- Baru: `Controllers/Api/MahasiswaApiController.php`, `Core/JsonResponse.php`, `Core/ErrorHandler.php`,
  `autoload.php`, `public/api/{mahasiswa,index}.php`, `database/api_data_awal.sql`.
- `Router`: grup route `ANY` (semua method) dan 404 berbentuk JSON untuk URL `/api/...`.
- `ErrorHandler`: error di URL `/api/...` dijawab JSON, halaman web tetap HTML 500.
- `public/index.php`: session hanya dimulai untuk halaman web.
- `public/api/index.php` diperlukan agar `/api/mahasiswa` juga jalan di server bawaan PHP (`php -S`);
  di Apache/XAMPP URL itu ditangani `.htaccess`.

## Menjalankan
Sesuaikan `config/database.php`. XAMPP: `http://localhost/si_akademik/public/` | Tanpa XAMPP: `cd public` lalu `php -S localhost:8000`.
Halaman web: login `admin` / `admin123`.
