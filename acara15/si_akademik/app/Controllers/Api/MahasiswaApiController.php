<?php
namespace App\Controllers\Api;

use App\Core\JsonResponse;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Models\Mahasiswa;
use App\Services\MahasiswaService;

/**
 * API Data Mahasiswa (JSON).
 *
 *   GET  /api/mahasiswa        -> seluruh data mahasiswa
 *   GET  /api/mahasiswa?id=1   -> satu mahasiswa
 *   POST /api/mahasiswa        -> tambah mahasiswa (body JSON)
 *
 * Alur: Request -> API -> Service (validasi) -> Repository -> Database -> Response JSON.
 * Controller ini hanya mengurus HTTP + JSON; logika bisnis tetap di MahasiswaService.
 */
class MahasiswaApiController
{
    private MahasiswaService $service;

    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    /** Pintu masuk: memilih aksi berdasarkan HTTP method. */
    public function handle(): void
    {
        try {
            switch ($_SERVER['REQUEST_METHOD'] ?? 'GET') {
                case 'GET':
                    if (isset($_GET['id'])) {
                        $this->show($_GET['id']);
                    } else {
                        $this->index();
                    }
                    break;

                case 'POST':
                    $this->store();
                    break;

                default:
                    header('Allow: GET, POST');
                    JsonResponse::send(false, 'Method tidak diizinkan', null, 405);
            }
        } catch (\Throwable $e) {
            // Error teknis: detail hanya ke log, klien cukup pesan umum
            Logger::error($e, __METHOD__);
            JsonResponse::send(false, 'Terjadi kesalahan pada server', null, 500);
        }
    }

    // GET /api/mahasiswa
    private function index(): void
    {
        $data = array_map([$this, 'present'], $this->service->all());
        JsonResponse::send(true, 'Data berhasil diambil', $data);
    }

    // GET /api/mahasiswa?id=1
    private function show($rawId): void
    {
        if (!ctype_digit((string) $rawId) || (int) $rawId < 1) {
            JsonResponse::send(false, 'Parameter id tidak valid', null, 400);
            return;
        }

        $mhs = $this->service->find((int) $rawId);
        if ($mhs === null) {
            JsonResponse::send(false, 'Data mahasiswa tidak ditemukan', null, 404);
            return;
        }

        JsonResponse::send(true, 'Data berhasil diambil', $this->present($mhs));
    }

    // POST /api/mahasiswa   body: {"nim": "23004", "nama": "Dewi", "email": "dewi@gmail.com"}
    private function store(): void
    {
        $input = json_decode((string) file_get_contents('php://input'), true);

        // Harus berupa objek JSON ({...}), bukan teks biasa, angka, atau array ([...])
        $isObject = is_array($input) && ($input === [] || array_keys($input) !== range(0, count($input) - 1));
        if (!$isObject) {
            JsonResponse::send(false, 'Format JSON tidak valid', null, 400);
            return;
        }
        foreach ($input as $value) {
            if (!is_scalar($value) && $value !== null) {
                JsonResponse::send(false, 'Nilai setiap field harus berupa teks atau angka', null, 400);
                return;
            }
        }

        try {
            $id  = $this->service->create($this->withDefaults($input));
            $mhs = $this->service->find($id);
        } catch (ValidationException $e) {
            JsonResponse::send(false, 'Data tidak valid', null, 422, ['errors' => $e->getErrors()]);
            return;
        }

        JsonResponse::send(true, 'Data berhasil ditambahkan', $this->present($mhs), 201);
    }

    /**
     * Contoh body di modul hanya berisi nim, nama, email, sedangkan tabel mahasiswa
     * juga mewajibkan prodi_id dan angkatan. Field yang tidak dikirim diisi nilai bawaan:
     *   - angkatan : dari 2 digit pertama NIM (23004 -> 2023)
     *   - status   : aktif
     *   - prodi_id : prodi dengan kode config 'api_default_prodi' (bila ada)
     * Klien selalu boleh mengirim ketiganya sendiri.
     */
    private function withDefaults(array $input): array
    {
        $nim = trim((string) ($input['nim'] ?? ''));

        if (!isset($input['angkatan']) && preg_match('/^\d{2}/', $nim)) {
            $input['angkatan'] = (int) ('20' . substr($nim, 0, 2));
        }
        if (!isset($input['status'])) {
            $input['status'] = 'aktif';
        }
        if (!isset($input['prodi_id'])) {
            $kode = (require __DIR__ . '/../../../config/app.php')['api_default_prodi'] ?? null;
            foreach ($this->service->prodiOptions() as $prodi) {
                if ($kode !== null && $prodi['kode'] === $kode) {
                    $input['prodi_id'] = $prodi['id'];
                    break;
                }
            }
        }

        return $input;
    }

    /** Objek Mahasiswa -> array untuk JSON. */
    private function present(Mahasiswa $m): array
    {
        return [
            'id'       => $m->getId(),
            'nim'      => $m->getNim(),
            'nama'     => $m->getNama(),
            'email'    => $m->getEmail(),
            'prodi'    => $m->getProdiNama(),
            'angkatan' => $m->getAngkatan(),
            'status'   => $m->getStatus(),
        ];
    }
}
