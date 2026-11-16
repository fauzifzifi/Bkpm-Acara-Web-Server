<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Logger.php';

try {
    $database = \App\Core\Database::getInstance();
    $statement = $database->getConnection()->query('SELECT * FROM mahasiswa');

    echo json_encode([
        'success' => true,
        'message' => 'Data berhasil diambil',
        'data' => $statement->fetchAll(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
} catch (\Throwable $exception) {
    \App\Core\Logger::error($exception);
    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Data gagal diambil',
        'data' => [],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}