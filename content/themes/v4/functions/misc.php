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
add_action( 'admin_menu', 'ah_remove_menu_pages' );
function ah_remove_menu_pages() {
	remove_menu_page( 'link-manager.php' );
}

function ah_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">Read More</a>';
}
add_filter( 'excerpt_more', 'ah_excerpt_more' );
