<?php
class Router {
    private array $map = [
        'mahasiswa' => 'MahasiswaController',
        'register'  => 'AuthController',
    ];

    public function dispatch(): void {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        $segments = explode('/', $uri);
        $resource = end($segments) ?: 'mahasiswa';
        $controllerName = $this->map[$resource] ?? null;

        if (!$controllerName) {
            Response::json(404, false, 'Endpoint tidak ditemukan');
        }

        $controller = new $controllerName();

        // Penanganan khusus: register bukan resource CRUD biasa
        if ($resource === 'register') {
            $controller->register();
            return;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':    $controller->index(); break;
            case 'POST':   $controller->store(); break;
            case 'PUT':    $controller->update($_GET['id'] ?? null); break;
            case 'DELETE': $controller->destroy($_GET['id'] ?? null); break;
            default:
                Response::json(405, false, 'Method tidak diizinkan');
        }
    }
}