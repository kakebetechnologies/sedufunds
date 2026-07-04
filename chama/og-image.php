<?php
// ============================================================
// ChamaFunds – og-image.php
// Generates a branded 1200×630 OG social card on the fly.
// Falls back to a redirect to a static branded image if GD
// is not available.
// ============================================================

// If GD not loaded, redirect to a reliable static fallback
if (!extension_loaded('gd')) {
    header('Location: https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1200&h=630&fit=crop&q=85');
    exit;
}

// Cache for 24 hours
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');

$title = $_GET['title'] ?? 'ChamaFunds';
$sub   = $_GET['sub']   ?? 'Pool money together for what matters most.';
$title = mb_substr(strip_tags($title), 0, 60);
$sub   = mb_substr(strip_tags($sub),   0, 90);

$W = 1200; $H = 630;
$img = imagecreatetruecolor($W, $H);

// ── Colours ──────────────────────────────────────────────────
$navy   = imagecolorallocate($img, 26,  42, 108);  // #1A2A6C
$navy2  = imagecolorallocate($img, 18,  30,  80);  // darker navy
$orange = imagecolorallocate($img, 255,107, 74);   // #FF6B4A
$white  = imagecolorallocate($img, 255,255,255);
$muted  = imagecolorallocate($img, 180,190,220);
$green  = imagecolorallocate($img,  16,185,129);   // #10b981
$dark   = imagecolorallocate($img,  10, 20, 60);

// ── Background gradient simulation (fill top/bottom halves) ──
imagefilledrectangle($img, 0,   0,   $W, 315, $navy2);
imagefilledrectangle($img, 0, 315,   $W, $H,  $navy);

// ── Decorative circles ────────────────────────────────────────
imagefilledellipse($img, -60, -60, 340, 340, $dark);
imagefilledellipse($img, $W+60, $H+60, 400, 400, $dark);
imagefilledellipse($img, $W-80, 80, 220, 220, imagecolorallocatealpha($img, 255,107,74, 90));

// ── Orange accent bar (left edge) ────────────────────────────
imagefilledrectangle($img, 0, 0, 8, $H, $orange);

// ── Logo box ─────────────────────────────────────────────────
$lx = 72; $ly = 72; $ls = 80;
imagefilledroundrect($img, $lx, $ly, $lx+$ls, $ly+$ls, 18, $orange);

// Helper: rounded rect
function imagefilledroundrect($img, $x1, $y1, $x2, $y2, $r, $col) {
    imagefilledrectangle($img, $x1+$r, $y1, $x2-$r, $y2, $col);
    imagefilledrectangle($img, $x1, $y1+$r, $x2, $y2-$r, $col);
    imagefilledellipse($img, $x1+$r, $y1+$r, $r*2, $r*2, $col);
    imagefilledellipse($img, $x2-$r, $y1+$r, $r*2, $r*2, $col);
    imagefilledellipse($img, $x1+$r, $y2-$r, $r*2, $r*2, $col);
    imagefilledellipse($img, $x2-$r, $y2-$r, $r*2, $r*2, $col);
}

// ── "CF" text inside logo box ─────────────────────────────────
$font = 5; // built-in GD font
$cfW = imagefontwidth($font) * 2;
$cfH = imagefontheight($font);
imagestring($img, $font, $lx + ($ls - $cfW)/2, $ly + ($ls - $cfH)/2, 'CF', $white);

// ── Brand name ───────────────────────────────────────────────
$font4 = 4;
imagestring($img, $font4, $lx + $ls + 18, $ly + 18, 'ChamaFunds', $white);
imagestring($img, 2, $lx + $ls + 18, $ly + 44, 'Mobile Money Crowdfunding', $muted);

// ── Divider line ──────────────────────────────────────────────
imageline($img, 72, 190, $W - 72, 190, imagecolorallocate($img, 40, 60, 130));

// ── Main title (word-wrap) ────────────────────────────────────
$lines = wordwrap($title, 36, "\n", true);
$linesArr = explode("\n", $lines);
$ty = 220;
foreach ($linesArr as $line) {
    imagestring($img, 5, 72, $ty, $line, $white);
    $ty += 30;
}

// ── Subtitle ──────────────────────────────────────────────────
$subLines = wordwrap($sub, 55, "\n", true);
$subArr = explode("\n", $subLines);
$sy = $ty + 14;
foreach ($subArr as $sl) {
    imagestring($img, 3, 72, $sy, $sl, $muted);
    $sy += 22;
}

// ── Green "Free to start" pill ───────────────────────────────
$pill = '● Free to start  ●  MTN & Airtel Money  ●  Same-day payout';
imagestring($img, 2, 72, $H - 90, $pill, $green);

// ── Bottom URL ───────────────────────────────────────────────
imagestring($img, 2, 72, $H - 60, 'chama.kakebeshop.com', $muted);

// ── Output ───────────────────────────────────────────────────
imagepng($img);
imagedestroy($img);
