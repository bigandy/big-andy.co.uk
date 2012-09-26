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
		
        <fieldset class="no-bg">
        	<label for="ahOutput">Enter Text:</label> 
        	<?php
        	
        	$options = get_option( 'ah_plugin_options' );
        	$ah_output = $options['output'];
			
			// echo $ah_output;
			
        	echo "<input name='ah_plugin_options[output]' type='text' id='ahOutput' value='{$options['output']}' />";?>
        	
        	
        </fieldset>


		<fieldset <?php if ($options['admin'] == "Y") echo 'class="is-active"'; ?>>
			<label for="adminArea">Admin Area</label>
			
				
				<select name="ah_plugin_options[admin]" id="adminArea">
                    <option value="Y" <?php selected( $options['admin'], "Y" ); ?> >Yes</option>
                    <option value="N" <?php selected( $options['admin'], "N" ); ?> >No</option>
                </select>
				
				
				
			
		</fieldset>

		<fieldset <?php if ($options['shortcodes'] == "Y") echo 'class="is-active"'; ?>>
			<label for="ahShortcodes">Shortcodes
			</label>
			<select name="ah_plugin_options[shortcodes]" id="ahShortcodes">
				<option value="Y" <?php selected( $options['shortcodes'], "Y" ); ?> >Yes</option>
				<option value="N" <?php selected( $options['shortcodes'], "N" ); ?> >No</option>
			</select>
		</fieldset>

		<fieldset <?php if ($options['security'] == "Y") echo 'class="is-active"'; ?>>
			<label for="ahSecurity">Security Stuff: 
			</label>
			<select name="ah_plugin_options[security]" id="ahSecurity">
				<option value="Y" <?php selected( $options['security'], "Y" ); ?> >Yes</option>
				<option value="N" <?php selected( $options['security'], "N" ); ?> >No</option>
			</select>
		</fieldset>

		<fieldset <?php if ($options['menu'] == "Y") echo 'class="is-active"'; ?>>
			<label for="ahMenuClasses">Remove Menu Classes: </label>
			<select name="ah_plugin_options[menu]" id="ahMenuClasses">
				<option value="Y" <?php selected( $options['menu'], "Y" ); ?> >Yes</option>
                <option value="N" <?php selected( $options['menu'], "N" ); ?> >No</option>
			</select>
		</fieldset>

		<fieldset <?php if ($options['widgets'] == "Y") echo 'class="is-active"'; ?>>
			<label for="ahWidgets">Widgets</label>
			<select name="ah_plugin_options[widgets]" id="ahWidgets">
				<option value="Y" <?php selected( $options['widgets'], "Y" ); ?> >Yes</option>
                <option value="N" <?php selected( $options['widgets'], "N" ); ?> >No</option>
			</select>
		</fieldset>

		<fieldset <?php if ($options['footer'] == "Y") echo 'class="is-active"'; ?>>
			<label for="ahFooter">Footer: </label>
			<select name="ah_plugin_options[footer]" id="ahFooter">
				<option value="Y" <?php selected( $options['footer'], "Y" ); ?> >Yes</option>
                <option value="N" <?php selected( $options['footer'], "N" ); ?> >No</option>
			</select>
		</fieldset>
		
		<fieldset <?php if ($options['darkLight'] == "1") echo 'class="is-active"'; ?>>
            <p class="fakeLabel">Dark/Light: </p>
            
            <label for="ahRadioTestOn">On</label>
                <input type="radio" name="ah_plugin_options[darkLight]" id="ahRadioTestOn" value="1" <?php checked( $options['darkLight'], 1 ); ?> />
            <label for="ahRadioTestOff">Off</label>
                <input type="radio" name="ah_plugin_options[darkLight]" id="ahRadioTestOff" value="0" <?php checked( $options['darkLight'], 0 ); ?> />
            
            
        </fieldset>

		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="ah_plugin_options" />
		<input type="submit" value="Save Changes" />
		
		<?php 
           
		      
    		$options = get_option( 'ah_plugin_options' );
        
            if ($options === false) {
                $options = array(
                    'output' => 'empty',
                    'admin' => 'N',
                    'security' => 'N',
                    'shortcodes' => 'N',
                    'menu' => 'N',
                    'widgets' => 'N',
                    'footer' => 'N',
                    'darkLight' => '0'
                );
                update_option( 'ah_plugin_options', $options );
            }
        
            $ah_output = $options['output'];
            $ah_admin = $options['admin'];
            $ah_security = $options['security'];
            $ah_shortcodes = $options['shortcodes'];
            $ah_menu = $options['menu'];
            $ah_widgets = $options['widgets'];
            $ah_footer = $options['footer'];
            $ah_darkLight = $options['darkLight'];
            
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
