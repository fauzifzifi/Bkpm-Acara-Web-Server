<?php
namespace App\Repositories;

use App\Core\Database;
use App\Core\Paginator;
use PDO;

class ProdiRepository
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    /** Semua prodi (untuk dropdown). */
    public function all(): array
    {
        return $this->pdo->query("SELECT id, kode, nama FROM prodi ORDER BY nama")->fetchAll();
    }

    public function paginate(int $page, int $perPage = 5): array
    {
        return Paginator::paginate($this->pdo, 'id, kode, nama', 'prodi', '', [], 'kode', $page, $perPage);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, kode, nama FROM prodi WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)");
        $stmt->execute([':kode' => $d['kode'], ':nama' => $d['nama']]);
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare("UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id");
        $stmt->execute([':kode' => $d['kode'], ':nama' => $d['nama'], ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM prodi WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
