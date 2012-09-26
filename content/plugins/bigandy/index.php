<?php 
/* 
Plugin Name: bigandy functionality
Version: 1.1
Description: Shortcode for address, plus other stuff
Author: Andrew Hudson
Author URI: http://andyhudson.me
Plugin URI: http://andyhudson.me
*/

// Now to be able to turn these on and off!

/* What to do when the plugin is activated? */
register_activation_hook( __FILE__,'ah_plugin_install' );

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'ah_plugin_remove' );

function my_first_plugin_install() {
	/* Create a new database field */
	add_option( 'ah_plugin_options' );
}

function my_first_plugin_remove() {
	/* Delete the database field */
	delete_option('ah_plugin_options');
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

// echo $output ."<br />". $admin ."<br />". $security ."<br />". $menu ."<br />". $widgets ."<br />". $footer ."<br />". $darkLight ."<br />"; 


add_action('admin_menu', 'ah_plugin_admin_menu');
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
	
	<?php wp_nonce_field('update-options'); ?>
		
        <fieldset <?php if ( $options['output'] ) echo 'class="is-active"';  ?> >
        	<label for="ahOutput">Enter Text:</label> 

        	<?php
                // echo $ah_output;
                echo "<input name='ah_plugin_options[output]' type='text' id='ahOutput' value='{$options['output']}' />";
        	?>
        	
        	<?php if ( !$options['output'] ) echo "isn't set";   ?>
        </fieldset>


		<fieldset <?php if ($options['adminArea'] == "N") echo 'class="is-active"'; ?>>
			<label for="adminArea">Admin Area</label>
			
				
				<select name="ah_plugin_options[adminArea]" id="adminArea">
                    <option value="Y" <?php selected( $options['adminArea'], "Y" ); ?> >Yes</option>
                    <option value="N" <?php selected( $options['adminArea'], "N" ); ?> >No</option>
                </select>
				
				
				
			
		</fieldset>

		<fieldset>
			<label for="ahShortcodes">Shortcodes
			</label>
			<select name="ah_plugin_options[ahShortcodes]" id="ahShortcodes">
				<option value="Y" <?php selected( $options['ahShortcodes'], "Y" ); ?> >Yes</option>
				<option value="N" <?php selected( $options['ahShortcodes'], "N" ); ?> >No</option>
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
		<input type="hidden" name="page_options" value="ah_plugin_options" />
		<input type="submit" value="Save Changes" />
		
		<?php 
    		$options = get_option( 'ah_plugin_options' );
        
            $ah_output = $options['output'];
            $ah_admin = $options['admin'];
            $ah_security = $options['security'];
            $ah_shortcodes = $options['shortcodes'];
            $ah_menu = $options['menu'];
            $ah_widgets = $options['widgets'];
            $ah_footer = $options['footer'];
            $ah_darkLight = $options['darkLight'];
            
            update_option( 'ah_plugin_options', $ah_plugin_options );
        ?>
	</form>
	
	
	<h2>Results</h2>
	
    <p><strong>Output: </strong><?php echo $ah_output ?></p>
    <p><strong>Admin: </strong><?php echo $ah_admin ?></p>
    <p><strong>Shortcodes: </strong><?php echo $ah_shortcodes ?></p>
    <p><strong>Security: </strong><?php echo $ah_security ?></p>
    <p><strong>Menu Classes: </strong><?php echo $ah_menu ?></p>
    <p><strong>Widgets: </strong><?php echo $ah_widgets  ?></p>
    <p><strong>Footer: </strong><?php echo $ah_footer ?></p>
    <p><strong>Dark/Light: </strong><?php echo $ah_darkLight ?></p>
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
