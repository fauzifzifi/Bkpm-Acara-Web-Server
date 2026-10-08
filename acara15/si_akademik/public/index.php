<?php
// Front Controller: semua request masuk lewat file ini.

require __DIR__ . '/../app/autoload.php';

// Session hanya untuk halaman web. API bersifat stateless (tanpa cookie session).
if (!App\Core\ErrorHandler::isApiRequest()) {
    session_start();
}

// Base path otomatis (misal /si_akademik/public), tidak perlu diedit manual.
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
define('BASE_URL', $base);

require __DIR__ . '/../app/Core/helpers.php';

// Penanganan error global (log + halaman 500 aman, atau JSON untuk URL /api/)
App\Core\ErrorHandler::register();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($base !== '' && str_starts_with($uri, $base)) {
    $uri = substr($uri, strlen($base));
}
$uri = '/' . trim($uri, '/');

$method = $_SERVER['REQUEST_METHOD'];
$routes = require __DIR__ . '/../routes/web.php';

App\Core\Router::dispatch($routes, $method, $uri);
