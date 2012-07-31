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
		#emotio_plugin_data { width:300px; }
		.output-container span {color:green;}
	</style>	

	<div class="wrap">
		<?php screen_icon(); ?>

		<h2>Emotio Admin Area</h2>
	
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options'); ?>

			<label for="emotio_plugin_data">Emotio Plugin Code:</label>
			<input name="emotio_plugin_data" type="text" id="emotio_plugin_data" placeholder="Enter your Emot.io code"value="<?php echo get_option('emotio_plugin_data'); ?>" />
	
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="emotio_plugin_data" />
			<p>
				<input type="submit" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
		
		<div class="output-container">
			<p><strong>Emotio Code</strong>: <span><?php echo get_option('emotio_plugin_data'); ?></span></p>
		</div>
	</div>
<?php 
} 

function display_jquery()
{
	if( ! is_admin() )
	{ 
		wp_enqueue_script( 'jquery' ); 
	}
}
add_action( 'init', 'display_jquery' );

function display_emotio_plugin_code() 
{
	// output of the emotio script that goes in the page header , plus the emotio key
	echo"<script type='text/javascript'>  
		   var _emq = _emq || [];
		   (function() {
			_emq.push(['setPID', '". get_option('emotio_plugin_data')."']);
		    var s = document.createElement('SCRIPT');
		    var c = document.getElementsByTagName('script')[0];
		    s.type = 'text/javascript';
		    s.async = true;
		    s.src = 'http://emotio.heroku.com/javascripts/widget/init.js';
		    c.parentNode.insertBefore(s, c);
		   })();
		 </script>";
}
add_action('wp_head','display_emotio_plugin_code');

/* add emotio div bar at the end of the content */

function display_emotio_div_feed($post_content) 
{
	if( !is_feed() || !is_home() )
	{
		$my_shortlink = wp_get_shortlink(); // finds the short url of the post
		
		$post_content.= "<div class='emotiobar'><a href='$my_shortlink'></a></div>"; // Combines the emotio bar with the post content
	
		return $post_content;
	}
}
add_filter( 'the_content', 'display_emotio_div_feed' );
