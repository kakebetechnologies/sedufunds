<?php
// ============================================================
// ChamaFunds – db/connection.php
// Central database connection (MySQLi)
// ============================================================

// Auto-detect environment: use local config on localhost, live config on server
$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1'])
        || str_starts_with($_SERVER['HTTP_HOST'] ?? '', 'localhost:');

if ($isLocal) {
    // Local XAMPP
    $host     = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'chamafunds';
} else {
    // Live server
    $host     = 'localhost';
    $username = 'u850523537_VPS';
    $password = '@Kt2026#Kakebe';
    $database = 'u850523537_chamafundsDB';
}

// Enable MySQLi error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $username, $password, $database);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    // Production-friendly error — no raw credentials exposed
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please contact support.'
    ]));
}

// Make connection available globally
return $conn;