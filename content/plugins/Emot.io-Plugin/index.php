<?php
/*
Plugin Name: Emotio Plugin
Plugin URI: http://www.big-andy.co.uk
Description: Allows you to put emot.io in your blog
Version: 1.1
Author: bigandy
Author URI: http://www.big-andy.co.uk
License: GPL
*/

register_activation_hook( __FILE__,'emotio_plugin_install' ); // Runs when plugin activation

register_deactivation_hook( __FILE__, 'emotio_plugin_remove' ); // Runs on plugin deactivation

function emotio_plugin_install() // Creates new database field
{
	add_option( 'emotio_plugin_data', 'Default', 'yes' ); 
}

function emotio_plugin_remove() // Deletes the database field
{
	delete_option( 'emotio_plugin_data' ); 
}

/* Down to the nuts and bolts of the plugin. When in admin area, print out form, add Emotio Plugin admin area in plugins section, take input and put in DB */

if ( is_admin() ) 
{
	add_action('admin_menu', 'emotio_plugin_admin_menu');
	add_filter('wp_footer','emotio_plugin_data');

	function emotio_plugin_admin_menu()
	{ 
		add_submenu_page ( 'plugins.php', 'Emotio Plugin', 'Emotio Plugin', 'manage_options', 'emotio-plugin', 'emotio_plugin_html_page' ); 
	}
}	

function emotio_plugin_html_page() 
{ 
?>
	<style>
		label { 
		    display:inline-block; width:150px; 
		}
		fieldset {
		     margin-bottom:10px;
		}
		.output-container span {color:green;}
	</style>	

	<div class="wrap">
		<?php screen_icon(); ?>

		<h2>Emotio Admin Area</h2>
	
		<form method="post" action="options.php" class="options">
			<?php wp_nonce_field('update-options'); ?>

			
			
			<fieldset>
				<label for="ah_plugin_az">Text (a-z): </label>
				<input name="ah_plugin_az" type="text" id="ah_plugin_az" value="<?php echo get_option('ah_plugin_az'); ?>" />
			</fieldset>
			
			<fieldset>
			    <label for="ah_plugin_AZ">Text (A-Z): </label>
                <input name="ah_plugin_AZ" type="text" id="ah_plugin_AZ" value="<?php echo get_option('ah_plugin_AZ'); ?>" />
            </fieldset>
            
            <fieldset>
                <label for="ah_plugin_numbers">Numbers (0-9): </label>
                <input name="ah_plugin_numbers" type="text" id="ah_plugin_numbers" value="<?php echo get_option('ah_plugin_numbers'); ?>" />
            </fieldset>
            
            <fieldset>
                <label for="ah_plugin_anything">Anything: </label>
                <input name="ah_plugin_anything" type="text" id="ah_plugin_anything" value="<?php echo get_option('ah_plugin_anything'); ?>" />
			</fieldset>
			
	
			
			<input type="hidden" name="ah_plugin_az" value="ah_plugin_az" />
			<input type="hidden" name="ah_plugin_AZ" value="ah_plugin_AZ" />
			<input type="hidden" name="ah_plugin_number" value="ah_plugin_number" />
			<input type="hidden" name="ah_plugin_anything" value="ah_plugin_anything" />
			<input type="hidden" name="action" value="update" />
			
			<p>
				<input type="submit" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		
		<div class="output-container">
			<p><strong>Text a-z </strong>: <span><?php echo get_option('ah_plugin_az'); ?></span></p>
			<p><strong>Text A-Z </strong>: <span><?php echo get_option('ah_plugin_AZ'); ?></span></p>
			<p><strong>Number </strong>: <span><?php echo get_option('ah_plugin_numbers'); ?></span></p>
			<p><strong>Anything </strong>: <span><?php echo get_option('ah_plugin_anything'); ?></span></p>
		</div>
	</div>
<?php 
} 
