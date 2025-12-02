<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //

// Load environment variables from .env file if it exists (only for local development)
$env_file = __DIR__ . '/.env';
$env_vars = [];

if (file_exists($env_file)) {
    // Parse .env file only if it exists (local development)
    $env_vars = parse_ini_file($env_file);
}

// Helper function to get environment variables from multiple sources
function get_env_var($key, $default = null)
{
    global $env_vars;

    // Priority: .env file > $_ENV > getenv() > default
    if (!empty($env_vars[$key])) {
        return $env_vars[$key];
    }

    if (isset($_ENV[$key]) && !empty($_ENV[$key])) {
        return $_ENV[$key];
    }

    $env_val = getenv($key);
    if ($env_val !== false && !empty($env_val)) {
        return $env_val;
    }

    return $default;
}

// Database settings - reads from Railway environment variables
define('DB_NAME', get_env_var('WORDPRESS_DB_NAME', 'wordpress'));
define('DB_USER', get_env_var('WORDPRESS_DB_USER', 'wordpress'));
define('DB_PASSWORD', get_env_var('WORDPRESS_DB_PASSWORD', ''));
define('DB_HOST', get_env_var('WORDPRESS_DB_HOST', 'localhost'));

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * WordPress URL Configuration
 * Force WordPress to use the correct domain
 */
if (!defined('WP_HOME')) {
    // Detect environment by checking for Railway-specific variables
    $db_host = getenv('WORDPRESS_DB_HOST');
    $is_production = !empty($db_host) && ($db_host !== 'localhost' && $db_host !== '127.0.0.1');

    if ($is_production) {
        // Railway production - use HTTPS and current domain
        define('WP_HOME', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        define('WP_SITEURL', 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    } else {
        // Local development
        define('WP_HOME', 'http://gymfitness.local');
        define('WP_SITEURL', 'http://gymfitness.local');
    }
}
/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',          '{}b78}XEv-^sKEk!dA>jXk?U(*XKg>z4Na]t(gJ;$#/La-S-R=<%b}6V*ZDy;%);');
define('SECURE_AUTH_KEY',   '?2<z_sUPc$_dA4Yra]_ -qAHv~m7p.@htid}oPj9}-BDYLU`-_({{SMw;553O<G[');
define('LOGGED_IN_KEY',     'z!5q[(sT9eS03OH|Z1o#{Oo76sS4ybO`sGL*l2?t8]RZ,E*~R?V`l4oXAr+egY3m');
define('NONCE_KEY',         '+Q[x 9w>@T]c{D=L/}!jH6r<OryuJ.e.P%IJ|abiA4)zhQ&mW`])mSbdm>>REuiH');
define('AUTH_SALT',         ';OJ~35/KoPtDYO~BFPJODRuUWN-(9@*U(-L:90V.eJj{|&UwI,QLj:-O]7a0W&T_');
define('SECURE_AUTH_SALT',  ',wRb3N}7+h![4CuU9+/egCfPR~{Th$3h_%6Rb_aX@? #Zn&V[kScsd CmOo~N$$J');
define('LOGGED_IN_SALT',    'WPH`zUmTN2op&^CYSBd2TUSAIU3_`hpDLC1#G7IcF-.>0e>p@r4R%@mC04|8*j~+');
define('NONCE_SALT',        '(3l%.&BEr6qrbGe|IOlOeQYo;DYqgcSu)Uyk8=a(Hjy]}AtF[;AUPKCFVTx$Dt0x');
define('WP_CACHE_KEY_SALT', '_KA +8TxEe0Oek!H_t:5v-NVm[QSEZ% hpk]|w;vAoC>5Ql[dsF;D>7Gi)Bis8wj');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if (! defined('WP_DEBUG')) {
    define('WP_DEBUG', false);
}

define('WP_ENVIRONMENT_TYPE', 'local');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
