# SI Akademik - Acara 14 (CRUD Mahasiswa Aman) + Acara 15 (API JSON) - Workshop SI Web Server TIF330805

Menggabungkan seluruh konsep sebelumnya: Security, Validasi, Prepared Statement, Exception Handling,
Logging, Session, Flash Message, PRG, dan CRUD lengkap.

## Alur
```
User -> Controller -> Service (validasi) -> Repository -> MySQL
                         |
                  Exception jika terjadi error
                         v
        Logging (storage/logs/app.log) -> Flash Message -> Redirect (PRG) -> User melihat hasil
```

## Langkah Praktikum
| No | Tugas | Lokasi |
|---|---|---|
| 1 | Database MySQL | `database/si_akademik.sql` (opsional bila database sudah ada) |
| 2 | Koneksi PDO | `app/Core/Database.php`, `config/database.php` |
| 3 | Repository + Prepared Statement: `all`, `find`, `create`, `update`, `delete`, `existsByNim` | `app/Repositories/MahasiswaRepository.php` |
| 4 | Service: validasi NIM, nama, email, NIM duplikat, memanggil Repository | `app/Services/MahasiswaService.php` |
| 5 | Exception Handling (try-catch pada proses CRUD) | `app/Controllers/MahasiswaController.php` |
| 6 | Logging ke `storage/logs/app.log` | `app/Core/Logger.php` |
| 7 | Flash Message | `app/Core/Flash.php` |
| 8 | PRG: POST -> proses -> redirect -> GET | semua method POST di Controller |
| 9 | CRUD lengkap | route di bawah |

## Halaman CRUD
| URL | Fungsi |
|---|---|
| `GET  /mahasiswa` | daftar mahasiswa (+ pencarian, pagination) |
| `GET  /mahasiswa/create` | form tambah |
| `POST /mahasiswa` | proses tambah |
| `GET  /mahasiswa/edit?id=1` | form edit |
| `POST /mahasiswa/update` | proses ubah (id dikirim via POST) |
| `POST /mahasiswa/delete` | proses hapus (id dikirim via POST) |

## Pesan Flash
- Data mahasiswa berhasil ditambahkan. / diubah. / dihapus.
- Data gagal disimpan. / Data gagal dihapus.
- NIM sudah terdaftar. (dan pesan validasi lain)

## Exception: dua jenis
| Jenis | Contoh | Ke pengguna | Ke log |
|---|---|---|---|
| `ValidationException` | NIM kosong, NIM sudah terdaftar | pesan aslinya (aman) | tidak |
| Error teknis (`RepositoryException`, dst.) | database menolak data, koneksi putus | hanya "Data gagal disimpan." | detail lengkap |

## Keamanan
- Prepared statement di semua query (input pengguna tidak pernah digabung ke string SQL).
- Validasi input di Service; output di View selalu di-escape (`e()`) untuk mencegah XSS.
- Pengguna tidak melihat error teknis: `'debug' => false` di `config/app.php` (isi `true` hanya saat belajar).
- Log tidak memuat password database (disamarkan) dan tidak memuat isi form; baris baru dibuang agar log tidak bisa dipalsukan.
- Semua POST berakhir dengan redirect, jadi refresh tidak mengirim ulang form.

## Contoh isi `storage/logs/app.log`
```
2026-10-05 19:28:21 - [App\Controllers\MahasiswaController::store] Operasi database gagal. (BaseModel.php:43) | penyebab: SQLSTATE[23000]: ...
2026-10-05 19:28:38 - [Unhandled] Database connection failed (Database.php:29) | penyebab: SQLSTATE[HY000] [1049] Unknown database ...
```

## Skenario Pengujian (modul)
1. Tambah data valid  2. NIM kosong  3. NIM sudah terdaftar  4. Email tidak valid  5. Edit  6. Hapus
7. Refresh setelah tambah  8. Simulasi error database  9. Periksa `app.log`  10. Pastikan pesan error aman.

## Perubahan dari Acara 13
- Service sekarang melempar exception (`ValidationException`) dan Controller menangkapnya (sebelumnya mengembalikan array hasil).
- Baru: `Core/Logger.php`, `Exceptions/ValidationException.php`, `database/si_akademik.sql`.
- Log sekarang ke `storage/logs/app.log` (sebelumnya `error.log`); `debug` default `false`.
- Route mahasiswa mengikuti modul: `/mahasiswa/edit?id=1`, `/mahasiswa/update`, `/mahasiswa/delete`.
- Email kini wajib diisi (sesuai kolom `email NOT NULL` di modul Acara 14).
- Prodi dan Mata Kuliah tidak diubah, kecuali error tak terduga kini ikut tercatat di `app.log`.

## Menjalankan
Sesuaikan `config/database.php`, login `admin` / `admin123`.
XAMPP: `http://localhost/si_akademik/public/` | Tanpa XAMPP: `cd public` lalu `php -S localhost:8000`.

---

# Acara 15: API Data Mahasiswa (Response JSON)

Endpoint JSON yang memakai lapisan Acara 14 (Service -> Repository -> PDO), jadi aturan validasi
dan keamanannya sama dengan CRUD di web. Tidak ada SQL di file endpoint.

## Berkas baru / berubah
| Berkas | Keterangan |
|---|---|
| `api/mahasiswa.php` | Endpoint (langkah 2-6 modul) |
| `api/.htaccess` | Agar `/api/mahasiswa` (tanpa `.php`) dan `?id=1` bisa dipakai di Apache/XAMPP |
| `app/Core/ApiResponse.php` | Pembentuk response `{success, message, data}` |
| `app/Services/MahasiswaService.php` | + method `all()` |
| `config/app.php` | + `api_default_prodi_id` |
| `database/si_akademik.sql` | + data contoh 23001-23003 (sama dengan modul) |

## Endpoint
| Method | URL | Hasil |
|---|---|---|
| GET  | `/api/mahasiswa.php` atau `/api/mahasiswa` | semua mahasiswa |
| GET  | `/api/mahasiswa.php?id=1` | satu mahasiswa |
| POST | `/api/mahasiswa.php` | tambah mahasiswa (body JSON) |

Contoh URL XAMPP: `http://localhost/si_akademik/api/mahasiswa.php`

POST (Postman: Body -> raw -> JSON):
```json
{ "nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com" }
```
Field opsional `prodi_id`, `angkatan`, `status`. Bila tidak dikirim: prodi = `api_default_prodi_id`
(config), angkatan = tahun berjalan, status = `aktif`.

## Format response
```json
{ "success": true, "message": "Data berhasil diambil", "data": [] }
```
Error validasi menambah `errors`: `{ "success": false, "message": "Data tidak valid.", "data": null, "errors": { "nim": "NIM sudah terdaftar." } }`

| Kode | Kapan |
|---|---|
| 200 | GET berhasil |
| 201 | POST berhasil |
| 400 | `id` bukan angka positif, atau body bukan JSON valid |
| 404 | `?id=` tidak ditemukan |
| 405 | method selain GET/POST (header `Allow: GET, POST`) |
| 422 | validasi gagal (NIM kosong/duplikat, email tidak valid, prodi tidak ada, dst.) |
| 500 | error teknis: klien hanya melihat pesan umum, detail ke `storage/logs/app.log` |

## Skenario uji (Postman)
1. GET semua  2. GET `?id=1`  3. GET `?id=999` (404)  4. GET `?id=abc` (400)
5. POST data tugas (201)  6. POST ulang (422 NIM duplikat)  7. POST NIM/nama kosong + email salah (422)
8. POST JSON rusak (400)  9. DELETE (405)

## Catatan
- API belum memakai autentikasi (sesuai modul). Jangan dipublikasikan ke internet apa adanya.
- Tanpa XAMPP: dari folder proyek jalankan `php -S localhost:8000`, lalu buka `/api/mahasiswa.php`
  (bentuk tanpa `.php` hanya berjalan di Apache karena memakai `.htaccess`).
