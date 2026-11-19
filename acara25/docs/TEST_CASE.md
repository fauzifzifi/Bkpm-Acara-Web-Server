# Laporan Pengujian API

## 1. Ruang Lingkup Pengujian

Pengujian mencakup endpoint yang terdaftar pada `Router`:

| Method | Endpoint | Controller/aksi | Autentikasi |
|---|---|---|---|
| POST | `/register` | `AuthController::register()` | Tidak |
| POST | `/login` | `AuthController::login()` | Tidak |
| GET | `/mahasiswa` | `MahasiswaController::index()` | Tidak |
| POST | `/mahasiswa` | `MahasiswaController::store()` | Bearer token |
| PUT | `/mahasiswa?id={id}` | `MahasiswaController::update()` | Bearer token |
| DELETE | `/mahasiswa?id={id}` | `MahasiswaController::destroy()` | Bearer token |

Pengujian manual dilakukan pada `http://127.0.0.1:8000` menggunakan request
HTTP dengan format JSON. Token pada respons login tidak dicantumkan secara
lengkap demi keamanan.

## 2. Test Case Equivalence Partitioning

| ID | Skenario | Data uji | Hasil yang diharapkan |
|---|---|---|---|
| TC01 | NIM valid dan lengkap (`MahasiswaController::store`) | `nim=220100123`, nama dan prodi terisi | 201 Created |
| TC02 | NIM kosong (partisi tidak valid) | `nim=""` | 400 atau 422 |
| TC03 | NIM duplikat (partisi tidak valid) | NIM milik data lain: `220100123` | 422 Unprocessable Entity |
| TC04 | Login dengan kredensial benar (`AuthController::login`) | Username dan password pengguna uji yang benar | 200 OK, token diterima |
| TC05 | Login dengan password salah | Password salah untuk username yang valid | 401 Unauthorized |
| TC06 | Akses store tanpa token (`Auth::requireAuth`) | Tanpa header `Authorization` | 401 Unauthorized |

## 3. Hasil Eksekusi Manual

Pengguna uji dibuat terlebih dahulu melalui `POST /register`, kemudian
digunakan untuk TC04, TC05, dan memperoleh token untuk TC01--TC03.

| ID | Status HTTP aktual | Respons aktual | Status |
|---|---:|---|---|
| TC01 | 201 Created | `Data berhasil ditambahkan` (id data: `6`) | **Pass** |
| TC02 | 400 Bad Request | `Field nim, nama, dan prodi wajib diisi` | **Pass** |
| TC03 | 201 Created | `Data berhasil ditambahkan` (id data: `7`) | **Fail** |
| TC04 | 200 OK | `Login berhasil`, token diterima, role `user` | **Pass** |
| TC05 | 401 Unauthorized | `Username atau password salah` | **Pass** |
| TC06 | 401 Unauthorized | `Token tidak ditemukan` | **Pass** |

### Catatan TC03

TC03 gagal karena data dengan NIM `220100123` dapat dibuat kembali dan API
mengembalikan `201 Created`. Implementasi saat ini belum melakukan validasi
keunikan NIM atau mengubah pelanggaran duplikasi menjadi `422 Unprocessable
Entity`.

## 4. Ringkasan

- Total test case: **6**
- Pass: **5**
- Fail: **1**
- Persentase keberhasilan: **83,33%**

Kegagalan yang ditemukan adalah validasi NIM duplikat pada `POST /mahasiswa`.