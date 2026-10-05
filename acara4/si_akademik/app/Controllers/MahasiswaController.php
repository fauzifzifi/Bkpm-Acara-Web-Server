<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Mahasiswa.php';
use App\Models\Mahasiswa;

class MahasiswaController
{
    public function index()
    {
        // 1. Instansiasi Object Model Mahasiswa (Simulasi Data)
        $mhs1 = new Mahasiswa('2401001', 'Budi Santoso', 'Teknik Informatika');
        $mhs2 = new Mahasiswa('2302015', 'Siti Aminah', 'Sistem Informasi');
        $mhs3 = new Mahasiswa('2203112', 'Andi Wijaya', 'Manajemen Informatika');

        // Masukkan object ke dalam array
        $listMahasiswa = [$mhs1, $mhs2, $mhs3];

        // 2. Tentukan file konten (View) yang akan dirender
        $content = __DIR__ . '/../Views/mahasiswa/index.php';

        // 3. Panggil Layout Utama (variabel $listMahasiswa dan $content otomatis dikenali)
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
?>