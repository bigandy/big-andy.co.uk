<?php
function ah_get_output_resp_image( $id, $class = '', $singular = false, $lazyload = false, $width = false, $height = false ) {

	$fallback_thumb = wp_get_attachment_image_src( $id, 'large' );
	$fallback_thumb = str_replace( 'https://res.cloudinary.com/https-big-andy-co-uk/image/upload/', 'https://res.cloudinary.com/https-big-andy-co-uk/image/upload/f_auto/', $fallback_thumb );

	// if ( false === $lazyload ) {
	// 	$html = '<img src="' . $fallback_thumb[0] . '" srcset="';
	// } else {
	// 	$html = '<img data-src="' . $fallback_thumb[0] . '" data-srcset="';
	// }

	// $count = 0;
	// foreach ( $sizes as $size => $key ) {
	// 	$thumb = wp_get_attachment_image_src( $id, $size );

	// 	$divider = (0 === $count) ? ''  : ', ';

	// 	$html .= $divider .  $thumb[0] . ' ' . $key . 'w';
	// 	$count++;
	// }

	// $html .= $image_class . $output_height . $output_width;
	// $html .= ' />';
	return $html;
}


function ah_featured_resp_image_replacement( $class ) {
	$post_thumbnail_id = get_post_thumbnail_id();
	ah_output_resp_img( $post_thumbnail_id, $class );
}
