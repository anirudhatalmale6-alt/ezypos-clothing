<?php
// PHP built-in server router for CodeIgniter 3
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// If the file exists, serve it directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Otherwise route through index.php
$_SERVER['PATH_INFO'] = $uri;
require_once __DIR__ . '/index.php';
