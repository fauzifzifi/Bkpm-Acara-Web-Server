<?php
namespace App\Services;

use App\Exceptions\DuplicateEntryException;
use App\Exceptions\InUseException;
use App\Exceptions\RepositoryException;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;

/**
 * Service Layer: tempat LOGIKA BISNIS mahasiswa.
 *
 *   Controller  -> mengatur alur (terima request, arahkan, tampilkan pesan)
 *   Service     -> menjalankan logika bisnis (validasi, aturan, beberapa proses)
 *   Repository  -> mengatur akses data (query ke database)
 *
 * Service tidak tahu apa-apa soal HTTP (redirect/session/view): ia menerima array
 * input dan mengembalikan hasil berupa array ['success' => bool, ...].
 */
class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    // Dependency disuntikkan lewat constructor (Dependency Injection)
    public function __construct(MahasiswaRepository $repo, ProdiRepository $prodiRepo)
    {
        $this->repo      = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    // ================= Operasi baca (diteruskan ke Repository) =================

    /** Daftar mahasiswa + pencarian + pagination. */
    public function paginate(string $search, int $page): array
    {
        return $this->repo->paginate($search, $page);
    }

    public function find(int $id): ?Mahasiswa
    {
        return $this->repo->find($id);
    }

    /** Pilihan program studi untuk dropdown form. */
    public function prodiOptions(): array
    {
        return $this->prodiRepo->all();
    }

    public function statusOptions(): array
    {
        return Mahasiswa::STATUS;
    }

    // ================= Operasi tulis =================

    /**
     * Proses penambahan mahasiswa.
     *
     * @return array ['success' => true, 'id' => int]
     *            atau ['success' => false, 'errors' => ['field' => 'pesan', ...]]
     */
    public function create(array $input): array
    {
        [$mhs, $errors] = $this->validate($input);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->repo->create($mhs);
        } catch (DuplicateEntryException $e) {
            // Jaring pengaman: dua request bersamaan dengan NIM sama (UNIQUE di database)
            return ['success' => false, 'errors' => ['nim' => 'NIM sudah terdaftar']];
        } catch (RepositoryException $e) {
            return ['success' => false, 'errors' => ['db' => 'Data gagal disimpan']];
        }

        return ['success' => true, 'id' => $mhs->getId()];
    }

    /**
     * Proses perubahan data mahasiswa.
     * NIM boleh tetap sama, tetapi tidak boleh dipakai mahasiswa lain.
     */
    public function update(int $id, array $input): array
    {
        if ($this->repo->find($id) === null) {
            return ['success' => false, 'errors' => ['id' => 'Mahasiswa tidak ditemukan']];
        }

        [$mhs, $errors] = $this->validate($input, $id);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->repo->update($mhs);
        } catch (DuplicateEntryException $e) {
            return ['success' => false, 'errors' => ['nim' => 'NIM sudah terdaftar']];
        } catch (RepositoryException $e) {
            return ['success' => false, 'errors' => ['db' => 'Data gagal disimpan']];
        }

        return ['success' => true, 'id' => $id];
    }

    /** Proses penghapusan mahasiswa. */
    public function delete(int $id): array
    {
        if ($this->repo->find($id) === null) {
            return ['success' => false, 'errors' => ['id' => 'Mahasiswa tidak ditemukan']];
        }

        try {
            $this->repo->delete($id);
        } catch (InUseException $e) {
            return ['success' => false, 'errors' => ['db' => 'Data gagal dihapus karena masih dipakai']];
        } catch (RepositoryException $e) {
            return ['success' => false, 'errors' => ['db' => 'Data gagal dihapus']];
        }

        return ['success' => true];
    }

    // ================= Validasi =================

    /**
     * Validasi input. Aturan format ada di setter entity Mahasiswa;
     * aturan yang butuh data lain (NIM unik, prodi tersedia) dicek lewat Repository.
     *
     * @return array [Mahasiswa, array $errors]  ($errors kosong = valid)
     */
    private function validate(array $input, ?int $ignoreId = null): array
    {
        $mhs = new Mahasiswa();
        $mhs->setId($ignoreId);
        $errors = [];

        // NIM wajib diisi
        $nim = trim((string) ($input['nim'] ?? ''));
        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi';
        }

        $fields = [
            'nim'      => ['setNim',      $nim],
            'nama'     => ['setNama',     (string) ($input['nama'] ?? '')],
            'email'    => ['setEmail',    (string) ($input['email'] ?? '')],
            'prodi_id' => ['setProdiId',  (int) ($input['prodi_id'] ?? 0)],
            'angkatan' => ['setAngkatan', (int) ($input['angkatan'] ?? 0)],
            'status'   => ['setStatus',   (string) ($input['status'] ?? '')],
        ];

        foreach ($fields as $field => [$setter, $value]) {
            if (isset($errors[$field])) {
                continue;       // sudah ada pesan untuk field ini
            }
            try {
                $mhs->$setter($value);
            } catch (InvalidArgumentException $e) {
                $errors[$field] = $e->getMessage();
            }
        }

        // NIM belum boleh terdaftar (kecuali milik mahasiswa yang sedang diubah)
        if (!isset($errors['nim']) && $this->repo->existsByNim($mhs->getNim(), $ignoreId)) {
            $errors['nim'] = 'NIM sudah terdaftar';
        }

        // Program studi harus tersedia
        if (!isset($errors['prodi_id']) && $this->prodiRepo->find($mhs->getProdiId()) === null) {
            $errors['prodi_id'] = 'Program studi tidak ditemukan';
        }

        return [$mhs, $errors];
    }
}
