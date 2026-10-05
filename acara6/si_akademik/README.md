# SI Akademik - Acara 6: Middleware & Session (Workshop SI Web Server TIF330805)

PHP native, struktur MVC sederhana, tanpa Composer.

## Menjalankan
- XAMPP: taruh di `htdocs/si_akademik`, buka `http://localhost/si_akademik/public/`
- Tanpa XAMPP: `cd public` lalu `php -S localhost:8000`

## Login (hardcode sementara)
`admin` / `admin123`

## Pengujian
1. Buka `/mahasiswa` tanpa login -> redirect ke `/login`
2. Login benar -> `/dashboard` dengan alert "Selamat datang, Admin"
3. Logout -> `/login` dengan alert "Anda telah logout"
