<?php
// ============================================================
// ChamaFunds – includes/auth.php
// Session management & role-based access helpers
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = require_once __DIR__ . '/../db/connection.php';

// ── Helpers ────────────────────────────────────────────────

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function currentRole(): string {
    return $_SESSION['role'] ?? 'guest';
}

function isAdmin(): bool {
    return currentRole() === 'admin';
}

function isCampaigner(): bool {
    return in_array(currentRole(), ['admin', 'campaigner']);
}

function requireLogin(string $redirect = '/chama/login.php'): void {
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit;
    }
}

function requireAdmin(string $redirect = '/chama/login.php'): void {
    if (!isLoggedIn() || !isAdmin()) {
        header("Location: $redirect");
        exit;
    }
}

function requireCampaigner(string $redirect = '/chama/login.php'): void {
    if (!isLoggedIn() || !isCampaigner()) {
        header("Location: $redirect");
        exit;
    }
}

// ── CSRF ───────────────────────────────────────────────────

function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Invalid CSRF token.']));
    }
}

// ── Sanitise input ─────────────────────────────────────────

function clean(string $val): string {
    return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
}

// ── Admin log helper ───────────────────────────────────────

function logAdminAction(
    mysqli $conn,
    string $action,
    string $targetType = '',
    int    $targetId   = 0,
    string $targetName = '',
    array  $changes    = []
): void {
    if (!isAdmin()) return;
    $adminId    = currentUserId();
    $ip         = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua         = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $changesJson = $conn->real_escape_string(json_encode($changes));
    $action      = $conn->real_escape_string($action);
    $targetType  = $conn->real_escape_string($targetType);
    $targetName  = $conn->real_escape_string($targetName);
    $ip          = $conn->real_escape_string($ip);
    $ua          = $conn->real_escape_string($ua);
    $conn->query(
        "INSERT INTO admin_logs (admin_id, action, target_type, target_id, target_name, changes, ip_address, user_agent)
         VALUES ($adminId, '$action', '$targetType', $targetId, '$targetName', '$changesJson', '$ip', '$ua')"
    );
}
