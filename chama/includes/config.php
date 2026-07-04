<?php
// ============================================================
// ChamaFunds – includes/config.php
// ============================================================

function getBaseUrl() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];

    // __DIR__ here is the /includes folder; project root is one level up
    $docRoot    = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
    $projectDir = rtrim(str_replace('\\', '/', dirname(__DIR__)), '/');

    // Get the web path to the project root (e.g. /sedufunds/chama on local, '' on live)
    $path = str_replace($docRoot, '', $projectDir);
    $path = rtrim($path, '/');

    return $protocol . $host . $path;
}

define('BASE', getBaseUrl());
define('CSS_URL', BASE . '/css');
define('JS_URL', BASE . '/js');
define('ASSETS_URL', BASE . '/assets');
define('IMAGES_URL', BASE . '/assets/images');

// Database connection - auto-detects local vs live
// Use $GLOBALS so $conn is accessible everywhere after this file is included
if (!isset($GLOBALS['conn']) || !($GLOBALS['conn'] instanceof mysqli)) {
    $GLOBALS['conn'] = (function() {
        $isLocal = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
                   strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;
        if ($isLocal) {
            $h = 'localhost'; $u = 'root'; $p = ''; $db = 'chamafunds';
        } else {
            $h = 'localhost'; $u = 'u850523537_VPS'; $p = '@Kt2026#Kakebe'; $db = 'u850523537_chamafundsDB';
        }
        $c = new mysqli($h, $u, $p, $db);
        if ($c->connect_error) die("DB connection failed: " . $c->connect_error);
        $c->set_charset("utf8mb4");
        return $c;
    })();
}
$conn = $GLOBALS['conn'];

function asset($path) {
    return BASE . '/' . ltrim($path, '/');
}
?>