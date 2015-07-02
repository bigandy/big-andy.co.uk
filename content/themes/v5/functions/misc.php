<?php
if ( ! function_exists( 'ah_preit' ) ) {
	function ah_preit( $obj, $echo = true ) {
		if ( $echo ) {
			echo '<pre>' . esc_html( print_r( $obj, true ) ) . '</pre>';
		} else {
			return '<pre>' . esc_html( print_r( $obj, true ) ) . '</pre>';
		}
	}
}
if ( ! function_exists( 'ah_silent' ) ) {
	function ah_silent( $obj ) {
		echo '<pre style="display: none;">' . esc_html( print_r( $obj, true ) ) . '</pre>';
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

		if ( ! defined( 'THEMECOLOR' ) ) {
			$theme_color = get_theme_mod( 'ah_meta_color' );

			if ( isset( $theme_color ) && '#008AD7' !== $theme_color ) {
				$meta_color = $theme_color;
			} else {
				$meta_color = '#008AD7';
			}

			define( 'THEMECOLOR', $meta_color );
		}
	}
}
add_action( 'init', 'ah_init_constants' );

if ( ! function_exists( 'ah_header_theme_color' ) ) {
	function ah_header_theme_color() {
		echo '<meta name="theme-color" content="' . esc_attr( THEMECOLOR ) . '">';
	}
	add_action( 'wp_head', 'ah_header_theme_color' );
}

// This fixes the broken floating wordpress admin menu that you get when window < 600px
if ( ! function_exists( 'ah_admin_mobile_menu_fix' ) && is_user_logged_in() ) {
	function ah_admin_mobile_menu_fix() {
		echo '<style>@media (max-width: 600px) { #wpadminbar { position: fixed; } }</style>';
	}
	add_action( 'wp_head', 'ah_admin_mobile_menu_fix' );
}
