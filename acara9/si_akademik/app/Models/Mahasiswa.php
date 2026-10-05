<?php
namespace App\Models;

use InvalidArgumentException;

/**
 * Entity Mahasiswa.
 * Semua atribut private; diakses lewat getter dan diubah lewat setter.
 * Validasi diletakkan di setter, sehingga objek Mahasiswa tidak pernah
 * berisi data yang tidak valid (nama kosong, NIM bukan angka, dst.).
 */
class Mahasiswa
{
    public const STATUS = ['aktif', 'cuti', 'lulus'];

    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private ?string $email = null;
    private int $prodiId = 0;
    private int $angkatan = 0;
    private string $status = 'aktif';
    private ?string $prodiNama = null;   // hasil JOIN, hanya untuk tampilan

    // ---------- Getter ----------
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getProdiNama(): ?string
    {
        return $this->prodiNama;
    }

    // ---------- Setter (dengan validasi) ----------
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNim(string $nim): void
    {
        $nim = trim($nim);
        // NIM harus berupa angka
        if (!preg_match('/^\d{4,20}$/', $nim)) {
            throw new InvalidArgumentException('NIM harus berupa angka (4-20 digit).');
        }
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);
        // Nama tidak boleh kosong
        if ($nama === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong.');
        }
        if (self::length($nama) > 100) {
            throw new InvalidArgumentException('Nama maksimal 100 karakter.');
        }
        $this->nama = $nama;
    }

    public function setEmail(?string $email): void
    {
        $email = $email === null ? '' : trim($email);

        if ($email === '') {            // email boleh kosong (NULL di database)
            $this->email = null;
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || self::length($email) > 100) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        $this->email = $email;
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId < 1) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }
        $this->prodiId = $prodiId;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 1901 || $angkatan > (int) date('Y') + 1) {
            throw new InvalidArgumentException('Angkatan tidak valid.');
        }
        $this->angkatan = $angkatan;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, self::STATUS, true)) {
            throw new InvalidArgumentException('Status harus aktif, cuti, atau lulus.');
        }
        $this->status = $status;
    }

    public function setProdiNama(?string $prodiNama): void
    {
        $this->prodiNama = $prodiNama;
    }

    private static function length(string $text): int
    {
        return function_exists('mb_strlen') ? mb_strlen($text) : (int) preg_match_all('/./us', $text);
    }
}
