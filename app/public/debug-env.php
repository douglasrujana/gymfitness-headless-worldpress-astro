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

echo "\n\npdo_connect test:\n";
try {
    $host = getenv('WORDPRESS_DB_HOST') ?: 'trolley.proxy.rlwy.net:47703';
    $user = getenv('WORDPRESS_DB_USER') ?: 'root';
    $pass = getenv('WORDPRESS_DB_PASSWORD') ?: '';
    $db = getenv('WORDPRESS_DB_NAME') ?: 'railway';

    echo "Attempting connection to: $host\n";
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "✓ Database connection successful!\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
}

echo "</pre>";
