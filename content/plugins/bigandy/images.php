<?php

/* Changing the [gallery] shortcode */

/* see gallery_shortcode() in wp-includes/media.php */

remove_shortcode( 'gallery', 'gallery_shortcode' );

add_shortcode('gallery', 'gallery_shortcode_ah_new');

/**
 * The Gallery shortcode.
 *
 * This implements the functionality of the Gallery Shortcode for displaying
 * WordPress images on a post.
 *
 * @since 2.5.0
 *
 * @param array $attr Attributes of the shortcode.
 * @return string HTML content to display gallery.
 */
function gallery_shortcode_ah_new($attr) {
	$post = get_post();

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => '',
		'icontag'    => '',
		'captiontag' => '',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'ids'        => '',
		'include'    => '',
		'exclude'    => '',
		'link'		 => 'file',
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty( $ids ) ) {
		// 'ids' is explicitly ordered
		$orderby = 'post__in';
		$include = $ids;
	}

	if ( !empty($include) ) {
		$_attachments = get_posts( array(
			'include' => $include, 
			'post_status' => 'inherit', 
			'post_type' => 'attachment', 
			'post_mime_type' => 'image', 
			'order' => $order, 
			'orderby' => $orderby
			) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$attachments = get_children( array(
				'post_parent' => $id, 
				'exclude' => $exclude, 
				'post_status' => 'inherit', 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image', 
				'order' => $order, 
				'orderby' => $orderby
				) );
	} else {
		$attachments = get_children( array(
			'post_parent' => $id, 
			'post_status' => 'inherit', 
			'post_type' => 'attachment', 
			'post_mime_type' => 'image', 
			'order' => $order, 
			'orderby' => $orderby
			) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<div class='gallery columns-{$columns}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		// $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);
		$link = wp_get_attachment_link($id, $size, false, false);

		$output .= $link;
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
	}

	$output .= "</div>\n";

	return $output;
}



// Altering the image_downsize function
// http://wordpress.stackexchange.com/questions/29881/stop-wordpress-from-hardcoding-img-width-and-height-attributes

function ah_image_downsize( $value = false, $id, $size ) {
	if ( !wp_attachment_is_image( $id ) )
		return false;

	$img_url = wp_get_attachment_url( $id );
	$is_intermediate = false;
	$img_url_basename = wp_basename( $img_url );

	// try for a new style intermediate size
	if ( $intermediate = image_get_intermediate_size( $id, $size ) ) {
		$img_url = str_replace( $img_url_basename, $intermediate['file'], $img_url );
		$is_intermediate = true;
	}
	elseif ( $size == 'thumbnail' ) {
		// Fall back to the old thumbnail
		if ( ( $thumb_file = wp_get_attachment_thumb_file( $id ) ) && $info = getimagesize( $thumb_file ) ) {
			$img_url = str_replace( $img_url_basename, wp_basename( $thumb_file ), $img_url );
			$is_intermediate = true;
		}
	}

	// We have the actual image size, but might need to further constrain it if content_width is narrower
	if ( $img_url ) {
		return array( $img_url, 0, 0, $is_intermediate );
	}
	return false;
}

add_filter( 'image_downsize', 'ah_image_downsize', 1, 3 );

/* END [gallery] */

// Remove <img> class and title in [gallery]
// http://wordpress.org/support/topic/wp_get_attachment_image_attributes-filter-not-working
function remove_img_title( $atts ) {
	unset( $atts['title'] );
	unset( $atts['class'] );
	return $atts;
}
add_filter( 'wp_get_attachment_image_attributes', 'remove_img_title', 10, 4 );

// remove title attribute from <a> in [gallery]
// modified from this post : http://oikos.org.uk/2011/09/tech-notes-using-resized-images-in-wordpress-galleries-and-lightboxes/
function ah_get_attachment_link_filter( $content ) {

	$new_content = preg_replace( '/title=\'(.*?)\'/', '', $content );
	return $new_content;
}
add_filter( 'wp_get_attachment_link', 'ah_get_attachment_link_filter', 10, 4 );

// remove class and title attribute from <img> in content
// http://wordpress.org/support/topic/remove-image-title-popup

function nuke_title_attribute( $output ) {
	$output = preg_replace( '/(title)=\"(.*?)\"/', '', $output );
	return $output;
}
add_filter( 'the_content', 'nuke_title_attribute' );

function ah_remove_title_attributes( $link ) {

	$link = preg_replace( '` title="(.+)"`', '', $link );

	return $link;
}
// For Page lists
add_filter( 'wp_list_pages', 'nuke_title_attribute' );
// For category lists
add_filter( 'wp_list_categories', 'nuke_title_attribute' );
// For archives
add_filter( 'get_archives_link', 'nuke_title_attribute' );
// For tag clouds
add_filter( 'wp_tag_cloud', 'nuke_title_attribute' );
// For post category links
add_filter( 'the_category', 'nuke_title_attribute' );
add_filter( 'edit_post_link', 'nuke_title_attribute' );
// For edit comment links
add_filter( 'edit_comment_link', 'nuke_title_attribute' );
add_filter( 'the_author_posts_link', 'nuke_title_attribute' );
add_filter( 'get_archives_link', 'nuke_title_attribute' );
