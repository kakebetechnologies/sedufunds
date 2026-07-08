<?php
// ============================================================
// fix-image-urls.php — Run ONCE then delete
// Fixes all broken image URLs in campaigns & campaign_images
// ============================================================
require_once __DIR__ . '/includes/config.php';

header('Content-Type: text/html');
echo '<h2>Fixing image URLs...</h2><pre>';

$fixed = 0;

// ── All patterns that need stripping to get to /uploads/... ──
$replacements = [
    // Old domain with double /chama/chama/
    'https://undpconnect.org/chama/chama' => '',
    // Old domain with single /chama/
    'https://undpconnect.org/chama'       => '',
    // Old domain root
    'https://undpconnect.org'             => '',
    // Old chamafunds sub
    'http://undpconnect.org/chama'        => '',
    // Any old chamafunds.com absolute
    'https://chamafunds.com'              => '',
    'http://chamafunds.com'               => '',
    // localhost variants
    'http://localhost/sedufunds/chama'    => '',
    'http://localhost/sedufunds'          => '',
    'http://localhost/chama'              => '',
    'https://localhost/sedufunds/chama'   => '',
];

// ── Fix campaigns.image_url ───────────────────────────────────
$result = $conn->query("SELECT campaign_id, image_url FROM campaigns WHERE image_url IS NOT NULL AND image_url != ''");
while ($row = $result->fetch_assoc()) {
    $old = $row['image_url'];
    $new = $old;

    // Skip already-correct Unsplash or relative paths
    if (strpos($new, 'unsplash.com') !== false) continue;

    foreach ($replacements as $find => $replace) {
        if (strpos($new, $find) === 0) {
            $new = $replace . substr($new, strlen($find));
            break;
        }
    }

    // Ensure it starts with /uploads/ not //uploads/
    $new = '/' . ltrim($new, '/');

    if ($new !== $old) {
        $esc = $conn->real_escape_string($new);
        $conn->query("UPDATE campaigns SET image_url='$esc' WHERE campaign_id={$row['campaign_id']}");
        echo "✅ campaign #{$row['campaign_id']}: \n   OLD: $old\n   NEW: $new\n\n";
        $fixed++;
    }
}

// ── Fix campaign_images.image_url ─────────────────────────────
$result2 = $conn->query("SELECT image_id, image_url FROM campaign_images WHERE image_url IS NOT NULL AND image_url != ''");
while ($row = $result2->fetch_assoc()) {
    $old = $row['image_url'];
    $new = $old;

    if (strpos($new, 'unsplash.com') !== false) continue;

    foreach ($replacements as $find => $replace) {
        if (strpos($new, $find) === 0) {
            $new = $replace . substr($new, strlen($find));
            break;
        }
    }

    $new = '/' . ltrim($new, '/');

    if ($new !== $old) {
        $esc = $conn->real_escape_string($new);
        $conn->query("UPDATE campaign_images SET image_url='$esc' WHERE image_id={$row['image_id']}");
        echo "✅ campaign_image #{$row['image_id']}: \n   OLD: $old\n   NEW: $new\n\n";
        $fixed++;
    }
}

echo "\n<strong>Done. $fixed URLs fixed.</strong>\n";
echo "\n<strong style='color:red'>DELETE this file from the server now!</strong></pre>";
?>
