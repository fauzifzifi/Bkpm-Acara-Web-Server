<?php
namespace App\Core;

use App\Exceptions\DuplicateEntryException;
use App\Exceptions\InUseException;
use App\Exceptions\InvalidReferenceException;
use App\Exceptions\RepositoryException;

/**
 * BaseController: method yang dibutuhkan SEMUA controller ditulis sekali di sini
 * (prinsip DRY), lalu diwariskan lewat `extends BaseController`.
 */
class BaseController
{
    /** Menampilkan halaman: view + layout utama. */
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . "/../Views/{$view}.php";
        require __DIR__ . '/../Views/layouts/main.php';
    }

    /** Mengarahkan user ke halaman lain (path aplikasi, mis. '/mahasiswa'). */
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    // ---------- bantuan umum lain yang juga dipakai banyak controller ----------

    /** Flash message (string atau array pesan), tampil sekali. */
    protected function flash($message, string $type = 'success'): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /** Validasi gagal: simpan pesan + input lama, kembali ke form. */
    protected function failWithOld($errors, string $to): void
    {
        $this->flash($errors, 'danger');
        $_SESSION['old'] = $_POST;
        $this->redirect($to);
    }

    /** Ubah exception dari Repository menjadi pesan yang ramah. */
    protected function repoMessage(RepositoryException $e, string $dup, string $inUse): string
    {
        if ($e instanceof DuplicateEntryException) {
            return $dup;
        }
        if ($e instanceof InUseException) {
            return $inUse;
        }
        if ($e instanceof InvalidReferenceException) {
            return 'Referensi data tidak valid.';
        }
        return 'Data tidak dapat diproses.';
    }
}
