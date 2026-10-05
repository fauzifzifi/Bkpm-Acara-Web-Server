<?php
namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    /**
     * Ambil semua data mahasiswa beserta nama prodinya (JOIN ke tabel prodi).
     */
    public function all(): array
    {
        $sql = "SELECT m.id, m.nim, m.nama, m.email, m.angkatan, m.status,
                       p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id
                ORDER BY m.nim";

        return $this->pdo->query($sql)->fetchAll();
    }
}
