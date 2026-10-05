<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\MahasiswaModel;

class MahasiswaController extends Controller
{
    // GET /mahasiswa -> tampilkan data dari database
    public function index()
    {
        $daftarMahasiswa = [];
        $error = null;

        try {
            $model = new MahasiswaModel();
            $daftarMahasiswa = $model->all();
        } catch (\PDOException $e) {
            $error = 'Gagal mengambil data dari database: ' . $e->getMessage();
        }

        $this->view('mahasiswa/index', [
            'title'           => 'Daftar Mahasiswa',
            'daftarMahasiswa' => $daftarMahasiswa,
            'error'           => $error,
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
        echo "Simpan data mahasiswa (belum diimplementasikan)";
    }

    // GET /mahasiswa/edit
    public function edit()
    {
        $this->view('mahasiswa/edit', ['title' => 'Edit Mahasiswa']);
    }
}
