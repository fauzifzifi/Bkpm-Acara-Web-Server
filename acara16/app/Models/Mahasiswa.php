<?php
namespace App\Models;

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $prodi;

    public function __construct(string $nim, string $nama, string $prodi)
    {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setProdi($prodi);
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        if ($nim === '' || !ctype_digit($nim)) {
            throw new \InvalidArgumentException('NIM harus berupa angka.');
        }

        $this->nim = $nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);
        if ($nama === '') {
            throw new \InvalidArgumentException('Nama mahasiswa tidak boleh kosong.');
        }

        $this->nama = $nama;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function setProdi(string $prodi): void
    {
        $this->prodi = $prodi;
    }

    // Tugas Mandiri: angkatan didapat dari 2 digit awal NIM
    public function getAngkatan(): string
    {
        $duaDigitAwal = substr($this->nim, 0, 2);
        return '20' . $duaDigitAwal;
    }
}
