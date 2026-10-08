<?php
namespace App\Core;

/**
 * Response JSON dengan format seragam (sesuai modul):
 *
 *   { "success": true, "message": "Data berhasil diambil", "data": [ ... ] }
 *
 * Untuk kegagalan: success = false, data = null, dan (bila ada) "errors".
 */
class JsonResponse
{
    public static function send(bool $success, string $message, $data = null, int $status = 200, array $extra = []): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');

        $payload = ['success' => $success, 'message' => $message, 'data' => $data] + $extra;

        echo json_encode(
            $payload,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}
