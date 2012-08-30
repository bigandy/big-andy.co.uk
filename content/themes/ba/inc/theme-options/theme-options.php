<?php
/**
 * ba Theme Options
 *
 * @package ba
 * @since ba 1.0
 */

/**
 * Register the form setting for our ba_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, ba_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are properly
 * formatted, and safe.
 *
 * @since ba 1.0
 */
function ba_theme_options_init() {
	register_setting(
		'ba_options', // Options group, see settings_fields() call in ba_theme_options_render_page()
		'ba_theme_options', // Database option, see ba_get_theme_options()
		'ba_theme_options_validate' // The sanitization callback, see ba_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see ba_theme_options_add_page()
	);

	// Register our individual settings fields
	add_settings_field(
		'sample_checkbox', // Unique identifier for the field for this section
		__( 'Sample Checkbox', 'ba' ), // Setting field label
		'ba_settings_field_sample_checkbox', // Function that renders the settings field
		'theme_options', // Menu slug, used to uniquely identify the page; see ba_theme_options_add_page()
		'general' // Settings section. Same as the first argument in the add_settings_section() above
	);

	add_settings_field( 'sample_text_input', __( 'Sample Text Input', 'ba' ), 'ba_settings_field_sample_text_input', 'theme_options', 'general' );
	add_settings_field( 'sample_select_options', __( 'Sample Select Options', 'ba' ), 'ba_settings_field_sample_select_options', 'theme_options', 'general' );
	add_settings_field( 'sample_radio_buttons', __( 'Sample Radio Buttons', 'ba' ), 'ba_settings_field_sample_radio_buttons', 'theme_options', 'general' );
	add_settings_field( 'sample_textarea', __( 'Sample Textarea', 'ba' ), 'ba_settings_field_sample_textarea', 'theme_options', 'general' );
}
add_action( 'admin_init', 'ba_theme_options_init' );

/**
 * Change the capability required to save the 'ba_options' options group.
 *
 * @see ba_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see ba_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function ba_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_ba_options', 'ba_option_page_capability' );

/**
 * Add our theme options page to the admin menu.
 *
 * This function is attached to the admin_menu action hook.
 *
 * @since ba 1.0
 */
function ba_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'ba' ),   // Name of page
		__( 'Theme Options', 'ba' ),   // Label in menu
		'edit_theme_options',          // Capability required
		'theme_options',               // Menu slug, used to uniquely identify the page
		'ba_theme_options_render_page' // Function that renders the options page
	);
}
add_action( 'admin_menu', 'ba_theme_options_add_page' );

/**
 * Returns an array of sample select options registered for ba.
 *
 * @since ba 1.0
 */
function ba_sample_select_options() {
	$sample_select_options = array(
		'0' => array(
			'value' =>	'0',
			'label' => __( 'Zero', 'ba' )
		),
		'1' => array(
			'value' =>	'1',
			'label' => __( 'One', 'ba' )
		),
		'2' => array(
			'value' => '2',
			'label' => __( 'Two', 'ba' )
		),
		'3' => array(
			'value' => '3',
			'label' => __( 'Three', 'ba' )
		),
		'4' => array(
			'value' => '4',
			'label' => __( 'Four', 'ba' )
		),
		'5' => array(
			'value' => '5',
			'label' => __( 'Five', 'ba' )
		)
	);

	return apply_filters( 'ba_sample_select_options', $sample_select_options );
}

/**
 * Returns an array of sample radio options registered for ba.
 *
 * @since ba 1.0
 */
function ba_sample_radio_buttons() {
	$sample_radio_buttons = array(
		'yes' => array(
			'value' => 'yes',
			'label' => __( 'Yes', 'ba' )
		),
		'no' => array(
			'value' => 'no',
			'label' => __( 'No', 'ba' )
		),
		'maybe' => array(
			'value' => 'maybe',
			'label' => __( 'Maybe', 'ba' )
		)
	);

	return apply_filters( 'ba_sample_radio_buttons', $sample_radio_buttons );
}

/**
 * Returns the options array for ba.
 *
 * @since ba 1.0
 */
function ba_get_theme_options() {
	$saved = (array) get_option( 'ba_theme_options' );
	$defaults = array(
		'sample_checkbox'       => 'off',
		'sample_text_input'     => '',
		'sample_select_options' => '',
		'sample_radio_buttons'  => '',
		'sample_textarea'       => '',
	);

	$defaults = apply_filters( 'ba_default_theme_options', $defaults );

	$options = wp_parse_args( $saved, $defaults );
	$options = array_intersect_key( $options, $defaults );

	return $options;
}

/**
 * Renders the sample checkbox setting field.
 */
function ba_settings_field_sample_checkbox() {
	$options = ba_get_theme_options();
	?>
	<label for="sample-checkbox">
		<input type="checkbox" name="ba_theme_options[sample_checkbox]" id="sample-checkbox" <?php checked( 'on', $options['sample_checkbox'] ); ?> />
		<?php _e( 'A sample checkbox.', 'ba' ); ?>
	</label>
	<?php
}

/**
 * Renders the sample text input setting field.
 */
function ba_settings_field_sample_text_input() {
	$options = ba_get_theme_options();
	?>
	<input type="text" name="ba_theme_options[sample_text_input]" id="sample-text-input" value="<?php echo esc_attr( $options['sample_text_input'] ); ?>" />
	<label class="description" for="sample-text-input"><?php _e( 'Sample text input', 'ba' ); ?></label>
	<?php
}

/**
 * Renders the sample select options setting field.
 */
function ba_settings_field_sample_select_options() {
	$options = ba_get_theme_options();
	?>
	<select name="ba_theme_options[sample_select_options]" id="sample-select-options">
		<?php
			$selected = $options['sample_select_options'];
			$p = '';
			$r = '';

			foreach ( ba_sample_select_options() as $option ) {
				$label = $option['label'];
				if ( $selected == $option['value'] ) // Make default first in list
					$p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
				else
					$r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
			}
			echo $p . $r;
		?>
	</select>
	<label class="description" for="sample_theme_options[selectinput]"><?php _e( 'Sample select input', 'ba' ); ?></label>
	<?php
}

/**
 * Renders the radio options setting field.
 *
 * @since ba 1.0
 */
function ba_settings_field_sample_radio_buttons() {
	$options = ba_get_theme_options();

	foreach ( ba_sample_radio_buttons() as $button ) {
	?>
	<div class="layout">
		<label class="description">
			<input type="radio" name="ba_theme_options[sample_radio_buttons]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['sample_radio_buttons'], $button['value'] ); ?> />
			<?php echo $button['label']; ?>
		</label>
	</div>
	<?php
	}
}

/**
 * Renders the sample textarea setting field.
 */
function ba_settings_field_sample_textarea() {
	$options = ba_get_theme_options();
	?>
	<textarea class="large-text" type="text" name="ba_theme_options[sample_textarea]" id="sample-textarea" cols="50" rows="10" /><?php echo esc_textarea( $options['sample_textarea'] ); ?></textarea>
	<label class="description" for="sample-textarea"><?php _e( 'Sample textarea', 'ba' ); ?></label>
	<?php
}

/**
 * Renders the Theme Options administration screen.
 *
 * @since ba 1.0
 */
function ba_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( '%s Theme Options', 'ba' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'ba_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see ba_theme_options_init()
 * @todo set up Reset Options action
 *
 * @param array $input Unknown values.
 * @return array Sanitized theme options ready to be stored in the database.
 *
 * @since ba 1.0
 */
function ba_theme_options_validate( $input ) {
	$output = array();

	// Checkboxes will only be present if checked.
	if ( isset( $input['sample_checkbox'] ) )
		$output['sample_checkbox'] = 'on';

	// The sample text input must be safe text with no HTML tags
	if ( isset( $input['sample_text_input'] ) && ! empty( $input['sample_text_input'] ) )
		$output['sample_text_input'] = wp_filter_nohtml_kses( $input['sample_text_input'] );

	// The sample select option must actually be in the array of select options
	if ( isset( $input['sample_select_options'] ) && array_key_exists( $input['sample_select_options'], ba_sample_select_options() ) )
		$output['sample_select_options'] = $input['sample_select_options'];

	// The sample radio button value must be in our array of radio button values
	if ( isset( $input['sample_radio_buttons'] ) && array_key_exists( $input['sample_radio_buttons'], ba_sample_radio_buttons() ) )
		$output['sample_radio_buttons'] = $input['sample_radio_buttons'];

	// The sample textarea must be safe text with the allowed tags for posts
	if ( isset( $input['sample_textarea'] ) && ! empty( $input['sample_textarea'] ) )
		$output['sample_textarea'] = wp_filter_post_kses( $input['sample_textarea'] );

	return apply_filters( 'ba_theme_options_validate', $output, $input );
}
