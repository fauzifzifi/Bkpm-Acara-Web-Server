<?php
// public/index.php - Front Controller
// Semua request (lewat .htaccess) masuk ke sini.

// Mulai session (harus dipanggil sebelum output apa pun) - dipakai untuk Auth
session_start();

// Autoload sederhana untuk class di dalam namespace App\ (tanpa Composer)
spl_autoload_register(function ($class) {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    if (str_starts_with($class, $prefix)) {
        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

// Konfigurasi & routing
$config = require __DIR__ . '/../config/app.php';
$routes = require __DIR__ . '/../routes/web.php';

define('BASE_URL', $config['base_url']);

// 1. Ambil URL yang diminta (tanpa query string)
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan base path karena project ada di subfolder Laragon (/acara_6/public)
if (str_starts_with($uri, $config['base_url'])) {
    $uri = substr($uri, strlen($config['base_url'])) ?: '/';
}
$uri = rtrim($uri, '/');
if ($uri === '') {
    $uri = '/';
}

// 2. Ambil method (GET/POST)
$method = $_SERVER['REQUEST_METHOD'];

// Jalankan middleware (jika ada) yang terpasang pada route, baru panggil Controller@action
$dispatch = function (array $route, ...$params): void {
    foreach ($route['middleware'] ?? [] as $middlewareClass) {
        (new $middlewareClass())->handle();
    }

    $controllerClass = "App\\Controllers\\{$route['controller']}";
    if ($route['controller'] === 'ApiMahasiswaController') {
        try {
            $database = App\Core\Database::getInstance();
            $mahasiswaRepository = new App\Repositories\MahasiswaRepository($database);
            $prodiRepository = new App\Repositories\ProdiRepository($database);
            $mahasiswaService = new App\Services\MahasiswaService($mahasiswaRepository, $prodiRepository);
            $controller = new $controllerClass($mahasiswaRepository, $mahasiswaService);
            $controller->{$route['action']}(...$params);
        } catch (Throwable $exception) {
            App\Core\Logger::error($exception);
            http_response_code(500);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data mahasiswa.',
                'data' => null,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return;
    }

    if ($route['controller'] === 'MahasiswaController') {
        try {
            $database = App\Core\Database::getInstance();
            $mahasiswaRepository = new App\Repositories\MahasiswaRepository($database);
            $prodiRepository = new App\Repositories\ProdiRepository($database);
            $mahasiswaService = new App\Services\MahasiswaService($mahasiswaRepository, $prodiRepository);
            $controller = new $controllerClass($mahasiswaRepository, $prodiRepository, $mahasiswaService);
            $controller->{$route['action']}(...$params);
        } catch (Throwable $exception) {
            App\Core\Logger::error($exception);
            http_response_code(500);
            echo 'Terjadi kesalahan saat memproses data mahasiswa.';
        }

        return;
    } else {
        $controller = new $controllerClass();
    }
    $controller->{$route['action']}(...$params);
};

// 3. Cari di array routes (rute statis)
if (isset($routes[$method][$uri])) {
    $dispatch($routes[$method][$uri]);
    return;
}

// API REST mahasiswa menggunakan route tersendiri agar tidak mengubah halaman web.
if (preg_match('#^/api/mahasiswa(?:/(\d+))?$#', $uri, $matches)) {
    $hasId = isset($matches[1]) && $matches[1] !== '';
    $apiRoutes = $hasId
        ? [
            'GET' => ['action' => 'show', 'params' => [(int) $matches[1]]],
            'PUT' => ['action' => 'update', 'params' => [(int) $matches[1]]],
            'DELETE' => ['action' => 'destroy', 'params' => [(int) $matches[1]]],
        ]
        : [
            'GET' => ['action' => 'index', 'params' => []],
            'POST' => ['action' => 'store', 'params' => []],
        ];

    if (!isset($apiRoutes[$method])) {
        http_response_code(405);
        header('Allow: ' . implode(', ', array_keys($apiRoutes)));
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Method tidak diizinkan untuk endpoint ini.',
            'data' => null,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return;
    }

    $apiRoute = $apiRoutes[$method];
    $dispatch([
        'controller' => 'ApiMahasiswaController',
        'action' => $apiRoute['action'],
    ], ...$apiRoute['params']);
    return;
}

// Route resource dinamis untuk edit, update, dan delete.
$resourceControllers = [
    'mahasiswa' => 'MahasiswaController',
    'prodi' => 'ProdiController',
    'matakuliah' => 'MatakuliahController',
];

if ($method === 'GET' && preg_match('#^/(mahasiswa|prodi|matakuliah)/(\d+)/edit$#', $uri, $matches)) {
    $resource = $matches[1];
    $dispatch([
        'controller' => $resourceControllers[$resource],
        'action' => 'edit',
        'middleware' => ['App\\Core\\Middleware\\AuthMiddleware'],
    ], (int) $matches[2]);
    return;
}

if ($method === 'GET' && preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches)) {
    $dispatch([
        'controller' => 'MahasiswaController',
        'action' => 'show',
        'middleware' => ['App\\Core\\Middleware\\AuthMiddleware'],
    ], (int) $matches[1]);
    return;
}

if ($method === 'POST' && preg_match('#^/(mahasiswa|prodi|matakuliah)/(\d+)(?:/(delete))?$#', $uri, $matches)) {
    $resource = $matches[1];
    $dispatch([
        'controller' => $resourceControllers[$resource],
        'action' => empty($matches[3]) ? 'update' : 'delete',
        'middleware' => ['App\\Core\\Middleware\\AuthMiddleware'],
    ], (int) $matches[2]);
    return;
}

// 4. Jika tidak ketemu, tampilkan 404
http_response_code(404);
$content = __DIR__ . '/../app/Views/errors/404.php';
require __DIR__ . '/../app/Views/layouts/main.php';
