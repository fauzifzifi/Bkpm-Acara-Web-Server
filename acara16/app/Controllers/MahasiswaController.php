<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Core\Logger;
use App\Repositories\ProdiRepository;
use App\Repositories\MahasiswaRepository;
use App\Services\MahasiswaService;
use Throwable;

class MahasiswaController extends BaseController
{
    public function __construct(
        private MahasiswaRepository $mahasiswaRepository,
        private ProdiRepository $prodiRepository,
        private MahasiswaService $mahasiswaService
    ) {
    }

    public function index(): void
    {
        $search = trim($_GET['q'] ?? '');
        try {
            $mahasiswaList = $this->mahasiswaRepository->all($search);
        } catch (Throwable $exception) {
            Logger::error($exception);
            $mahasiswaList = [];
            $_SESSION['flash_error'] = 'Data gagal dimuat.';
        }

        $this->view('mahasiswa/index', compact('search', 'mahasiswaList'));
    }

    public function create(): void
    {
        $mahasiswa = [];
        try {
            $prodiList = $this->prodiRepository->all();
        } catch (Throwable $exception) {
            Logger::error($exception);
            $_SESSION['flash_error'] = 'Data gagal dimuat.';
            $this->redirect('/mahasiswa');
        }

        $isEditing = false;
        $this->view('mahasiswa/create', compact('mahasiswa', 'prodiList', 'isEditing'));
    }

    public function store(): void
    {
        $this->setFlash($this->mahasiswaService->create($_POST));
        $this->redirect('/mahasiswa');
    }

    public function editFromQuery(): void
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            $this->notFound();
            return;
        }

        $this->edit($id);
    }

    public function updateFromRequest(): void
    {
        $id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            $_SESSION['flash_error'] = 'Data gagal disimpan.';
            $this->redirect('/mahasiswa');
        }

        $this->update($id);
    }

    public function deleteFromRequest(): void
    {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            $_SESSION['flash_error'] = 'Data gagal dihapus.';
            $this->redirect('/mahasiswa');
        }

        $result = $this->mahasiswaService->delete($id);
        if ($result === 'success') {
            $_SESSION['flash_success'] = 'Data mahasiswa berhasil dihapus.';
        } else {
            $_SESSION['flash_error'] = 'Data gagal dihapus.';
        }

        $this->redirect('/mahasiswa');
    }

    public function show(int $id): void
    {
        try {
            $mhs = $this->mahasiswaRepository->findWithProdi($id);
        } catch (Throwable $exception) {
            Logger::error($exception);
            $_SESSION['flash_error'] = 'Data gagal dimuat.';
            $this->redirect('/mahasiswa');
        }

        if ($mhs === null) {
            $this->notFound();
            return;
        }

        $this->view('mahasiswa/show', compact('mhs'));
    }

    public function edit(int $id): void
    {
        try {
            $mahasiswa = $this->mahasiswaRepository->find($id);
            $prodiList = $this->prodiRepository->all();
        } catch (Throwable $exception) {
            Logger::error($exception);
            $_SESSION['flash_error'] = 'Data gagal dimuat.';
            $this->redirect('/mahasiswa');
        }

        if ($mahasiswa === null) {
            $this->notFound();
            return;
        }

        $isEditing = true;
        $this->view('mahasiswa/create', compact('mahasiswa', 'prodiList', 'isEditing'));
    }

    public function update(int $id): void
    {
        $this->setFlash($this->mahasiswaService->update($id, $_POST), true);
        $this->redirect('/mahasiswa');
    }

    public function delete(int $id): void
    {
        $result = $this->mahasiswaService->delete($id);
        if ($result === 'success') {
            $_SESSION['flash_success'] = 'Data mahasiswa berhasil dihapus.';
        } else {
            $_SESSION['flash_error'] = 'Data gagal dihapus.';
        }

        $this->redirect('/mahasiswa');
    }

    private function setFlash(string $result, bool $isUpdate = false): void
    {
        if ($result === 'success') {
            $_SESSION['flash_success'] = $isUpdate
                ? 'Data mahasiswa berhasil diubah.'
                : 'Data mahasiswa berhasil ditambahkan.';
            return;
        }

        $_SESSION['flash_error'] = $result === 'duplicate'
            ? 'NIM sudah terdaftar.'
            : 'Data gagal disimpan.';
    }

    private function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404');
    }
}
