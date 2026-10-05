<?php
namespace App\Models;

class Mahasiswa
{
    // Properties / Atribut
    private string $nim;
    private string $nama;
    private string $prodi;

    // Constructor
    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
    }

    // Getter Methods
    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    // Tugas Mandiri: angkatan dari 2 digit awal NIM
    public function getAngkatan(): string
    {
        return '20' . substr($this->nim, 0, 2); // '24' -> '2024'
    }
}
