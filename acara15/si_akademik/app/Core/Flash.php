<?php
namespace App\Core;

/**
 * Flash Message: pesan sementara yang disimpan di $_SESSION dan
 * hanya ditampilkan SATU KALI (setelah dibaca, langsung dihapus dari session).
 *
 * Alur: Controller -> Flash::set() -> redirect -> halaman tujuan -> Flash::pull()
 */
class Flash
{
    private const KEY = 'flash';

    /** Simpan pesan. $type: success | danger | warning | info (kelas alert Bootstrap). */
    public static function set(string $type, $message): void
    {
        $_SESSION[self::KEY] = ['type' => $type, 'message' => $message];
    }

    public static function has(): bool
    {
        return isset($_SESSION[self::KEY]);
    }

    /** Ambil pesan lalu HAPUS dari session, sehingga tidak tampil dua kali. */
    public static function pull(): ?array
    {
        $flash = $_SESSION[self::KEY] ?? null;
        unset($_SESSION[self::KEY]);
        return $flash;
    }
}
