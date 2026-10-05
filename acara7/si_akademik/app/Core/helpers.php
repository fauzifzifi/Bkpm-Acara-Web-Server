<?php
// Fungsi bantu global.

/** Bangun URL dengan memperhitungkan base path (aman di subfolder XAMPP). */
function url(string $path = ''): string
{
    return (defined('BASE_URL') ? BASE_URL : '') . '/' . ltrim($path, '/');
}

/** Escape output HTML (cegah XSS). */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
