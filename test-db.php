<?php
require_once 'includes/config.php';

echo "Environment: " . (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? 'LOCAL' : 'LIVE') . "<br>";
echo "Database: " . (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ? 'chamafunds' : 'u850523537_chamafundsDB') . "<br>";

if ($conn) {
    echo "✅ Database connection successful!<br>";
    
    // Test query
    $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM users");
    $row = mysqli_fetch_assoc($result);
    echo "👤 Users in database: " . $row['count'];
} else {
    echo "❌ Database connection failed!";
}
?>