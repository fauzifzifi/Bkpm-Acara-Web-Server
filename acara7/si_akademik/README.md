# SI Akademik - Acara 7: Database MySQL & Model (Workshop SI Web Server TIF330805)

Lanjutan Acara 6. Data mahasiswa sekarang diambil dari database `si_akademik` lewat PDO.

## Langkah
1. Buat database `si_akademik` di phpMyAdmin.
2. Jalankan CREATE TABLE dan INSERT dari modul.
3. Tugas mandiri, jalankan di tab SQL:
   ```sql
   ALTER TABLE mahasiswa
     ADD COLUMN status ENUM('aktif','cuti','lulus') NOT NULL DEFAULT 'aktif' AFTER angkatan;
   UPDATE mahasiswa SET status = 'aktif';
   ```
4. Sesuaikan `config/database.php` bila user/password MySQL berbeda.
5. Login (`admin` / `admin123`), lalu buka `/mahasiswa`.

## File baru/berubah
- `config/database.php`
- `app/Core/Model.php` (Model dasar, koneksi PDO)
- `app/Models/MahasiswaModel.php` (method `all()`)
- `app/Controllers/MahasiswaController.php` dan `app/Views/mahasiswa/index.php`
