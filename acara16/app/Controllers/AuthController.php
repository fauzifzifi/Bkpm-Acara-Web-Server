<?php
namespace App\Controllers;

class AuthController
{
    // Kredensial sementara (hardcode). Akan diganti tabel user di database pada Acara 7/8.
    private const USERNAME = '1';
    private const PASSWORD = '1';

    // GET /login
    public function loginForm(): void
    {
        // Kalau sudah login, tidak perlu lihat form login lagi
        if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $content = __DIR__ . '/../Views/auth/login.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    // POST /login
    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === self::USERNAME && $password === self::PASSWORD) {
            $_SESSION['user_id']    = 1;
            $_SESSION['user_name']  = 'Admin';
            $_SESSION['logged_in']  = true;

            // Tugas Mandiri: flash message setelah login sukses
            $_SESSION['flash_success'] = 'Selamat datang, Admin';

            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        $_SESSION['flash_error'] = 'Username atau password salah.';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // GET /logout
    public function logout(): void
    {
        // Hapus data login. Sesi tidak di-destroy penuh agar flash message
        // di bawah ini masih bisa ditampilkan setelah redirect.
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['logged_in']);

        // Tugas Mandiri: flash message setelah logout
        $_SESSION['flash_info'] = 'Anda telah logout';

        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
