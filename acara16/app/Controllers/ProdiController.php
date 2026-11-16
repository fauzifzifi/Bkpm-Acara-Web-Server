<?php
namespace App\Controllers;

use App\Models\ProdiModel;

class ProdiController
{
    public function index(): void
    {
        $prodiList = (new ProdiModel())->all();
        $content = __DIR__ . '/../Views/prodi/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function create(): void
    {
        $prodi = [];
        $isEditing = false;
        $content = __DIR__ . '/../Views/prodi/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function store(): void
    {
        $data = $this->formData();
        if ($data !== null) {
            (new ProdiModel())->create($data);
        }

        header('Location: ' . BASE_URL . '/prodi');
        exit;
    }

    public function edit(int $id): void
    {
        $prodi = (new ProdiModel())->find($id);
        if ($prodi === null) {
            $this->notFound();
            return;
        }

        $isEditing = true;
        $content = __DIR__ . '/../Views/prodi/create.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function update(int $id): void
    {
        $data = $this->formData();
        if ($data !== null) {
            (new ProdiModel())->update($id, $data);
        }

        header('Location: ' . BASE_URL . '/prodi');
        exit;
    }

    public function delete(int $id): void
    {
        (new ProdiModel())->delete($id);
        header('Location: ' . BASE_URL . '/prodi');
        exit;
    }

    private function formData(): ?array
    {
        $data = [
            'kode' => trim($_POST['kode'] ?? ''),
            'nama' => trim($_POST['nama'] ?? ''),
        ];

        return $data['kode'] !== '' && $data['nama'] !== '' ? $data : null;
    }

    private function notFound(): void
    {
        http_response_code(404);
        $content = __DIR__ . '/../Views/errors/404.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}
