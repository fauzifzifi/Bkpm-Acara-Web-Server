<?php
namespace App\Core;

use PDO;

/**
 * Model dasar: membuka koneksi PDO dari config/database.php.
 * Semua Model (MahasiswaModel, dst.) mewarisi class ini.
 */
class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
