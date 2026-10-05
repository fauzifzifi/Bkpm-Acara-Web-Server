# SI Akademik - Acara 8: CRUD dengan PDO Prepared Statement (Workshop SI Web Server TIF330805)

Lanjutan Acara 7. CRUD penuh untuk Mahasiswa, Program Studi, dan Mata Kuliah.

## Persiapan
1. Database `si_akademik` sudah ada (Acara 7), termasuk kolom `status` di tabel `mahasiswa`.
2. Sesuaikan `config/database.php` bila user/password MySQL berbeda.
3. Login `admin` / `admin123`.

## Yang dikerjakan (Prosedur Kerja)
1. **Database singleton**: `app/Core/Database.php` (`Database::getInstance()`), dipakai semua Model lewat `Core/Model`.
2. **Model** (`all`, `find`, `create`, `update`, `delete` + `paginate`): `MahasiswaModel`, `ProdiModel`, `MataKuliahModel`. Semua query pakai prepared statement.
3. **Controller + View CRUD Mahasiswa**: `index` (+ pagination), `create`, `store`, `edit`, `update`, `destroy`.
4. **Prodi dan Mata Kuliah**: pola yang sama.
5. **JOIN**: daftar mahasiswa dan mata kuliah menampilkan nama prodi.
6. **Konfirmasi hapus**: atribut `data-confirm` pada form hapus, ditangani di `public/assets/js/app.js`.

## Tugas Mandiri
Pencarian di `/mahasiswa` berdasarkan nama atau NIM (`LIKE` + prepared statement), tetap jalan bersama pagination.
Input seperti `' OR '1'='1` diperlakukan sebagai teks biasa, bukan perintah SQL.

## Catatan
- `store`, `update`, `destroy` memakai method POST.
- Hapus prodi yang masih dipakai mahasiswa/mata kuliah ditolak oleh FOREIGN KEY, dan pesannya ditampilkan ramah.
- Error tak terduga dicatat di `storage/logs/error.log` dan memunculkan halaman 500
  (detail error tampil jika `debug => true` di `config/app.php`).
