<?php
use App\Models\Mahasiswa;

require __DIR__ . '/../app/Models/Mahasiswa.php';

$daftarMahasiswa = [
    new Mahasiswa('E1234356', 'Budi Santoso', 'D4 Teknik Informatika'),
    new Mahasiswa('E2234511', 'Siti Aminah', 'D4 Teknik Informatika'),
];

$content = __DIR__ . '/../app/Views/mahasiswa/index.php';
require __DIR__ . '/../app/Views/layouts/main.php';