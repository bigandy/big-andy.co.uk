<?php
/*
Plugin Name: Test Validation Admin Area
Plugin URI: http://www.big-andy.co.uk
Version: 1.0
Author: bigandy
Author URI: http://www.big-andy.co.uk
License: GPL
*/
if ( is_admin() ) 
{
	add_action('admin_menu', 'ah_plugin_admin_menu');
	// add_filter('wp_footer','emotio_plugin_data');

	function ah_plugin_admin_menu()
	{ 
		add_submenu_page ( 
			'plugins.php', 
			'AH Plugin', 
			'AH Plugin', 
			'manage_options', 
			'ah-plugin', 
			'ah_form_html_page'
		); 
	}
}	


function ah_form_html_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>

		<h2>Emotio Admin Area</h2>
		<form action="" method="post">
			
			
			<input type="hidden" name="ah_action" value="" id="ah_action" />
			<input type="hidden" name"id" value="" id="id" />
			<label for="name">Enter your code:</label>
			<input type="text" name="code" value="" id="code" />
			<input type="submit" value="Submit" />  
		</form>
	</div>
	<?php
	
}
