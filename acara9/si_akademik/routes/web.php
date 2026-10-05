<?php
use App\Core\Middleware\AuthMiddleware;

$auth = [AuthMiddleware::class];

// Format: 'URI' => [Controller, method, [middleware...]]
// {id} dikirim sebagai argumen ke method controller.
// store, update, destroy memakai POST (bukan GET).
return [
    'GET' => [
        '/'                      => ['HomeController', 'index'],
        '/login'                 => ['AuthController', 'loginForm'],
        '/logout'                => ['AuthController', 'logout'],
        '/dashboard'             => ['HomeController', 'dashboard', $auth],

        // Mahasiswa
        '/mahasiswa'             => ['MahasiswaController', 'index', $auth],
        '/mahasiswa/create'      => ['MahasiswaController', 'create', $auth],
        '/mahasiswa/{id}/edit'   => ['MahasiswaController', 'edit', $auth],

        // Program Studi
        '/prodi'                 => ['ProdiController', 'index', $auth],
        '/prodi/create'          => ['ProdiController', 'create', $auth],
        '/prodi/{id}/edit'       => ['ProdiController', 'edit', $auth],

        // Mata Kuliah
        '/matakuliah'            => ['MataKuliahController', 'index', $auth],
        '/matakuliah/create'     => ['MataKuliahController', 'create', $auth],
        '/matakuliah/{id}/edit'  => ['MataKuliahController', 'edit', $auth],
    ],
    'POST' => [
        '/login'                 => ['AuthController', 'login'],

        '/mahasiswa'             => ['MahasiswaController', 'store', $auth],
        '/mahasiswa/{id}/update' => ['MahasiswaController', 'update', $auth],
        '/mahasiswa/{id}/delete' => ['MahasiswaController', 'destroy', $auth],

        '/prodi'                 => ['ProdiController', 'store', $auth],
        '/prodi/{id}/update'     => ['ProdiController', 'update', $auth],
        '/prodi/{id}/delete'     => ['ProdiController', 'destroy', $auth],

        '/matakuliah'            => ['MataKuliahController', 'store', $auth],
        '/matakuliah/{id}/update' => ['MataKuliahController', 'update', $auth],
        '/matakuliah/{id}/delete' => ['MataKuliahController', 'destroy', $auth],
    ],
];
