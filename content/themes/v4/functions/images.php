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
function ba_add_additional_image_sizes( $sizes ) {
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

add_filter( 'image_size_names_choose', 'ba_add_additional_image_sizes' );


if (function_exists('picturefill_wp_add_image_size')) {
	picturefill_wp_add_image_size('size1', 1050, 999, false, 'medium');
	picturefill_wp_add_image_size('size2', 1550, 999, false, 'large');
	picturefill_wp_add_image_size('size3', 550, 999, false, 'small');
}

add_theme_support('html5', array( 'gallery' ));




function ba_get_extra_thumbnail_sizes(){
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
	$sizes = ba_get_extra_thumbnail_sizes();

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
		'url' => '',
		'id' => '',
		'class' => ''
	), $atts ) );

	if ($url) {
		echo $url;
	}

	if ($id) {
		return ah_output_picture($id, $class);
	}

	$img = $content;
	echo $content;

	// <img class="alignnone size-full wp-image-2544" src="http://big-andy.local/content/uploads/da.jpg" alt="da" width="1006" height="1500">

	// return ah_output_picture($picture_id);
}

add_shortcode( 'picture', 'ah_picture_shortcode' );







function ah_picture_content ($content) {




	if ( is_page_template('templates/template-picture.php') && is_singular() && is_main_query()) {

		// This is an example generated image markup :
		// $img_src = '<img src="http://big-andy.local/content/uploads/700.jpeg" alt="700" width="1400" height="700" class="alignnone size-full wp-image-2558">';

		// first want to get the image link http://big-andy.local/content/uploads/700.jpeg

		// then want to get the id of the image, the number in wp-image-2558

		// then want to get the other classes: alignnone and size-full


		// $return = preg_match_all($pattern, $content, $matches);
		// preg_match_all('/< *img[^>]*src *= *["\']?([^"\']*)/', $content, $src_matches);

		// preg_match_all("/wp-image-(\d*)/", $input_lines, $output_array);
		preg_match_all("/wp-image-(\d*)/", $content, $id_array);

		// preg_match_all("/class="(.*)"/", $contentss, $outputss_array);
		// preg_match_all('/< *img[^>]*class *= *["\']?([^"\']*)/', $content, $class_matches);



		// the array that I want:

		// $ideal_output = array(
		// 	'item' => array(
		// 		'source' => 'http://big-andy.local/content/uploads/700.jpeg',
		// 		'id' => '2558',
		// 		'class' => 'alignnone size-full wp-image-2558'
		// 	)
		// );

		// ba_preit($ideal_output);

		// foreach ($ideal_output[item] as $key => $value) {
		// 	echo $key . ' ' .$value . '<br />';


		// }



		// echo ah_get_output_picture($ideal_output['item']['id'], $ideal_output['item']['class']);


		$image_ids = $id_array[1];
		$html = '';
		foreach ($image_ids as $id) {
			$html .= ah_get_output_picture($id);
		}

		return $html;

		// ba_preit($classes);

		// $html = '';
		// foreach ($sources as $picture) {
		// 	echo '<picture><img src="'.$picture.'" />';
		// 	// $html .='<picture class="'$classes[0]'">';
		// }

		// echo $html;
		// return $content;


	}
	return $content;


}
add_filter( 'the_content', 'ah_picture_content' );



