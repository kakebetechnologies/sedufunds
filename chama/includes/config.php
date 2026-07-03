<?php
// ============================================================
// ChamaFunds – includes/config.php
// ============================================================

function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $path = dirname($scriptName);
    
    if ($path === DIRECTORY_SEPARATOR || $path === '\\' || $path === '/') {
        $path = '';
    }
    $path = rtrim($path, '/');
    
    return $protocol . $host . $path;
}

define('BASE', getBaseUrl());
define('CSS_URL', BASE . '/css');
define('JS_URL', BASE . '/js');
define('ASSETS_URL', BASE . '/assets');
define('IMAGES_URL', BASE . '/assets/images');

// Database connection - auto-detects local vs live
$conn = require_once __DIR__ . '/../db/connection.php';

function asset($path) {
    return BASE . '/' . ltrim($path, '/');
}
?>