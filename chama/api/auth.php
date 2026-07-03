<?php
// ============================================================
// ChamaFunds – api/auth.php
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();

// Include config - this will give us $conn
require_once __DIR__ . '/../includes/config.php';

// $conn should now be available from config.php
// But to be safe, let's make sure we have it
if (!isset($conn)) {
    // If $conn isn't set, get it directly
    $conn = require_once __DIR__ . '/../db/connection.php';
}

$action = $_GET['action'] ?? '';

if ($action === 'login') {
    // Get form data
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please enter both email/phone and password.']);
        exit;
    }

    // Check if identifier is email or phone
    $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        $sql = "SELECT * FROM users WHERE email = '" . mysqli_real_escape_string($conn, $identifier) . "' AND is_active = 1";
    } else {
        $phone = preg_replace('/[^0-9]/', '', $identifier);
        $sql = "SELECT * FROM users WHERE phone = '" . mysqli_real_escape_string($conn, $phone) . "' AND is_active = 1";
    }

    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // Verify password
    if ($user && password_verify($password, $user['password_hash'])) {
        // Login successful
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

        // Update last login
        mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE user_id = " . $user['user_id']);

        // Determine redirect
        if ($user['role'] === 'admin') {
            $dest = '/admin/index.php';
        } else {
            $dest = '/dashboard.php';
        }

        echo json_encode([
            'success' => true,
            'redirect' => BASE . $dest
        ]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        exit;
    }
}

// Handle logout
if ($action === 'logout') {
    session_destroy();
    echo json_encode(['success' => true, 'redirect' => BASE . '/login.php?msg=logged_out']);
    exit;
}

// Fallback
echo json_encode(['success' => false, 'message' => 'Invalid action.']);
exit;
?>