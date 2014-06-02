<?php

add_theme_support('post-thumbnails');

add_image_size('pic-max', 1400, 9999, false);
add_image_size('pic-large', 1000, 9999, false);
add_image_size('pic-medium', 750, 9999, false);
add_image_size('pic-small', 500, 9999, false);

/**
 * Adds the medium and the full to the image size list in the editor, so people can
 * only insert them into pages and articles.
 */
function ah_add_additional_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) ) {
		return $sizes;
	}

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) ) {
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
	}

	return $sizes;
}

add_filter( 'image_size_names_choose', 'ah_add_additional_image_sizes' );


if (function_exists('picturefill_wp_add_image_size')) {
	picturefill_wp_add_image_size('size1', 1050, 999, false, 'medium');
	picturefill_wp_add_image_size('size2', 1550, 999, false, 'large');
	picturefill_wp_add_image_size('size3', 550, 999, false, 'small');
}

add_theme_support('html5', array( 'gallery' ));




function ah_get_extra_thumbnail_sizes(){
    global $_wp_additional_image_sizes;

 	$sizes = array();

 	// remove first 3 i.e. the default sizes 'thumbnail', 'medium', and 'large'
 	$sliced_sizes = array_slice(get_intermediate_image_sizes(), 3);

		foreach( $sliced_sizes as $s ){
			$sizes[ $s ] = array( 0, 0 );

		if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
					$sizes[ $s ] = $_wp_additional_image_sizes[ $s ]['width'];
		}
	}

		return $sizes;
 }

function ah_get_output_picture ($id, $class = '') {
	$sizes = ah_get_extra_thumbnail_sizes();

	if ($class) {
		$picture_class = 'class="'.$class.'"';
	} else {
		$picture_class = '';
	}

	$html = '<picture '.$picture_class.'>';
    	foreach ($sizes as $size => $key) {
	    	$thumb = wp_get_attachment_image_src($id, $size);
    		$html .= '<source media="(min-width: '.$key.'px)" srcset="'.$thumb[0].'"></source>';
    	}

    	$fallback_thumb = wp_get_attachment_image_src($id, 'large');
    	$html .= '<img src="'.$fallback_thumb[0].'" />';
	$html .= '</picture>';
	return $html;
}

function ah_output_picture ($id, $class = '') {
	echo ah_get_output_picture($id, $class);
}

function ah_featured_picture_replacement () {
	$post_thumbnail_id = get_post_thumbnail_id();

	ah_output_picture($post_thumbnail_id);
}


function ah_picture_shortcode( $atts, $content ) {
	 extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );


	if ($id) {
		return ah_output_picture($id);
	}

}
add_shortcode( 'picture', 'ah_picture_shortcode' );

function ah_replace_content_img_with_picture( $content ) {

	if ( is_page_template('templates/template-picture.php') && is_singular() && is_main_query()) {
	    $regex = "/<img (.*?)class=\"((.*?)wp-image-(\d+)(.*?))\" (alt=\"(.*?)\")((.*?)width=\"(\d+)\"(.*?))\/>/i";

	    // echo $content;

	    preg_match_all("/<img (.*?)class=\"((.*?)wp-image-(\d+)(.*?))\"(.*?)>/", $content, $matches);

	    // ah_preit($matches);

	    foreach ($matches[0] as $key => $imgstring) {
	        // If the width of the image is larger than 1024

	            // let's construct the image itself
	            $id = $matches[4][$key];
	            $class = $matches[2][$key];


	            $imgstring = $matches[0][$key];
	            $string = ah_get_output_picture($id,$class);

	            // let's replace the original one
	            $content = str_replace($imgstring, $string, $content);
	    }

	    return $content;
	}
	return $content;
}
add_filter( 'the_content', 'ah_replace_content_img_with_picture' );



