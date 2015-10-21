<?php

/* Shortcodes */
// [address] using microformats : http://microformats.org/code/hcard/creator
function ah_address_shortcode( $atts ) {
	extract( shortcode_atts( array(
				'street' => '40 Hawthorn Place',
				'town' => 'Didcot',
				'county' => 'Oxfordshire',
				'postcode' => 'OX11 6BF',
			), $atts ) );
	return '<div class="adr">
  <span class="street-address">' . $street . '</span>,
  <span class="locality">' . $town . '</span>,
  <span class="region">' . $county . '</span>,
  <span class="postal-code">' . $postcode . '</span>
 </div>';
}
add_shortcode( 'address', 'ah_address_shortcode' );

// [telephone]
function ah_telephone_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
				'telephone' => '077 36063 671',
				'label' => '',
			), $atts ) );
	return '<div class="tel">'. $label . $telephone .'</div>';
}
add_shortcode( 'telephone', 'ah_telephone_shortcode' );

// [email]
function ah_email_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
				'email' => 'andy@big-andy.co.uk',
			), $atts ) );
	return '<a class="email" href="mailto:'. $email .'">'. $email .'</a>';
}
add_shortcode( 'email', 'ah_email_shortcode' );

// [name]
function ah_name_shortcode( $atts, $content = null ) {
	extract( shortcode_atts( array(
				'name' => 'Andrew Hudson',
				'wrapper' => 'span',
				'link' => '',
				'class' => '',
			), $atts ) );

	if ( $link != "" ) {
		$output = '<'. $wrapper .' class="fn '. $class .'"><a href="'. $link .'">'. $name .'</a></'. $wrapper .'>';
	} else {
		$output = '<'. $wrapper .' class="fn '. $class .'">'. $name .'</'. $wrapper .'>';
	}
	return $output;
}
add_shortcode( 'name', 'ah_name_shortcode' );


// [website]
function ah_website_shortcode( $atts, $content = null ) {

	extract( shortcode_atts( array(
				'url' => 'https://big-andy.co.uk'
			), $atts ) );

	return '<a class="url" href="' . esc_url( $url ) . '">' . preg_replace( '(^https?://)', '', $url ) . '</a>';
}
add_shortcode( 'website', 'ah_website_shortcode' );

// [vcard] :: allows for the other shortcodes to be within this shortcode!
function ah_vcard_shortcode( $atts, $content = null ) {
	return '<div class="vcard">'. do_shortcode( $content ) .'</div>';
}
add_shortcode( 'vcard', 'ah_vcard_shortcode' );
