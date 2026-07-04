<?php
// ============================================================
// ChamaFunds – includes/notifications.php
// ============================================================

function notifyNewCampaign($conn, $campaign_id, $campaign_data) {
    sendCampaignCreationEmail($campaign_data);
    saveInAppNotification($conn, $campaign_data);
}

function sendCampaignCreationEmail($campaign_data) {
    $admin_email = 'ot.sedrick@gmail.com'; // ✅ Your email
    
    $subject = '🚀 New Campaign Created: ' . $campaign_data['title'];
    // ... (rest of email function)
}

function saveInAppNotification($conn, $campaign_data) {
    // ... (in-app notification function)
}
?>