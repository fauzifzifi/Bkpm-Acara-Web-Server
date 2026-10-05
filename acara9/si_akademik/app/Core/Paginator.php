<?php
namespace App\Core;

use PDO;

/**
 * Helper pagination. Bagian $columns/$from/$where/$orderBy HANYA berisi
 * string tetap dari Repository (bukan input user). Input user selalu lewat
 * $params (prepared statement).
 */
class Paginator
{
    public static function paginate(
        PDO $pdo,
        string $columns,
        string $from,
        string $where,
        array $params,
        string $orderBy,
        int $page,
        int $perPage
    ): array {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$from} {$where}");
        $stmt->execute($params);
        $total = (int) $stmt->fetchColumn();

        $pages  = max(1, (int) ceil($total / $perPage));
        $page   = min(max(1, $page), $pages);
        $offset = ($page - 1) * $perPage;

        $stmt = $pdo->prepare(
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
