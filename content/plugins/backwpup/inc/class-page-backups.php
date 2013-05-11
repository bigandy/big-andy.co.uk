<?php
/**
 *
 */
class BackWPup_Page_Backups extends WP_List_Table {

	private static $listtable = NULL;

	/**
	 * @var int
	 */
	private $jobid = 1;
	/**
	 * @var string
	 */
	private $dest = 'FOLDER';

	/**
	 *
	 */
	function __construct() {

		parent::__construct( array(
								  'plural'   => 'backups',
								  'singular' => 'backup',
								  'ajax'     => TRUE
							 ) );
	}

	/**
	 * @return bool
	 */
	function ajax_user_can() {

		return current_user_can( 'backwpup_backups' );
	}

	/**
	 *
	 */
	function prepare_items() {

		$destinations=BackWPup::get_destinations();

		$per_page = $this->get_items_per_page( 'backwpupbackups_per_page' );
		if ( empty( $per_page ) || $per_page < 1 )
			$per_page = 20;

		if ( isset( $_GET[ 'jobdest' ] ) ) {
			$jobdest = $_GET[ 'jobdest' ];
		}
		else {
			$jobdests = $this->get_destinations_list();
			if ( empty( $jobdests ) )
				$jobdests = array( '_' );
			$jobdest           = $jobdests[ 0 ];
			$_GET[ 'jobdest' ] = $jobdests[ 0 ];
		}

		list( $this->jobid, $this->dest ) = explode( '_', $jobdest );

		if ( isset($destinations[ $this->dest ] ) && is_object( $destinations[ $this->dest ] ) )
			$this->items = $destinations[ $this->dest ]->file_get_list( $jobdest );

		//if no items brake
		if ( ! $this->items ) {
			$this->items = '';

			return;
		}

		//Sorting
		$order   = isset( $_GET[ 'order' ] ) ? $_GET[ 'order' ] : 'desc';
		$orderby = isset( $_GET[ 'orderby' ] ) ? $_GET[ 'orderby' ] : 'time';
		$tmp     = Array();
		if ( $orderby == 'time' ) {
			if ( $order == 'asc' ) {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "time" ];
				}
				array_multisort( $tmp, SORT_ASC, $this->items );
			}
			else {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "time" ];
				}
				array_multisort( $tmp, SORT_DESC, $this->items );
			}
		}
		elseif ( $orderby == 'file' ) {
			if ( $order == 'asc' ) {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "filename" ];
				}
				array_multisort( $tmp, SORT_ASC, $this->items );
			}
			else {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "filename" ];
				}
				array_multisort( $tmp, SORT_DESC, $this->items );
			}
		}
		elseif ( $orderby == 'folder' ) {
			if ( $order == 'asc' ) {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "folder" ];
				}
				array_multisort( $tmp, SORT_ASC, $this->items );
			}
			else {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "folder" ];
				}
				array_multisort( $tmp, SORT_DESC, $this->items );
			}
		}
		elseif ( $orderby == 'size' ) {
			if ( $order == 'asc' ) {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "filesize" ];
				}
				array_multisort( $tmp, SORT_ASC, $this->items );
			}
			else {
				foreach ( $this->items as &$ma ) {
					$tmp[ ] = & $ma[ "filesize" ];
				}
				array_multisort( $tmp, SORT_DESC, $this->items );
			}
		}

		$this->set_pagination_args( array(
										 'total_items' => count( $this->items ),
										 'per_page'    => $per_page,
										 'jobdest'     => $jobdest,
										 'orderby'     => $orderby,
										 'order'       => $order
									) );

	}

	/**
	 *
	 */
	function no_items() {
		_e( 'No files could be found. (List will be generated during next backup.)', 'backwpup' );
	}

	/**
	 * @return array
	 */
	function get_bulk_actions() {

		if ( ! $this->has_items() )
			return array ();

		$actions             = array();
		$actions[ 'delete' ] = __( 'Delete', 'backwpup' );

		return $actions;
	}

	/**
	 * @param $which
	 *
	 * @return mixed
	 */
	function extra_tablenav( $which ) {

		if ( 'top' != $which )
			return;

		$destinations_list = $this->get_destinations_list();
		if ( count( $destinations_list ) <= 1 ) {
			echo '<input type="hidden" name="jobdest" value="' . $destinations_list[0] . '">';
			
			return;
		}

		echo '<div class="alignleft actions">';
		echo "<select name=\"jobdest\" id=\"jobdest\" class=\"postform\">" . PHP_EOL;
		foreach ( $this->get_destinations_list() as $jobdest ) {
			list( $jobid, $dest ) = explode( '_', $jobdest );
			echo "\t<option value=\"" . $jobdest . "\" " . selected( $this->jobid . '_' . $this->dest, $jobdest ) . ">" . $dest . ": " . esc_html( BackWPup_Option::get( $jobid, 'name' ) ) . "</option>" . PHP_EOL;
		}

		echo "</select>" . PHP_EOL;
		submit_button( __( 'Change destination', 'backwpup' ), 'secondary', '', FALSE, array( 'id' => 'post-query-submit' ) );
		echo '</div>';
	}

	/**
	 * @return array
	 */
	function get_destinations_list() {

		$jobdest      = array();
		$jobids       = BackWPup_Option::get_job_ids();
		$destinations = BackWPup::get_destinations();

		if ( ! empty( $jobids )) {
			foreach ( $jobids as $jobid ) {
				if ( BackWPup_Option::get( $jobid, 'backuptype' ) == 'sync') // jump over sync
					continue;
				$dests = BackWPup_Option::get( $jobid, 'destinations' );
				foreach ( $dests as $dest ) {
					if ( ! isset( $destinations[ $dest ] ) )
						continue;
					$can_do_dest = $destinations[ $dest ]->file_get_list( $jobid . '_' . $dest );
					if ( ! empty($can_do_dest) )
						$jobdest[ ] = $jobid . '_' . $dest;
				}
			}
		}

		return $jobdest;
	}

	/**
	 * @return array
	 */
	function get_columns() {

		$posts_columns             = array();
		$posts_columns[ 'cb' ]     = '<input type="checkbox" />';
		$posts_columns[ 'file' ]   = __( 'File', 'backwpup' );
		$posts_columns[ 'folder' ] = __( 'Folder', 'backwpup' );
		$posts_columns[ 'size' ]   = __( 'Size', 'backwpup' );
		$posts_columns[ 'time' ]   = __( 'Time', 'backwpup' );

		return $posts_columns;
	}

	/**
	 * @return array
	 */
	function get_sortable_columns() {

		return array(
			'file'   => array( 'file', FALSE ),
			'folder' => 'folder',
			'size'   => 'size',
			'time'   => array( 'time', FALSE )
		);
	}

	/**
	 *
	 */
	function display_rows() {

		$style = '';

		foreach ( $this->items as $backup ) {
			$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
			echo PHP_EOL . "\t", $this->single_row( $backup, $style );
		}
	}

	/**
	 * @param object $backup
	 * @param string $style
	 * @return string
	 */
	function single_row( $backup, $style = '' ) {

		list( $columns, $hidden, $sortable ) = $this->get_column_info();
		$r = '<tr ' . $style . '>';

		foreach ( $columns as $column_name => $column_display_name ) {
			$class = "class=\"$column_name column-$column_name\"";

			$style = '';
			if ( in_array( $column_name, $hidden ) )
				$style = ' style="display:none;"';

			$attributes = "$class$style";

			switch ( $column_name ) {
				case 'cb':
					$r .= '<th scope="row" class="check-column"><input type="checkbox" name="backupfiles[]" value="' . esc_attr( $backup[ 'file' ] ) . '" /></th>';
					break;
				case 'file':
					$r .= "<td $attributes><strong>" . $backup[ 'filename' ] . "</strong>";
					$actions               = array();
					if ( current_user_can( 'backwpup_backups_delete' ) )
						$actions[ 'delete' ]   = "<a class=\"submitdelete\" href=\"" . wp_nonce_url( network_admin_url( 'admin.php' ) . '?page=backwpupbackups&action=delete&jobdest=' . $this->jobid . '_' . $this->dest . '&paged=' . $this->get_pagenum() . '&backupfiles[]=' . esc_attr( $backup[ 'file' ] ), 'bulk-backups' ) . "\" onclick=\"if ( confirm('" . esc_js( __( "You are about to delete this backup archive. \n  'Cancel' to stop, 'OK' to delete.", "backwpup" ) ) . "') ) { return true;}return false;\">" . __( 'Delete', 'backwpup' ) . "</a>";
					if ( current_user_can( 'backwpup_backups_download' ) )
						$actions[ 'download' ] = "<a href=\"" . wp_nonce_url( $backup[ 'downloadurl' ], 'download-backup' ) . "\">" . __( 'Download', 'backwpup' ) . "</a>";
					$r .= $this->row_actions( $actions );
					$r .= "</td>";
					break;
				case 'folder':
					$r .= "<td $attributes>";
					$r .= $backup[ 'folder' ];
					$r .= "</td>";
					break;
				case 'size':
					$r .= "<td $attributes>";
					if ( ! empty( $backup[ 'filesize' ] ) && $backup[ 'filesize' ] != - 1 ) {
						$r .= size_format( $backup[ 'filesize' ], 2 );
					}
					else {
						$r .= __( '?', 'backwpup' );
					}
					$r .= "</td>";
					break;
				case 'time':
					$r .= "<td $attributes>";
					$backup[ 'time' ] = $backup[ 'time' ] + get_option( 'gmt_offset' ) * 3600;
					$r .= sprintf( __( '%1$s at %2$s', 'backwpup' ), date_i18n( get_option( 'date_format' ), $backup[ 'time' ], TRUE ), date_i18n( get_option( 'time_format' ), $backup[ 'time' ], TRUE ) );
					$r .= "</td>";
					break;
			}
		}
		$r .= '</tr>';

		return $r;
	}

	/**
	 *
	 */
	public static function load() {

		//Create Table
		self::$listtable = new BackWPup_Page_Backups;

		switch ( self::$listtable->current_action() ) {
			case 'delete': //Delete Backup archives
				check_admin_referer( 'bulk-backups' );
				if ( ! current_user_can( 'backwpup_backups_delete' ) )
					wp_die( __( 'Sorry, you don\'t have permissions to do that.', 'backwpup') );
				$destinations = BackWpup::get_destinations();
				list( $jobid, $dest ) = explode( '_', strtoupper( $_GET[ 'jobdest' ] ) );
				foreach ( $_GET[ 'backupfiles' ] as $backupfile ) {
					$destinations[ $dest ]->file_delete( $_GET[ 'jobdest' ], $backupfile );
				}
				break;
			default:
				$dest = strtoupper( str_replace( 'download', '', self::$listtable->current_action() ) );
				if ( !empty( $dest ) && strstr( self::$listtable->current_action(), 'download') ) {
					$destinations = BackWpup::get_destinations();
					if ( ! current_user_can( 'backwpup_backups_download' ) )
						wp_die( __( 'Sorry, you don\'t have permissions to do that.', 'backwpup') );
					check_admin_referer( 'download-backup' );
					$destinations[ $dest ]->file_download( (int)$_GET[ 'jobid' ], $_GET[ 'file' ] );
					die();
				}
				break;
		}

		//Save per page
		if ( isset( $_POST[ 'screen-options-apply' ] ) && isset( $_POST[ 'wp_screen_options' ][ 'option' ] ) && isset( $_POST[ 'wp_screen_options' ][ 'value' ] ) && $_POST[ 'wp_screen_options' ][ 'option' ] == 'backwpupbackups_per_page' ) {
			check_admin_referer( 'screen-options-nonce', 'screenoptionnonce' );
			global $current_user;
			if ( $_POST[ 'wp_screen_options' ][ 'value' ] > 0 && $_POST[ 'wp_screen_options' ][ 'value' ] < 1000 ) {
				update_user_option( $current_user->ID, 'backwpupbackups_per_page', (int)$_POST[ 'wp_screen_options' ][ 'value' ] );
				wp_redirect( remove_query_arg( array( 'pagenum', 'apage', 'paged' ), wp_get_referer() ) );
				exit;
			}
		}

		add_screen_option( 'per_page', array(
											'label'   => __( 'Logs', 'backwpup' ),
											'default' => 20,
											'option'  => 'backwpupbackups_per_page'
									   ) );

		self::$listtable->prepare_items();
	}

	/**
	 *
	 * Output css
	 *
	 * @return nothing
	 */
	public static function admin_print_styles() {

		wp_enqueue_style('backwpupgeneral');

		?>
		<style type="text/css" media="screen">
			.column-size, .column-time {
				width: 10%;
			}
		</style>
		<?php
	}

	/**
	 *
	 * Output js
	 *
	 * @return void
	 */
	public static function admin_print_scripts() {

		wp_enqueue_script( 'backwpupgeneral' );
	}

	/**
	 * Display the page content
	 */
	public static function page() {

		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php echo esc_html( sprintf( __( '%s Manage Backup Archives', 'backwpup' ), BackWPup::get_plugin_data( 'name' ) ) ); ?></h2>
			<?php BackWPup_Admin::display_messages(); ?>
            <form id="posts-filter" action="" method="get">
            	<input type="hidden" name="page" value="backwpupbackups" />
				<?php self::$listtable->display(); ?>
                <div id="ajax-response"></div>
            </form>
        </div>
		<?php
	}
}

