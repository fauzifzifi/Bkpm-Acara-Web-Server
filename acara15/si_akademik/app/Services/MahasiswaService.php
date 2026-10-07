<?php
namespace App\Services;

use App\Exceptions\DuplicateEntryException;
use App\Exceptions\ValidationException;
use App\Models\Mahasiswa;
use App\Repositories\MahasiswaRepository;
use App\Repositories\ProdiRepository;
use InvalidArgumentException;

/**
 * Service: validasi + logika bisnis mahasiswa.
 *
 *   User -> Controller -> Service (validasi) -> Repository -> MySQL
 *
 * Cara melaporkan hasil:
 *   - input tidak valid / aturan bisnis dilanggar -> throw ValidationException
 *     (pesannya aman ditampilkan ke pengguna)
 *   - error teknis (database, dst.)              -> exception dari Repository dibiarkan naik
 *     ke Controller, yang mencatatnya ke log dan hanya menampilkan pesan umum
 */
class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(MahasiswaRepository $repo, ProdiRepository $prodiRepo)
    {
        $this->repo      = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    // ================= Baca =================

    public function paginate(string $search, int $page): array
    {
        return $this->repo->paginate($search, $page);
    }

    /** @return Mahasiswa[] seluruh mahasiswa (dipakai API). */
    public function all(): array
    {
        return $this->repo->all();
    }

    public function find(int $id): ?Mahasiswa
    {
        return $this->repo->find($id);
    }

    public function prodiOptions(): array
    {
        return $this->prodiRepo->all();
    }

    public function statusOptions(): array
    {
        return Mahasiswa::STATUS;
    }

    // ================= Tulis =================

    /**
     * Tambah mahasiswa.
     * @return int id mahasiswa baru
     * @throws ValidationException
     */
    public function create(array $input): int
    {
        $mhs = $this->validate($input);

        try {
            $this->repo->create($mhs);
        } catch (DuplicateEntryException $e) {
            // Jaring pengaman: UNIQUE di database menolak (mis. dua request bersamaan)
            throw new ValidationException(['nim' => 'NIM sudah terdaftar.']);
        }

        return $mhs->getId();
    }

    /**
     * Ubah data mahasiswa. NIM boleh tetap sama, tetapi tidak boleh milik mahasiswa lain.
     * @throws ValidationException
     */
    public function update(int $id, array $input): void
    {
        $this->mustExist($id);
        $mhs = $this->validate($input, $id);

        try {
            $this->repo->update($mhs);
        } catch (DuplicateEntryException $e) {
            throw new ValidationException(['nim' => 'NIM sudah terdaftar.']);
        }
    }

    /**
     * Hapus mahasiswa.
     * @throws ValidationException bila data tidak ditemukan
     */
    public function delete(int $id): void
    {
        $this->mustExist($id);
        $this->repo->delete($id);
    }

    // ================= Validasi =================

    private function mustExist(int $id): void
    {
        if ($id < 1 || $this->repo->find($id) === null) {
            throw new ValidationException(['id' => 'Mahasiswa tidak ditemukan.']);
        }
    }

    /**
     * Validasi: NIM, nama, email, program studi, NIM duplikat.
     * Aturan format ada di setter entity Mahasiswa; aturan yang butuh data lain
     * (NIM unik, prodi tersedia) dicek lewat Repository.
     *
     * @throws ValidationException berisi SEMUA pesan error sekaligus
     */
    private function validate(array $input, ?int $ignoreId = null): Mahasiswa
    {
        $mhs = new Mahasiswa();
        $mhs->setId($ignoreId);
        $errors = [];

        $nim   = trim((string) ($input['nim'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));

        if ($nim === '') {
            $errors['nim'] = 'NIM wajib diisi.';
        }
        if ($email === '') {
            $errors['email'] = 'Email wajib diisi.';
        }

        $fields = [
            'nim'      => ['setNim',      $nim],
            'nama'     => ['setNama',     (string) ($input['nama'] ?? '')],
            'email'    => ['setEmail',    $email],
            'prodi_id' => ['setProdiId',  (int) ($input['prodi_id'] ?? 0)],
            'angkatan' => ['setAngkatan', (int) ($input['angkatan'] ?? 0)],
            'status'   => ['setStatus',   (string) ($input['status'] ?? '')],
        ];

        foreach ($fields as $field => [$setter, $value]) {
            if (isset($errors[$field])) {
                continue;
            }
            try {
                $mhs->$setter($value);
            } catch (InvalidArgumentException $e) {
                $errors[$field] = $e->getMessage();
            }
        }

        // Pengecekan NIM duplikat
        if (!isset($errors['nim']) && $this->repo->existsByNim($mhs->getNim(), $ignoreId)) {
            $errors['nim'] = 'NIM sudah terdaftar.';
        }

        // Program studi harus tersedia
        if (!isset($errors['prodi_id']) && $this->prodiRepo->find($mhs->getProdiId()) === null) {
            $errors['prodi_id'] = 'Program studi tidak ditemukan.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        return $mhs;
    }
}
