<?php
namespace App\Core;

/**
 * Base Controller
 */
class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . "/../Views/{$view}.php";
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

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

    /** Ubah error database menjadi pesan yang ramah. */
    protected function dbMessage(\PDOException $e, string $dup, string $fk): string
    {
        $code = $e->errorInfo[1] ?? 0;
        switch ($code) {
            case 1062: return $dup;                                   // duplicate key
            case 1451: return $fk;                                    // masih dipakai tabel lain
            case 1452: return 'Referensi data tidak valid.';          // FK tujuan tidak ada
            default:   return 'Terjadi kesalahan database: ' . $e->getMessage();
        }
    }
}
