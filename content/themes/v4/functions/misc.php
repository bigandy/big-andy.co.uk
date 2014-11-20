<?php
if ( ! function_exists( 'ah_preit' ) ) {
	function ah_preit( $obj, $echo = true ) {
		if ( $echo ) {
			echo '<pre>' . print_r( $obj, true ) . '</pre>';
		} else {
			return '<pre>' . print_r( $obj, true ) . '</pre>';
		}
	}
}
if ( ! function_exists( 'ah_silent' ) ) {
	function ah_silent( $obj ) {
		echo '<pre style="display: none;">' . print_r( $obj, true ) . '</pre>';
	}
}

// Hide Links Admin Menu
if ( ! function_exists( 'ah_remove_menu_pages' ) ) {
	function ah_remove_menu_pages() {
		remove_menu_page( 'link-manager.php' );
	}
	add_action( 'admin_menu', 'ah_remove_menu_pages' );
}

if ( ! function_exists( 'ah_init_constants' ) ) {
	function ah_init_constants() {
		if ( ! defined( 'TEMPLATEURI' ) ) {
			define( 'TEMPLATEURI', trailingslashit( get_stylesheet_directory_uri() ) );
		}

		if ( ! defined( 'HOMEURL' ) ) {
			define( 'HOMEURL', trailingslashit( get_home_url() ) );
		}
	}
}
add_action( 'init', 'ah_init_constants' );
