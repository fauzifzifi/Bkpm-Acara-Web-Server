<?php
namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller
{
    // GET /login -> tampilkan form login
    public function loginForm()
    {
        if (!empty($_SESSION['logged_in'])) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login', ['title' => 'Login']);
    }

    // POST /login -> proses login (username/password hardcode sementara)
    public function login()
    {
        $config   = require __DIR__ . '/../../config/app.php';
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === $config['admin_user'] && $password === $config['admin_pass']) {
            session_regenerate_id(true); // cegah session fixation

            $_SESSION['logged_in'] = true;
            $_SESSION['user_name'] = 'Admin';
            $this->flash('Selamat datang, Admin', 'success');
            $this->redirect('/dashboard');
        }

        $this->flash('Login gagal, username atau password salah.', 'danger');
        $this->redirect('/login');
    }

    // GET /logout -> hapus session
    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        session_start(); // session baru hanya untuk membawa flash message
        $_SESSION['flash'] = ['type' => 'info', 'message' => 'Anda telah logout'];
        $this->redirect('/login');
    }
}
