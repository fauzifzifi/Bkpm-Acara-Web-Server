<?php
// app/Core/Middleware/AuthMiddleware.php
namespace App\Core\Middleware;

class AuthMiddleware
{
    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Belum login -> arahkan ke halaman login
        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
