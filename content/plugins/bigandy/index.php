<?php 
/* 
* Plugin Name: bigandy functionality
* Version : 1.1
* Description : Shortcode for address, plus other stuff
*/

// Now to be able to turn these on and off!

/* What to do when the plugin is activated? */
register_activation_hook( __FILE__,'my_first_plugin_install' );

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'my_first_plugin_remove' );

function my_first_plugin_install() {
/* Create a new database field */
add_option("my_first_data", 'Testing !! My Plugin is Working Fine.', 'This is my first plugin panel data.', 'yes');
}

function my_first_plugin_remove() {
/* Delete the database field */
delete_option('my_first_data');
}



add_action('admin_menu', 'my_first_admin_menu');
function my_first_admin_menu() {
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

	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		
        <fieldset class="<?php if( get_option('my_first_data') ) {echo "is-active";}?>">
        	<label>Enter Text:</label> 
        	<input name="my_first_data" type="text" id="my_first_data" value="<?php echo get_option('my_first_data'); ?>" />
        </fieldset>


		<fieldset class="is-active">
			<label for="adminArea">Admin Area
			</label>
			<select name="adminArea" id="adminArea">
				<option value="Y" selected="selected">Y</option>
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
		
		<fieldset>
            <p class="fakeLabel">Dark/Light: </p>
            
            <label for="ahRadioTestOn">On</label><input type="radio" name="ahTestRadioGroup" id="ahRadioTestOn" />
            <label for="ahRadioTestOff">Off</label><input type="radio" name="ahTestRadioGroup" id="ahRadioTestOff" />
            
            
        </fieldset>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="my_first_data" />

		<input type="submit" value="Save Changes" />
	</form>
    <?php echo "<p><strong>Output: </strong>".get_option('my_first_data') . "</p>"; ?>
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
