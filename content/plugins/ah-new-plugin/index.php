<?php 
/*
Plugin Name: AH New Plugin
Description: where I can test out the Plugin Settings Chapter of Professional WordPress Plugin Development
Version: 0.1
Author: Andrew Hudson
Author URI: http://andyhudson.me
Plugin URI: http://andyhudson.me
*/

// add menu for our option page

register_activation_hook( __FILE__,'ah_new_plugin_install' );

/* What to do when the plugin is deactivated? */
register_deactivation_hook( __FILE__, 'ah_new_plugin_remove' );

function ah_new_plugin_install() {
	/* Create a new database field */
	add_option( 'ah_new_plugin_options' );
}

function ah_new_plugin_remove() {
	/* Delete the database field */
	delete_option('ah_new_plugin_options');
}


add_action( 'admin_menu', 'ah_new_plugin_add_page' );
function ah_new_plugin_add_page() {
	add_options_page( 
		'New Plugin',
		'AH New Plugin Options',
		'manage_options',
		'ah_new_plugin',
		'ah_new_plugin_options_page'		
	);
}




// Draw the options page


function ah_new_plugin_options_page() {
	?>
	
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2>Title</h2>		
		<form action="options.php" method="post">
			<?php settings_fields( 'ah_new_plugin_options' ); ?>
			
			<?php do_settings_sections( 'ah_new_plugin' ); ?>
			
			<input type="submit" name="Submit" value="Save Changes"  />
			
		</form>
		
	</div>
	
	<?php
}


// Register and define the settings

function ah_new_plugin_admin_init() {
	
	register_setting( 
			'ah_new_plugin_options', 
			'ah_new_plugin_options', 
			'ah_new_plugin_validate_options' 
	);
	
	
		add_settings_section( 
			'ah_new_plugin_main', 
			'My Plugin Settings', 
			'ah_new_plugin_section_text', 
			'ah_new_plugin' 
		);
		
		add_settings_field( 
			'ah_new_plugin_text_string', 
			'<label for="text_string">Enter text here:</label>', 
			'ah_new_plugin_settings_input', 
			'ah_new_plugin', 
			'ah_new_plugin_main' 
		);
		
		add_settings_field( 
			'ah_new_plugin_text_string_b', 
			'<label for="text_string">Moar text here:</label>', 
			'ah_new_plugin_settings_input_b', 
			'ah_new_plugin', 
			'ah_new_plugin_main' 
		);
		
		/*
		add_settings_field(
					'plugin_text_string_a', 
					'Option A', 
					'settingsapi_setting_string_a', 
					'settingsapi_pageslug', 
					'plugin_main'
				);
				add_settings_field(
					'plugin_text_string_b', 
					'Option B', 
					'settingsapi_setting_string_b', 
					'settingsapi_pageslug', 
					'plugin_main'
				);*/
		
		
		
		
}


add_action( 'admin_init', 'ah_new_plugin_admin_init' );


// Draw the section header 



// Display and fill the form field

function ah_new_plugin_settings_input() {
	// get option 'text_string' from the DB
	
	
	
	$options = get_option( 'ah_new_plugin_options' );
	$text_string_a = $options['text_string_a'];
	
	// echo the field
	
	echo "<input type='text' id='text_string_a' name='ah_new_plugin_options[text_string_a]' value='$text_string_a' />";
	
}


function ah_new_plugin_settings_input_b() {
	// get option 'text_string' from the DB
	
	$options = get_option( 'ah_new_plugin_options' );
	$text_string_b = $options['text_string_b'];
	
	// echo the field
	
	echo "<input type='text' id='text_string_b' name='ah_new_plugin_options[text_string_b]' value='$text_string_b' />";
	
}

// validate user input (we want text only)


function ah_new_plugin_validate_options( $input ) {
	$valid = array();
	
	$valid['text_string_a'] = preg_replace( '/[^a-zA-Z]/', '', $input['text_string_a'] );
	
	if ( $valid['text_string_a'] != $input['text_string'] ) {
		add_settings_error(
			'ah_new_plugin_text_string',
			'ah_new_plugin_error',
			'Incorrect value entered',
			'error'
		);
	}
	
	
	
	return $valid;
	
	
}

/*
 * http://wordpress.stackexchange.com/questions/62371/adding-form-fields-with-settings-api

add_action('admin_init', 'settingsapi_init');
function settingsapi_init(){
    register_setting( 'settingsapi_optiongroupname', 'settingsapi_optionname');
    add_settings_section('plugin_main', 'Section 1', 'settingsapi_sectiondescription', 'settingsapi_pageslug');
    add_settings_field('plugin_text_string_a', 'Option A', 'settingsapi_setting_string_a', 'settingsapi_pageslug', 'plugin_main');
    add_settings_field('plugin_text_string_b', 'Option B', 'settingsapi_setting_string_b', 'settingsapi_pageslug', 'plugin_main');
}

function settingsapi_sectiondescription() {
    echo '<p>This is a section description.</p>';
}

// First field callback.
function settingsapi_setting_string_a() {
    $options = get_option('settingsapi_optionname');
    echo "<input id='plugin_text_string' name='settingsapi_optionname[option_a]' size='40' type='text' value='{$options['option_a']}' />";
}

// Second field callback.
function settingsapi_setting_string_b(){
    $options = get_option('settingsapi_optionname');

    echo "<input id='plugin_text_string' name='settingsapi_optionname[option_b]' size='40' type='text' value='{$options['option_b']}' />";
}

// admin menu
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
    add_options_page('Custom Plugin Page', 'Demo Settings API Menu', 'manage_options', 'settingsapi_pageslug', 'settingsapi_adminpage');
}

function settingsapi_adminpage() {
    ?>
    <div class="wrap">
        <?php screen_icon(); ?> <h2>Demo Plugin for Settings API</h2>
        <form action="options.php" method="post">
            <?php settings_fields('settingsapi_optiongroupname'); ?>
            <?php do_settings_sections('settingsapi_pageslug'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>*/
