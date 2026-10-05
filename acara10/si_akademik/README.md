# SI Akademik - Acara 10: Inheritance & Repository Pattern (Workshop SI Web Server TIF330805)

Lanjutan Acara 9. Fiturnya tetap sama (CRUD, JOIN, pagination, pencarian), struktur kodenya dirapikan.

## Alur
```
Router -> Container -> MahasiswaController (extends BaseController)
                           -> MahasiswaRepository (extends BaseModel, memegang PDO)
                                -> MySQL
```

## Prosedur Kerja
| No | Tugas | File |
|---|---|---|
| 1 | `BaseController` berisi `view()` dan `redirect()` | `app/Core/BaseController.php` |
| 2 | `MahasiswaController extends BaseController`, menampilkan halaman lewat `view()` warisan | `app/Controllers/MahasiswaController.php` |
| 3 | `MahasiswaRepository` menangani seluruh operasi database | `app/Repositories/MahasiswaRepository.php` |
| 4 | Controller hanya memanggil `$repo->all()`, `find()`, `create()`, `update()`, `delete()`, tanpa SQL | `MahasiswaController` |
| 5 | Tampil, tambah, ubah, hapus berjalan | route `/mahasiswa` |

## Inheritance
- `BaseController`: `view()`, `redirect()` (+ `flash()`, `failWithOld()`, `repoMessage()`). Ditulis sekali, diwarisi semua controller.
- `BaseModel` (disebut di Studi Kasus): menyimpan `PDO` yang disuntikkan lewat constructor dan helper
  pagination. Diwarisi semua Repository, jadi tidak ada copy-paste.

## Repository Pattern
- Seluruh SQL (prepared statement) hanya ada di `app/Repositories/`.
- Kode error khusus MySQL (1062, 1451, 1452) hanya ada di `BaseModel::guard()`, yang menerjemahkannya menjadi
  exception aplikasi (`app/Exceptions/`: `DuplicateEntryException`, `InUseException`, `InvalidReferenceException`).
  Controller cukup menangkap `RepositoryException`, tanpa mengenal PDO atau MySQL.
- Jika database berubah, yang diubah hanya Repository (dan `guard()`), bukan Controller.

## Perubahan dari Acara 9
- `Core/Controller.php` diganti nama menjadi `Core/BaseController.php`.
- Baru: `Core/BaseModel.php` (menggantikan `Core/Paginator.php`) dan folder `app/Exceptions/`.
- Repository sekarang menerima `PDO` (bukan objek `Database`); `Core/Container.php` yang menyuntikkannya.
- Controller tidak lagi menangkap `PDOException`.

## Menjalankan
Database `si_akademik` sudah ada, sesuaikan `config/database.php`, login `admin` / `admin123`.
