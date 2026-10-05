<?php
namespace App\Core;

class Router
{
    public static function dispatch(array $routes, string $method, string $uri): void
    {
        if (!isset($routes[$method][$uri])) {
            http_response_code(404);
            require __DIR__ . '/../Views/errors/404.php';
            return;
        }

        $route = $routes[$method][$uri];
        $controllerName = $route[0];
        $action = $route[1];
        $middlewareList = $route[2] ?? [];

        // Pasang middleware sebelum controller dipanggil
        foreach ($middlewareList as $mw) {
            $mwInstance = new $mw();
            $mwInstance->handle();
        }

        $controllerClass = "App\\Controllers\\{$controllerName}";
        $controller = new $controllerClass();
        $controller->$action();
    }
}
