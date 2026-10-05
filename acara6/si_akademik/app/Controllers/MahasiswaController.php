<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    // GET /mahasiswa (data dummy, belum pakai database)
    public function index()
    {
        $daftarMahasiswa = [
            new Mahasiswa('24010001', 'Budi Santoso', 'Teknik Informatika'),
            new Mahasiswa('24010002', 'Siti Aminah', 'Sistem Informasi'),
            new Mahasiswa('23010003', 'Andi Wijaya', 'Teknik Informatika'),
        ];

        $this->view('mahasiswa/index', [
            'title'           => 'Daftar Mahasiswa',
            'daftarMahasiswa' => $daftarMahasiswa,
        ]);
    }

    // GET /mahasiswa/create
    public function create()
    {
        $this->view('mahasiswa/create', ['title' => 'Tambah Mahasiswa']);
    }

    // POST /mahasiswa
    public function store()
    {
        echo "Simpan data mahasiswa (simulasi, belum ada database)";
    }

    // GET /mahasiswa/edit
    public function edit()
    {
        $this->view('mahasiswa/edit', ['title' => 'Edit Mahasiswa']);
    }
}
