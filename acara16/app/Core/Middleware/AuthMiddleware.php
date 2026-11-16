<?php
namespace App\Core\Middleware;

class AuthMiddleware
{
    // Dipanggil router sebelum Controller dijalankan.
    // Jika user belum login, alihkan ke halaman /login.
    public function handle(): void
    {
        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . BASE_URL . '/login');
            exit; // penting! stop eksekusi setelah header Location
        }
    }
}
