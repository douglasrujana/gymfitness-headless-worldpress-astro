<?php

/**
 * WordPress URL Fixer for Railway
 * This script updates the siteurl and home options in the WordPress database
 * Run once and delete after use
 */

// Load WordPress
require_once __DIR__ . '/wp-load.php';
global $wpdb;

echo "<pre style='background:#f5f5f5;padding:16px;font-family:monospace;'>";
echo "=== WP Search & Replace ===\n\n";

$old = isset($_GET['old']) ? trim($_GET['old']) : 'gymfitness.local';
$new = isset($_GET['new']) ? trim($_GET['new']) : ((isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] : ''));

if (empty($new)) {
    echo "ERROR: new URL not detected. Pass ?new=yourdomain or ensure HTTP_HOST is set.\n";
    echo "</pre>";
    exit;
}

echo "Replacing occurrences of: '$old' => '$new'\n\n";

function sr_replace_value($value, $old, $new)
{
    if (is_string($value)) {
        if (strpos($value, $old) !== false) {
            return str_replace($old, $new, $value);
        }
        return $value;
    }
    if (is_array($value)) {
        foreach ($value as $k => $v) {
            $value[$k] = sr_replace_value($v, $old, $new);
        }
        return $value;
    }
    if (is_object($value)) {
        foreach ($value as $k => $v) {
            $value->$k = sr_replace_value($v, $old, $new);
        }
        return $value;
    }
    return $value;
}

$like = '%' . $wpdb->esc_like($old) . '%';
$options = $wpdb->get_results($wpdb->prepare("SELECT option_id, option_name, option_value FROM {$wpdb->options} WHERE option_value LIKE %s", $like));

echo "Options to check: " . count($options) . "\n";
foreach ($options as $opt) {
    $val = $opt->option_value;
    $un = @maybe_unserialize($val);
    if (is_array($un) || is_object($un)) {
        $new_un = sr_replace_value($un, $old, $new);
        if ($new_un !== $un) {
            $new_val = maybe_serialize($new_un);
            $wpdb->update($wpdb->options, ['option_value' => $new_val], ['option_id' => $opt->option_id]);
            echo "Updated option: {$opt->option_name}\n";
        }
    } else {
        if (strpos($val, $old) !== false) {
            $new_val = str_replace($old, $new, $val);
            $wpdb->update($wpdb->options, ['option_value' => $new_val], ['option_id' => $opt->option_id]);
            echo "Updated option (string): {$opt->option_name}\n";
        }
    }
}

$posts = $wpdb->get_results($wpdb->prepare("SELECT ID, post_content, guid FROM {$wpdb->posts} WHERE post_content LIKE %s OR guid LIKE %s", $like, $like));
echo "\nPosts to check: " . count($posts) . "\n";
<?php
/**
 * Simple DB-based URL fixer (avoids loading WP to prevent redirects)
 * Usage: /fix-urls.php?old=gymfitness.local&new=https://your-domain
 * Run once and delete the file.
 */

echo "<pre style='background:#f5f5f5;padding:12px;font-family:monospace;'>";

$old = isset($_GET['old']) ? trim($_GET['old']) : 'gymfitness.local';
$new = isset($_GET['new']) ? trim($_GET['new']) : (isset($_SERVER['HTTP_HOST']) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] : '');

if (empty($new)) {
    echo "ERROR: new URL not provided (use ?new=https://your-domain)\n";
    echo "</pre>";
    exit;
}

echo "Connecting to DB using environment variables...\n";
$dbHost = getenv('WORDPRESS_DB_HOST') ?: getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('WORDPRESS_DB_USER') ?: getenv('DB_USER') ?: 'root';
$dbPass = getenv('WORDPRESS_DB_PASSWORD') ?: getenv('DB_PASSWORD') ?: '';
$dbName = getenv('WORDPRESS_DB_NAME') ?: getenv('DB_NAME') ?: '';

echo "DB host: $dbHost\n";
echo "DB name: $dbName\n";

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_TIMEOUT => 5]);
} catch (Exception $e) {
    echo "✗ DB connection failed: " . $e->getMessage() . "\n";
    echo "</pre>";
    exit;
}

// Try to detect table prefix from wp-config.php
$table_prefix = 'wp_';
$cfg = @file_get_contents(__DIR__ . '/wp-config.php');
if ($cfg && preg_match('/\$table_prefix\s*=\s*["\'](.*?)["\']\s*;/', $cfg, $m)) {
    $table_prefix = $m[1];
}

echo "Using table prefix: $table_prefix\n\n";

$opts_table = $table_prefix . 'options';
$posts_table = $table_prefix . 'posts';
$postmeta_table = $table_prefix . 'postmeta';

// Update siteurl and home directly
echo "Updating siteurl and home to: $new\n";
$stmt = $pdo->prepare("UPDATE `$opts_table` SET option_value = :new WHERE option_name IN ('siteurl','home')");
$stmt->execute([':new' => $new]);
echo "Affected rows: " . $stmt->rowCount() . "\n";

// Basic replace in options
$like = "%" . str_replace('%', '\\%', $old) . "%";
$stmt = $pdo->prepare("SELECT option_id, option_name, option_value FROM `$opts_table` WHERE option_value LIKE :like");
$stmt->execute([':like' => $like]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Options containing old URL: " . count($rows) . "\n";
foreach ($rows as $r) {
    $newval = str_replace($old, $new, $r['option_value']);
    $u = $pdo->prepare("UPDATE `$opts_table` SET option_value = :val WHERE option_id = :id");
    $u->execute([':val' => $newval, ':id' => $r['option_id']]);
    echo "Updated option: " . $r['option_name'] . "\n";
}

// Posts
$stmt = $pdo->prepare("SELECT ID, post_content, guid FROM `$posts_table` WHERE post_content LIKE :like OR guid LIKE :like");
$stmt->execute([':like' => $like]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nPosts containing old URL: " . count($posts) . "\n";
foreach ($posts as $p) {
    $pc = str_replace($old, $new, $p['post_content']);
    $g = str_replace($old, $new, $p['guid']);
    $u = $pdo->prepare("UPDATE `$posts_table` SET post_content = :pc, guid = :g WHERE ID = :id");
    $u->execute([':pc' => $pc, ':g' => $g, ':id' => $p['ID']]);
    echo "Updated post ID: " . $p['ID'] . "\n";
}

// Postmeta
$stmt = $pdo->prepare("SELECT meta_id, meta_value FROM `$postmeta_table` WHERE meta_value LIKE :like");
$stmt->execute([':like' => $like]);
$metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "\nPostmeta containing old URL: " . count($metas) . "\n";
foreach ($metas as $m) {
    $newv = str_replace($old, $new, $m['meta_value']);
    $u = $pdo->prepare("UPDATE `$postmeta_table` SET meta_value = :v WHERE meta_id = :id");
    $u->execute([':v' => $newv, ':id' => $m['meta_id']]);
    echo "Updated postmeta id: " . $m['meta_id'] . "\n";
}

echo "\nDone. IMPORTANT: delete this file after use.\n";
echo "</pre>";
