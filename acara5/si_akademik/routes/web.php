<?php

return [
    'GET' => [
        '/' => ['HomeController', 'index'],
        '/mahasiswa' => ['MahasiswaController', 'index'],
        '/mahasiswa/create' => ['MahasiswaController', 'create'],
    ],
    'POST' => [
        '/mahasiswa' => ['MahasiswaController', 'store'],
    ],
];