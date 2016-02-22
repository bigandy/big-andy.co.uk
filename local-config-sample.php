<?php
/*
This is a sample local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

define( 'DB_NAME', 'ba-wordpress' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', 'localhost' );

define( 'WP_HOME', 'http://big-andy.dev' );
define( 'WP_SITEURL', 'http://big-andy.dev/wp' );

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'db-prefix';
