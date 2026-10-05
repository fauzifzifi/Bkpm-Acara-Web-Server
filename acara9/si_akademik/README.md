# SI Akademik - Acara 9: Object Composition, Dependency Injection, Getter/Setter (Workshop SI Web Server TIF330805)

Refactor Acara 8. Fiturnya sama (CRUD, JOIN, pagination, pencarian), tetapi strukturnya
memakai **Object Composition**: Controller memiliki Repository, Repository memiliki PDO.

```
Router -> Container -> MahasiswaController(MahasiswaRepository, ProdiRepository)
                           -> MahasiswaRepository(Database)
                                -> Database::getInstance() -> PDO
```

## Prosedur Kerja
| No | Tugas | File |
|---|---|---|
| 1 | Class `Database` mengelola koneksi PDO | `app/Core/Database.php` |
| 2 | `MahasiswaRepository` menerima `Database` lewat constructor, berisi CRUD | `app/Repositories/MahasiswaRepository.php` |
| 3 | `MahasiswaController` menerima `MahasiswaRepository` lewat constructor (tidak membuat koneksi sendiri) | `app/Controllers/MahasiswaController.php` |
| 4 | Atribut `Mahasiswa` jadi private + getter/setter | `app/Models/Mahasiswa.php` |
| 5 | Validasi di setter: nama tidak boleh kosong, NIM harus angka | `app/Models/Mahasiswa.php` |
| 6 | Aplikasi tetap jalan: tambah, ubah, tampil, hapus | semua route `/mahasiswa` |

## Dependency Injection
Router membuat controller lewat `app/Core/Container.php`. Container membaca type-hint constructor
lalu membuat dan menyuntikkan dependency-nya secara otomatis. Karena itu Controller bisa diuji dengan
repository palsu:

```php
class FakeRepo extends MahasiswaRepository {
    public function __construct() {}            // tanpa Database
    public function paginate(...): array { /* data palsu */ }
}
$controller = new MahasiswaController(new FakeRepo(), new FakeProdiRepo());
```

## Perubahan dari Acara 8
- `Core/Model.php` dan `Models/*Model.php` dihapus, diganti `Repositories/*Repository.php`
  (memakai composition, bukan pewarisan).
- `Database::getInstance()` sekarang mengembalikan objek `Database`; PDO diambil lewat `getConnection()`.
- Repository mahasiswa mengembalikan objek `Mahasiswa` (bukan array), jadi View memakai getter.
- Baru: `Core/Container.php` (DI) dan `Core/Paginator.php` (helper pagination).
- Prodi dan Mata Kuliah ikut dipindah ke Repository + constructor injection (datanya masih array).

## Menjalankan
Sama seperti Acara 8: database `si_akademik` sudah ada, sesuaikan `config/database.php`,
login `admin` / `admin123`.
