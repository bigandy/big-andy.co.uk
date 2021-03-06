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

if ( ! function_exists( 'ah_init_constants' ) ) {
	function ah_init_constants() {
		if ( ! defined( 'TEMPLATEURI' ) ) {
			define( 'TEMPLATEURI', trailingslashit( get_stylesheet_directory_uri() ) );
		}

		if ( ! defined( 'HOMEURL' ) ) {
			define( 'HOMEURL', trailingslashit( get_home_url() ) );
		}

		if ( ! defined( 'THEMECOLOR' ) ) {
			$theme_colour = get_theme_mod( 'ah_theme_color' );

			if ( ! empty( $theme_colour ) && '#008AD7' !== $theme_colour ) {
				$meta_color = $theme_colour;
			} else {
				$meta_color = '#008AD7';
			}

			define( 'THEMECOLOR', $meta_color );
		}

		if ( ! defined( 'HEADERCOLOR' ) ) {
			$theme_color = get_theme_mod( 'ah_header_color' );

			if ( ! empty( $theme_color ) ) {
				$meta_color = $theme_color;
			} else {
				$meta_color = '#000000';
			}

			define( 'HEADERCOLOR', $meta_color );
		}


		// ah_preit( $assets );
		if ( ! defined( 'ASSET_CSS' ) ) {
			$assets = json_decode( file_get_contents( SITEROOT . '/content/themes/v5/build/assets.json' ), true );
			define( 'ASSET_CSS', $assets['style.css'] );
		}
	}



	add_action( 'init', 'ah_init_constants' );
}

function ah_add_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-formats', array( 'aside', 'link' ) );
}
add_action( 'after_setup_theme', 'ah_add_theme_support' );

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
