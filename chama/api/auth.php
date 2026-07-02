<?php
// ============================================================
// ChamaFunds – api/auth.php
// Handles login, signup, logout (JSON + redirect)
// ============================================================

if (session_status() === PHP_SESSION_NONE) session_start();

$conn = require_once __DIR__ . '/../db/connection.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

// ── LOGOUT ──────────────────────────────────────────────────
if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    header('Location: <?= BASE ?>/login.php?msg=logged_out');
    exit;
}

// ── LOGIN ────────────────────────────────────────────────────
if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email/phone and password are required.']);
        exit;
    }

    $identifier = $conn->real_escape_string($identifier);

    $stmt = $conn->prepare(
        "SELECT user_id, full_name, email, phone, password_hash, role, country, avatar_url, is_active, is_verified
         FROM users
         WHERE (email = ? OR phone = ?) LIMIT 1"
    );
    $stmt->bind_param('ss', $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'No account found with those credentials.']);
        exit;
    }

    if (!$user['is_active']) {
        echo json_encode(['success' => false, 'message' => 'Your account has been suspended. Contact support.']);
        exit;
    }

    if (!password_verify($password, $user['password_hash'])) {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
        exit;
    }

    // Update last login
    $uid = $user['user_id'];
    $conn->query("UPDATE users SET last_login = NOW() WHERE user_id = $uid");

    // Store session
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role']    = $user['role'];
    $_SESSION['user']    = [
        'user_id'    => $user['user_id'],
        'full_name'  => $user['full_name'],
        'email'      => $user['email'],
        'phone'      => $user['phone'],
        'role'       => $user['role'],
        'country'    => $user['country'],
        'avatar_url' => $user['avatar_url'],
        'is_verified'=> $user['is_verified'],
    ];

    $redirect = $user['role'] === 'admin' ? '/chama/admin/index.php' : '/chama/dashboard.php';
    echo json_encode(['success' => true, 'redirect' => $redirect, 'role' => $user['role']]);
    exit;
}

// ── REGISTER ─────────────────────────────────────────────────
if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $fullName = trim($_POST['full_name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $role     = in_array($_POST['role'] ?? '', ['campaigner', 'donor']) ? $_POST['role'] : 'donor';
    $country  = trim($_POST['country'] ?? 'Uganda');

    // Basic validation
    if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit;
    }
    if ($password !== $confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    // Check duplicate
    $emailEsc = $conn->real_escape_string($email);
    $phoneEsc = $conn->real_escape_string($phone);
    $existing = $conn->query("SELECT user_id FROM users WHERE email='$emailEsc' OR phone='$phoneEsc' LIMIT 1");
    if ($existing->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'An account with that email or phone already exists.']);
        exit;
    }

    $hash        = password_hash($password, PASSWORD_BCRYPT);
    $fullNameEsc = $conn->real_escape_string($fullName);
    $countryEsc  = $conn->real_escape_string($country);
    $hashEsc     = $conn->real_escape_string($hash);

    $conn->query(
        "INSERT INTO users (full_name, email, phone, password_hash, role, country, is_active, is_verified)
         VALUES ('$fullNameEsc', '$emailEsc', '$phoneEsc', '$hashEsc', '$role', '$countryEsc', 1, 0)"
    );
    $newId = $conn->insert_id;

    // Auto-login
    $_SESSION['user_id'] = $newId;
    $_SESSION['role']    = $role;
    $_SESSION['user']    = [
        'user_id'    => $newId,
        'full_name'  => $fullName,
        'email'      => $email,
        'phone'      => $phone,
        'role'       => $role,
        'country'    => $country,
        'avatar_url' => '',
        'is_verified'=> false,
    ];

    echo json_encode(['success' => true, 'redirect' => '/chama/dashboard.php']);
    exit;
}

// ── DB STATUS (used by index.php for connection ping) ─────────
if ($action === 'ping') {
    header('Content-Type: application/json');
    $ok = ($conn && !$conn->connect_error);
    echo json_encode(['success' => $ok, 'message' => $ok ? 'Database connected successfully!' : 'Connection failed.']);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid action.']);
