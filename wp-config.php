<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cosxix_wp');

/** MySQL database username */
define('DB_USER', 'cosxix_wp');

/** MySQL database password */
define('DB_PASSWORD', '6Y6wEfA3ViHPMoJqNCL');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '-K[Ik?}t@{0<.sd;K}v<wGtC0!y|FK5HK,sRjbM9pV5.6`h>AA|B`nTHJXB++=g)');
define('SECURE_AUTH_KEY', '6+7`3iNUN}_B,`O#[&`:dm[0f-i|IIt{9z&-A1h=m/j[xe36|^+8&*A0o}lyfy=W');
define('LOGGED_IN_KEY', 'KaXa;3u|B>H-xt$jD`8 U8q$p5$c%i1O]e>=}6D||_FsFWUDQq0ipx>8&_c!;2L8');
define('NONCE_KEY', 'E8`IZ-%#F<Ut8Hi_l3p<2hw29fN<+ G5ql{oLXNur.B:7#ci-%c.cHz?A~Rg@q k');
define('AUTH_SALT', '5fh{`6>;.jDs|ZkA>@yTZQ{{[!|CL_6|aS+aacpvB!`<e,.HcsnEDYjU:e-0+TdC');
define('SECURE_AUTH_SALT', '8h`ngV<aUSo7B(>oj /@Y01}N5u#K}H.w[wmsg}_?%N>J}!J>9;Mo79HR;,/lyiw');
define('LOGGED_IN_SALT', '>lYDF2nLl`B+PP+}w|~aArkglX=>S@vKnSTtM|{&56(8H;vRsj0||4|i>rz/s hZ');
define('NONCE_SALT', '[hti)u4ODFU~+wQ=xHNvp4pRve_5%<)|:;w|o5|FLg-x+u9V0D4aWr-W-ovuD:ov');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
