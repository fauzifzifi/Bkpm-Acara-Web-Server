<?php
namespace App\Models;

use App\Core\Model;

class MatakuliahModel extends Model
{
    protected string $table = 'matakuliah';

    public function all(): array
    {
        return $this->db->query(
            'SELECT matakuliah.*, prodi.nama AS prodi
             FROM matakuliah
             LEFT JOIN prodi ON prodi.id = matakuliah.prodi_id
             ORDER BY matakuliah.id'
        )->fetchAll();
    }
}
