<?php
// Button to refresh service Worker
function ba_refresh_sw_button( $wp_admin_bar ) {
	$args = array(
		'id' => 'refresh-service-worker',
		'title' => 'Refresh Service Worker',
		'href' => '#',
	);

	$wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'ba_refresh_sw_button', 50 );
