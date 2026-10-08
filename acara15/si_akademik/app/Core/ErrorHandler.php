<?php
namespace App\Core;

/**
 * Penanganan error global (exception yang tidak tertangkap di tempat lain).
 * Selalu dicatat ke storage/logs/app.log. Pengguna hanya melihat pesan aman:
 *   - URL /api/...  -> response JSON  {"success": false, "message": "...", "data": null}
 *   - halaman lain  -> halaman HTML 500
 * Detail teknis hanya tampil jika 'debug' => true di config/app.php.
 */
class ErrorHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handle']);
    }

    public static function handle(\Throwable $e): void
    {
        Logger::error($e, 'Unhandled');

        $config  = require __DIR__ . '/../../config/app.php';
        $debug   = $config['debug'] ?? false;
        $message = $e->getMessage();

        if (self::isApiRequest()) {
            JsonResponse::send(false, 'Terjadi kesalahan pada server', null, 500, $debug ? ['debug' => $message] : []);
            return;
        }

        http_response_code(500);
        require __DIR__ . '/../Views/errors/500.php';   // memakai $debug dan $message
    }

    public static function isApiRequest(): bool
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
        return (bool) preg_match('#/api/#', $path);
    }
}
