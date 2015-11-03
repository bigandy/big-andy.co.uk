<?php
/**
 * Register meta boxes
 *
 * @return void
 */
if ( ! function_exists( 'rw_register_meta_boxes' ) ) {

	function rw_register_meta_boxes() {
		global $meta_boxes;
		// Make sure there's no errors when the plugin is deactivated or during upgrade
		if ( class_exists( 'RW_Meta_Box' ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				if ( isset( $meta_box['not_on'] ) && ! rw_maybe_include( $meta_box['not_on'], 0 ) ) {
					continue;
				}
				if ( isset( $meta_box['only_on'] ) && ! rw_maybe_include( $meta_box['only_on'], 1 ) ) {
					continue;
				}

				new RW_Meta_Box( $meta_box );
			}
		}
	}

	add_action( 'admin_init', 'rw_register_meta_boxes' );
}
