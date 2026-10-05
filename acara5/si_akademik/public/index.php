<?php
// public/index.php
require_once __DIR__ . '/../config/app.php';
$routes = require __DIR__ . '/../routes/web.php';

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../app/Controllers/{$class}.php",
        __DIR__ . "/../app/Models/{$class}.php",
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hilangkan base path jika project di subfolder
$base = '/acara5/si_akademik/public';
if (str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base)) ?: '/';
}

$method = $_SERVER['REQUEST_METHOD'];

if (preg_match('#^/mahasiswa/(\d+)$#', $uri, $matches) && $method === 'GET') {
    $controller = new MahasiswaController();
    $controller->show($matches[1]);
    return;
}

if (isset($routes[$method][$uri])) {
    [$controllerName, $action] = $routes[$method][$uri];
    $controller = new $controllerName();
    $controller->$action();
} else {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}