<?php
namespace App\Core;

/**
 * ApiResponse: satu-satunya tempat yang membentuk response JSON API.
 * Format seragam (sesuai modul Acara 15):
 *   { "success": true|false, "message": "...", "data": ... }
 * Pada error validasi ditambah "errors": { "field": "pesan" }.
 */
class ApiResponse
{
    public static function success($data, string $message, int $status = 200): void
    {
        self::send($status, true, $message, $data);
    }

    /** @param string[] $headers header tambahan, mis. ['Allow: GET, POST'] */
    public static function error(int $status, string $message, ?array $errors = null, array $headers = []): void
    {
        self::send($status, false, $message, null, $errors, $headers);
    }

    private static function send(
        int $status,
        bool $success,
        string $message,
        $data,
        ?array $errors = null,
        array $headers = []
    ): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        foreach ($headers as $header) {
            header($header);
        }

        $body = ['success' => $success, 'message' => $message, 'data' => $data];
        if ($errors !== null) {
            $body['errors'] = $errors;
        }

        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }
}
