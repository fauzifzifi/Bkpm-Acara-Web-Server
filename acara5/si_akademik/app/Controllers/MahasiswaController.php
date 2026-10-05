<?php

class MahasiswaController
{
    public function index()
    {
        echo "Halaman daftar mahasiswa";
    }

    public function create()
    {
        echo "Halaman tambah mahasiswa";
    }

    public function store()
    {
        echo "Simpan data mahasiswa";
    }

    // Tugas Mandiri: parameter URL /mahasiswa/5
    public function show($id)
    {
        echo "Detail mahasiswa dengan id: " . htmlspecialchars($id);
    }
}