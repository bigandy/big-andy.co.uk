<?php
function ah_plugin_styles() {
	// Register the style like this for a plugin:
	wp_register_style( 'ba-style', plugins_url( 'css/bigandy.css', __FILE__ ) );
	wp_enqueue_style( 'ba-style' );
}

function ah_plugin_scripts() {
	// Register the script like this for a plugin:
	wp_register_script( 'ba-script', plugins_url( 'js/bigandy.js', __FILE__ ), null, false, true );
	wp_enqueue_script( 'ba-script' );
}
add_action( 'admin_enqueue_scripts', 'ah_plugin_styles' );
add_action( 'admin_enqueue_scripts', 'ah_plugin_scripts' );

// This is included so can use current_user_can() and is_user_logged_in() functions
include_once( ABSPATH . 'wp-includes/pluggable.php' );

// output the ajaxurl for the bigandy.js script
function ah_add_ajax_url() {
	echo '<script> var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '";</script>';
}
// show script if user is logged in and is an admin
if ( current_user_can( 'update_core' ) && is_user_logged_in() ) {
	add_action( 'wp_enqueue_scripts', 'ah_plugin_scripts' );
	add_action( 'wp_head', 'ah_add_ajax_url' );
}
