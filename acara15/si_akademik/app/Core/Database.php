<?php
namespace App\Core;

use PDO;

/**
 * Database: bertugas mengelola koneksi ke database menggunakan PDO.
 * Singleton: objek (dan koneksinya) hanya dibuat satu kali, lalu
 * "disuntikkan" (injected) ke Repository lewat constructor.
 */
class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // error jadi exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,                    // prepared statement asli
            ]);
        } catch (\PDOException $e) {
            // Pesan umum; detail asli tetap ada di $e (previous) untuk dicatat ke log
            throw new \RuntimeException('Database connection failed', 0, $e);
        }
    }

    private function __clone() {}

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
