<?php
// ============================================================
// ChamaFunds – api/auth.php
// Handles login and registration via AJAX
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db/connection.php';

// Set JSON response header
header('Content-Type: application/json');

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
        // Clean phone number
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
            $redirect = '/admin/index.php';
        } else {
            $redirect = '/dashboard.php';
        }

        echo json_encode([
            'success' => true,
            'message' => 'Login successful!',
            'redirect' => BASE . $redirect,
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email/phone or password.']);
    }
    exit;
}

if ($action === 'register') {
    // Get form data
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'donor';
    $country = $_POST['country'] ?? 'Uganda';

    // Validate
    $errors = [];
    if (empty($full_name)) $errors[] = 'Full name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($phone)) $errors[] = 'Phone number is required';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit;
    }

    // Check if email exists
    $check = "SELECT user_id FROM users WHERE email = '" . mysqli_real_escape_string($conn, $email) . "'";
    $result = mysqli_query($conn, $check);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        exit;
    }

    // Check if phone exists
    $check = "SELECT user_id FROM users WHERE phone = '" . mysqli_real_escape_string($conn, $phone) . "'";
    $result = mysqli_query($conn, $check);
    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['success' => false, 'message' => 'Phone number already registered.']);
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $sql = "INSERT INTO users (full_name, email, phone, password_hash, role, country, is_active, is_verified, created_at) 
            VALUES (
                '" . mysqli_real_escape_string($conn, $full_name) . "',
                '" . mysqli_real_escape_string($conn, $email) . "',
                '" . mysqli_real_escape_string($conn, $phone) . "',
                '" . mysqli_real_escape_string($conn, $password_hash) . "',
                '" . mysqli_real_escape_string($conn, $role) . "',
                '" . mysqli_real_escape_string($conn, $country) . "',
                1,
                1,
                NOW()
            )";

    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully! Please log in.',
            'redirect' => BASE . '/login.php?registered=true'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
    exit;
}

// Fallback for unknown action
echo json_encode(['success' => false, 'message' => 'Invalid action.']);
exit;
?>