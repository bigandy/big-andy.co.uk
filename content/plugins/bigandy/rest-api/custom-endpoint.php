<?php

/**
 * Class for handling Links in the REST API.
 *
 * @since 1.0.0
 */
class AH_REST_Link_Controller extends WP_REST_Controller {

	/**
	 * The base to use in the API route.
	 *
	 * @var string
	 */
	protected $base = 'health';

	/**
	 * The namespace for these routes.
	 *
	 * @var string
	 */
	protected $namespace = 'bigandy/v2';

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		// Register the general endpoint route.
		register_rest_route( $this->namespace, "/{$this->base}", array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => array(
					'hide_invisible' => array(
						'default'           => true,
						'sanitize_callback' => array( $this, 'sanitize_hide_invisible' ),
					),
				),
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'manage_links_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );

		// Register the individual object endpoint route.
		register_rest_route( $this->namespace, "/{$this->base}/(?P<id>[\d]+)", array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'get_item_permissions_check' ),
				'args'                => array(
					'context' => array(
						'default' => 'view',
					),
				),
			),
			array(
				'methods'              => WP_REST_Server::EDITABLE,
				'callback'             => array( $this, 'update_item' ),
				'permissions_callback' => array( $this, 'manage_links_check' ),
				'args'                 => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
			),
			array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_item' ),
				'permission_callback' => array( $this, 'manage_links_check' ),
				'args'                => array(
					'force' => array(
						'default' => false,
					),
				),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Get a collection of items.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$args = array(
			'hide_invisible' => (bool) $request['hide_invisible'],
		);

		$links = get_bookmarks( $args );
		$return = array();

		foreach ( $links as $link_object ) {
			$data     = $this->prepare_item_for_response( $link_object, $request );
			$return[] = $this->prepare_response_for_collection( $data );
		}

		$response = rest_ensure_response( $return );
		$response->header( 'X-WP-Total', count( $links ) );

		return $response;
	}

	/**
	 * Get one item from the collection.
	 *
	 * @param array|WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item( $request ) {
		$post_id   = (int) $request['id'];

		$comments = get_post_meta( $post_id, '_ah_health_comments', true );
		$weight = get_post_meta( $post_id, '_ah_health_weight', true );

		$response = [
			'date' => get_the_date( 'd.m.Y', $post_id ),
			'weight' => ( $weight ) ? $weight : null,
			'comments' => ( $comments ) ? $comments : null,
		];

		if ( is_wp_error( $check ) ) {
			return $check;
		}

		return rest_ensure_response( $this->prepare_item_for_response( $response, $request ) );
	}

	/**
	 * Create one item from the collection.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		if ( ! function_exists( 'wp_insert_link' ) ) {
			jpry_require_once_file( ABSPATH . 'wp-admin/includes/bookmark.php' );
		}

		if ( ! empty( $request['id'] ) ) {
			return new WP_Error(
				'rest_link_exists',
				__( 'Cannot create existing link.', 'rest-api-link-manager' ),
				array( 'status' => 400 )
			);
		}

		$link = $this->prepare_item_for_database( $request );
		if ( is_wp_error( $link ) ) {
			return $link;
		}

		$link_id = wp_insert_link( $link, true );
		if ( is_wp_error( $link_id ) ) {

			if ( in_array( $link_id->get_error_code(), array( 'db_insert_error' ) ) ) {
				$link_id->add_data( array( 'status' => 500 ) );
			} else {
				$link_id->add_data( array( 'status' => 400 ) );
			}

			return $link_id;
		}
		$link['link_id'] = $link_id;

		/**
		 * Fires after a single link is created or updated via the REST API.
		 *
		 * @param array           $link     The inserted link data.
		 * @param WP_REST_Request $request  Request object.
		 * @param bool            $creating True when creating a link, false when updating.
		 */
		do_action( 'rest_insert_link', $link, $request, true );

		$response = rest_ensure_response( $this->get_item( array(
			'id'      => $link_id,
			'context' => 'edit',
		) ) );
		$response->set_status( 201 );
		$response->header( 'Location', rest_url( "{$this->namespace}/{$this->base}/{$link_id}" ) );

		return $response;
	}

	/**
	 * Update one item from the collection.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item( $request ) {
		if ( ! function_exists( 'wp_update_link' ) ) {
			jpry_require_once_file( ABSPATH . 'wp-admin/includes/bookmark.php' );
		}

		$id = (int) $request['id'];

		$check = $this->check_link_valid( $id );
		if ( is_wp_error( $check ) ) {
			return $check;
		}

		$link = $this->prepare_item_for_database( $request );
		if ( is_wp_error( $link ) ) {
			return $link;
		}

		$link_id = wp_update_link( $link );
		if ( is_wp_error( $link_id ) ) {

			if ( in_array( $link_id->get_error_code(), array( 'db_insert_error' ) ) ) {
				$link_id->add_data( array( 'status' => 500 ) );
			} else {
				$link_id->add_data( array( 'status' => 400 ) );
			}

			return $link_id;
		}

		/** This action is documented in class-jpry-rest-link-controller.php */
		do_action( 'rest_insert_link', $link, $request, false );

		return $this->get_item( array(
			'id'      => $link_id,
			'context' => 'edit',
		) );
	}

	/**
	 * Delete one item from the collection.
	 *
	 * Without the 'force' parameter set to true, a link will only be set to hidden
	 * instead of deleted.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item( $request ) {
		if ( ! function_exists( 'wp_delete_link' ) ) {
			jpry_require_once_file( ABSPATH . 'wp-admin/includes/bookmark.php' );
		}

		$id    = (int) $request['id'];
		$force = (bool) $request['force'];
		$link  = get_bookmark( $id );
		$check = $this->check_link_valid( $id );
		if ( is_wp_error( $check ) ) {
			return $check;
		}

		// Grab the original object to pass back in the response.
		$request = new WP_REST_Request( 'GET', "/{$this->namespace}/{$this->base}/{$id}" );
		$request->set_param( 'context', 'edit' );
		$response = rest_do_request( $request );

		// Delete permanently if we're forcing.
		if ( $force ) {
			$result = wp_delete_link( $id );
			$status = 'deleted';
		} else {
			if ( isset( $link->link_visible ) && 'N' === $link->link_visible ) {
				return new WP_Error(
					'rest_already_deleted',
					__( 'The link has already been deleted.', 'rest-api-link-manager' ),
					array( 'status' => 410 )
				);
			}

			$result = wp_update_link( array(
				'link_id'      => $id,
				'link_visible' => 'N',
			) );
			$status = 'hidden';
		}

		if ( ! $result ) {
			return new WP_Error(
				'rest_cannot_delete',
				__( 'The link cannot be deleted.', 'rest-api-link-manager' ),
				array( 'status' => 500 )
			);
		}

		$data = $response->get_data();
		$data = array(
			'data'  => $data,
			$status => true,
		);
		$response->set_data( $data );

		/**
		 * Fires after a single link is deleted or hidden via the REST API.
		 *
		 * @param stdClass        $link    The deleted or hidden link.
		 * @param array           $data    The response data.
		 * @param WP_REST_Request $request The request sent to the API.
		 */
		do_action( 'rest_delete_link', $link, $data, $request );

		return $response;
	}

	/**
	 * Check if a given request has access to get items.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		if ( ! $this->check_can_manage_links( $request ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to edit links.', 'rest-api-link-manager' ),
				array( 'status' => 403 )
			);
		}

		return true;
	}

	/**
	 * Check if a given request has access to get a specific item.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		if ( ! $this->check_can_manage_links( $request ) ) {
			return new WP_Error(
				'rest_forbidden_context',
				__( 'Sorry, you are not allowed to edit links.', 'rest-api-link-manager' ),
				array( 'status' => 403 )
			);
		}

		$link = get_bookmark( $request['id'] );
		if ( $link ) {
			return $this->check_link_visible( $request['id'] );
		}

		return true;
	}

	/**
	 * Determine if the current user can manage links.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function manage_links_check( $request ) {
		return current_user_can( 'manage_links' );
	}

	/**
	 * Prepare the item for create or update operation.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_Error|array $prepared_item
	 */
	protected function prepare_item_for_database( $request ) {
		$prepared_link = array();
		$schema        = $this->get_item_schema();

		foreach ( $schema['properties'] as $field => $data ) {
			$field_data = null;
			$field_name = "link_{$field}";
			if ( ! isset( $request[ $field ] ) ) {
				// If the field could have a raw version, then possibly use that instead.
				if ( ! isset( $data['properties']['raw'], $request[ $field ]['raw'] ) ) {
					continue;
				}
			}

			switch ( $field ) {
				// URL fields.
				case 'url':
				case 'image':
				case 'rss':
					if ( is_string( $request[ $field ] ) ) {
						$field_data = esc_url_raw( $request[ $field ] );
					}
					break;

				// Fields that could have RAW data.
				case 'name':
					if ( is_string( $request[ $field ] ) ) {
						$field_data = wp_filter_post_kses( $request[ $field ] );
					} elseif ( isset( $request['name']['raw'] ) ) {
						$field_data = wp_filter_post_kses( $request['name']['raw'] );
					}
					break;

				// Convert author to link owner.
				case 'author':
					$field_name = 'link_owner';
					$field_data = $this->handle_author_param( (int) $request[ $field ] );
					break;

				// Fields that should be an integer.
				case 'rating':
				case 'id':
					if ( is_numeric( $request[ $field ] ) ) {
						$field_data = (int) $request[ $field ];
					}
					break;

				// Convert the raw categories into array of IDs.
				case 'raw_categories':
					$field_name = 'link_category';
					$field_data = $this->handle_raw_categories( $request[ $field ] );
					break;

				// Everything else should be a string.
				default:
					if ( is_string( $request[ $field ] ) ) {
						$field_data = wp_filter_post_kses( $request[ $field ] );
					}
			}

			/**
			 * Filter the data for a field before it is inserted into the database.
			 *
			 * This filter assumes that the returned data has been properly escaped for insertion
			 * into the database, so be careful with return values. The dynamic part of this filter,
			 * $field, refers to the field name from the schema.
			 *
			 * @param mixed           $field_data The data for the field.
			 * @param WP_REST_Request $request    Full details about the request.
			 */
			$field_data = apply_filters( "rest_prepare_link_{$field}", $field_data, $request );

			if ( is_wp_error( $field_data ) ) {
				return $field_data;
			}

			$prepared_link[ $field_name ] = $field_data;
		}

		/**
		 * Filter the arguments used to insert a new link.
		 *
		 * @param array           $prepared_link The array of data used to create a new link.
		 * @param WP_REST_Request $request       The Request object.
		 */
		return apply_filters( 'rest_pre_insert_link', $prepared_link, $request );
	}

	/**
	 * Prepare the item for the REST response.
	 *
	 * @param stdClass        $item    WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		$data = array(
			'id'          => $item->link_id,
			'url'         => $item->link_url,
			'name'        => $item->link_name,
			'image'       => $item->link_image,
			'target'      => $item->link_target,
			'description' => $item->link_description,
			'visible'     => $item->link_visible,
			'author'      => $item->link_owner,
			'rating'      => $item->link_rating,
			'updated'     => $this->prepare_date_response( $item->link_updated ),
			'rel'         => $item->link_rel,
			'notes'       => $item->link_notes,
			'rss'         => $item->link_rss,
			'categories'  => $this->prepare_category_response( $item ),
		);

		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->filter_response_by_context( $data, $context );
		$data    = rest_ensure_response( $data );

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		if ( method_exists( $data, 'add_links' ) ) {
			$data->add_links( $this->prepare_links( $item ) );
		}

		return $data;
	}

	/**
	 * Get the item's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'links',
			'type'       => 'object',
			'properties' => array(
				'id'             => array(
					'description' => 'Unique identifier for the object.',
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'url'            => array(
					'description' => 'URL to the object.',
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit', 'embed' ),
					'arg_options' => array(
						'required' => true,
					),
				),
				'name'           => array(
					'description' => 'The name of the object.',
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'arg_options' => array(
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					),
					'properties'  => array(
						'raw'      => array(
							'description' => 'Name of the object, as it exists in the database.',
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'rendered' => array(
							'description' => 'Name of the object, transformed for display.',
							'type'        => 'string',
							'context'     => array( 'view', 'edit', 'embed' ),
						),
					),
				),
				'image'          => array(
					'description' => 'URL to an image to be displayed with the object.',
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'target'         => array(
					'description' => 'Target frame for the object.',
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'enum'        => array( 'none', '_top', '_blank' ),
				),
				'description'    => array(
					'description' => 'Description of the object.',
					'type'        => 'string',
					'context'     => array( 'view', 'edit', 'embed' ),
					'arg_options' => array(
						'sanitize_callback' => 'wp_filter_post_kses',
					),
				),
				'visible'        => array(
					'description' => 'Whether the object is publicly visible.',
					'type'        => 'string',
					'context'     => array( 'edit' ),
					'enum'        => array( 'Y', 'N' ),
					'arg_options' => array(
						'default' => 'Y',
					),
				),
				'author'         => array(
					'description' => 'The ID for the author of the object.',
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
				),
				'rating'         => array(
					'description' => 'A ranking of the object.',
					'type'        => 'integer',
					'context'     => array( 'view', 'edit' ),
					'enum'        => range( 0, 10 ),
				),
				'updated'        => array(
					'description' => "The date the object was last updated, in the site's timezone.",
					'type'        => 'string',
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'rel'            => array(
					'description' => 'The XFN Relationship for the object.',
					'type'        => 'string',
					'context'     => array( 'view', 'edit' ),
					'arg_options' => array(
						'sanitize_callback' => array( $this, 'sanitize_link_rels' ),
					),
				),
				'notes'          => array(
					'description' => 'Internal notes to store about the object.',
					'type'        => 'string',
					'context'     => array( 'edit' ),
				),
				'rss'            => array(
					'description' => 'URL of the RSS feed associated with the object.',
					'type'        => 'string',
					'format'      => 'uri',
					'context'     => array( 'view', 'edit' ),
				),
				'categories'     => array(
					'description' => 'Array of categories associated with the object.',
					'type'        => 'array',
					'context'     => array( 'view', 'edit' ),
					'readonly'    => true,
				),
				'raw_categories' => array(
					'description' => 'Array of raw category data to assign to the object.',
					'type'        => 'array',
					'context'     => array( 'edit' ),
					'properties'  => array(
						'name'        => array(
							'description' => 'The front-facing name of the link category.',
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'slug'        => array(
							'description' => 'The URL-friendly version of the link category.',
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
						'description' => array(
							'description' => 'A description for the link category.',
							'type'        => 'string',
							'context'     => array( 'edit' ),
						),
					),
				),
			),
		);

		return $schema;
	}

	/**
	 * Check whether a given link is publicly visible.
	 *
	 * @param int $link_id The link ID to check.
	 *
	 * @return bool Whether the link is publicly visible.
	 */
	public function check_link_visible( $link_id ) {
		$visible = get_bookmark_field( 'link_visible', $link_id, 'raw' );

		return is_string( $visible ) && 'Y' === strtoupper( $visible );
	}

	/**
	 * Check whether the current user can manage links.
	 *
	 * This only applies to the 'edit' context. For all other contexts, this will simply
	 * return true.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return bool
	 */
	public function check_can_manage_links( $request ) {
		if ( 'edit' === $request['context'] ) {
			return $this->manage_links_check( $request );
		}

		return true;
	}

	/**
	 * Strip invalid XFN strings from rel links.
	 *
	 * Note that this does not validate whether one or more XFN strings are valid when used together.
	 *
	 * @param string|array $rels A single string of XFN strings separated by spaces and/or commas,
	 *                           or an array of XFN strings.
	 *
	 * @return string Space-separated XFN strings with invalid options removed.
	 */
	public function sanitize_link_rels( $rels ) {
		$valid_rels = array(
			'acquaintance' => 1,
			'friend'       => 1,
			'met'          => 1,
			'co-worker'    => 1,
			'colleague'    => 1,
			'co-resident'  => 1,
			'neighbor'     => 1,
			'child'        => 1,
			'parent'       => 1,
			'sibling'      => 1,
			'spouse'       => 1,
			'muse'         => 1,
			'crush'        => 1,
			'date'         => 1,
			'sweetheart'   => 1,
		);

		if ( ! is_array( $rels ) ) {
			$rels = preg_split( '#[\s,]+#', $rels );
		}

		foreach ( $rels as $key => $value ) {
			if ( ! isset( $valid_rels[ $value ] ) ) {
				unset( $rels[ $key ] );
			}
		}

		/**
		 * Filter the array of rels to attach to a certain link.
		 *
		 * @param array $rels The array of rels that will be attached to the link.
		 */
		$rels = apply_filters( 'sanitize_rest_link_rels', $rels );

		return implode( ' ', $rels );
	}

	/**
	 * Sanitize the 'hide_invisible' value.
	 *
	 * This will ensure that only users who can manage links are able to show invisible links.
	 *
	 * @param mixed $value The passed value.
	 *
	 * @return bool The sanitized value.
	 */
	public function sanitize_hide_invisible( $value ) {
		if ( ! current_user_can( 'manage_links' ) ) {
			$value = true;
		}

		return (bool) $value;
	}

	/**
	 * Get the route base.
	 *
	 * @return string
	 */
	public function get_base() {
		return $this->base;
	}

	/**
	 * Check the post_date_gmt or modified_gmt and prepare any post or
	 * modified date for single post output.
	 *
	 * @param string|null $date
	 *
	 * @return string|null ISO8601/RFC3339 formatted datetime.
	 */
	protected function prepare_date_response( $date ) {
		if ( '0000-00-00 00:00:00' === $date ) {
			return null;
		}

		return mysql_to_rfc3339( $date );
	}

	/**
	 * Prepare the link_category items for a given link.
	 *
	 * This will simply give an array of category names.
	 *
	 * @param stdClass $item
	 *
	 * @return array
	 */
	protected function prepare_category_response( $item ) {
		return wp_get_object_terms( $item->link_id, 'link_category', array( 'fields' => 'names' ) );
	}

	/**
	 * Prepare links for an individual link object.
	 *
	 * @param $link_object
	 *
	 * @return array Links for the given link object.
	 */
	protected function prepare_links( $link_object ) {
		$base  = "/{$this->namespace}/{$this->base}";
		$links = array(
			'self'       => array(
				'href' => rest_url( "{$base}/{$link_object->link_id}" ),
			),
			'collection' => array(
				'href' => rest_url( $base ),
			),
		);

		// Handle the link owner.
		$links['author'] = array(
			'href'       => rest_url( "/wp/v2/users/{$link_object->link_owner}" ),
			'embeddable' => true,
		);

		// Handle taxonomies.
		$taxonomies = get_object_taxonomies( 'link' );
		if ( ! empty( $taxonomies ) ) {
			$links['https://api.w.org/term'] = array();

			foreach ( $taxonomies as $tax ) {
				$taxonomy_obj = get_taxonomy( $tax );
				// Skip taxonomies that are not public.
				if ( false === $taxonomy_obj->public ) {
					continue;
				}

				$tax_base  = ! empty( $taxonomy_obj->rest_base ) ? $taxonomy_obj->rest_base : $tax;
				$terms_url = rest_url( "{$base}/{$link_object->link_id}/terms/{$tax_base}" );

				$links['https://api.w.org/term'][] = array(
					'href'       => $terms_url,
					'taxonomy'   => $tax,
					'embeddable' => true,
				);
			}
		}

		return $links;
	}

	/**
	 * Determine valididty of a provided author param.
	 *
	 * @param int $author
	 *
	 * @return int|WP_Error
	 */
	protected function handle_author_param( $author ) {
		if ( get_current_user_id() !== $author ) {
			$author = get_userdata( $author );

			if ( ! $author ) {
				return new WP_Error(
					'rest_invalid_author',
					__( 'Invalid author ID.', 'rest-api-link-manager' ),
					array( 'status' => 400 )
				);
			}
		}

		return $author;
	}

	/**
	 * Convert raw link_category data into an array of IDs suitable for wp_insert_link().
	 *
	 * Each individual category array should have these keys:
	 *
	 * - name
	 * - slug
	 * - description
	 *
	 * @param array $raw_categories Multidimensional array of raw link_category data.
	 *
	 * @return array|WP_Error
	 */
	protected function handle_raw_categories( $raw_categories ) {
		$cat_ids = array();
		foreach ( $raw_categories as $raw_category ) {
			// Ensure each array has the keys we need.
			$raw_category = wp_parse_args( $raw_category, array(
				'name'        => '',
				'slug'        => '',
				'description' => '',
			) );

			// If we already have a term, use its ID. Otherwise, insert it and use the new ID.
			$cat = get_term_by( 'slug', $raw_category['slug'], 'link_category' );
			if ( $cat ) {
				$cat_ids[] = $cat->term_taxonomy_id;
			} else {
				$result = wp_insert_term( $raw_category['name'], 'link_category', array(
					'slug'        => $raw_category['slug'],
					'description' => $raw_category['description'],
				) );

				if ( is_wp_error( $result ) ) {
					return $result;
				}

				$cat_ids[] = $result['term_taxonomy_id'];
			}
		}

		return $cat_ids;
	}

	/**
	 * Determine if a single link is valid for display or editing.
	 *
	 * @param int $id The link ID to check.
	 *
	 * @return bool|WP_Error True for a valid link, WP_Error on failure.
	 */
	public function check_link_valid( $id ) {
		$link = get_bookmark( $id );

		ah_preit($id);

		if ( null === $link || empty( $link ) ) {
			return new WP_Error(
				'rest_link_invalid_id',
				__( 'Invalid link ID.', 'rest-api-link-manager' ),
				array( 'status' => 404 )
			);
		}

		// Ensure that a hidden link isn't displayed to a non-authenticated user.
		if ( isset( $link->link_visible ) && 'Y' !== $link->link_visible && ! current_user_can( 'manage_links' ) ) {
			return new WP_Error(
				'rest_link_invalid_id',
				__( 'Invalid link ID.', 'rest-api-link-manager' ),
				array( 'status' => 404 )
			);
		}

		return true;
	}

	/**
	 * Find a link ID given the URL.
	 *
	 * @param string $url The URL to search for.
	 *
	 * @return null|string
	 */
	public function find_link_by_url( $url ) {
		/** @var wpdb */
		global $wpdb;

		$id = $wpdb->get_var( $wpdb->prepare(
			"SELECT link_id FROM {$wpdb->links} WHERE link_url = %s",
			$url
		) );

		return $id;
	}
}
