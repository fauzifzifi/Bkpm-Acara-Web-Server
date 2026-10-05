<?php
namespace App\Repositories;

use App\Core\BaseModel;
use App\Models\Mahasiswa;

/**
 * Repository: tempat semua query CRUD mahasiswa.
 * Mewarisi BaseModel (akses PDO yang disuntikkan lewat constructor),
 * jadi Repository tidak membuat koneksi sendiri dan Controller tidak perlu menulis SQL.
 */
class MahasiswaRepository extends BaseModel
{
    // JOIN one-to-many: banyak mahasiswa -> satu prodi
    private const COLUMNS = "m.id, m.nim, m.nama, m.email, m.prodi_id, m.angkatan, m.status, p.nama AS prodi_nama";
    private const FROM    = "mahasiswa m JOIN prodi p ON p.id = m.prodi_id";

    /** @return Mahasiswa[] */
    public function all(): array
    {
        $rows = $this->pdo->query(
            "SELECT " . self::COLUMNS . " FROM " . self::FROM . " ORDER BY m.nim"
        )->fetchAll();

        return array_map([$this, 'hydrate'], $rows);
    }

    /** Daftar + pagination + pencarian nama/NIM. 'data' berisi array objek Mahasiswa. */
    public function paginate(string $search, int $page, int $perPage = 5): array
    {
        $where  = '';
        $params = [];

        if ($search !== '') {
            $where  = 'WHERE (m.nama LIKE :q1 OR m.nim LIKE :q2)';
            $like   = '%' . addcslashes($search, '\\%_') . '%';
            $params = [':q1' => $like, ':q2' => $like];
        }

        $result = $this->paginateQuery(self::COLUMNS, self::FROM, $where, $params, 'm.nim', $page, $perPage);
        $result['data'] = array_map([$this, 'hydrate'], $result['data']);

        return $result;
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare(
            "SELECT " . self::COLUMNS . " FROM " . self::FROM . " WHERE m.id = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row ? $this->hydrate($row) : null;
    }

    public function create(Mahasiswa $m): void
    {
        $this->guard(function () use ($m) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
                 VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)"
            );
            $stmt->execute($this->bind($m));
            $m->setId((int) $this->pdo->lastInsertId());
        });
    }

    public function update(Mahasiswa $m): void
    {
        $this->guard(function () use ($m) {
            $stmt = $this->pdo->prepare(
                "UPDATE mahasiswa
                 SET nim = :nim, nama = :nama, email = :email,
                     prodi_id = :prodi_id, angkatan = :angkatan, status = :status
                 WHERE id = :id"
            );
            $stmt->execute($this->bind($m) + [':id' => $m->getId()]);
        });
    }

    public function delete(int $id): void
    {
        $this->guard(function () use ($id) {
            $stmt = $this->pdo->prepare("DELETE FROM mahasiswa WHERE id = :id");
            $stmt->execute([':id' => $id]);
        });
    }

    // ---------- helper ----------

    /** Baris database -> objek Mahasiswa (lewat setter, jadi ikut tervalidasi). */
    private function hydrate(array $row): Mahasiswa
    {
        $m = new Mahasiswa();
        $m->setId((int) $row['id']);
        $m->setNim($row['nim']);
        $m->setNama($row['nama']);
        $m->setEmail($row['email']);
        $m->setProdiId((int) $row['prodi_id']);
        $m->setAngkatan((int) $row['angkatan']);
        $m->setStatus($row['status']);
        $m->setProdiNama($row['prodi_nama'] ?? null);
        return $m;
    }

    /** Objek Mahasiswa -> parameter prepared statement (lewat getter). */
    private function bind(Mahasiswa $m): array
    {
        return [
            ':nim'      => $m->getNim(),
            ':nama'     => $m->getNama(),
            ':email'    => $m->getEmail(),
            ':prodi_id' => $m->getProdiId(),
            ':angkatan' => $m->getAngkatan(),
            ':status'   => $m->getStatus(),
        ];
    }
}
