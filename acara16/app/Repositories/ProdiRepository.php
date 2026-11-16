<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

class ProdiRepository
{
    private PDO $db;

    public function __construct(Database $database)
    {
        $this->db = $database->getConnection();
    }

    public function all(): array
    {
        $statement = $this->db->query('SELECT * FROM prodi ORDER BY id');

        return $statement->fetchAll();
    }

    public function exists(int $id): bool
    {
        $statement = $this->db->prepare('SELECT 1 FROM prodi WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetchColumn() !== false;
    }
}
