<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

class MahasiswaRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    public function all(string $search = ''): array
    {
        $statement = $this->db->prepare(
            'SELECT mahasiswa.id, mahasiswa.nim, mahasiswa.nama, mahasiswa.angkatan, prodi.nama AS prodi
             FROM mahasiswa
             LEFT JOIN prodi ON prodi.id = mahasiswa.prodi_id
             WHERE mahasiswa.nama LIKE :name OR mahasiswa.nim LIKE :nim
             ORDER BY mahasiswa.id'
        );
        $term = '%' . $search . '%';
        $statement->execute(['name' => $term, 'nim' => $term]);

        return $statement->fetchAll();
    }

    public function allWithProdi(): array
    {
        $statement = $this->db->query(
            'SELECT mahasiswa.*, prodi.nama AS prodi
             FROM mahasiswa
             LEFT JOIN prodi ON prodi.id = mahasiswa.prodi_id
             ORDER BY mahasiswa.id'
        );

        return $statement->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM mahasiswa WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function existsByNim(string $nim, ?int $excludeId = null): bool
    {
        $sql = 'SELECT 1 FROM mahasiswa WHERE nim = :nim';
        $parameters = ['nim' => $nim];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :id';
            $parameters['id'] = $excludeId;
        }

        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchColumn() !== false;
    }

    public function findWithProdi(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT mahasiswa.*, prodi.nama AS prodi
             FROM mahasiswa
             LEFT JOIN prodi ON prodi.id = mahasiswa.prodi_id
             WHERE mahasiswa.id = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function findWithProdiByNim(string $nim): ?array
    {
        $statement = $this->db->prepare(
            'SELECT mahasiswa.*, prodi.nama AS prodi
             FROM mahasiswa
             LEFT JOIN prodi ON prodi.id = mahasiswa.prodi_id
             WHERE mahasiswa.nim = :nim'
        );
        $statement->execute(['nim' => $nim]);

        return $statement->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );

        return $statement->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $statement = $this->db->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email, prodi_id = :prodi_id,
                 angkatan = :angkatan, status = :status
             WHERE id = :id'
        );
        $data['id'] = $id;

        return $statement->execute($data);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM mahasiswa WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }
}