<?php
class BigAndyThemeCustomizer {
	function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( $wp_customize ) {
		// Colour Picker for theme-color meta in header.php
		// add new section
		$wp_customize->add_section(
			'es_theme_colors',
			array(
				'title' => __( 'Theme Colours', 'es' ),
				'priority' => 100,
			)
		);

		// add color picker setting
		$wp_customize->add_setting(
			'es_meta_color',
			array(
				'default' => '#008AD7',
			)
		);

		// add color picker control
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'es_theme_colors',
				array(
					'label' => 'Link Color',
					'section' => 'es_theme_colors',
					'settings' => 'es_meta_color',
				)
			)
		);
		// END of theme-color Colour Picker
	}
}
$batc = new BigAndyThemeCustomizer;
