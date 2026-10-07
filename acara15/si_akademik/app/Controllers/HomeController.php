<?php
namespace App\Controllers;

use App\Core\BaseController;

class HomeController extends BaseController
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
