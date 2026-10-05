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

    /** Simpan flash message (tampil sekali di request berikutnya). */
    protected function flash(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}
