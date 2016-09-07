<?php
/**
 * Remove Items from the admin bar
 *
 * @param  WP_Admin_Bar $wp_admin_bar [description]
 * @return [type]                     [description]
 */
function ah_admin_bar_remove_items( WP_Admin_Bar $wp_admin_bar ) {
	// bail if current user doesnt have cap
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// remove items from menu bar
	$remove_array = [
		'customize',
		'comments',
		'wp-logo',
		'wpseo-menu',
		'backwpup',
		'new-post',
		'new-media',
		'new-page',
		'new-user',
	];

	foreach ( $remove_array as $item ) {
		$wp_admin_bar->remove_node( $item );
	}
}
add_action( 'admin_bar_menu', 'ah_admin_bar_remove_items', 9999 );


class BigAndyThemeCustomizer {
	function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( $wp_customize ) {
		// Colour Picker for theme-color meta in header.php
		// add new section
		$wp_customize->add_section(
			'ah_theme_colors',
			array(
				'title' => __( 'Theme Colours', 'ah' ),
				'priority' => 100,
			)
		);

		$colors = array();
		$colors[] = array(
			'slug'		=> 'ah_header_color',
			'default'	=> '#000000',
			'label'		=> 'Admin Menu Bar Color',
			'priority'	=> 20,
		);
		$colors[] = array(
			'slug'		=> 'ah_theme_color',
			'default'	=> '#008AD7',
			'label'		=> 'Theme Colour',
			'priority'	=> 21,
		);
		// Build settings from $colors array
		foreach ( $colors as $color ) {

			// customizer settings
			$wp_customize->add_setting( $color['slug'], array(
				'default'		=> $color['default'],
				'type'			=> 'theme_mod',
				'capability'	=> 'edit_theme_options',
			) );

			// customizer controls
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $color['slug'], array(
				'label'		=> $color['label'],
				'section'	=> 'ah_theme_colors',
				'settings'	=> $color['slug'],
				'priority'	=> $color['priority'],
			) ) );
		}
		// END of theme-color Colour Picker
	}
}
$ahtc = new BigAndyThemeCustomizer;
