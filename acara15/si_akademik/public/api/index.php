<?php
/**
 * Pintu masuk folder /api/.
 *
 * Di Apache/XAMPP, URL /api/mahasiswa diteruskan ke router oleh public/.htaccess.
 * Server bawaan PHP (php -S) tidak membaca .htaccess dan berhenti di folder api/
 * jika tidak ada index.php, sehingga file ini meneruskan permintaan ke endpoint
 * yang sama (hasilnya identik).
 */
require_once __DIR__ . '/../../app/autoload.php';
require_once __DIR__ . '/../../app/Core/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';

if (preg_match('#/api/mahasiswa/?$#', $path)) {
    require __DIR__ . '/mahasiswa.php';
    return;
}

App\Core\JsonResponse::send(false, 'Endpoint tidak ditemukan', null, 404);
