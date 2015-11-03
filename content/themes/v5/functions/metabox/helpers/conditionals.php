<?php
/**
 * Metabox Conditionals
 * Check if meta boxes is included
 *
 * @return bool
 */
if ( ! function_exists( 'rw_maybe_include' ) ) {
	function rw_maybe_include( $conditions, $bool = -1 ) {
		// Include in back-end only
		if ( ! defined( 'WP_ADMIN' ) || ! WP_ADMIN ) {
			return false;
		}

		// Always include for ajax
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}

		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
		}
		elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		}
		else {
			$post_id = false;
		}

		$post_id = (int) $post_id;
		$post    = get_post( $post_id );

		if ( ! is_object( $post ) ) {
			return;
		}

		switch ( $bool ) {
			// if we're including (only_on)
			case 1:
				foreach ( $conditions as $cond => $v ) {
					// Catch non-arrays too
					if ( ! is_array( $v ) ) {
						$v = array( $v );
					}

					switch ( $cond ) {
						case 'id':
							if ( in_array( $post_id, $v ) ) {
								return true;
							}
						break;
						case 'parent':
							$post_parent = $post->post_parent;
							if ( in_array( $post_parent, $v ) ) {
								return true;
							}
						break;
						case 'slug':
							$post_slug = $post->post_name;
							if ( in_array( $post_slug, $v ) ) {
								return true;
							}
						break;
						case 'template':
							$template = get_post_meta( $post_id, '_wp_page_template', true );
							if ( in_array( $template, $v ) ) {
								return true;
							}
						break;
						case 'is_meta':
							$true = 1;
							foreach ( $v as $_key => $_value ) {
								$_meta = get_post_meta( $post_id, $_key, true );

								if ( $_meta != $_value ) {
									$true = 0;
								} else {
									wp_die( es_preit( array( $post_id, $_meta, $_value, $_key ), true ) );
								}
							}

							if ( $true ) {
								return true;
							}
						break;
						case 'post_format':
							$pf = get_post_format( $post_id );
							if ( in_array( $pf, $v ) ) {
								return true;
							}
							return false;
						break;
					}
				}
				break;
			// when we're excluding (not_on)
			case 0:
				foreach ( $conditions as $cond => $v ) {
					// Catch non-arrays too
					if ( ! is_array( $v ) ) {
						$v = array( $v );
					}

					switch ( $cond ) {
						case 'id':
							if ( ! in_array( $post_id, $v ) ) {
								return true;
							}
						break;
						case 'parent':
							$post_parent = $post->post_parent;
							if ( ! in_array( $post_parent, $v ) ) {
								return true;
							}
						break;
						case 'slug':
							$post_slug = $post->post_name;
							if ( ! in_array( $post_slug, $v ) ) {
								return true;
							}
						break;
						case 'template':
							$template = get_post_meta( $post_id, '_wp_page_template', true );
							if ( ! in_array( $template, $v ) ) {
								return true;
							}
						break;
						case 'post_format':
							$pf = get_post_format( $post_id );
							if ( ! in_array( $pf, $v ) ) {
								return true;
							}
							return false;
						break;
					}
				}
				break;
			default:
				return true;
		}

		// If no condition matched
		return false;
	}
}
