<?php
namespace App\Core;

/**
 * Logging: mencatat error ke storage/logs/app.log untuk programmer.
 * Pengguna TIDAK melihat isi log; ia hanya mendapat pesan yang aman.
 *
 * Contoh baris log:
 *   2026-08-08 10:30:15 - [MahasiswaController::store] Operasi database gagal. (BaseModel.php:51)
 *       | penyebab: SQLSTATE[23000]: Integrity constraint violation ... (MahasiswaRepository.php:98)
 */
class Logger
{
    private const FILE = __DIR__ . '/../../storage/logs/app.log';

    public static function error(\Throwable $e, string $context = ''): void
    {
        // Kapan - bagian aplikasi - error apa - di file/baris mana
        $line = date('Y-m-d H:i:s') . ' - '
              . ($context !== '' ? "[{$context}] " : '')
              . self::describe($e);

        // Sertakan penyebab asli (mis. PDOException di balik RepositoryException)
        $previous = $e->getPrevious();
        while ($previous !== null) {
            $line .= ' | penyebab: ' . self::describe($previous);
            $previous = $previous->getPrevious();
        }

        error_log($line . PHP_EOL, 3, self::FILE);
    }

    private static function describe(\Throwable $e): string
    {
        return self::sanitize($e->getMessage()) . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')';
    }

    /**
     * Informasi sensitif (password database) TIDAK boleh masuk log.
     * Baris baru juga dibuang agar isi log tidak bisa dipalsukan (log forging).
     */
    private static function sanitize(string $text): string
    {
        $config   = @include __DIR__ . '/../../config/database.php';
        $password = is_array($config) ? (string) ($config['password'] ?? '') : '';

        if ($password !== '') {
            $text = str_replace($password, '***', $text);
        }
        $text = preg_replace('/(password|passwd|pwd)\s*[=:]\s*\S+/i', '$1=***', $text);

        return str_replace(["\r", "\n"], ' ', $text);
    }
}
