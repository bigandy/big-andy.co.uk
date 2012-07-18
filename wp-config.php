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
define('DB_NAME', 'ba-wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'v![[[zx%3e7uw/; 1V0AbX~bS0V49>),-KoF5eEsqtl,S[Ds#P?[?$$;o[)?,Lm ');
define('SECURE_AUTH_KEY',  'Q-6>vz3x{):^)zPZNP N]J2<NoPhNf|{!gcOvcFZjk7DA]^C@*l&V<88ODNG3_Ye');
define('LOGGED_IN_KEY',    'N]sJ%lnB=]2z(A2~U wLq QHX}p,KyG#93S@Ip/.oTWH)u_rpOCZ9FBMwY}7x:[U');
define('NONCE_KEY',        'us|Yy>#!E^y_4S~J`|sfcwiPn;@6j{79,6w8LTn#Z1!H5(!)dz(7(#03q>EDbq_(');
define('AUTH_SALT',        'L4+pVH8J]VTuF@$kP!SA/<!!^we]di!nn9#T4a9Ft<RhX/R8wZo:,i313T?OdXfv');
define('SECURE_AUTH_SALT', ';>Ea~L? $gIUU7NME}%kcveD~2%Vs*QD)*IF<YRLohath(,V-@8R;}#-Zm._gVY5');
define('LOGGED_IN_SALT',   'h9viwt&cE`UFTN62n:m`o{gPX=04]gv7|g.#_l>(&MfJ^ZPn]j30xl-v/O]9zc?5');
define('NONCE_SALT',       'm#fh#mnpy,fD$ixwT+Zb3;2.5>=+^antOdtW^mXpuks_WNT 6a5p|G+R3wGx9rTi');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'bawp_';

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
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
