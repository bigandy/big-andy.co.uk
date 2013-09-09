<?php
/**
 *
 */
class BackWPup_Page_Editjob {

	/**
	 *
	 */
	public static function auth() {

		//check $_GET[ 'tab' ]
		if ( isset($_GET[ 'tab' ] ) ) {
			$_GET[ 'tab' ] = sanitize_title_with_dashes( $_GET[ 'tab' ] );
			if ( substr( $_GET[ 'tab' ], 0, 5 ) != 'dest-' && substr( $_GET[ 'tab' ], 0, 8 ) != 'jobtype-'  && ! in_array( $_GET[ 'tab' ], array( 'job','cron' ) ) )
				$_GET[ 'tab' ] = 'job';			
		}

		if ( substr( $_GET[ 'tab' ], 0, 5 ) == 'dest-' ) {
			$destinations = BackWPup::get_destinations();
			$jobid        = (int)$_GET[ 'jobid' ];
			$id = strtoupper( str_replace( 'dest-', '', $_GET[ 'tab' ] ) );
			$destinations[ $id ]->edit_auth( $jobid );
		}
	}

	/**
	 *
	 */
	public static function load() {

		//add Help tab
		BackWPup_Help::add_tab( array(
									 'id'      => 'overview',
									 'title'   => __( 'Overview','backwpup' ),
									 'content' =>
									 '<p>' . '</p>'
								) );

	}
	/**
	 *
	 * Save Form data
	 */
	public static function save_post_form($tab, $jobid) {

		if ( ! current_user_can( 'backwpup_jobs_edit' ) )
			return __( 'Sorry, you don\'t have permissions to do that.', 'backwpup');

		$message="";
		$destinations = BackWPup::get_destinations();
		$job_types    = BackWPup::get_job_types();

		switch ( $tab ) {
			case 'job':
				BackWPup_Option::update( $jobid, 'jobid', $jobid );

				if ( isset( $_POST[ 'type' ] ) && is_array( $_POST[ 'type' ] ) ) {
					foreach ( (array)$_POST[ 'type' ] as $typeid ) {
						if ( empty( $job_types[ $typeid ] ) )
							unset( $_POST[ 'type' ][ $typeid ] );
					}
					if ( is_array( $_POST[ 'type' ] ) )
						sort( $_POST[ 'type' ] );
				} else {
					$_POST[ 'type' ]= array();
				}
				//test if job type makes backup
				/* @var BackWPup_JobTypes $job_type */
				$makes_file = FALSE;
				foreach ( $job_types as $type_id => $job_type) {
					if ( in_array( $type_id, $_POST[ 'type' ] ) ) {
						if ( $job_type->creates_file() ) {
							$makes_file = TRUE;
							break;
						}
					}
				}
				if ( ! $makes_file )
					$_POST[ 'destinations' ] = array();
				BackWPup_Option::update( $jobid, 'type', $_POST[ 'type' ] );

				if ( isset( $_POST[ 'destinations' ] ) && is_array( $_POST[ 'destinations' ] ) ) {
					foreach ( (array)$_POST[ 'destinations' ] as $key => $destid ) {
						if ( empty( $destinations[ $destid ] ) ) //remove all destinations that not exists
							unset( $_POST[ 'destinations' ][ $key ] );
						if ( class_exists( 'BackWPup_Features', FALSE ) && $_POST[ 'backuptype' ] == 'sync' ) { //if sync remove all not sync destinations
							if ( ! $destinations[ $destid ]->can_sync() )
								unset( $_POST[ 'destinations' ][ $key ] );
						}
					}
					if ( is_array( $_POST[ 'destinations' ] ) )
						sort( $_POST[ 'destinations' ] );
				} else {
					$_POST[ 'destinations' ] = array();
				}
				BackWPup_Option::update( $jobid, 'destinations', $_POST[ 'destinations' ] );

				BackWPup_Option::update( $jobid, 'name', esc_html( $_POST[ 'name' ] ) );
				BackWPup_Option::update( $jobid, 'mailaddresslog', sanitize_email( $_POST[ 'mailaddresslog' ] ) );
				
				$_POST[ 'mailaddresssenderlog' ] = trim( $_POST[ 'mailaddresssenderlog' ] );
				if ( empty($_POST[ 'mailaddresssenderlog' ] ) )
					BackWPup_Option::delete( $jobid, 'mailaddresssenderlog');
				else
					BackWPup_Option::update( $jobid, 'mailaddresssenderlog', $_POST[ 'mailaddresssenderlog' ] );
				
				BackWPup_Option::update( $jobid, 'mailerroronly', ( isset( $_POST[ 'mailerroronly' ] ) && $_POST[ 'mailerroronly' ] == 1 ) ? TRUE : FALSE );
				if ( class_exists( 'BackWPup_Features', FALSE ) )
					BackWPup_Option::update( $jobid, 'backuptype', $_POST[ 'backuptype' ] );
				else
					BackWPup_Option::update( $jobid, 'backuptype', 'archive' );
				BackWPup_Option::update( $jobid, 'archiveformat', $_POST[ 'archiveformat' ] );
				BackWPup_Option::update( $jobid, 'archivename', $_POST[ 'archivename' ] );
				break;
			case 'cron':
				if ( $_POST[ 'activetype' ] == '' || $_POST[ 'activetype' ] == 'wpcron' || $_POST[ 'activetype' ] == 'link' )
					BackWPup_Option::update( $jobid, 'activetype', $_POST[ 'activetype' ] );

				BackWPup_Option::update( $jobid, 'cronselect', $_POST[ 'cronselect' ] == 'advanced' ? 'advanced' : 'basic' );

				if ( isset($_POST[ 'cronselect' ]) && $_POST[ 'cronselect' ] == 'advanced' ) { //save advanced
					if ( empty( $_POST[ 'cronminutes' ] ) || $_POST[ 'cronminutes' ][ 0 ] == '*' ) {
						if ( ! empty( $_POST[ 'cronminutes' ][ 1 ] ) )
							$_POST[ 'cronminutes' ] = array( '*/' . $_POST[ 'cronminutes' ][ 1 ] );
						else
							$_POST[ 'cronminutes' ] = array( '*' );
					}
					if ( empty( $_POST[ 'cronhours' ] ) || $_POST[ 'cronhours' ][ 0 ] == '*' ) {
						if ( ! empty( $_POST[ 'cronhours' ][ 1 ] ) )
							$_POST[ 'cronhours' ] = array( '*/' . $_POST[ 'cronhours' ][ 1 ] );
						else
							$_POST[ 'cronhours' ] = array( '*' );
					}
					if ( empty( $_POST[ 'cronmday' ] ) || $_POST[ 'cronmday' ][ 0 ] == '*' ) {
						if ( ! empty( $_POST[ 'cronmday' ][ 1 ] ) )
							$_POST[ 'cronmday' ] = array( '*/' . $_POST[ 'cronmday' ][ 1 ] );
						else
							$_POST[ 'cronmday' ] = array( '*' );
					}
					if ( empty( $_POST[ 'cronmon' ] ) || $_POST[ 'cronmon' ][ 0 ] == '*' ) {
						if ( ! empty( $_POST[ 'cronmon' ][ 1 ] ) )
							$_POST[ 'cronmon' ] = array( '*/' . $_POST[ 'cronmon' ][ 1 ] );
						else
							$_POST[ 'cronmon' ] = array( '*' );
					}
					if ( empty( $_POST[ 'cronwday' ] ) || $_POST[ 'cronwday' ][ 0 ] == '*' ) {
						if ( ! empty( $_POST[ 'cronwday' ][ 1 ] ) )
							$_POST[ 'cronwday' ] = array( '*/' . $_POST[ 'cronwday' ][ 1 ] );
						else
							$_POST[ 'cronwday' ] = array( '*' );
					}
					$cron = implode( ",", $_POST[ 'cronminutes' ] ) . ' ' . implode( ",", $_POST[ 'cronhours' ] ) . ' ' . implode( ",", $_POST[ 'cronmday' ] ) . ' ' . implode( ",", $_POST[ 'cronmon' ] ) . ' ' . implode( ",", $_POST[ 'cronwday' ] );
					BackWPup_Option::update( $jobid, 'cron', $cron );
				} else {  //Save basic
					if ( $_POST[ 'cronbtype' ] == 'mon' )
						BackWPup_Option::update( $jobid, 'cron', $_POST[ 'moncronminutes' ] . ' ' . $_POST[ 'moncronhours' ] . ' ' . $_POST[ 'moncronmday' ] . ' * *' );
					if ( $_POST[ 'cronbtype' ] == 'week' )
						BackWPup_Option::update( $jobid, 'cron', $_POST[ 'weekcronminutes' ] . ' ' . $_POST[ 'weekcronhours' ] . ' * * ' . $_POST[ 'weekcronwday' ] );
					if ( $_POST[ 'cronbtype' ] == 'day' )
						BackWPup_Option::update( $jobid, 'cron', $_POST[ 'daycronminutes' ] . ' ' . $_POST[ 'daycronhours' ] . ' * * *' );
					if ( $_POST[ 'cronbtype' ] == 'hour' )
						BackWPup_Option::update( $jobid, 'cron', $_POST[ 'hourcronminutes' ] . ' * * * *' );
				}
				//reschedule
				wp_clear_scheduled_hook( 'backwpup_cron', array( 'id' => $jobid ) );
				if ( BackWPup_Option::get( $jobid, 'activetype' ) == 'wpcron' ) {
					$cron_next = BackWPup_Cron::cron_next( BackWPup_Option::get( $jobid, 'cron' ) );
					wp_schedule_single_event( $cron_next, 'backwpup_cron', array( 'id' => $jobid ) );
				}
				break;
			default:
				if ( strstr( $tab, 'dest-' ) ) {
					$id = strtoupper( str_replace( 'dest-', '', $tab ) );
					$message.=call_user_func( array( $destinations[ $id ], 'edit_form_post_save'), $jobid  );
				}
				if ( strstr( $tab, 'jobtype-' ) ) {
					$id = strtoupper( str_replace( 'jobtype-', '', $tab ) );
					$message.=call_user_func( array( $job_types[ $id ], 'edit_form_post_save' ), $jobid );
				}
		}
		//saved message
		$message .= sprintf( __( 'Changes for job <i>%s</i> saved.', 'backwpup' ), BackWPup_Option::get( $jobid, 'name' ) );

		return $message;

	}

	/**
	 *
	 * Output css
	 *
	 * @return void
	 */
	public static function admin_print_styles() {

		wp_enqueue_style('backwpupgeneral');

		//add css for the first tabs
		if ( $_GET[ 'tab' ] == 'cron' ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				wp_enqueue_style( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/css/page_edit_tab_cron.dev.css', '', time(), 'screen' );
			} else {
				wp_enqueue_style( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/css/page_edit_tab_cron.css', '', BackWPup::get_plugin_data( 'Version' ), 'screen' );
			}
		}
		//add css for all other tabs
		elseif ( substr( $_GET[ 'tab' ], 0, 5 ) == 'dest-' ) {
			$destinations = BackWPup::get_destinations();
			$id    = strtoupper( str_replace( 'dest-', '', $_GET[ 'tab' ] ) );
			$destinations[ $id ]->admin_print_styles();
		}
		elseif ( substr( $_GET[ 'tab' ], 0, 8 ) == 'jobtype-' ) {
			$job_type = BackWPup::get_job_types();
			$id       = strtoupper( str_replace( 'jobtype-', '', $_GET[ 'tab' ] ) );
			$job_type[ $id ]->admin_print_styles( );
		}
	}

	/**
	 *
	 * Output js
	 *
	 * @return void
	 */
	public static function admin_print_scripts() {

		wp_enqueue_script( 'backwpupgeneral' );

		//add js for the first tabs
		if ( $_GET[ 'tab' ] == 'job' ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				wp_enqueue_script( 'backwpuptabjob', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_tab_job.dev.js', array('jquery'), time(), TRUE );
			} else {
				wp_enqueue_script( 'backwpuptabjob', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_tab_job.js', array('jquery'), BackWPup::get_plugin_data( 'Version' ), TRUE );
			}
		}
 		elseif ( $_GET[ 'tab' ] == 'cron' ) {
			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				wp_enqueue_script( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_tab_cron.dev.js', array('jquery'), time(), TRUE );
				wp_enqueue_style( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/css/page_edit_tab_cron.dev.css', '', time(), 'screen' );
			} else {
				wp_enqueue_script( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_tab_cron.js', array('jquery'), BackWPup::get_plugin_data( 'Version' ), TRUE );
				wp_enqueue_style( 'backwpuptabcron', BackWPup::get_plugin_data( 'URL' ) . '/css/page_edit_tab_cron.css', '', BackWPup::get_plugin_data( 'Version' ), 'screen' );
			}
		}
		//add js for all other tabs
		elseif ( strstr( $_GET[ 'tab' ], 'dest-' ) ) {
			$destinations = BackWPup::get_destinations();
			$id    = strtoupper( str_replace( 'dest-', '', $_GET[ 'tab' ] ) );
			$destinations[ $id ]->admin_print_scripts( );
		}
		elseif (  strstr( $_GET[ 'tab' ], 'jobtype-' ) ) {
			$job_type = BackWPup::get_job_types();
			$id       = strtoupper( str_replace( 'jobtype-', '', $_GET[ 'tab' ] ) );
			$job_type[ $id ]->admin_print_scripts( );
		}
	}

	/**
	 * @static
	 *
	 * @param string $args
	 *
	 * @return mixed
	 */
	public static function ajax_cron_text( $args = '' ) {

		if ( is_array( $args ) ) {
			extract( $args );
			$ajax = FALSE;
		} else {
			if ( ! current_user_can( 'backwpup_jobs_edit' ) )
				wp_die( -1 );
			check_ajax_referer( 'backwpup_ajax_nonce' );
			if ( empty( $_POST[ 'cronminutes' ] ) || $_POST[ 'cronminutes' ][ 0 ] == '*' ) {
				if ( ! empty( $_POST[ 'cronminutes' ][ 1 ] ) )
					$_POST[ 'cronminutes' ] = array( '*/' . $_POST[ 'cronminutes' ][ 1 ] );
				else
					$_POST[ 'cronminutes' ] = array( '*' );
			}
			if ( empty( $_POST[ 'cronhours' ] ) || $_POST[ 'cronhours' ][ 0 ] == '*' ) {
				if ( ! empty( $_POST[ 'cronhours' ][ 1 ] ) )
					$_POST[ 'cronhours' ] = array( '*/' . $_POST[ 'cronhours' ][ 1 ] );
				else
					$_POST[ 'cronhours' ] = array( '*' );
			}
			if ( empty( $_POST[ 'cronmday' ] ) || $_POST[ 'cronmday' ][ 0 ] == '*' ) {
				if ( ! empty( $_POST[ 'cronmday' ][ 1 ] ) )
					$_POST[ 'cronmday' ] = array( '*/' . $_POST[ 'cronmday' ][ 1 ] );
				else
					$_POST[ 'cronmday' ] = array( '*' );
			}
			if ( empty( $_POST[ 'cronmon' ] ) || $_POST[ 'cronmon' ][ 0 ] == '*' ) {
				if ( ! empty( $_POST[ 'cronmon' ][ 1 ] ) )
					$_POST[ 'cronmon' ] = array( '*/' . $_POST[ 'cronmon' ][ 1 ] );
				else
					$_POST[ 'cronmon' ] = array( '*' );
			}
			if ( empty( $_POST[ 'cronwday' ] ) || $_POST[ 'cronwday' ][ 0 ] == '*' ) {
				if ( ! empty( $_POST[ 'cronwday' ][ 1 ] ) )
					$_POST[ 'cronwday' ] = array( '*/' . $_POST[ 'cronwday' ][ 1 ] );
				else
					$_POST[ 'cronwday' ] = array( '*' );
			}
			$crontype  = $_POST[ 'crontype' ];
			$cronstamp = implode( ",", $_POST[ 'cronminutes' ] ) . ' ' . implode( ",", $_POST[ 'cronhours' ] ) . ' ' . implode( ",", $_POST[ 'cronmday' ] ) . ' ' . implode( ",", $_POST[ 'cronmon' ] ) . ' ' . implode( ",", $_POST[ 'cronwday' ] );
			$ajax      = TRUE;
		}
		echo '<p class="wpcron" id="schedulecron">';

		if ( $crontype == 'advanced' ) {
			echo str_replace( '\"','"', __( 'Working as <a href="http://wikipedia.org/wiki/Cron">Cron</a> schedule:', 'backwpup' ) );
			echo ' <i><b>' . $cronstamp . '</b></i><br />';
		}

		list( $cronstr[ 'minutes' ], $cronstr[ 'hours' ], $cronstr[ 'mday' ], $cronstr[ 'mon' ], $cronstr[ 'wday' ] ) = explode( ' ', $cronstamp, 5 );
		if ( FALSE !== strpos( $cronstr[ 'minutes' ], '*/' ) || $cronstr[ 'minutes' ] == '*' ) {
			$repeatmins = str_replace( '*/', '', $cronstr[ 'minutes' ] );
			if ( $repeatmins == '*' || empty( $repeatmins ) )
				$repeatmins = 5;
			echo '<span style="color:red;">' . sprintf( __( 'ATTENTION: Job runs every %d minutes!', 'backwpup' ), $repeatmins ) . '</span><br />';
		}
		if ( FALSE !== strpos( $cronstr[ 'hours' ], '*/' ) || $cronstr[ 'hours' ] == '*' ) {
			$repeathouer = str_replace( '*/', '', $cronstr[ 'hours' ] );
			if ( $repeathouer == '*' || empty( $repeathouer ) )
				$repeathouer = 1;
			echo '<span style="color:red;">' . sprintf( __( 'ATTENTION: Job runs every %d hours!', 'backwpup' ), $repeathouer ) . '</span><br />';
		}
		$cron_next = BackWPup_Cron::cron_next( $cronstamp ) + ( get_option( 'gmt_offset' ) * 3600 );
		if ( 2147483647 == $cron_next ) {
			echo '<span style="color:red;">' . __( 'ATTENTION: Can\'t calculate cron!', 'backwpup' ) . '</span><br />';
		}
		else {
			_e( 'Next runtime:', 'backwpup' );
			echo ' <b>' . date_i18n( 'D, j M Y, H:i', $cron_next, TRUE ) . '</b>';
		}
		echo "</p>";

		if ( $ajax )
			die();
		else
			return;
	}

	/**
	 *
	 */
	public static function page() {

		if ( ! empty( $_GET[ 'jobid' ] ) ) {
			$jobid = (int)$_GET[ 'jobid' ];
		}
		else {
			//generate jobid if not exists
			$newjobid = BackWPup_Option::get_job_ids();
			sort( $newjobid );
			$jobid = end( $newjobid ) + 1;
		}

		$destinations = BackWPup::get_destinations();
		$job_types    = BackWPup::get_job_types();

		?>
    <div class="wrap">
		<?php
		screen_icon();

		//default tabs
		$tabs = array( 'job' => array( 'name' => __( 'General', 'backwpup' ), 'display' => TRUE ), 'cron' => array( 'name' => __( 'Schedule', 'backwpup' ), 'display' => TRUE ) );
		//add jobtypes to tabs
		$job_job_types = BackWPup_Option::get( $jobid, 'type' );
		foreach ( $job_types as $typeid => $typeclass ) {
			$tabid          = 'jobtype-' . strtolower( $typeid );
			$tabs[ $tabid ][ 'name' ] = $typeclass->info[ 'name' ];
			$tabs[ $tabid ][ 'display' ] = TRUE;
			if ( ! in_array( $typeid, $job_job_types ) )
				$tabs[ $tabid ][ 'display' ] = FALSE;

		}
		//add destinations to tabs
		$jobdests = BackWPup_Option::get( $jobid, 'destinations' );
		foreach ( $destinations as $destid => $destclass ) {
			$tabid          = 'dest-' . strtolower( $destid );
			$tabs[ $tabid ][ 'name' ] = sprintf(__( 'To: %s', 'backwpup' ), $destclass->info[ 'name' ]);
			$tabs[ $tabid ][ 'display' ] = TRUE;
			if ( ! in_array( $destid, $jobdests ) )
				$tabs[ $tabid ][ 'display' ] = FALSE;
		}
		//display tabs
		echo '<h2 class="nav-tab-wrapper">' . sprintf( __( '%s Job:', 'backwpup' ), BackWPup::get_plugin_data( 'name' ) ). '&nbsp;';
		echo '<span id="h2jobtitle">' .htmlspecialchars( BackWPup_Option::get( $jobid, 'name' ) ) . '</span><br /><br />';
		foreach ( $tabs as $id => $tab ) {
			$addclass = '';
			if ( $id == $_GET[ 'tab' ] )
				$addclass = ' nav-tab-active';
			$display = '';
			if ( ! $tab[ 'display' ] )
				$display = ' style="display:none;"';
			echo '<a href="' . wp_nonce_url( network_admin_url( 'admin.php' ) . '?page=backwpupeditjob&tab=' . $id . '&jobid=' . $jobid, 'edit-job' ) . '" class="nav-tab' . $addclass . '" id="tab-' . $id . '" data-nexttab="' . $id . '" ' . $display . '>' . $tab[ 'name' ] . '</a>';
		}
		echo '</h2>';
		//display messages
		BackWPup_Admin::display_messages();
		echo '<form name="editjob" id="editjob" method="post" action="' . admin_url( 'admin-post.php?action=backwpup' ) . '">';
		echo '<input type="hidden" id="jobid" name="jobid" value="' . $jobid . '" />';
		echo '<input type="hidden" name="tab" value="' . $_GET[ 'tab' ] . '" />';
		echo '<input type="hidden" name="nexttab" value="' . $_GET[ 'tab' ] . '" />';
		echo '<input type="hidden" name="page" value="backwpupeditjob" />';
    	echo '<input type="hidden" name="anchor" value="" />';
		wp_nonce_field( 'backwpupeditjob_page' );
		wp_nonce_field( 'backwpup_ajax_nonce', 'backwpupajaxnonce', FALSE );

		switch ( $_GET[ 'tab' ] ) {
			case 'job':
				echo '<div class="table" id="info-tab-job">';
				?>
				<h3 class="title"><?php _e( 'Job Name', 'backwpup' ) ?></h3>
				<p></p>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="name"><?php _e( 'Please name this job.', 'backwpup' ) ?></label></th>
						<td>
							<input name="name" type="text" id="name"
								   value="<?php echo BackWPup_Option::get( $jobid, 'name' );?>" class="regular-text" />
						</td>
					</tr>
				</table>

				<h3 class="title"><?php _e( 'Job Tasks', 'backwpup' ) ?></h3>
				<p></p>
				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'This job is a&#160;&hellip;', 'backwpup' ) ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Job tasks', 'backwpup' ) ?></span>
								</legend><?php
								foreach ( $job_types as $id => $typeclass ) {
									$fileclass = '';
									if ( call_user_func( array( $typeclass, 'creates_file' ) ) )
										$fileclass = ' filetype';
									echo '<label for="jobtype-select-' . strtolower( $id ) . '"><input class="jobtype-select checkbox' . $fileclass . '" id="jobtype-select-' . strtolower( $id ) . '" type="checkbox" ' . checked( TRUE, in_array( $id, BackWPup_Option::get( $jobid, 'type' ) ), FALSE ) . ' name="type[]" value="' . $id . '" /> ' . $typeclass->info[ 'description' ];
									if ( ! empty( $info[ 'help' ] ) )
										BackWPup_Help::tip( $typeclass->info[ 'help' ] );
									echo "</label><br />";
								}
								?></fieldset>
						</td>
					</tr>
				</table>

				<h3 class="title hasdests"><?php _e( 'Backup File Creation', 'backwpup' ) ?></h3>
				<p class="hasdests"></p>
				<table class="form-table hasdests">
					<?php if ( class_exists( 'BackWPup_Features', FALSE ) ) { ?>
					<tr>
						<th scope="row"><?php _e( 'Backup type', 'backwpup' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Backup type', 'backwpup' ) ?></span>
								</legend>
								<label for="idbackuptype-sync"><input class="radio"
									   type="radio"<?php checked( 'sync', BackWPup_Option::get( $jobid, 'backuptype' ), TRUE ); ?>
									   name="backuptype" id="idbackuptype-sync"
									   value="sync"/> <?php _e( 'Synchronize file by file to destination', 'backwpup' ); ?></label><br/>
                                <label for="idbackuptype-archive"><input class="radio"
									   type="radio"<?php checked( 'archive', BackWPup_Option::get( $jobid, 'backuptype' ), TRUE ); ?>
									   name="backuptype" id="idbackuptype-archive"
									   value="archive"/> <?php _e( 'Create a backup archive', 'backwpup' ); ?></label><br/>
							</fieldset>
						</td>
					</tr>
					<?php } ?>
					<tr class="nosync">
						<th scope="row"><label for="archivename"><?php _e( 'Archive name', 'backwpup' ) ?></label></th>
						<td>
							<input name="archivename" type="text" id="archivename"
								value="<?php echo BackWPup_Option::get( $jobid, 'archivename' );?>"
								class="regular-text code" />
							<?php
							$patterns = array (
								__( '%d = Two digit day of the month, with leading zeros', 'backwpup' ),
								__( '%j = Day of the month, without leading zeros', 'backwpup' ),
								__( '%m = Day of the month, with leading zeros', 'backwpup' ),
								__( '%n = Representation of the month (without leading zeros)', 'backwpup' ),
								__( '%Y = Four digit representation for the year', 'backwpup' ),
								__( '%y = Two digit representation of the year', 'backwpup' ),
								__( '%a = Lowercase ante meridiem (am) and post meridiem (pm)', 'backwpup' ),
								__( '%A = Uppercase ante meridiem (AM) and post meridiem (PM)', 'backwpup' ),
								__( '%B = Swatch Internet Time', 'backwpup' ),
								__( '%g = Hour in 12-hour format, without leading zeros', 'backwpup' ),
								__( '%G = Hour in 24-hour format, without leading zeros', 'backwpup' ),
								__( '%h = Hour in 12-hour format, with leading zeros', 'backwpup' ),
								__( '%H = Hour in 24-hour format, with leading zeros', 'backwpup' ),
								__( '%i = Two digit representation of the minute', 'backwpup' ),
								__( '%s = Two digit representation of the second', 'backwpup' ),
								__( '%u = Two digit representation of the microsecond', 'backwpup' ),
								__( '%U = UNIX timestamp (seconds since January 1 1970 00:00:00 GMT)', 'backwpup' ),
							);

							BackWPup_Help::tip(
								"<strong>" . __( 'Replacement patterns:', 'backwpup' ) . "</strong><br />"
								. join( '<br />', $patterns )
							);

							$datevars    = array( '%d', '%j', '%m', '%n', '%Y', '%y', '%a', '%A', '%B', '%g', '%G', '%h', '%H', '%i', '%s', '%u', '%U' );
							$datevalues  = array( date_i18n( 'd' ), date_i18n( 'j' ), date_i18n( 'm' ), date_i18n( 'n' ), date_i18n( 'Y' ), date_i18n( 'y' ), date_i18n( 'a' ), date_i18n( 'A' ), date_i18n( 'B' ), date_i18n( 'g' ), date_i18n( 'G' ), date_i18n( 'h' ), date_i18n( 'H' ), date_i18n( 'i' ), date_i18n( 's' ), date_i18n( 'u' ), date_i18n( 'U' ) );
							$archivename = str_replace( $datevars, $datevalues, BackWPup_Option::get( $jobid, 'archivename' ) );
							$archivename = sanitize_file_name( $archivename );
							echo '<p>Preview: <code><span id="archivefilename">' . $archivename . '</span><span id="archiveformat">' . BackWPup_Option::get( $jobid, 'archiveformat' ) . '</span></code></p>';
							?>
						</td>
					</tr>
					<tr class="nosync">
						<th scope="row"><?php _e( 'Archive Format', 'backwpup' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Archive Format', 'backwpup' ) ?></span>
								</legend><?php
								if ( function_exists( 'gzopen' ) || class_exists( 'ZipArchive' ) )
									echo '<label for="idarchiveformat-zip"><input class="radio" type="radio"' . checked( '.zip', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-zip" value=".zip" /> ' . __( 'Zip', 'backwpup' ) . BackWPup_Help::tip( __( 'PHP Zip functions will be used if available (needs less memory). Otherwise the PCLZip class will be used.', 'backwpup' ), FALSE ) . '</label><br />';
								else
									echo '<label for="idarchiveformat-zip"><input class="radio" type="radio"' . checked( '.zip', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-zip" value=".zip" disabled="disabled" /> ' . __( 'Zip', 'backwpup' ) . BackWPup_Help::tip( __( 'Disabled due to missing PHP function.', 'backwpup' ), FALSE ) . '</label><br />';
								echo '<label for="idarchiveformat-tar"><input class="radio" type="radio"' . checked( '.tar', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-tar" value=".tar" /> ' . __( 'Tar', 'backwpup' ) . BackWPup_Help::tip( __( 'A tarballed, not compressed archive (fast and less memory)', 'backwpup' ), FALSE ) . '</label><br />';
								if ( function_exists( 'gzopen' ) )
									echo '<label for="idarchiveformat-targz"><input class="radio" type="radio"' . checked( '.tar.gz', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-targz" value=".tar.gz" /> ' . __( 'Tar GZip', 'backwpup' ) . BackWPup_Help::tip( __( 'A tarballed, GZipped archive (fast and less memory)', 'backwpup' ), FALSE ) . '</label><br />';
								else
									echo '<label for="idarchiveformat-targz"><input class="radio" type="radio"' . checked( '.tar.gz', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-targz" value=".tar.gz" disabled="disabled" /> ' . __( 'Tar GZip', 'backwpup' ) . BackWPup_Help::tip( __( 'Disabled due to missing PHP function.', 'backwpup' ), FALSE ) . '</label><br />';
								if ( function_exists( 'bzopen' ) )
									echo '<label for="idarchiveformat-tarbz2"><input class="radio" type="radio"' . checked( '.tar.bz2', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-tarbz2" value=".tar.bz2" /> ' . __( 'Tar BZip2', 'backwpup' ) . BackWPup_Help::tip( __( 'A tarballed, BZipped archive (fast and less memory)', 'backwpup' ), FALSE ) . '</label><br />';
								else
									echo '<label for="idarchiveformat-tarbz2"><input class="radio" type="radio"' . checked( '.tar.bz2', BackWPup_Option::get( $jobid, 'archiveformat' ), FALSE ) . ' name="archiveformat" id="idarchiveformat-tarbz2" value=".tar.bz2" disabled="disabled" /> ' . __( 'Tar BZip2', 'backwpup' ) . BackWPup_Help::tip( __( 'Disabled due to missing PHP function.', 'backwpup' ), FALSE ) . '</label><br />';
								?></fieldset>
						</td>
					</tr>
				</table>

				<h3 class="title hasdests"><?php _e( 'Job Destination', 'backwpup' ) ?></h3>
				<p class="hasdests"></p>
				<table class="form-table hasdests">
					<tr>
						<th scope="row"><?php _e( 'Where should your backup file be stored?', 'backwpup' ) ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Where should your backup file be stored?', 'backwpup' ) ?></span>
								</legend><?php
								foreach ( $destinations as $id => $destclass ) {
									$syncclass = '';
									if ( ! call_user_func( array( $destclass, 'can_sync' ) ) )
										$syncclass = 'nosync';
									echo '<span class="' . $syncclass . '"><label for="dest-select-' . strtolower( $id ) . '"><input class="checkbox" id="dest-select-' . strtolower( $id ) . '" type="checkbox" ' . checked( TRUE, in_array( $id, BackWPup_Option::get( $jobid, 'destinations' ) ), FALSE ) . ' name="destinations[]" value="' . $id . '"/> ' . $destclass->info[ 'description' ];
									if ( ! empty( $info[ 'help' ] ) )
										BackWPup_Help::tip( $destclass->info[ 'help' ] );
									echo '</label><br /></span>';
								}
								?></fieldset>
						</td>
					</tr>
				</table>

				<h3 class="title"><?php _e( 'Log Files', 'backwpup' ) ?></h3>
				<p></p>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="mailaddresslog"><?php _e( 'Send log to e-mail address', 'backwpup' ) ?></label></th>
						<td>
							<input name="mailaddresslog" type="text" id="mailaddresslog"
								   value="<?php echo BackWPup_Option::get( $jobid, 'mailaddresslog' );?>"
								   class="regular-text" /><?php BackWPup_Help::tip( __( 'Leave empty to not have log sent.', 'backwpup' ) ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="mailaddresssenderlog"><?php _e( 'E-Mail FROM field', 'backwpup' ) ?></label></th>
						<td>
							<input name="mailaddresssenderlog" type="text" id="mailaddresssenderlog"
								   value="<?php echo BackWPup_Option::get( $jobid, 'mailaddresssenderlog' );?>"
								   class="regular-text" /><?php BackWPup_Help::tip( __( 'E-Mail "From" field (Name &lt;&#160;you@your-email-address.tld&#160;&gt;)', 'backwpup' ) ); ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php _e( 'Errors only', 'backwpup' ); ?></th>
						<td>
                            <label for="idmailerroronly">
							<input class="checkbox" value="1" id="idmailerroronly"
								   type="checkbox" <?php checked( BackWPup_Option::get( $jobid, 'mailerroronly' ), TRUE ); ?>
								   name="mailerroronly" /> <?php _e( 'Send e-mail with log only when errors occur during job execution.', 'backwpup' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<?php
				echo '</div>';
				break;
			case 'cron':
				echo '<div class="table" id="info-tab-cron">';
				?>
				<h3 class="title"><?php _e( 'Job Schedule', 'backwpup' ) ?></h3>
				<p></p>
				<table class="form-table">
					<tr>
                        <th scope="row"><?php _e( 'Start job', 'backwpup' ); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text"><span><?php _e( 'Start job', 'backwpup' ) ?></span></legend>
                                <label for="idactivetype"><input class="radio"
                                       type="radio"<?php checked( '', BackWPup_Option::get( $jobid, 'activetype' ), TRUE ); ?>
                                       name="activetype" id="idactivetype"
                                       value="" /> <?php _e( 'manually only', 'backwpup' ); ?></label><br/>
                                <label for="idactivetype-wpcron"><input class="radio"
                                       type="radio"<?php checked( 'wpcron', BackWPup_Option::get( $jobid, 'activetype' ), TRUE ); ?>
                                       name="activetype" id="idactivetype-wpcron"
                                       value="wpcron" /> <?php _e( 'with WordPress cron', 'backwpup' ); ?></label><br/>
								<?php
								$url = BackWPup_Job::get_jobrun_url( 'runext', BackWPup_Option::get( $jobid, 'jobid' ) );
								?>
                                <label for="idactivetype-link"><input class="radio"
									   type="radio"<?php checked( 'link', BackWPup_Option::get( $jobid, 'activetype' ), TRUE ); ?>
									   name="activetype" id="idactivetype-link"
									   value="link" /> <?php _e( 'with a link', 'backwpup' ); ?> <code><?php echo $url[ 'url' ];?></code></label>
								<?php
									BackWPup_Help::tip( __( 'Copy the link for an external start. This option has to be activated to make the link work.', 'backwpup' ) );
								?>
								<br />
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
						<th scope="row"><?php _e( 'Start job with CLI', 'backwpup' ); ?></th>
						<td>
							<?php
								echo str_replace( '\"','"', sprintf ( __( 'Use <a href="http://wp-cli.org/">WP-CLI</a> to run jobs from commandline or <a href="%s">get the start script</a>.', 'backwpup' ),  wp_nonce_url( network_admin_url( 'admin.php' ) . '?page=backwpupjobs&action=start_cli&jobid=' . $jobid, 'start_cli' ) ) );
								BackWPup_Help::tip( __( 'Generate a server script file to let the job start with the server’s cron on command line interface. Alternatively use WP-CLI commands.', 'backwpup' ) );
							?>
						</td>
                    </tr>
				</table>
				<h3 class="title wpcron"><?php _e( 'Schedule execution time', 'backwpup' ) ?></h3>
				<?php BackWPup_Page_Editjob::ajax_cron_text( array( 'cronstamp' => BackWPup_Option::get( $jobid, 'cron' ), 'crontype' => BackWPup_Option::get( $jobid, 'cronselect' ) ) ); ?>
				<table class="form-table wpcron">
					<tr>
						<th scope="row"><?php _e( 'Scheduler type', 'backwpup' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text"><span><?php _e( 'Scheduler type', 'backwpup' ) ?></span>
								</legend>
                                <label for="idcronselect-basic"><input class="radio"
									   type="radio"<?php checked( 'basic', BackWPup_Option::get( $jobid, 'cronselect' ), TRUE ); ?>
									   name="cronselect" id="idcronselect-basic"
									   value="basic" /> <?php _e( 'basic', 'backwpup' ); ?></label><br/>
                                <label for="idcronselect-advanced"><input class="radio"
									   type="radio"<?php checked( 'advanced', BackWPup_Option::get( $jobid, 'cronselect' ), TRUE ); ?>
									   name="cronselect" id="idcronselect-advanced"
									   value="advanced" /> <?php _e( 'advanced', 'backwpup' ); ?></label><br/>
							</fieldset>
						</td>
					</tr>
					<?php

					list( $cronstr[ 'minutes' ], $cronstr[ 'hours' ], $cronstr[ 'mday' ], $cronstr[ 'mon' ], $cronstr[ 'wday' ] ) = explode( ' ', BackWPup_Option::get( $jobid, 'cron' ), 5 );
					if ( strstr( $cronstr[ 'minutes' ], '*/' ) )
						$minutes = explode( '/', $cronstr[ 'minutes' ] );
					else
						$minutes = explode( ',', $cronstr[ 'minutes' ] );
					if ( strstr( $cronstr[ 'hours' ], '*/' ) )
						$hours = explode( '/', $cronstr[ 'hours' ] );
					else
						$hours = explode( ',', $cronstr[ 'hours' ] );
					if ( strstr( $cronstr[ 'mday' ], '*/' ) )
						$mday = explode( '/', $cronstr[ 'mday' ] );
					else
						$mday = explode( ',', $cronstr[ 'mday' ] );
					if ( strstr( $cronstr[ 'mon' ], '*/' ) )
						$mon = explode( '/', $cronstr[ 'mon' ] );
					else
						$mon = explode( ',', $cronstr[ 'mon' ] );
					if ( strstr( $cronstr[ 'wday' ], '*/' ) )
						$wday = explode( '/', $cronstr[ 'wday' ] );
					else
						$wday = explode( ',', $cronstr[ 'wday' ] );
					?>
                    <tr class="wpcronbasic"<?php if ( BackWPup_Option::get( $jobid, 'cronselect' ) != 'basic' ) echo ' style="display:none;"';?>>
                        <th scope="row"><?php _e( 'Scheduler', 'backwpup' ); ?></th>
                        <td>
                            <table id="wpcronbasic">
                                <tr>
                                    <th>
										<?php _e( 'Type', 'backwpup' ); ?>
                                    </th>
                                    <th>
                                    </th>
                                    <th>
										<?php _e( 'Hour', 'backwpup' ); ?>
                                    </th>
                                    <th>
										<?php _e( 'Minute', 'backwpup' ); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td><label for="idcronbtype-mon"><?php echo '<input class="radio" type="radio"' . checked( TRUE, is_numeric( $mday[ 0 ] ), FALSE ) . ' name="cronbtype" id="idcronbtype-mon" value="mon" /> ' . __( 'monthly', 'backwpup' ); ?></label></td>
                                    <td><select name="moncronmday"><?php for ( $i = 1; $i <= 31; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $mday, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . __( 'on', 'backwpup' ) . ' ' . $i . '.</option>';
									} ?></select></td>
                                    <td><select name="moncronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                    <td><select name="moncronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 5 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                </tr>
                                <tr>
                                    <td><label for="idcronbtype-week"><?php echo '<input class="radio" type="radio"' . checked( TRUE, is_numeric( $wday[ 0 ] ), FALSE ) . ' name="cronbtype" id="idcronbtype-week" value="week" /> ' . __( 'weekly', 'backwpup' ); ?></label></td>
                                    <td><select name="weekcronwday">
										<?php     echo '<option ' . selected( in_array( "0", $wday, TRUE ), TRUE, FALSE ) . '  value="0" />' . __( 'Sunday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "1", $wday, TRUE ), TRUE, FALSE ) . '  value="1" />' . __( 'Monday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "2", $wday, TRUE ), TRUE, FALSE ) . '  value="2" />' . __( 'Tuesday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "3", $wday, TRUE ), TRUE, FALSE ) . '  value="3" />' . __( 'Wednesday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "4", $wday, TRUE ), TRUE, FALSE ) . '  value="4" />' . __( 'Thursday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "5", $wday, TRUE ), TRUE, FALSE ) . '  value="5" />' . __( 'Friday', 'backwpup' ) . '</option>';
										echo '<option ' . selected( in_array( "6", $wday, TRUE ), TRUE, FALSE ) . '  value="6" />' . __( 'Saturday', 'backwpup' ) . '</option>'; ?>
                                    </select></td>
                                    <td><select name="weekcronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                    <td><select name="weekcronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 5 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                </tr>
                                <tr>
                                    <td><label for="idcronbtype-day"><?php echo '<input class="radio" type="radio"' . checked( "**", $mday[ 0 ] . $wday[ 0 ], FALSE ) . ' name="cronbtype" id="idcronbtype-day" value="day" /> ' . __( 'daily', 'backwpup' ); ?></label></td>
                                    <td></td>
                                    <td><select name="daycronhours"><?php for ( $i = 0; $i < 24; $i ++ ) {
										echo '<option ' . selected( in_array( "$i", $hours, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                    <td><select name="daycronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 5 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                </tr>
                                <tr>
                                    <td><label for="idcronbtype-hour"><?php echo '<input class="radio" type="radio"' . checked( "*", $hours[ 0 ], FALSE, FALSE ) . ' name="cronbtype" id="idcronbtype-hour" value="hour" /> ' . __( 'hourly', 'backwpup' ); ?></label></td>
                                    <td></td>
                                    <td></td>
                                    <td><select name="hourcronminutes"><?php for ( $i = 0; $i < 60; $i = $i + 5 ) {
										echo '<option ' . selected( in_array( "$i", $minutes, TRUE ), TRUE, FALSE ) . '  value="' . $i . '" />' . $i . '</option>';
									} ?></select></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
					<tr class="wpcronadvanced"<?php if ( BackWPup_Option::get( $jobid, 'cronselect' ) != 'advanced' ) echo ' style="display:none;"';?>>
						<th scope="row"><?php _e( 'Scheduler', 'backwpup' ); ?></th>
						<td>
                            <div id="cron-min-box">
                                <b><?php _e( 'Minutes:', 'backwpup' ); ?></b><br/>
								<?php
								echo '<label for="idcronminutes"><input class="checkbox" type="checkbox"' . checked( in_array( "*", $minutes, TRUE ), TRUE, FALSE ) . ' name="cronminutes[]" id="idcronminutes" value="*" /> ' . __( 'Any (*)', 'backwpup' ) . '</label><br />';
								?>
                                <div id="cron-min"><?php
									for ( $i = 0; $i < 60; $i = $i + 5 ) {
										echo '<label for="idcronminutes-' . $i . '"><input class="checkbox" type="checkbox"' . checked( in_array( "$i", $minutes, TRUE ), TRUE, FALSE ) . ' name="cronminutes[]" id="idcronminutes-' . $i . '" value="' . $i . '" /> ' . $i . '</label><br />';
									}
									?>
                                </div>
                            </div>
                            <div id="cron-hour-box">
                                <b><?php _e( 'Hours:', 'backwpup' ); ?></b><br/>
								<?php

								echo '<label for="idcronhours"><input class="checkbox" type="checkbox"' . checked( in_array( "*", $hours, TRUE ), TRUE, FALSE ) . ' name="cronhours[]" for="idcronhours" value="*" /> ' . __( 'Any (*)', 'backwpup' ) . '</label><br />';
								?>
                                <div id="cron-hour"><?php
									for ( $i = 0; $i < 24; $i ++ ) {
										echo '<label for="idcronhours-' . $i . '"><input class="checkbox" type="checkbox"' . checked( in_array( "$i", $hours, TRUE ), TRUE, FALSE ) . ' name="cronhours[]" id="idcronhours-' . $i . '" value="' . $i . '" /> ' . $i . '</label><br />';
									}
									?>
                                </div>
                            </div>
                            <div id="cron-day-box">
                                <b><?php _e( 'Day of Month:', 'backwpup' ); ?></b><br/>
                                <label for="idcronmday"><input class="checkbox" type="checkbox"<?php checked( in_array( "*", $mday, TRUE ), TRUE, TRUE ); ?>
                                       name="cronmday[]" id="idcronmday" value="*"/> <?php _e( 'Any (*)', 'backwpup' ); ?></label>
                                <br/>

                                <div id="cron-day">
									<?php
									for ( $i = 1; $i <= 31; $i ++ ) {
										echo '<label for="idcronmday-' . $i . '"><input class="checkbox" type="checkbox"' . checked( in_array( "$i", $mday, TRUE ), TRUE, FALSE ) . ' name="cronmday[]" id="idcronmday-' . $i . '" value="' . $i . '" /> ' . $i . '</label><br />';
									}
									?>
                                </div>
                            </div>
                            <div id="cron-month-box">
                                <b><?php _e( 'Month:', 'backwpup' ); ?></b><br/>
								<?php
								echo '<label for="idcronmon"><input class="checkbox" type="checkbox"' . checked( in_array( "*", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon" value="*" /> ' . __( 'Any (*)', 'backwpup' ) . '</label><br />';
								?>
                                <div id="cron-month">
									<?php
									echo '<label for="idcronmon-1"><input class="checkbox" type="checkbox"' . checked( in_array( "1", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-1" value="1" /> ' . __( 'January', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-2"><input class="checkbox" type="checkbox"' . checked( in_array( "2", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-2" value="2" /> ' . __( 'February', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-3"><input class="checkbox" type="checkbox"' . checked( in_array( "3", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-3" value="3" /> ' . __( 'March', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-4"><input class="checkbox" type="checkbox"' . checked( in_array( "4", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-4" value="4" /> ' . __( 'April', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-5"><input class="checkbox" type="checkbox"' . checked( in_array( "5", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-5" value="5" /> ' . __( 'May', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-6"><input class="checkbox" type="checkbox"' . checked( in_array( "6", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-6" value="6" /> ' . __( 'June', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-7"><input class="checkbox" type="checkbox"' . checked( in_array( "7", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-7" value="7" /> ' . __( 'July', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-8"><input class="checkbox" type="checkbox"' . checked( in_array( "8", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-8" value="8" /> ' . __( 'August', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-9"><input class="checkbox" type="checkbox"' . checked( in_array( "9", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-9" value="9" /> ' . __( 'September', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-10"><input class="checkbox" type="checkbox"' . checked( in_array( "10", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-10" value="10" /> ' . __( 'October', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-11"><input class="checkbox" type="checkbox"' . checked( in_array( "11", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-11" value="11" /> ' . __( 'November', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronmon-12"><input class="checkbox" type="checkbox"' . checked( in_array( "12", $mon, TRUE ), TRUE, FALSE ) . ' name="cronmon[]" id="idcronmon-12" value="12" /> ' . __( 'December', 'backwpup' ) . '</label><br />';
									?>
                                </div>
                            </div>
                            <div id="cron-weekday-box">
                                <b><?php _e( 'Day of Week:', 'backwpup' ); ?></b><br/>
								<?php
								echo '<label for="idcronwday"><input class="checkbox" type="checkbox"' . checked( in_array( "*", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday" value="*" /> ' . __( 'Any (*)', 'backwpup' ) . '</label><br />';
								?>
                                <div id="cron-weekday">
									<?php
									echo '<label for="idcronwday-0"><input class="checkbox" type="checkbox"' . checked( in_array( "0", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-0" value="0" /> ' . __( 'Sunday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-1"><input class="checkbox" type="checkbox"' . checked( in_array( "1", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-1" value="1" /> ' . __( 'Monday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-2"><input class="checkbox" type="checkbox"' . checked( in_array( "2", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-2" value="2" /> ' . __( 'Tuesday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-3"><input class="checkbox" type="checkbox"' . checked( in_array( "3", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-3" value="3" /> ' . __( 'Wednesday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-4"><input class="checkbox" type="checkbox"' . checked( in_array( "4", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-4" value="4" /> ' . __( 'Thursday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-5"><input class="checkbox" type="checkbox"' . checked( in_array( "5", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-5" value="5" /> ' . __( 'Friday', 'backwpup' ) . '</label><br />';
									echo '<label for="idcronwday-6"><input class="checkbox" type="checkbox"' . checked( in_array( "6", $wday, TRUE ), TRUE, FALSE ) . ' name="cronwday[]" id="idcronwday-6" value="6" /> ' . __( 'Saturday', 'backwpup' ) . '</label><br />';
									?>
                                </div>
                            </div>
                            <br class="clear"/>
						</td>
					</tr>
				</table>
				<?php
				echo '</div>';
				break;
			default:
				echo '<div class="table" id="info-tab-' . $_GET[ 'tab' ] . '">';
				if ( strstr( $_GET[ 'tab' ], 'dest-' ) ) {
					$id = strtoupper( str_replace( 'dest-', '', $_GET[ 'tab' ] ) );
					call_user_func( array( $destinations[ $id ], 'edit_tab' ), $jobid );
				}
				if ( strstr( $_GET[ 'tab' ], 'jobtype-' ) ) {
					$id = strtoupper( str_replace( 'jobtype-', '', $_GET[ 'tab' ] ) );
					call_user_func( array( $job_types[ $id ], 'edit_tab' ), $jobid );
				}
				echo '</div>';
		}
		echo '<br />';
		submit_button( __( 'Save changes', 'backwpup' ), 'primary', 'save', FALSE, array( 'tabindex' => '2', 'accesskey' => 'p' ) );
		echo '</form>';
		?>
    </div>

    <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready(function ($) {
        // auto post if things changed
        var changed = false;
        $( '#editjob' ).change( function () {
            changed = true;
        });
		$( '.nav-tab' ).click( function () {
			if ( changed ) {
				$( 'input[name="nexttab"]' ).val( $(this).data( "nexttab" ) );
				$( '#editjob' ).submit();
				return false;
            }
		});
		<?php
		//add inline js
		if ( strstr( $_GET[ 'tab' ], 'dest-' ) ) {
			$id = strtoupper( str_replace( 'dest-', '', $_GET[ 'tab' ] ) );
			call_user_func( array( $destinations[ $id ], 'edit_inline_js' ) );
		}
		if ( strstr( $_GET[ 'tab' ], 'jobtype-' ) ) {
			$id = strtoupper( str_replace( 'jobtype-', '', $_GET[ 'tab' ] ) );
			call_user_func( array( $job_types[ $id ], 'edit_inline_js' ) );
		}
		?>
    });
    //]]>
    </script>
	<?php
	}
}

