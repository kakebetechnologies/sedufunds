<?php
// ============================================================
// test-email.php
// ============================================================

// Load Composer autoloader - adjust path if needed
$autoload_path = __DIR__ . '/vendor/autoload.php';

if (!file_exists($autoload_path)) {
    die('❌ autoload.php not found at: ' . $autoload_path . 
        '<br>Run: composer require phpmailer/phpmailer');
}

require_once $autoload_path;

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo '✅ PHPMailer loaded successfully!<br>';
} else {
    die('❌ PHPMailer class not found. Try running: composer dump-autoload');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ── SMTP Settings ──
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ot.sedrick@gmail.com';
    $mail->Password   = 'igemnyvfuejonian'; // Google App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('ot.sedrick@gmail.com', 'ChamaFunds');
    $mail->addAddress('ot.sedrick@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = '✅ Test Email from ChamaFunds';
    $mail->Body    = '<h2>Test Successful!</h2><p>Your PHPMailer is working.</p>';
    $mail->AltBody = 'Test Successful!';

    $mail->send();
    echo '✅ Email sent successfully!';
} catch (Exception $e) {
    echo "❌ Email failed: {$mail->ErrorInfo}";
}
?>