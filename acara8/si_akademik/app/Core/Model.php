<?php
namespace App\Core;

use PDO;

/**
 * Model dasar: mengambil koneksi dari Database singleton.
 */
class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Query daftar + pagination sederhana.
     * Bagian $columns/$from/$where/$orderBy HANYA berisi string tetap dari Model
     * (bukan input user). Semua input user lewat $params (prepared statement).
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
