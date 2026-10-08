<?php
// Autoloader sederhana (pengganti Composer): App\Core\Router -> app/Core/Router.php
// Dipakai bersama oleh public/index.php (halaman web) dan public/api/mahasiswa.php (API).
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $path = __DIR__ . "/{$relative}.php";
    if (file_exists($path)) {
        require $path;
    }
});
