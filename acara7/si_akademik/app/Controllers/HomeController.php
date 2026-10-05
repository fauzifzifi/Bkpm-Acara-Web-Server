<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('home/index', ['title' => 'Beranda']);
    }

    public function dashboard()
    {
        $this->view('home/dashboard', [
            'title'    => 'Dashboard',
            'userName' => $_SESSION['user_name'] ?? 'User',
        ]);
    }
}
