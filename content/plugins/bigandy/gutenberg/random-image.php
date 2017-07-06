<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

/**
 * Registers block scripts and styles for the editing interface.
 *
 * @since 0.1.0
 */
function ah_gutenberg_random_image_block_enqueue_editor_assets() {
	// Block scripts for the editor.
	wp_enqueue_script(
		'ah-random-image-editor',
		plugins_url( 'js/random-image-block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'js/random-image-block.js' )
	);


	// Block styles for the editor.
	wp_enqueue_style(
		'ah-random-image-editor',
		plugins_url( 'step-02/editor.css', __FILE__ ),
		array( 'wp-edit-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/random-image-block.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'ah_gutenberg_random_image_block_enqueue_editor_assets' );

/**
 * Registers block styles common to the editor and the frontend.
 *
 * @since 0.1.0
 */
function ah_gutenberg_random_image_block_enqueue_common_assets() {
	// Block styles common to the editor and the frontend.
	wp_enqueue_style(
		'ah-random-image-front-end',
		plugins_url( 'css/random-image-block.css', __FILE__ ),
		array( 'wp-blocks' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/random-image-block.css' )
	);
}
add_action( 'enqueue_block_assets', 'ah_gutenberg_random_image_block_enqueue_common_assets' );
