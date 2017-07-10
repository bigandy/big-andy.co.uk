<?php
/**
 * Plugin Name: Bigandy Gutenberg
 * Plugin URI: https://github.com/WordPress/gutenberg-examples
 * Description: This is a plugin demonstrating how to register new blocks for the Gutenberg editor.
 * Version: 0.1.0
 * Author: Bigandy
 *
 * @package bigandy-gutenberg
 */
defined( 'ABSPATH' ) || exit;

function ba_gutenberg_enqueue_block_editor_assets() {
	wp_enqueue_script(
		'ba-gutenberg-block',
		plugins_url( 'js/bundle.js', __FILE__ ),
		array( 'wp-blocks' )
	);
}
add_action( 'enqueue_block_editor_assets', 'ba_gutenberg_enqueue_block_editor_assets' );
