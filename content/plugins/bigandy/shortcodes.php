<?php
/* Shortcodes */
// [address] using microformats : http://microformats.org/code/hcard/creator
function ah_address_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'street' => '40 Hawthorn Place',
			'town' => 'Didcot',
			'county' => 'Oxfordshire',
			'postcode' => 'OX11 6BF',
		), $atts, 'address' );

	return '<div class="adr">
  <span class="street-address">' . esc_html( $atts['street'] ) . '</span>,
  <span class="locality">' . esc_html( $atts['town'] ) . '</span>,
  <span class="region">' . esc_html( $atts['county'] ) . '</span>,
  <span class="postal-code">' . esc_html( $atts['postcode'] ) . '</span>
 </div>';
}
add_shortcode( 'address', 'ah_address_shortcode' );

// [telephone]
function ah_telephone_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'telephone' => '077 36063 671',
			'label' => '',
		), $atts, 'telephone' );

	return '<div class="tel">'. esc_html( $atts['label'] ) . esc_html( $atts['telephone'] ) .'</div>';
}
add_shortcode( 'telephone', 'ah_telephone_shortcode' );

// [email]
function ah_email_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'email' => 'andy@big-andy.co.uk',
		), $atts, 'email' );

	extract( shortcode_atts( array(
				'email' => 'andy@big-andy.co.uk',
			), $atts ) );


	return '<a class="email" href="mailto:'. esc_url( $atts['email'] ) .'">'. $atts['email'] .'</a>';
}
add_shortcode( 'email', 'ah_email_shortcode' );

// [name]
function ah_name_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'name' => 'Andrew Hudson',
			'wrapper' => 'span',
			'link' => '',
			'class' => '',
		), $atts, 'name' );


	if ( $atts['link'] !== '' ) {
		$output = '<'. $atts['wrapper'] .' class="fn '. $atts['class'] .'"><a href="'. esc_url( $atts['link'] ) .'">'. $atts['name'] .'</a></'. $atts['wrapper'] .'>';
	} else {
		$output = '<'. $atts['wrapper'] .' class="fn '. $atts['class'] .'">'. $atts['name'] .'</'. $atts['wrapper'] .'>';
	}
	return $output;
}
add_shortcode( 'name', 'ah_name_shortcode' );


// [website]
function ah_website_shortcode( $atts, $content = null ) {
	$atts = shortcode_atts(
		array(
			'url' => 'https://big-andy.co.uk',
		), $atts, 'link' );

	return '<a href="' . esc_url( $atts['url'] ) . '">' . esc_html( preg_replace( '(^https?://)', '', $atts['url'] ) ) . '</a>';
}
add_shortcode( 'website', 'ah_website_shortcode' );

// [vcard] :: allows for the other shortcodes to be within this shortcode!
function ah_vcard_shortcode( $atts, $content = null ) {
	return '<div class="vcard">'. do_shortcode( $content ) .'</div>';
}
add_shortcode( 'vcard', 'ah_vcard_shortcode' );
