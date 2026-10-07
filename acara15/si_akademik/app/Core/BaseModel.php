<?php
namespace App\Core;

use App\Exceptions\DuplicateEntryException;
use App\Exceptions\InUseException;
use App\Exceptions\InvalidReferenceException;
use App\Exceptions\RepositoryException;
use PDO;
use PDOException;

/**
 * BaseModel: induk semua Repository.
 * Menyimpan akses PDO (disuntikkan lewat constructor) dan helper pagination,
 * sehingga tidak perlu di-copy-paste di tiap Repository.
 */
class BaseModel
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Jalankan operasi tulis dan terjemahkan error database menjadi exception aplikasi.
     * Kode error khusus MySQL hanya ada di sini; jika DBMS diganti, cukup ubah di sini
     * dan di Repository, Controller tidak perlu diubah.
     */
    protected function guard(callable $operation)
    {
        try {
            return $operation();
        } catch (PDOException $e) {
            switch ($e->errorInfo[1] ?? 0) {
                case 1062: throw new DuplicateEntryException('Data duplikat.', 0, $e);
                case 1451: throw new InUseException('Data masih dipakai.', 0, $e);
                case 1452: throw new InvalidReferenceException('Referensi data tidak valid.', 0, $e);
                default:
                    // Error tak terduga: bungkus sebagai RepositoryException (PDOException
                    // asli tetap tersimpan sebagai 'previous'). Pencatatan ke log dilakukan
                    // oleh error handler di Controller, supaya hanya tercatat satu kali.
                    throw new RepositoryException('Operasi database gagal.', 0, $e);
            }
        }
    }

    /**
     * Query daftar + pagination sederhana.
     * Bagian $columns/$from/$where/$orderBy HANYA berisi string tetap dari Repository
     * (bukan input user). Input user selalu lewat $params (prepared statement).
     */
    protected function paginateQuery(
        string $columns,
        string $from,
        string $where,
        array $params,
        string $orderBy,
        int $page,
        int $perPage
    ): array {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM {$from} {$where}");
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        $pages  = max(1, (int) ceil($total / $perPage));
        $page   = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $this->pdo->prepare(
            "SELECT {$columns} FROM {$from} {$where} ORDER BY {$orderBy} LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data'    => $stmt->fetchAll(),
            'total'   => $total,
            'page'    => $page,
            'pages'   => $pages,
            'perPage' => $perPage,
        ];
    }
}
