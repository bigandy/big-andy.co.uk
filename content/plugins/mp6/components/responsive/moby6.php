<?php

function moby6_init() {
	add_action( 'admin_head', 'moby6_add_headers' );
	add_action( 'admin_print_styles', 'moby6_add_css' );
	add_action( 'admin_print_scripts', 'moby6_add_js' );
	add_filter( 'shortcut_link', 'moby6_enlarge_pressthis' );
}
add_action( 'admin_init', 'moby6_init' );

function moby6_add_headers() {
	echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
}

function moby6_add_css() {
	wp_enqueue_style( 'moby6-shared', plugins_url( 'css/shared.css', __FILE__ ) );

	// TODO: Specific tweaks for mobile and tablet sizes.
	// wp_enqueue_style( 'moby6-smartphone', plugins_url( 'css/smartphone.css', __FILE__ ) );
	// wp_enqueue_style( 'moby6-tablet', plugins_url( 'css/tablet.css', __FILE__ ) );
}

function moby6_add_js() {
	wp_enqueue_script( 'moby6-shared', plugins_url( 'js/shared.js', __FILE__ ), array( 'jquery', 'backbone' ) );
}

function moby6_enlarge_pressthis($link) {
	return str_replace( 'width=720,height=570', 'width=770,height=570', $link );
}