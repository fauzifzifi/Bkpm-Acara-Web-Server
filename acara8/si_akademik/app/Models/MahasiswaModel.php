<?php
namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    // JOIN one-to-many: banyak mahasiswa -> satu prodi (tampilkan nama prodi)
    private const COLUMNS = "m.id, m.nim, m.nama, m.email, m.prodi_id, m.angkatan, m.status, p.nama AS prodi_nama";
    private const FROM    = "mahasiswa m JOIN prodi p ON p.id = m.prodi_id";

    public function all(): array
    {
        return $this->pdo->query(
            "SELECT " . self::COLUMNS . " FROM " . self::FROM . " ORDER BY m.nim"
        )->fetchAll();
    }

    /**
     * Daftar + pagination + pencarian (Tugas Mandiri).
     * Pencarian memakai LIKE dengan prepared statement, jadi input berbahaya
     * seperti ' OR '1'='1 diperlakukan sebagai teks biasa, bukan perintah SQL.
     */
    public function paginate(string $search, int $page, int $perPage = 5): array
    {
        $where  = '';
        $params = [];

        if ($search !== '') {
            // Dua placeholder berbeda (:q1, :q2) karena emulate prepares dimatikan
            $where  = 'WHERE (m.nama LIKE :q1 OR m.nim LIKE :q2)';
            $like   = '%' . addcslashes($search, '\\%_') . '%';   // escape wildcard dari user
            $params = [':q1' => $like, ':q2' => $like];
        }

        return $this->paginateQuery(self::COLUMNS, self::FROM, $where, $params, 'm.nim', $page, $perPage);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, nim, nama, email, prodi_id, angkatan, status FROM mahasiswa WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
        );
        $stmt->execute($this->bind($d));
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id"
        );
        $stmt->execute($this->bind($d) + [':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }

    private function bind(array $d): array
    {
        return [
            ':nim'      => $d['nim'],
            ':nama'     => $d['nama'],
            ':email'    => $d['email'] !== '' ? $d['email'] : null,   // email boleh kosong (NULL)
            ':prodi_id' => $d['prodi_id'],
            ':angkatan' => $d['angkatan'],
            ':status'   => $d['status'],
        ];
    }
}
