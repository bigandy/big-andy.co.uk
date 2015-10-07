<?php
function ah_plugin_styles() {
	// Register the style like this for a plugin:
	wp_register_style( 'ba-style', plugins_url( 'css/bigandy.css', __FILE__ ) );
	wp_enqueue_style( 'ba-style' );
}
add_action( 'admin_enqueue_scripts', 'ah_plugin_styles' );

add_action( 'wp_print_scripts','example_dequeue_myscript' );
function example_dequeue_myscript() {
	wp_dequeue_script( 'wp-oembed' );
}

// if ( ! is_singular() ) {
// 	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
// }

