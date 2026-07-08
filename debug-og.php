<?php
// ============================================================
// debug-og.php — paste ?id=10 (or any active campaign id)
// DELETE after debugging
// ============================================================
require_once __DIR__ . '/includes/config.php';

$cid = (int)($_GET['id'] ?? 10);

header('Content-Type: text/plain');

echo "BASE = " . BASE . "\n\n";

// Fetch campaign
$c = $conn->query(
    "SELECT c.*, ROUND((c.raised_amount/c.goal_amount)*100,1) AS pct,
            DATEDIFF(c.end_date, NOW()) AS days_left
     FROM campaigns c WHERE c.campaign_id = $cid LIMIT 1"
)->fetch_assoc();

echo "campaign_id   = " . $c['campaign_id'] . "\n";
echo "title         = " . $c['title'] . "\n";
echo "image_url     = [" . $c['image_url'] . "]\n\n";

// Fetch campaign_images
$imgsResult = $conn->query(
    "SELECT image_id, image_url, is_cover FROM campaign_images
     WHERE campaign_id=$cid ORDER BY is_cover DESC, sort_order ASC LIMIT 5"
);
echo "campaign_images rows: " . $imgsResult->num_rows . "\n";
while ($img = $imgsResult->fetch_assoc()) {
    echo "  img_id={$img['image_id']} is_cover={$img['is_cover']} url=[{$img['image_url']}]\n";
}

echo "\n";

// Simulate resolveImgUrl
function resolveImgUrl($url, $base) {
    if (empty($url)) return '';
    if (strpos($url, 'http') === 0) return $url;
    return $base . '/' . ltrim($url, '/');
}

$resolved = resolveImgUrl($c['image_url'], BASE);
echo "Resolved image_url = [" . $resolved . "]\n";

// What would ogImage be?
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = trim($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https' ? 'https' : $protocol;
}

$ogImage = $resolved;
if (!empty($ogImage) && strpos($ogImage, 'http') !== 0) {
    $ogImage = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . ltrim($ogImage, '/');
}
if (!empty($ogImage)) {
    $ogImage = preg_replace('#^http://#', 'https://', $ogImage);
}

echo "Final ogImage  = [" . $ogImage . "]\n\n";

// Check if file exists on disk
$path = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($c['image_url'], '/');
echo "Physical file path = " . $path . "\n";
echo "File exists on disk? " . (file_exists($path) ? "YES ✅" : "NO ❌") . "\n";
?>
