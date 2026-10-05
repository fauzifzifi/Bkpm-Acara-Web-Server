<?php
namespace App\Core;

use PDO;

/**
 * Base Model (koneksi PDO). Dipakai saat data sudah pindah ke database.
 */
class Model
{
    protected PDO $pdo;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    }
}
