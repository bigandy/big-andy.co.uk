<?php
/*
Plugin Name: bigandy functionality
Version: 1.2
Description: Shortcode for address, plus other stuff
Author: Andrew Hudson
Author URI: http://andyhudson.me
Plugin URI: http://andyhudson.me
*/

// Now to be able to turn these on and off!

/* What to do when the plugin is activated? */
register_activation_hook( __FILE__, 'ah_plugin_install' );

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'ah_plugin_remove' );

function my_first_plugin_install() {
	/* Create a new database field */
	add_option( 'ah_plugin_options' );
	flush_rewrite_rules();
}

function my_first_plugin_remove() {
	/* Delete the database field */
	delete_option( 'ah_plugin_options' );
	flush_rewrite_rules();
}

$ah_plugin_options = array(
	'output' => 'test',
	'admin' => 'yes',
	'security' => 'yes',
	'shortcodes' => 'no',
	'menu' => 'yes',
	'widgets' => 'yes',
	'footer' => 'yes',
	'darkLight' => 'light',
);

add_action( 'admin_menu', 'ah_plugin_admin_menu' );
function ah_plugin_admin_menu() {
	add_options_page(
		'Plugin Admin Options',
		'Bigandy Options',
		'manage_options',
		'bigandy-admin',
		'ah_plugin_admin_options_page'
	);
}


function ah_plugin_admin_options_page() {

?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Bigandy Plugin Options</h2>

		<form method="post" action="options.php" class="ahform">

		<?php wp_nonce_field( 'update-options' ); ?>

	        <fieldset class="no-bg">
	        	<label for="ahOutput">Google Analytics Code:</label>
	        	<?php

	$options = get_option( 'ah_plugin_options' );
	$ah_output = $options['output'];

	echo "<input name='ah_plugin_options[output]' type='text' id='ahOutput' value='{$options['output']}' />";?>


	        </fieldset>


			<fieldset <?php if ( $options['admin'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="adminArea">Admin Area</label>
				<select name="ah_plugin_options[admin]" id="adminArea">
	                <option value="Y" <?php selected( $options['admin'], "Y" ); ?> >Yes</option>
	                <option value="N" <?php selected( $options['admin'], "N" ); ?> >No</option>
	            </select>

			</fieldset>

			<fieldset <?php if ( $options['shortcodes'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahShortcodes">Shortcodes
				</label>
				<select name="ah_plugin_options[shortcodes]" id="ahShortcodes">
					<option value="N" <?php selected( $options['shortcodes'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['shortcodes'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['security'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahSecurity">Security Stuff:
				</label>
				<select name="ah_plugin_options[security]" id="ahSecurity">
					<option value="N" <?php selected( $options['security'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['security'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['menu'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahMenuClasses">Remove Menu Classes: </label>
				<select name="ah_plugin_options[menu]" id="ahMenuClasses">
					<option value="N" <?php selected( $options['menu'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['menu'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['images'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahImages">Images: </label>
				<select name="ah_plugin_options[images]" id="ahImages">
					<option value="N" <?php selected( $options['images'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['images'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['widgets'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahWidgets">Widgets</label>
				<select name="ah_plugin_options[widgets]" id="ahWidgets">
	                <option value="N" <?php selected( $options['widgets'], "N" ); ?> >No</option>
	                <option value="Y" <?php selected( $options['widgets'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['footer'] == "Y" ) echo 'class="is-active"'; ?>>
				<label for="ahFooter">Footer: </label>
				<select name="ah_plugin_options[footer]" id="ahFooter">
	                <option value="N" <?php selected( $options['footer'], "N" ); ?> >No</option>
	                <option value="Y" <?php selected( $options['footer'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( $options['darkLight'] == "Dark" ) echo 'class="is-active"'; ?>>
	            <p class="label">Dark/Light: </p>

	            <label for="ahRadioTestOn">Light</label>
	                <input type="radio" name="ah_plugin_options[darkLight]" id="ahRadioTestOn" value="Light" <?php checked( $options['darkLight'], "Light" ); ?> />
	            <label for="ahRadioTestOff">Dark</label>
	                <input type="radio" name="ah_plugin_options[darkLight]" id="ahRadioTestOff" value="Dark" <?php checked( $options['darkLight'], "Dark" ); ?> />


	        </fieldset>

			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="ah_plugin_options" />
			<input type="submit" value="Save Changes" />

			<?php


	$options = get_option( 'ah_plugin_options' );

	if ( $options === false ) {
		$options = array(
			'output' => 'empty',
			'admin' => 'N',
			'security' => 'N',
			'shortcodes' => 'N',
			'menu' => 'N',
			'images' => 'Y',
			'widgets' => 'N',
			'footer' => 'N',
			'darkLight' => 'Light'
		);
		update_option( 'ah_plugin_options', $options );
	}

?>
		</form>


		<h2>Results</h2>
		<?php
	$ah_options = array(
		'output',
		'admin',
		'shortcodes',
		'security',
		'menu',
		'images',
		'widgets',
		'footer',
		'darkLight'
	);

	echo "<ul class='bullet'>";
	foreach ( $ah_options as $ah_option ) {
		echo"<li><strong>".ucfirst( $ah_option ).":</strong> <span>". $options[$ah_option]."</span></li>";
	}
	echo "</ul>";

?>
	</div>
	<?php

	return $output;
}

$options = get_option( 'ah_plugin_options' );

// include sub pages
require_once 'init-styles.php';
require_once 'admin-area.php';
require_once 'cpts.php';
require_once 'content.php';


if ( $options['shortcodes'] == "Y" ) {
	require_once 'shortcodes.php';
}

if ( $options['security'] == "Y" ) {
	require_once 'security-stuff.php';
}

if ( $options['menu'] == "Y" ) {
	require_once 'remove-menu-classes.php';
}
if ( $options['images'] == "Y" ) {
	require_once 'images.php';
}
if ( $options['widgets'] == "Y" ) {
	require_once 'ah-widgets.php';
}
if ( $options['footer'] == "Y" ) {
	require_once 'ah-footer.php';
}

// Add specific CSS class by filter
add_filter( 'body_class', 'ah_body_class_names' );
function ah_body_class_names( $classes ) {
	// add 'class-name' to the $classes array

	$options = get_option( 'ah_plugin_options' );

	if ( $options['darkLight'] == "Dark" ) {
		$lightDark = "dark";
	} else {
		$lightDark = "light";
	}

	$classes[] = $lightDark;
	// return the $classes array
	return $classes;
}


// remove contact form 7 JS and CSS
define('WPCF7_LOAD_CSS', false);
define('WPCF7_LOAD_JS', false);