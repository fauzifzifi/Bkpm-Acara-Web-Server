# SI Akademik - Acara 13: Service Layer & Flash Message (Workshop SI Web Server TIF330805)

Refactoring proses tambah dan ubah data mahasiswa agar memakai **Service Layer**.

## Alur
```
Request -> MahasiswaController  (mengatur alur: panggil Service, simpan Flash, redirect)
              -> MahasiswaService   (logika bisnis: validasi, aturan NIM unik, prodi tersedia)
                   -> MahasiswaRepository (akses data / query)
                        -> Database
```

## Langkah Praktikum
| No | Tugas | Lokasi |
|---|---|---|
| 1 | Class `MahasiswaService` | `app/Services/MahasiswaService.php` |
| 2 | Inject `MahasiswaRepository` dan `ProdiRepository` lewat constructor | constructor `MahasiswaService` (dirakit `Core/Container`) |
| 3 | Validasi dipindah dari Controller ke Service | `MahasiswaService::validate()` |
| 4 | `create()` menangani penambahan | `MahasiswaService::create()` |
| 5 | `update()` menangani perubahan | `MahasiswaService::update()` |
| 6 | Controller tanpa validasi dan tanpa query | `MahasiswaController` hanya bergantung pada `MahasiswaService` |
| 7 | Mekanisme Flash Message dengan `$_SESSION` | `app/Core/Flash.php` |
| 8 | Pesan: berhasil ditambahkan / diubah / gagal disimpan / NIM sudah terdaftar | `MahasiswaController` + `MahasiswaService` |
| 9 | Pesan dihapus dari session setelah tampil | `Flash::pull()` (dipakai `Views/partials/flash.php`) |
| 10 | Uji lewat browser | lihat bawah |

## Service
`create()` dan `update()` mengembalikan array hasil, tanpa menyentuh HTTP/session:
```php
['success' => true,  'id' => 12]
['success' => false, 'errors' => ['nim' => 'NIM sudah terdaftar']]
```
Aturan yang dicek: NIM wajib diisi, nama tidak boleh kosong, email valid, program studi tersedia,
NIM belum terdaftar (saat update, NIM milik sendiri boleh tetap sama).
Aturan format ada di setter `Models/Mahasiswa` (Acara 9); aturan yang butuh data lain dicek lewat Repository.

## Flash Message
```php
Flash::set('success', 'Data berhasil ditambahkan');   // disimpan di $_SESSION['flash']
$flash = Flash::pull();                               // dibaca lalu langsung dihapus dari session
```
Alur: Controller menyimpan flash, redirect ke `/mahasiswa`, halaman menampilkannya sekali, lalu pesan hilang.

## Pengujian di browser
1. Login `admin` / `admin123`, buka `/mahasiswa`.
2. Tambah mahasiswa valid, muncul "Data berhasil ditambahkan", reload dan pesan hilang.
3. Tambah dengan NIM yang sudah ada, muncul "NIM sudah terdaftar".
4. Ubah data, muncul "Data berhasil diubah".
5. Kosongkan nama / isi email salah, muncul daftar pesan validasi dari Service.

## Perubahan dari Acara 10
- Baru: `Services/MahasiswaService.php`, `Core/Flash.php`, `MahasiswaRepository::existsByNim()`.
- `MahasiswaController` hanya menerima `MahasiswaService`, tidak lagi `MahasiswaRepository`/`ProdiRepository`.
- `BaseModel::guard()`: error database tak terduga dicatat ke `storage/logs/error.log` lalu dilaporkan sebagai
  `RepositoryException` (sebelumnya langsung halaman 500), sehingga muncul pesan "Data gagal disimpan".
- Prodi dan Mata Kuliah tidak diubah (di luar tugas ini).
