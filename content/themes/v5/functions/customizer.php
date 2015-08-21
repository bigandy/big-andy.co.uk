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

		// add color picker setting
		$wp_customize->add_setting(
			'ah_meta_color',
			array(
				'default' => '#008AD7',
			)
		);

		// add color picker control
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'ah_theme_colors',
				array(
					'label' => 'Link Color',
					'section' => 'ah_theme_colors',
					'settings' => 'ah_meta_color',
				)
			)
		);
		// END of theme-color Colour Picker
	}
}
$ahtc = new BigAndyThemeCustomizer;
