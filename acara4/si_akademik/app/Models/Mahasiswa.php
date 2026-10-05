<?php
// app/Models/Mahasiswa.php
namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
    }

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

    public function setNama(string $nama): void
    {
        if (strlen($nama) < 3) {
            throw new \InvalidArgumentException("Nama terlalu pendek");
        }
        $this->nama = $nama;
    }

    // Tugas Mandiri: angkatan dari 2 digit awal NIM
    public function getAngkatan(): string
    {
        // Ambil hanya bagian angka dari NIM, lalu ambil 2 digit pertamanya
        $angkaSaja = preg_replace('/[^0-9]/', '', $this->nim);
        $duaDigit = substr($angkaSaja, 0, 2);
        return '20' . $duaDigit;
    }
}