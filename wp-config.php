<?php


// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/local-config.php' ) ) {
	define( 'WP_LOCAL_DEV', true );
	include( dirname( __FILE__ ) . '/local-config.php' );
} else {
	define( 'WP_LOCAL_DEV', false );
	define( 'DB_NAME', '%%DB_NAME%%' );
	define( 'DB_USER', '%%DB_USER%%' );
	define( 'DB_PASSWORD', '%%DB_PASSWORD%%' );
	define( 'DB_HOST', '%%DB_HOST%%' ); // Probably 'localhost'
}

define( 'WP_STACK_CDN_DOMAIN', 'cdn.bigandy.netdna-cdn.com' );

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ===================================
// Prevent file editing via admin area
// ===================================
// disable file editing from the admin area : 
// http://www.wpbeginner.com/wp-tutorials/how-to-disable-theme-and-plugin-editors-from-wordpress-admin-panel/
define( 'DISALLOW_FILE_EDIT', true );

// a few mods to the wp-config following advice from : http://digwp.com/2010/08/pimp-your-wp-config-php/

// Disable the post-revisioning feature 
define('WP_POST_REVISIONS', 1); // kill the bloat -> stops wp from keeping any revisions. set it to an integer and only keep that number max.

// Define how often trash is emptied
define('EMPTY_TRASH_DAYS', 7); // empty weekly

// define size of PHP memory
define('WP_MEMORY_LIMIT', '64M');

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );


// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         '((nqiSce-c|jS9&h& riMa1S,epuNPvH_07ZR/>Gz?&,4)4.5vc-0mUQEO*|}!b[');
define('SECURE_AUTH_KEY',  'zGrEBK5b9`ajbcGH-FIV=N>J/K4?{})<hlN^;nu@BLO#5mo]D-vR(7_6l)BoV=U$');
define('LOGGED_IN_KEY',    ';J$xYET&$Z+ a#B/9G8A4N9lh1]InebQg/J< d6m8d^,0|Lv9Jst TD!CU~d*?A_');
define('NONCE_KEY',        'prk+Ua%_voEC2M_[EW.w|Fy?,wv]/.e;qceY*HTT^9Q@Gar+0:;UJArnUl{V`bFA');
define('AUTH_SALT',        '}r}.<Ubd0,&MelE6bCj3.qsh012Q/nMN#:N${tvH21U9E+0@kXJ#k-Oe)3(M|i#^');
define('SECURE_AUTH_SALT', '3r.5~n~xZ=nWI{Q|nc_$i+X}hQvf{m<D%Lk8Y@0^/,r_KJ8$Pts9b}2*]boY*jTN');
define('LOGGED_IN_SALT',   '0zEuGlEL+q&o-eeARQ6xT;]|F)P+:4p+Yn3yZq+eUe_*XZ]nX9ffhAx/Ak`=_cze');
define('NONCE_SALT',       'a-WdR#IQYD4LZ.lOw!([tTnWm}Stb&6N;9.S+qTtVmqGZ[`)4dR-!0C6Z{D+|{md');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'bav3_';

// ================================
// Language
// Leave blank for American English
// ================================
define( 'WPLANG', '' );

// ===========
// Hide errors
// ===========
ini_set( 'display_errors', 0 );
define( 'WP_DEBUG_DISPLAY', false );

// =================================================================
// Debug mode
// Debugging? Enable these. Can also enable them in local-config.php
// =================================================================
// define( 'SAVEQUERIES', true );
// define( 'WP_DEBUG', true );

// ===========================================================================================
// This can be used to programatically set the stage when deploying (e.g. production, staging)
// ===========================================================================================
define( 'WP_STAGE', '%%WP_STAGE%%' );
define( 'STAGING_DOMAIN', '%%WP_STAGING_DOMAIN%%' ); // Does magic in WP Stack to handle staging domain rewriting

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );


