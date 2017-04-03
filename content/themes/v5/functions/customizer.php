<?php
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
