<?php

/**
 * Diagnostics - Check environment variables
 * Delete this file after debugging
 */

echo "<pre>";
echo "=== Environment Variables Debug ===\n\n";

echo "getenv() results:\n";
echo "WORDPRESS_DB_HOST: " . var_export(getenv('WORDPRESS_DB_HOST'), true) . "\n";
echo "WORDPRESS_DB_USER: " . var_export(getenv('WORDPRESS_DB_USER'), true) . "\n";
echo "WORDPRESS_DB_PASSWORD: " . var_export(getenv('WORDPRESS_DB_PASSWORD'), true) . "\n";
echo "WORDPRESS_DB_NAME: " . var_export(getenv('WORDPRESS_DB_NAME'), true) . "\n";

echo "\n\$_SERVER variables:\n";
echo "WORDPRESS_DB_HOST: " . var_export($_SERVER['WORDPRESS_DB_HOST'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_USER: " . var_export($_SERVER['WORDPRESS_DB_USER'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_PASSWORD: " . var_export($_SERVER['WORDPRESS_DB_PASSWORD'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_NAME: " . var_export($_SERVER['WORDPRESS_DB_NAME'] ?? 'NOT SET', true) . "\n";

echo "\n\$_ENV variables:\n";
echo "WORDPRESS_DB_HOST: " . var_export($_ENV['WORDPRESS_DB_HOST'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_USER: " . var_export($_ENV['WORDPRESS_DB_USER'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_PASSWORD: " . var_export($_ENV['WORDPRESS_DB_PASSWORD'] ?? 'NOT SET', true) . "\n";
echo "WORDPRESS_DB_NAME: " . var_export($_ENV['WORDPRESS_DB_NAME'] ?? 'NOT SET', true) . "\n";

echo "\n\nDatabase Connection Test:\n";
try {
    $host = getenv('WORDPRESS_DB_HOST') ?: 'localhost';
    $user = getenv('WORDPRESS_DB_USER') ?: 'root';
    $pass = getenv('WORDPRESS_DB_PASSWORD') ?: '';
    $db = getenv('WORDPRESS_DB_NAME') ?: 'wordpress';

    echo "Attempting connection to: $host\n";
    echo "Database: $db\n";
    echo "User: $user\n\n";
    
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✓ Database connection successful!\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n\nWordPress Configuration Check:\n";
$wp_config = __DIR__ . '/wp-config.php';
if (file_exists($wp_config)) {
    echo "✓ wp-config.php found\n\n";
    ob_start();
    include($wp_config);
    ob_end_clean();
    
    if (defined('DB_NAME')) {
        echo "Constants defined:\n";
        echo "DB_NAME: " . DB_NAME . "\n";
        echo "DB_USER: " . DB_USER . "\n";
        echo "DB_HOST: " . DB_HOST . "\n";
    }
} else {
    echo "✗ wp-config.php NOT found\n";
}

echo "</pre>";
