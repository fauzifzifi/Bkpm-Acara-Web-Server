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

/** Ambil input lama (setelah validasi gagal), atau nilai default. */
function old(string $key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

/** URL halaman pagination, mempertahankan parameter lain (mis. ?q=...). */
function page_url(int $page): string
{
    $query = $_GET;
    $query['page'] = $page;
    return strtok($_SERVER['REQUEST_URI'], '?') . '?' . http_build_query($query);
}

/** Panjang teks (karakter UTF-8); tetap jalan walau ekstensi mbstring tidak aktif. */
function str_len(string $text): int
{
    return function_exists('mb_strlen') ? mb_strlen($text) : (int) preg_match_all('/./us', $text);
}
