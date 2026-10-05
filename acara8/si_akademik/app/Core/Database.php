<?php
namespace App\Core;

use PDO;

/**
 * Database singleton: koneksi PDO hanya dibuat SATU kali per request,
 * lalu dipakai ulang oleh semua Model lewat Database::getInstance().
 */
class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}   // cegah new Database
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // error jadi exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,                    // prepared statement asli
            ]);
        }
        return self::$instance;
    }
}
