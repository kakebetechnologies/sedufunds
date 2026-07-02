<?php
/**
 * Pesapal Configuration
 * Keep this file secure - DO NOT commit to public repositories
 */

// ── Environment ───────────────────────────────────────────────
// true  = sandbox (test mode, fake money)
// false = production (LIVE, real money)
define('PESAPAL_SANDBOX', false);   // ← PRODUCTION MODE (LIVE)

// ── API Credentials ───────────────────────────────────────────
// Get your LIVE keys from: https://pay.pesapal.com/merchant/settings/api
if (PESAPAL_SANDBOX) {
    // Sandbox credentials
    define('PESAPAL_CONSUMER_KEY',    'u+nAfIIT/y0vtZdwd4ypMumdpUUPmyYm');
    define('PESAPAL_CONSUMER_SECRET', 'r2hNkSEMzL9P4ByZiltITGsj/3g=');
} else {
    // Production credentials — replace with your real keys
    define('PESAPAL_CONSUMER_KEY',    'u+nAfIIT/y0vtZdwd4ypMumdpUUPmyYm');
    define('PESAPAL_CONSUMER_SECRET', 'r2hNkSEMzL9P4ByZiltITGsj/3g=');
}

// ── Public Base URL ───────────────────────────────────────────
// When running locally, set this to your ngrok HTTPS URL so
// Pesapal can reach your callback and IPN endpoints.
// Example: 'https://a1b2-105-163-0-42.ngrok-free.app'
// Leave as empty string ('') to auto-detect (works on live servers).
define('PESAPAL_NGROK_URL', '');    // ← paste your ngrok URL here when testing locally

// ── Auto-detect or use ngrok ──────────────────────────────────
if (!defined('PESAPAL_BASE_URL')) {
    if (PESAPAL_NGROK_URL !== '') {
        define('PESAPAL_BASE_URL', rtrim(PESAPAL_NGROK_URL, '/'));
    } else {
        // ALWAYS HTTPS for production. Do NOT trust $_SERVER['HTTPS'].
        $scheme = 'https'; 
        $host   = $_SERVER['HTTP_HOST'] ?? 'chama.kakebeshop.com';
        define('PESAPAL_BASE_URL', $scheme . '://' . $host);
    }
}

// ── Callback & IPN URLs ───────────────────────────────────────
define('PESAPAL_CALLBACK_URL', PESAPAL_BASE_URL . '/chama/payment_callback.php');
define('PESAPAL_IPN_URL',      PESAPAL_BASE_URL . '/chama/ipn_handler.php');

// ── Currency ──────────────────────────────────────────────────
define('PESAPAL_DEFAULT_CURRENCY', 'UGX');
