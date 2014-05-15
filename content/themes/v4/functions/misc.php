<?php
if( !function_exists('ba_preit') ) {
	function ba_preit( $obj, $echo = true ) {
		if( $echo ) {
			echo '<pre>' . print_r( $obj, true ) . '</pre>';
		} else {
			return '<pre>' . print_r( $obj, true ) . '</pre>';
		}
	}
}
if( !function_exists('ba_silent') ) {
	function ba_silent( $obj ) {
		echo '<pre style="display: none;">' . print_r( $obj, true ) . '</pre>';
	}
}

// Hide Links Admin Menu
add_action( 'admin_menu', 'ba_remove_menu_pages' );
function ba_remove_menu_pages() {
	remove_menu_page('link-manager.php');
}

/**
 * [ah_excerpt_more description]
 * @param  [type] $more [description]
 * @return [type]       [description]
 * from http://codex.wordpress.org/Function_Reference/the_excerpt#Make_the_.22read_more.22_link_to_the_post
 */
function ah_excerpt_more( $more ) {
	return ' <a class="read-more" href="'. get_permalink( get_the_ID() ) . '">' . __('Read More', 'ah') . '</a>';
}
add_filter( 'excerpt_more', 'ah_excerpt_more' );
