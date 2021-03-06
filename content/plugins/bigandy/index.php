<?php
/*
Plugin Name: bigandy functionality
Version: 2.2
Description: functionality for big-andy.co.uk in a plugin
Author: Andrew Hudson
Author URI: https://big-andy.co.uk
Plugin URI: https://big-andy.co.uk
*/

// Now to be able to turn these on and off!

/* What to do when the plugin is activated? */
register_activation_hook( __FILE__, 'ah_plugin_install' );

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'ah_plugin_remove' );

function ah_plugin_install() {
	/* Create a new database field */
	add_option( 'ah_plugin_options' );
	flush_rewrite_rules();
}

function ah_plugin_remove() {
	/* Delete the database field */
	delete_option( 'ah_plugin_options' );
	flush_rewrite_rules();
}

$ah_plugin_options = array(
	'admin' => 'yes',
	'security' => 'yes',
	'shortcodes' => 'no',
	'menu' => 'yes',
	'footer' => 'yes',
	'show_service_worker' => 'yes',
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
	$options = get_option( 'ah_plugin_options' );
	?>
	<div class="wrap">
		<h2>Bigandy Plugin Options</h2>

		<form method="post" action="options.php" class="ahform">

		<?php wp_nonce_field( 'update-options' ); ?>

			<fieldset <?php if ( 'Y' === $options['admin'] ) echo 'class="is-active"'; ?>>
				<label for="adminArea">Admin Area</label>
				<select name="ah_plugin_options[admin]" id="adminArea">
					<option value="Y" <?php selected( $options['admin'], 'Y' ); ?> >Yes</option>
					<option value="N" <?php selected( $options['admin'], 'N' ); ?> >No</option>
				</select>
			</fieldset>

			<fieldset <?php if ( 'Y' === $options['shortcodes'] ) echo 'class="is-active"'; ?>>
				<label for="ahShortcodes">Shortcodes
				</label>
				<select name="ah_plugin_options[shortcodes]" id="ahShortcodes">
					<option value="N" <?php selected( $options['shortcodes'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['shortcodes'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( 'Y' === $options['security'] ) echo 'class="is-active"'; ?>>
				<label for="ahSecurity">Security Stuff:
				</label>
				<select name="ah_plugin_options[security]" id="ahSecurity">
					<option value="N" <?php selected( $options['security'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['security'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( 'Y' === $options['menu'] ) echo 'class="is-active"'; ?>>
				<label for="ahMenuClasses">Remove Menu Classes: </label>
				<select name="ah_plugin_options[menu]" id="ahMenuClasses">
					<option value="N" <?php selected( $options['menu'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['menu'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( 'Y' === $options['images'] ) echo 'class="is-active"'; ?>>
				<label for="ahImages">Images: </label>
				<select name="ah_plugin_options[images]" id="ahImages">
					<option value="N" <?php selected( $options['images'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['images'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<fieldset <?php if ( 'Y' === $options['show_service_worker'] ) echo 'class="is-active"'; ?>>
				<label for="ahServiceWorker">Use Service Worker: </label>
				<select name="ah_plugin_options[show_service_worker]" id="ahServiceWorker">
					<option value="N" <?php selected( $options['show_service_worker'], "N" ); ?> >No</option>
					<option value="Y" <?php selected( $options['show_service_worker'], "Y" ); ?> >Yes</option>
				</select>
			</fieldset>

			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="ah_plugin_options" />
			<input type="submit" value="Save Changes" class="button" />

			<?php
			$options = get_option( 'ah_plugin_options' );

			if ( false === $options ) {
				$options = array(
					'admin' => 'N',
					'security' => 'N',
					'shortcodes' => 'N',
					'menu' => 'N',
					'images' => 'Y',
					'show_service_worker' => 'Y',
				);
				update_option( 'ah_plugin_options', $options );
			}
			?>
		</form>

		<h2>Results</h2>
		<?php
		$ah_options = array(
			'admin',
			'shortcodes',
			'security',
			'menu',
			'images',
		);
		?>
		<ul class="bullet">
			<?php
			foreach ( $ah_options as $ah_option ) {
				echo '<li><strong>' . ucfirst( $ah_option ) . ':</strong> <span>' . $options[$ah_option] . '</span></li>';
			}
			?>
		</ul>
	</div>
	<?php
}

$options = get_option( 'ah_plugin_options' );

// include sub pages
require_once 'init-scripts-styles.php';
require_once 'admin-area.php';
require_once 'content.php';
require_once 'cpts.php';
require_once 'metaboxes.php';
require_once 'api-calls.php';
require_once 'rest-api/wp-api.php';
require_once 'refresh-service-worker.php';

// // Gutenberg blocks
// require_once 'gutenberg/random-image.php';


if ( 'Y' === $options['shortcodes'] ) {
	require_once 'shortcodes.php';
}
if ( 'Y' === $options['security'] ) {
	require_once 'security-stuff.php';
}
if ( 'Y' === $options['menu'] ) {
	require_once 'remove-menu-classes.php';
}
if ( 'Y' === $options['images'] ) {
	require_once 'images.php';
}
