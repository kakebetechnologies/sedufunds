<?php
// ============================================================
// ChamaFunds – includes/config.php
// Site-wide configuration — included via header.php
// ============================================================

if (!defined('BASE')) {
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isLocal = in_array($host, ['localhost', '127.0.0.1', '::1'])
            || str_starts_with($host, 'localhost:');

    // Local XAMPP: files live under /chama
    // Live server: files live at the domain root
    define('BASE', $isLocal ? '/chama' : '');
}
