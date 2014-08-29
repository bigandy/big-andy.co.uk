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


function ah_shortcode_icon( $atts, $content ) {
	extract( shortcode_atts( array(
		'type' => 'pen',
		'size' => '',
	), $atts ) );

	if ( $size !== '' ) {
		$size = ' icon--' . $size;
	}


	$html = '<svg class="icon icon--shortcode' . $size . '"><use xlink:href="#'. $type .'" /></svg>';
	return $html;
}
add_shortcode( 'icon', 'ah_shortcode_icon' );
