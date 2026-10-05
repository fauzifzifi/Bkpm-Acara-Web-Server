<?php
use App\Core\Middleware\AuthMiddleware;

// Format: 'URI' => [Controller, method, [middleware...]]
return [
    'GET' => [
        '/'                 => ['HomeController', 'index'],
        '/login'            => ['AuthController', 'loginForm'],
        '/logout'           => ['AuthController', 'logout'],

        // Route yang dilindungi AuthMiddleware
        '/dashboard'        => ['HomeController', 'dashboard', [AuthMiddleware::class]],
        '/mahasiswa'        => ['MahasiswaController', 'index', [AuthMiddleware::class]],
        '/mahasiswa/create' => ['MahasiswaController', 'create', [AuthMiddleware::class]],
        '/mahasiswa/edit'   => ['MahasiswaController', 'edit', [AuthMiddleware::class]],
    ],
    'POST' => [
        '/login'            => ['AuthController', 'login'],
        '/mahasiswa'        => ['MahasiswaController', 'store', [AuthMiddleware::class]],
    ],
];
