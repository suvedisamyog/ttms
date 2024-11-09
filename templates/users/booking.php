<?php
// Get the HTTP Referer (the URL the user came from)
$referrer = $_SERVER['HTTP_REFERER'] ?? '';
lg($referrer);
// Check if the referrer is from a specific page (optional)
if ($referrer) {
    echo "User came from: " . htmlspecialchars($referrer);
} else {
    echo "User may have typed the URL manually.";
}
?>
