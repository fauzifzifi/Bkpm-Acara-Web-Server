<?php
// Front Controller: semua request masuk lewat file ini.

// Autoloader sederhana (pengganti Composer): App\Core\Router -> app/Core/Router.php
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $path = __DIR__ . "/../app/{$relative}.php";
    if (file_exists($path)) {
        require $path;
    }
});

session_start();

// Base path otomatis (misal /si_akademik/public), tidak perlu diedit manual.
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $base);

require __DIR__ . '/../app/Core/helpers.php';

// Penanganan error global: catat ke storage/logs/app.log, lalu tampilkan halaman 500
// yang aman (tanpa detail teknis kecuali debug = true).
set_exception_handler(function (Throwable $e) {
    App\Core\Logger::error($e, 'Unhandled');

    $config = require __DIR__ . '/../config/app.php';
    http_response_code(500);
    $debug = $config['debug'] ?? false;
    $message = $e->getMessage();
    require __DIR__ . '/../app/Views/errors/500.php';
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . trim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];
$routes = require __DIR__ . '/../routes/web.php';

App\Core\Router::dispatch($routes, $method, $uri);
