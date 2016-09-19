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

	// extract( shortcode_atts( array(
	// 			'email' => 'andy@big-andy.co.uk',
	// 		), $atts ) );


	return '<a class="email" href="mailto:'. sanitize_email( $atts['email'] ) .'">'. $atts['email'] .'</a>';
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

	$escaped_url = preg_replace( '(^https?://)', '', $atts['url'] ); // remove the http(s)
	$escaped_url = preg_replace( '(^www.)', '', $escaped_url ); // remove the www.
	$escaped_url = str_replace( '/', '', $escaped_url ); // remove the final slash

	return '<a href="' . esc_url( $atts['url'] ) . '">' . esc_html( $escaped_url ) . '</a>';
}
add_shortcode( 'website', 'ah_website_shortcode' );

// [vcard] :: allows for the other shortcodes to be within this shortcode!
function ah_vcard_shortcode( $atts, $content = null ) {
	return '<div class="vcard">'. do_shortcode( $content ) .'</div>';
}
add_shortcode( 'vcard', 'ah_vcard_shortcode' );

function ah_empty_paragraph_fix( $content ) {
	// An array of the offending tags.
	$arr = array(
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']',
	);
	// Remove the offending tags and return the stripped content.
	$stripped_content = strtr( $content, $arr );
	return $stripped_content;
}
add_filter( 'the_content', 'ah_empty_paragraph_fix' );

/**
 * Shorcode to produce half column
 */
function ah_shortcode_half( $atts, $content ) {
	$html = '<div class="large-6 columns">' . do_shortcode( $content ) . '</div>';
	return $html;
}
add_shortcode( 'half', 'ah_shortcode_half' );

function ah_shortcode_full( $atts, $content ) {
	$html = '<div class="large-12 columns">' . do_shortcode( $content ) . '</div>';
	return $html;
}
add_shortcode( 'full', 'ah_shortcode_full' );

function ah_shortcode_row( $atts, $content ) {
	$html = '<div class="row">' . do_shortcode( $content ) . '</div>';
	return $html;
}
add_shortcode( 'row', 'ah_shortcode_row' );

function ah_shortcode_icon( $atts ) {
	$atts = shortcode_atts( array(
		'type' => 'pen',
		'size' => '',
	), $atts, 'icon' );

	$size = '';
	$type = $atts['type'];

	if ( $atts['size'] !== '' ) {
		$size = ' icon--' . $atts['size'];
	}

	$html = '<svg class="icon icon--shortcode' . $size . '"><use xlink:href="#'. $type .'" /></svg>';
	return $html;
}
add_shortcode( 'icon', 'ah_shortcode_icon' );

function ah_shortcode_lazy( $atts, $content ) {

	$html = '<div data-lazy-widget="lazy"></div>';

	$html .= '<div id="lazy"><!--';
		$html .= $content;
	$html .= '--></div>';

	return $html;
}
add_shortcode( 'lazy', 'ah_shortcode_lazy' );

function ah_shortcode_weather( $atts, $content ) {
	$weather = ah_set_weather();

	return esc_html( $weather );
}
add_shortcode( 'weather', 'ah_shortcode_weather' );
