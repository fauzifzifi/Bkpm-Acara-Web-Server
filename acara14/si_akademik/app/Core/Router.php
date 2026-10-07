<?php
namespace App\Core;

class Router
{
    public static function dispatch(array $routes, string $method, string $uri): void
    {
        foreach ($routes[$method] ?? [] as $pattern => $route) {
            // '/mahasiswa/{id}/edit' -> regex dengan named group (id = angka)
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>\d+)', $pattern) . '$#';

            if (!preg_match($regex, $uri, $matches)) {
                continue;
            }

            $params = array_values(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
            $controllerName = $route[0];
            $action = $route[1];
            $middlewareList = $route[2] ?? [];

            // Middleware dijalankan sebelum controller dibuat
            foreach ($middlewareList as $mw) {
                $mwInstance = new $mw();
                $mwInstance->handle();
            }

            $controllerClass = "App\\Controllers\\{$controllerName}";
            // Container membuat controller + menyuntikkan dependency-nya (DI)
            $controller = Container::make($controllerClass);
            $controller->$action(...$params);
            return;
        }

        http_response_code(404);
        require __DIR__ . '/../Views/errors/404.php';
    }
}
