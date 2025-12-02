<?php
/**
 * WordPress URL Fixer for Railway
 * This script updates the siteurl and home options in the WordPress database
 * Run once and delete after use
 */

// Load WordPress
require_once(__DIR__ . '/wp-load.php');

echo "<pre style='background: #f5f5f5; padding: 20px; font-family: monospace;'>";
echo "=== WordPress URL Fixer ===\n\n";

// Get current and desired URLs
$current_http_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$current_protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$new_home_url = $current_protocol . '://' . $current_http_host;

// Get current values from database
$old_home = get_option('home');
$old_siteurl = get_option('siteurl');

echo "Current Database Values:\n";
echo "  home: " . $old_home . "\n";
echo "  siteurl: " . $old_siteurl . "\n\n";

echo "New Values to Set:\n";
echo "  home: " . $new_home_url . "\n";
echo "  siteurl: " . $new_home_url . "\n\n";

if ($old_home !== $new_home_url || $old_siteurl !== $new_home_url) {
    echo "Updating database...\n\n";
    
    // Update the options
    $result1 = update_option('home', $new_home_url);
    $result2 = update_option('siteurl', $new_home_url);
    
    if ($result1 || $result2) {
        echo "✓ SUCCESS: Database updated!\n\n";
        echo "Verifying changes:\n";
        echo "  home: " . get_option('home') . "\n";
        echo "  siteurl: " . get_option('siteurl') . "\n";
    } else {
        echo "✗ FAILED: Could not update database\n";
    }
} else {
    echo "✓ URLs are already correct!\n";
}

echo "\n=== Done ===\n";
echo "You can now delete this file (fix-urls.php)\n";
echo "</pre>";
?>
