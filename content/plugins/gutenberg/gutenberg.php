<?php
/**
 * Plugin Name: Gutenberg
 * Plugin URI: https://github.com/WordPress/gutenberg
 * Description: Printing since 1440. This is the development plugin for the new block editor in core. <strong>Meant for development, do not run on real sites.</strong>
 * Version: 0.9.0
 * Author: Gutenberg Team
 *
 * @package gutenberg
 */

### BEGIN AUTO-GENERATED DEFINES
define( 'GUTENBERG_VERSION', '0.9.0' );
define( 'GUTENBERG_GIT_COMMIT', '4a99994d3640a4860e90af71c8d7f97e4ef4b29d' );
### END AUTO-GENERATED DEFINES

require_once dirname( __FILE__ ) . '/lib/init-checks.php';
if ( gutenberg_can_init() ) {
	// Load API functions, register scripts and actions, etc.
	require_once dirname( __FILE__ ) . '/lib/class-wp-block-type.php';
	require_once dirname( __FILE__ ) . '/lib/class-wp-block-type-registry.php';
	require_once dirname( __FILE__ ) . '/lib/blocks.php';
	require_once dirname( __FILE__ ) . '/lib/client-assets.php';
	require_once dirname( __FILE__ ) . '/lib/compat.php';
	require_once dirname( __FILE__ ) . '/lib/i18n.php';
	require_once dirname( __FILE__ ) . '/lib/parser.php';
	require_once dirname( __FILE__ ) . '/lib/register.php';

	// Register server-side code for individual blocks.
	foreach ( glob( dirname( __FILE__ ) . '/blocks/library/*/index.php' ) as $block_logic ) {
		require_once $block_logic;
	}
}
