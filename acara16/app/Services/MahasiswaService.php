<?php
namespace App\Services;

use App\Core\Logger;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use PDOException;
use Throwable;

class MahasiswaService
{
    public function __construct(
        private MahasiswaRepository $mahasiswaRepository,
        private ProdiRepository $prodiRepository
    ) {
    }

    public function create(array $input): string
    {
        $result = $this->createWithStatus($input);

        return in_array($result, ['success', 'duplicate'], true) ? $result : 'failure';
    }

    public function createWithStatus(array $input): string
    {
        $data = $this->validatedData($input);
        if ($data === null) {
            return 'invalid';
        }

        try {
            if (!$this->prodiRepository->exists($data['prodi_id'])) {
                return 'invalid';
            }

            if ($this->mahasiswaRepository->existsByNim($data['nim'])) {
                return 'duplicate';
            }

            return $this->mahasiswaRepository->create($data) ? 'success' : 'error';
        } catch (Throwable $exception) {
            Logger::error($exception);
            return $exception instanceof PDOException && $this->isDuplicateKey($exception)
                ? 'duplicate'
                : 'error';
        }
    }

    public function update(int $id, array $input): string
    {
        $result = $this->updateWithStatus($id, $input);

        return in_array($result, ['success', 'duplicate'], true) ? $result : 'failure';
    }

    public function updateWithStatus(int $id, array $input): string
    {
        $data = $this->validatedData($input);
        if ($data === null) {
            return 'invalid';
        }

        try {
            if (!$this->prodiRepository->exists($data['prodi_id'])) {
                return 'invalid';
            }

            if ($this->mahasiswaRepository->existsByNim($data['nim'], $id)) {
                return 'duplicate';
            }

            return $this->mahasiswaRepository->update($id, $data) ? 'success' : 'error';
        } catch (Throwable $exception) {
            Logger::error($exception);
            return $exception instanceof PDOException && $this->isDuplicateKey($exception)
                ? 'duplicate'
                : 'error';
        }
    }

    public function delete(int $id): string
    {
        try {
            return $this->mahasiswaRepository->delete($id) ? 'success' : 'failure';
        } catch (Throwable $exception) {
            Logger::error($exception);
            return 'failure';
        }
    }

    public function deleteWithStatus(int $id): string
    {
        try {
            if ($this->mahasiswaRepository->find($id) === null) {
                return 'not_found';
            }

            return $this->mahasiswaRepository->delete($id) ? 'success' : 'error';
        } catch (Throwable $exception) {
            Logger::error($exception);
            return 'error';
        }
    }

    private function validatedData(array $input): ?array
    {
        $nim = $input['nim'] ?? '';
        $nama = $input['nama'] ?? '';
        $email = $input['email'] ?? '';
        $status = $input['status'] ?? '';
        $data = [
            'nim' => is_scalar($nim) ? trim((string) $nim) : '',
            'nama' => is_scalar($nama) ? trim((string) $nama) : '',
            'email' => is_scalar($email) ? trim((string) $email) : '',
            'prodi_id' => filter_var($input['prodi_id'] ?? null, FILTER_VALIDATE_INT) ?: 0,
            'angkatan' => filter_var($input['angkatan'] ?? null, FILTER_VALIDATE_INT) ?: 0,
            'status' => is_scalar($status) ? (string) $status : '',
        ];

        if (
            !ctype_digit($data['nim']) || $data['nama'] === '' ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
            $data['prodi_id'] < 1 || $data['angkatan'] < 1 ||
            !in_array($data['status'], ['aktif', 'cuti', 'lulus'], true)
        ) {
            return null;
        }

        return $data;
    }

    private function isDuplicateKey(PDOException $exception): bool
    {
        return $exception->getCode() === '23000' &&
            (int) ($exception->errorInfo[1] ?? 0) === 1062;
    }
}
