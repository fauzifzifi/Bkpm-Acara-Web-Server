<?php
namespace App\Repositories;

use App\Core\Database;
use App\Core\Paginator;
use PDO;

class MataKuliahRepository
{
    private const COLUMNS = "mk.id, mk.kode, mk.nama, mk.sks, mk.prodi_id, p.nama AS prodi_nama";
    private const FROM    = "matakuliah mk JOIN prodi p ON p.id = mk.prodi_id";

    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->getConnection();
    }

    public function all(): array
    {
        return $this->pdo->query(
            "SELECT " . self::COLUMNS . " FROM " . self::FROM . " ORDER BY mk.kode"
        )->fetchAll();
    }

    public function paginate(int $page, int $perPage = 5): array
    {
        return Paginator::paginate($this->pdo, self::COLUMNS, self::FROM, '', [], 'mk.kode', $page, $perPage);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, kode, nama, sks, prodi_id FROM matakuliah WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $d): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (:kode, :nama, :sks, :prodi_id)"
        );
        $stmt->execute([
            ':kode' => $d['kode'], ':nama' => $d['nama'],
            ':sks' => $d['sks'], ':prodi_id' => $d['prodi_id'],
        ]);
    }

    public function update(int $id, array $d): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id WHERE id = :id"
        );
        $stmt->execute([
            ':kode' => $d['kode'], ':nama' => $d['nama'],
            ':sks' => $d['sks'], ':prodi_id' => $d['prodi_id'], ':id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM matakuliah WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
