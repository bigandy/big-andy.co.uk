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

if ( ! function_exists( 'ah_excerpt_more' ) ) {
	function ah_excerpt_more( $more ) {
		return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
	}
	// add_filter( 'excerpt_more', 'ah_excerpt_more' );
}

/*
 * function ah_wrap_embed()
 * Wrap embed in div.flex-video
 * http://wordpress.stackexchange.com/questions/119547/oembed-youtube-video-aspect-ratio
 */
if ( ! function_exists( 'ah_wrap_embed' ) ) {
	function ah_wrap_embed( $html, $url, $attr, $post_id ) {
		ah_preit( $html );


		return '<div class="flex-video">' . $html . '</div>';;
	}
	// add_filter( 'embed_oembed_html', 'ah_wrap_embed', 10, 4 );
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
