<?php
// ============================================================
// ChamaFunds – db/connection.php
// Auto-detects local vs live environment
// ============================================================

// Detect if we're on localhost
$isLocal = strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || 
           strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false;

if ($isLocal) {
    // ============================================================
    // LOCAL ENVIRONMENT (XAMPP)
    // ============================================================
    $host     = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'chamafunds';
} else {
    // ============================================================
    // LIVE ENVIRONMENT (Hostinger)
    // ============================================================
    $host     = 'localhost';
    $username = 'u850523537_VPS';
    $password = '@Kt2026#Kakebe';
    $database = 'u850523537_chamafundsDB';
}

// ============================================================
// Create connection
// ============================================================
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8mb4");

// ============================================================
// RETURN the connection object
// ============================================================
return $conn;
?>