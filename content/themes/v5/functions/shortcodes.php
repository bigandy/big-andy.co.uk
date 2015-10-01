<?php
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

	$html = '<div data-lazy-widget="lazy" class="g-plusone"></div>';

	$html .= '<div id="lazy"><!--';
		$html .= $content;
	$html .= '--></div>';

	return $html;
}
add_shortcode( 'lazy', 'ah_shortcode_lazy' );

