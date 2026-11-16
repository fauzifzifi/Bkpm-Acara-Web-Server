<?php
namespace App\Core;

use PDO;

class Model
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all(): array
    {
        return $this->db->query('SELECT * FROM ' . $this->quotedTable() . ' ORDER BY id')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM ' . $this->quotedTable() . ' WHERE id = :id');
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public function create(array $data): bool
    {
        $columns = $this->quotedColumns($data);
        $statement = $this->db->prepare(
            'INSERT INTO ' . $this->quotedTable() . ' (' . implode(', ', $columns) . ') VALUES (' .
            implode(', ', array_fill(0, count($columns), '?')) . ')'
        );

        return $statement->execute(array_values($data));
    }

    public function update(int $id, array $data): bool
    {
        $columns = $this->quotedColumns($data);
        $assignments = array_map(static fn (string $column): string => $column . ' = ?', $columns);
        $statement = $this->db->prepare(
            'UPDATE ' . $this->quotedTable() . ' SET ' . implode(', ', $assignments) . ' WHERE id = ?'
        );
        $values = array_values($data);
        $values[] = $id;

        return $statement->execute($values);
    }

    public function delete(int $id): bool
    {
        $statement = $this->db->prepare('DELETE FROM ' . $this->quotedTable() . ' WHERE id = :id');

        return $statement->execute(['id' => $id]);
    }

    private function quotedTable(): string
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $this->table)) {
            throw new \LogicException('Nama tabel model tidak valid.');
        }

        return '`' . $this->table . '`';
    }

    private function quotedColumns(array $data): array
    {
        if ($data === []) {
            throw new \InvalidArgumentException('Data tidak boleh kosong.');
        }

        $columns = array_keys($data);
        foreach ($columns as $column) {
            if (!is_string($column) || !preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
                throw new \InvalidArgumentException('Nama kolom tidak valid.');
            }
        }

        return array_map(static fn (string $column): string => '`' . $column . '`', $columns);
    }
}