<?php
// only display custom menu bar if the user is logged in and is an admin
if ( current_user_can( 'update_core' ) && is_user_logged_in() ) {
	function es_admin_css() {
		?>
		<style> #wpadminbar { background: <?php echo esc_attr( HEADERCOLOR ); ?>; }</style>
		<?php
	}
	add_action( 'wp_head', 'es_admin_css' );
	add_action( 'admin_head', 'es_admin_css' );
}
