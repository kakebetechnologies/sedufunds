<?php
// ============================================================
// ChamaFunds – db/connection.php
// ============================================================

// Detect if we're on localhost
$isLocal = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;

if ($isLocal) {
    // LOCAL
    $host     = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'chamafunds';
} else {
    // LIVE
    $host     = 'localhost';
    $username = 'u850523537_VPS';
    $password = '@Kt2026#Kakebe';
    $database = 'u850523537_chamafundsDB';
}

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

return $conn;
?>