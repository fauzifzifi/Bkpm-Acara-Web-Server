<?php
namespace App\Controllers;

use App\Core\Logger;
use App\Repositories\MahasiswaRepository;
use App\Services\MahasiswaService;
use JsonException;
use Throwable;

class ApiMahasiswaController
{
    public function __construct(
        private MahasiswaRepository $mahasiswaRepository,
        private MahasiswaService $mahasiswaService
    ) {
    }

    public function index(): void
    {
        try {
            $this->respond(200, true, 'Data mahasiswa berhasil diambil.', $this->mahasiswaRepository->allWithProdi());
        } catch (Throwable $exception) {
            $this->reportError($exception);
        }
    }

    public function show(int $id): void
    {
        try {
            $mahasiswa = $this->mahasiswaRepository->findWithProdi($id);
        } catch (Throwable $exception) {
            $this->reportError($exception);
            return;
        }

        if ($mahasiswa === null) {
            $this->respond(404, false, 'Data mahasiswa tidak ditemukan.', null);
            return;
        }

        $this->respond(200, true, 'Data mahasiswa berhasil diambil.', $mahasiswa);
    }

    public function store(): void
    {
        $input = $this->readJsonBody();
        if ($input === null) {
            return;
        }

        $result = $this->mahasiswaService->createWithStatus($input);
        if ($result === 'success') {
            try {
                $mahasiswa = $this->mahasiswaRepository->findWithProdiByNim(trim((string) $input['nim']));
            } catch (Throwable $exception) {
                $this->reportError($exception);
                return;
            }

            if ($mahasiswa === null) {
                $this->respond(500, false, 'Data mahasiswa berhasil disimpan tetapi gagal dimuat.', null);
                return;
            }

            header('Location: ' . BASE_URL . '/api/mahasiswa/' . $mahasiswa['id']);
            $this->respond(201, true, 'Data mahasiswa berhasil ditambahkan.', $mahasiswa);
            return;
        }

        $this->respondForWriteResult($result);
    }

    public function update(int $id): void
    {
        $input = $this->readJsonBody();
        if ($input === null) {
            return;
        }

        try {
            if ($this->mahasiswaRepository->find($id) === null) {
                $this->respond(404, false, 'Data mahasiswa tidak ditemukan.', null);
                return;
            }
        } catch (Throwable $exception) {
            $this->reportError($exception);
            return;
        }

        $result = $this->mahasiswaService->updateWithStatus($id, $input);
        if ($result === 'success') {
            try {
                $mahasiswa = $this->mahasiswaRepository->findWithProdi($id);
            } catch (Throwable $exception) {
                $this->reportError($exception);
                return;
            }

            $this->respond(200, true, 'Data mahasiswa berhasil diubah.', $mahasiswa);
            return;
        }

        $this->respondForWriteResult($result);
    }

    public function destroy(int $id): void
    {
        $result = $this->mahasiswaService->deleteWithStatus($id);
        if ($result === 'success') {
            $this->respond(200, true, 'Data mahasiswa berhasil dihapus.', null);
            return;
        }

        $this->respondForWriteResult($result);
    }

    private function readJsonBody(): ?array
    {
        $contentType = strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
        if ($contentType !== 'application/json') {
            $this->respond(415, false, 'Content-Type harus application/json.', null);
            return null;
        }

        $body = file_get_contents('php://input');
        if ($body === false) {
            $this->respond(400, false, 'Request body tidak dapat dibaca.', null);
            return null;
        }

        try {
            $decoded = json_decode($body, false, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            $this->respond(400, false, 'Format JSON tidak valid.', null);
            return null;
        }

        if (!$decoded instanceof \stdClass) {
            $this->respond(400, false, 'Request body harus berupa objek JSON.', null);
            return null;
        }

        return get_object_vars($decoded);
    }

    private function respondForWriteResult(string $result): void
    {
        [$status, $message] = match ($result) {
            'duplicate' => [409, 'NIM sudah terdaftar.'],
            'not_found' => [404, 'Data mahasiswa tidak ditemukan.'],
            'error' => [500, 'Terjadi kesalahan saat memproses data mahasiswa.'],
            default => [422, 'Data mahasiswa tidak valid.'],
        };

        $this->respond($status, false, $message, null);
    }

    private function reportError(Throwable $exception): void
    {
        Logger::error($exception);
        $this->respond(500, false, 'Terjadi kesalahan saat memproses data mahasiswa.', null);
    }

    private function respond(int $status, bool $success, string $message, mixed $data): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_THROW_ON_ERROR);
    }
}
