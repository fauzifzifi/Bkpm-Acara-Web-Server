<?php
namespace App\Models;

use App\Core\Model;

class MahasiswaModel extends Model
{
    protected string $table = 'mahasiswa';

    public function all(string $search = ''): array
    {
        $sql = 'SELECT mahasiswa.id, mahasiswa.nim, mahasiswa.nama, mahasiswa.angkatan, prodi.nama AS prodi
                FROM mahasiswa
                LEFT JOIN prodi ON prodi.id = mahasiswa.prodi_id
                WHERE mahasiswa.nama LIKE :name OR mahasiswa.nim LIKE :nim
                ORDER BY mahasiswa.id';
        $statement = $this->db->prepare($sql);
        $term = '%' . $search . '%';
        $statement->execute(['name' => $term, 'nim' => $term]);

        return $statement->fetchAll();
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
}