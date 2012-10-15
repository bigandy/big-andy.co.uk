<?php 
// deregister style and scripts so can combine in one .css and one .js file

add_action( 'wp_print_styles', 'ah_deregister_styles', 100 );
add_action( 'wp_print_scripts', 'ah_deregister_scripts', 100 );

function ah_deregister_styles() {
	wp_deregister_style( 'contact-form-7' );
	wp_deregister_style( 'jquery.lightbox.min.css' );
}

function ah_deregister_scripts() {
	wp_deregister_script( 'contact-form-7' );
	wp_deregister_script( 'wp-jquery-lightbox' );
}
