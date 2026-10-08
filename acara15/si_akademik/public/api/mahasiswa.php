<?php
/**
 * Endpoint API Mahasiswa (sesuai modul: api/mahasiswa.php)
 *
 *   GET  /api/mahasiswa.php          -> seluruh data mahasiswa
 *   GET  /api/mahasiswa.php?id=1     -> satu mahasiswa
 *   POST /api/mahasiswa.php          -> tambah mahasiswa (body JSON)
 *
 * Alamat yang sama juga tersedia lewat router sebagai /api/mahasiswa.
 * Keduanya memakai MahasiswaApiController, jadi hasilnya identik.
 */
require_once __DIR__ . '/../../app/autoload.php';
require_once __DIR__ . '/../../app/Core/helpers.php';

App\Core\ErrorHandler::register();

// Controller dibuat lewat Container (dependency: Service -> Repository -> PDO)
App\Core\Container::make(App\Controllers\Api\MahasiswaApiController::class)->handle();
