<?php
// ============================================================
// ChamaFunds – includes/config.php
// Site-wide configuration — included via header.php
// ============================================================

if (!defined('BASE')) {
    $isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1'])
            || str_starts_with($_SERVER['HTTP_HOST'] ?? '', 'localhost:');
    // localhost XAMPP: '/chama'  |  live server root: ''
    define('BASE', $isLocal ? '/chama' : '');
}
