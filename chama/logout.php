<?php
// ============================================================
// ChamaFunds – logout.php
// ============================================================

// Include the auth system
require_once __DIR__ . '/includes/auth.php';

// Call the logout function
// This will clear session, delete cookie, destroy session, and redirect
logoutUser('/chama/login.php?msg=logged_out');
exit;
?>