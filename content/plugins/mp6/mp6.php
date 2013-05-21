<?php
/*
Plugin Name: MP6
Plugin URI: http://wordpress.org/extend/plugins/mp6/
Description: This is a plugin to break the wp-admin UI, and is not recommended for non-savvy users.
Version: 1.2
Author:
Author URI: http://wordpress.org
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Load the responsive component of MP6
require_once( plugin_dir_path(__FILE__) . 'components/responsive/moby6.php' );

// load the sticky admin menu component
require_once( plugin_dir_path(__FILE__) . 'components/sticky-menu/sticky-menu.php' );

// register Open Sans stylesheet
add_action( 'init', 'mp6_register_open_sans' );
function mp6_register_open_sans() {
	wp_register_style( 'open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600&subset=latin-ext,latin', false, 0.1 );
}

add_action( 'admin_init', 'mp6_register_admin_color_schemes', 1 );
function mp6_register_admin_color_schemes() {

	// remove classic and fresh color schemes, to prevent confusion
	global $_wp_admin_css_colors;
	unset( $_wp_admin_css_colors[ 'classic' ] );
	unset( $_wp_admin_css_colors[ 'fresh' ] );
		
	wp_admin_css_color(
		'mp6',
		_x( 'MP6', 'admin color scheme' ),
		plugins_url( 'css/colors-mp6.css', __FILE__ ),
		array( '#333', '#444', '#0074a2', '#2ea2cc' )
	);
	
	// hack to adjust the version, because the enqueue system has no nice way to modify script data once it's already added elsewhere
	$modtime = filemtime( plugin_dir_path( __FILE__ ) . 'css/colors-mp6.css' );
	global $wp_styles;
		
	if ( ! is_a( $wp_styles, 'WP_Styles' ) )
		$wp_styles = new WP_Styles();
	
	$wp_styles->registered[ 'colors-fresh' ]->ver = $modtime;
	$wp_styles->registered[ 'colors' ]->deps[] = 'open-sans';
}

add_action( 'login_init', 'mp6_fix_login_color_scheme', 1 );
function mp6_fix_login_color_scheme() {
	// fiddle with the login styling to load our colors file instead
	global $wp_styles;
	if ( ! is_a( $wp_styles, 'WP_Styles' ) )
		$wp_styles = new WP_Styles();
	$wp_styles->registered[ 'colors-fresh' ]->src = plugins_url( 'css/colors-mp6.css', __FILE__ );
}

// force the MP6 color-scheme setting on to use the new CSS
add_filter( 'get_user_option_admin_color', 'mp6_force_admin_color' );
function mp6_force_admin_color( $color_scheme ) {
	return 'mp6';
}

// replace the admin bar css with one from MP6
add_action( 'init', 'mp6_replace_admin_bar_style' );
function mp6_replace_admin_bar_style() {
	wp_deregister_style( 'admin-bar' );
	wp_register_style(
		'admin-bar',
		plugins_url( 'css/admin-bar.css', __FILE__ ),
		array( 'open-sans' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'css/admin-bar.css' )
	);
}

// override WP's default toolbar top margin
add_action( 'wp_head', 'mp6_override_toolbar_margin', 11 );
function mp6_override_toolbar_margin() {
	if ( is_admin_bar_showing() ) : ?>
<style type="text/css" media="screen">
	html { margin-top: 32px !important; }
	* html body { margin-top: 32px !important; }
</style>
<?php endif;
}

// Add an MP6 body class to the front end.
add_filter( 'body_class', 'mp6_add_mp6_body_class' );
function mp6_add_mp6_body_class( $classes ) {
	$classes[] = 'mp6';
	return $classes;
}