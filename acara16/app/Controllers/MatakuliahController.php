<?php
namespace App\Controllers;

use App\Models\MatakuliahModel;
use App\Models\ProdiModel;

class MatakuliahController
{
    public function index(): void
    {
        $matakuliahList = (new MatakuliahModel())->all();
        $content = __DIR__ . '/../Views/matakuliah/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $matakuliah = [];
        $prodiList = (new ProdiModel())->all();
        $isEditing = false;
        $content = __DIR__ . '/../Views/matakuliah/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = $this->formData();
        if ($data !== null) {
            (new MatakuliahModel())->create($data);
        }

        header('Location: ' . BASE_URL . '/matakuliah');
        exit;
    }

    public function edit(int $id): void
    {
        $matakuliah = (new MatakuliahModel())->find($id);
        if ($matakuliah === null) {
            $this->notFound();
            return;
        }

        $prodiList = (new ProdiModel())->all();
        $isEditing = true;
        $content = __DIR__ . '/../Views/matakuliah/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        $data = $this->formData();
        if ($data !== null) {
            (new MatakuliahModel())->update($id, $data);
        }

        header('Location: ' . BASE_URL . '/matakuliah');
        exit;
    }

    public function delete(int $id): void
    {
        (new MatakuliahModel())->delete($id);
        header('Location: ' . BASE_URL . '/matakuliah');
        exit;
    }

    private function formData(): ?array
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
            'sks' => (int) ($_POST['sks'] ?? 0),
            'prodi_id' => (int) ($_POST['prodi_id'] ?? 0),
        ];

        return $data['kode'] !== '' && $data['nama'] !== '' && $data['sks'] > 0 && $data['prodi_id'] > 0
            ? $data
            : null;
    }

    private function notFound(): void
    {
        http_response_code(404);
        $content = __DIR__ . '/../Views/errors/404.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
