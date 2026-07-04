<?php
// ============================================================
// ChamaFunds – api/auth.php
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/config.php';

// Get database connection
if (!isset($conn)) {
    $conn = require_once __DIR__ . '/../db/connection.php';
}

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter both email/phone and password.']);
        exit;
    }

    $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        $sql = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $identifier) . "' AND is_active = 1";
    } else {
        $phone = preg_replace('/[^0-9]/', '', $identifier);
        $sql = "SELECT * FROM users WHERE phone = '" . mysqli_real_escape_string($conn, $phone) . "' AND is_active = 1";
    }

    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // Plain text password verification
    if ($user && $password === $user['password_hash']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['user'] = [
            'user_id' => $user['user_id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'role' => $user['role'],
            'avatar_url' => $user['avatar_url'] ?? ''
        ];

        mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE user_id = " . $user['user_id']);

        // ============================================================
        // FIX: Correct redirect path
        // ============================================================
        $dest = ($user['role'] === 'admin') ? '/admin/index.php' : '/dashboard.php';
        
        // Remove /api from BASE if present
        $base = str_replace('/api', '', BASE);
        
        echo json_encode([
            'success' => true,
            'redirect' => $base . $dest
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        exit;
    }
}

// In api/auth.php
if ($action === 'logout') {
    // Include auth functions
    require_once __DIR__ . '/../includes/auth.php';
    
    // Call logout function - this will redirect
    logoutUser('/chama/login.php?msg=logged_out');
    exit;
}


// In api/auth.php
if ($action === 'logout') {
    session_destroy();
    
    // Get base URL without /api
    $base = str_replace('/api', '', BASE);
    
    echo json_encode([
        'success' => true,
        'redirect' => $base . '/login.php?msg=logged_out'
    ]);
    exit;
}

?>