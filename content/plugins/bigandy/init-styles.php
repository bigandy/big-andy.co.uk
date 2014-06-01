<?php

function ah_plugin_styles() {
	// Register the style like this for a plugin:
	wp_register_style( 'ba-style', plugins_url( 'css/bigandy.css', __FILE__ ) );
	wp_enqueue_style( 'ba-style' );
}
add_action( 'admin_enqueue_scripts', 'ah_plugin_styles' );
