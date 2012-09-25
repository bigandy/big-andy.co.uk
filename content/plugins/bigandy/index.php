<?php 
/* 
* Plugin Name: bigandy functionality
* Version : 1.1
* Description : Shortcode for address, plus other stuff
*/

// Now to be able to turn these on and off!

add_action('admin_menu', 'my_first_admin_menu');
function my_first_admin_menu() {
add_options_page( 
            'Plugin Admin Options', 
            'Bigandy Plugins', 
            'manage_options',
            'my-first', 
            'ah_plugin_admin_options_page'
        );
}

function ah_plugin_admin_options_page() {
?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2>Bigandy Plugin Options</h2>

	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<!--<label for="my_first_data">Enter Text:</label>
		<input name="my_first_data" type="text" id="my_first_data" value="<?php echo get_option('my_first_data'); ?>" />-->

		<fieldset>
			<label for="adminArea">Admin Area
			</label>
			<select name="adminArea" id="adminArea">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<fieldset>
			<label for="ahShortcodes">Shortcodes
			</label>
			<select name="ahShortcodes" id="ahShortcodes">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<fieldset>
			<label for="ahSecurity">Security Stuff: 
			</label>
			<select name="ahSecurity" id="ahSecurity">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<fieldset>
			<label for="ahMenuClasses">Remove Menu Classes: </label>
			<select name="ahMenuClasses" id="ahMenuClasses">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<fieldset>
			<label for="ahWidgets">Widgets</label>
			<select name="ahWidgets" id="ahWidgets">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<fieldset>
			<label for="ahFooter">Footer: </label>
			<select name="ahFooter" id="ahFooter">
				<option value="Y">Y</option>
				<option value="N">N</option>
			</select>
		</fieldset>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="my_first_data" />

		<input type="submit" value="Save Changes" />
	</form>

</div>
<?php
}

// include sub pages
require_once('init-styles.php');
require_once('admin-area.php');
require_once('shortcodes.php');
require_once('security-stuff.php');
require_once('remove-menu-classes.php');
require_once('images.php');
require_once('ah-widgets.php');
require_once('ah-footer.php');
