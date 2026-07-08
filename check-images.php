<?php
require_once __DIR__ . '/includes/config.php';
header('Content-Type: text/plain');

echo "BASE = " . BASE . "\n\n";

echo "=== campaigns table — image columns ===\n";
$result = $conn->query("SELECT campaign_id, title, image_url, multiple_images FROM campaigns ORDER BY campaign_id DESC LIMIT 10");

if (!$result) {
    // image_url column may not exist — try without it
    $result = $conn->query("SELECT campaign_id, title, multiple_images FROM campaigns ORDER BY campaign_id DESC LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        echo "ID={$row['campaign_id']} | title={$row['title']}\n";
        echo "  multiple_images=[{$row['multiple_images']}]\n\n";
    }
} else {
    while ($row = $result->fetch_assoc()) {
        echo "ID={$row['campaign_id']} | title={$row['title']}\n";
        echo "  image_url=[" . ($row['image_url'] ?? 'NULL') . "]\n";
        echo "  multiple_images=[{$row['multiple_images']}]\n\n";
    }
}

echo "\n=== campaign_images table (first 10) ===\n";
$r2 = $conn->query("SELECT image_id, campaign_id, image_url FROM campaign_images ORDER BY image_id DESC LIMIT 10");
if ($r2 && $r2->num_rows > 0) {
    while ($row = $r2->fetch_assoc()) {
        echo "img_id={$row['image_id']} camp={$row['campaign_id']} url=[{$row['image_url']}]\n";
    }
} else {
    echo "No rows found (or table doesn't exist)\n";
    if ($conn->error) echo "Error: " . $conn->error . "\n";
}
?>
