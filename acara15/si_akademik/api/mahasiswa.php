<?php
/**
 * Acara 15: API Data Mahasiswa (response JSON).
 *
 *   GET  /api/mahasiswa           -> seluruh data mahasiswa
 *   GET  /api/mahasiswa?id=1      -> satu mahasiswa
 *   POST /api/mahasiswa           -> tambah mahasiswa (body JSON)
 *
 * Memakai lapisan yang sama dengan Acara 14 (tidak ada SQL di sini):
 *   Endpoint -> MahasiswaService (validasi) -> MahasiswaRepository (PDO + prepared statement) -> MySQL
 */

use App\Core\ApiResponse;
use App\Core\Container;
use App\Core\Logger;
use App\Exceptions\ValidationException;
use App\Models\Mahasiswa;
use App\Services\MahasiswaService;

// Autoloader sederhana (sama seperti public/index.php)
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $path = __DIR__ . "/../app/{$relative}.php";
    if (file_exists($path)) {
        require $path;
    }
});

// Pesan error PHP tidak boleh tercampur ke JSON dan tidak boleh sampai ke pengguna API.
ini_set('display_errors', '0');

/** Objek Mahasiswa -> array untuk JSON. */
function mahasiswaToArray(Mahasiswa $m): array
{
    return [
        'id'       => $m->getId(),
        'nim'      => $m->getNim(),
        'nama'     => $m->getNama(),
        'email'    => $m->getEmail(),
        'prodi_id' => $m->getProdiId(),
        'prodi'    => $m->getProdiNama(),
        'angkatan' => $m->getAngkatan(),
        'status'   => $m->getStatus(),
    ];
}

/**
 * Baca body request: JSON (Content-Type: application/json) atau form biasa.
 * Hanya field yang dikenal yang diambil; nilai non-skalar dianggap kosong.
 */
function readInput(): array
{
    $type = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($type, 'application/json') !== false) {
        $json = json_decode((string) file_get_contents('php://input'), true);
        if (!is_array($json)) {
            ApiResponse::error(400, 'Body harus berupa JSON yang valid.');
        }
        $raw = $json;
    } else {
        $raw = $_POST;
    }

    $input = [];
    foreach (['nim', 'nama', 'email', 'prodi_id', 'angkatan', 'status'] as $field) {
        $value = $raw[$field] ?? '';
        $input[$field] = is_scalar($value) ? trim((string) $value) : '';
    }

    // Modul hanya mengirim nim, nama, email. Kolom lain di tabel (Acara 14) diberi nilai bawaan.
    $config = require __DIR__ . '/../config/app.php';
    $defaults = [
        'prodi_id' => $config['api_default_prodi_id'] ?? 1,
        'angkatan' => date('Y'),
        'status'   => 'aktif',
    ];
    foreach ($defaults as $field => $default) {
        if ($input[$field] === '') {
            $input[$field] = (string) $default;
        }
    }

    return $input;
}

try {
    $service = Container::make(MahasiswaService::class);
    $method  = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // GET /api/mahasiswa?id=1
        if (array_key_exists('id', $_GET)) {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($id === false) {
                ApiResponse::error(400, 'Parameter id harus berupa angka positif.');
            }

            $mhs = $service->find($id);
            if ($mhs === null) {
                ApiResponse::error(404, 'Data mahasiswa tidak ditemukan.');
            }
            ApiResponse::success(mahasiswaToArray($mhs), 'Data berhasil diambil');
        }

        // GET /api/mahasiswa
        ApiResponse::success(array_map('mahasiswaToArray', $service->all()), 'Data berhasil diambil');
    }

    if ($method === 'POST') {
        // POST /api/mahasiswa  (validasi NIM, nama, email, NIM duplikat dilakukan Service)
        $id  = $service->create(readInput());
        $mhs = $service->find($id);
        ApiResponse::success(mahasiswaToArray($mhs), 'Data mahasiswa berhasil ditambahkan', 201);
    }

    ApiResponse::error(405, 'Method tidak didukung. Gunakan GET atau POST.', null, ['Allow: GET, POST']);
} catch (ValidationException $e) {
    // Pesan validasi aman ditampilkan (sama seperti di Acara 14)
    ApiResponse::error(422, 'Data tidak valid.', $e->getErrors());
} catch (Throwable $e) {
    // Error teknis: detail hanya ke log, klien hanya mendapat pesan umum
    Logger::error($e, 'API mahasiswa');
    ApiResponse::error(500, 'Terjadi kesalahan pada server.');
}
