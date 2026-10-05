<?php
namespace App\Models;

use App\Core\Model;

class ProdiModel extends Model
{
    /** Semua prodi (untuk dropdown). */
    public function all(): array
    {
        return $this->pdo->query("SELECT id, kode, nama FROM prodi ORDER BY nama")->fetchAll();
    }

    public function paginate(int $page, int $perPage = 5): array
    {
        return $this->paginateQuery('id, kode, nama', 'prodi', '', [], 'kode', $page, $perPage);
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
