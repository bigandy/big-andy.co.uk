<?php 
/**
 * Override the default customizer styles with MP6.
 */

// replace some default css files with ours
add_action( 'admin_init', 'mp6_replace_customizer_styles' );
function mp6_replace_customizer_styles() {

	global $wp_styles;

	$wp_styles->registered[ 'customize-controls' ]->src = plugins_url( 'customize-controls.css', __FILE__ );
	$wp_styles->registered[ 'customize-controls' ]->ver = filemtime( plugin_dir_path( __FILE__ ) . 'customize-controls.css' );


}