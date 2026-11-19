# Dokumentasi API

Base URL mengikuti konfigurasi web server, misalnya `http://localhost/acara_21`.
Semua endpoint mengembalikan `Content-Type: application/json`.

## Format Respons

Setiap respons menggunakan format berikut:

```json
{
  "success": true,
  "message": "Pesan respons",
  "data": {},
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

Nilai `data` dapat berupa object, array, atau `null`, bergantung pada endpoint.

## POST /register

Controller: `AuthController::register()`

Mendaftarkan pengguna baru. Field `username` dan `email` harus unik.

**Headers:** `Content-Type: application/json`

**Request Body:**

```json
{
  "username": "admin",
  "email": "admin@example.com",
  "password": "rahasia123"
}
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Registrasi berhasil",
  "data": {
    "id": "3"
  },
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

**Response 400 Bad Request:**

```json
{
  "success": false,
  "message": "Username, email, dan password wajib diisi",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## POST /login

Controller: `AuthController::login()`

Melakukan login dan menghasilkan token Bearer yang berlaku selama 1 jam.

**Headers:** `Content-Type: application/json`

**Request Body:**

```json
{
  "username": "admin",
  "password": "rahasia123"
}
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "token": "eyJ...signature",
    "role": "user"
  },
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

**Response 401 Unauthorized:**

```json
{
  "success": false,
  "message": "Username atau password salah",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## GET /mahasiswa

Controller: `MahasiswaController::index()`

Mengambil seluruh data mahasiswa, diurutkan berdasarkan `id` terbaru.

**Headers:** Tidak ada header khusus.

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": [
    {
      "id": 5,
      "nim": "123456789",
      "nama": "Budi Santoso",
      "prodi": "Teknik Informatika",
      "created_at": "2026-11-01 10:00:00"
    }
  ],
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## GET /mahasiswa?id={id}

Controller: `MahasiswaController::index()`

Mengambil satu data mahasiswa berdasarkan `id`.

**Headers:** Tidak ada header khusus.

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {
    "id": 5,
    "nim": "123456789",
    "nama": "Budi Santoso",
    "prodi": "Teknik Informatika",
    "created_at": "2026-11-01 10:00:00"
  },
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

**Response 404 Not Found:**

```json
{
  "success": false,
  "message": "Data tidak ditemukan",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## POST /mahasiswa

Controller: `MahasiswaController::store()`

Menambahkan data mahasiswa. Endpoint ini memerlukan autentikasi.

**Headers:**

```text
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
  "nim": "123456789",
  "nama": "Budi Santoso",
  "prodi": "Teknik Informatika"
}
```

**Response 201 Created:**

```json
{
  "success": true,
  "message": "Data berhasil ditambahkan",
  "data": {
    "id": "5"
  },
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

**Response 400 Bad Request:**

```json
{
  "success": false,
  "message": "Field nim, nama, dan prodi wajib diisi",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

**Response 422 Unprocessable Entity:**

```json
{
  "success": false,
  "message": "Format NIM tidak valid, harus 9 digit",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## PUT /mahasiswa?id={id}

Controller: `MahasiswaController::update()`

Memperbarui data mahasiswa. Endpoint ini memerlukan autentikasi.

**Headers:**

```text
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
  "nim": "987654321",
  "nama": "Budi Santoso Update",
  "prodi": "Sistem Informasi"
}
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil diperbarui",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## DELETE /mahasiswa?id={id}

Controller: `MahasiswaController::destroy()`

Menghapus data mahasiswa. Endpoint ini memerlukan autentikasi.

**Headers:**

```text
Authorization: Bearer <token>
```

**Response 200 OK:**

```json
{
  "success": true,
  "message": "Data berhasil dihapus",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

## Respons Autentikasi

Endpoint `POST /mahasiswa`, `PUT /mahasiswa?id={id}`, dan
`DELETE /mahasiswa?id={id}` menolak permintaan tanpa token atau dengan token
yang tidak valid.

**Response 401 Unauthorized:**

```json
{
  "success": false,
  "message": "Token tidak ditemukan",
  "data": null,
  "timestamp": "2026-11-01T22:19:46+07:00"
}
```

Untuk token yang tidak valid atau kedaluwarsa, nilai `message` adalah
`"Token tidak valid atau kedaluwarsa"`.