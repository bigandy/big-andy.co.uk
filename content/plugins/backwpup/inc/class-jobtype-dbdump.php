<?php
/**
 *
 */
class BackWPup_JobType_DBDump extends BackWPup_JobTypes {

	/**
	 *
	 */
	public function __construct() {

		$this->info[ 'ID' ]          = 'DBDUMP';
		$this->info[ 'name' ]        = __( 'DB Backup', 'backwpup' );
		$this->info[ 'description' ] = __( 'Database backup', 'backwpup' );
		$this->info[ 'help' ]        = __( 'Creates an .sql database dump file', 'backwpup' );
		$this->info[ 'URI' ]         = translate( BackWPup::get_plugin_data( 'PluginURI' ), 'backwpup' );
		$this->info[ 'author' ]      = BackWPup::get_plugin_data( 'Author' );
		$this->info[ 'authorURI' ]   = translate( BackWPup::get_plugin_data( 'AuthorURI' ), 'backwpup' );
		$this->info[ 'version' ]     = BackWPup::get_plugin_data( 'Version' );

	}

	/**
	 * @return bool
	 */
	public function creates_file() {

		return TRUE;
	}

	/**
	 * @return array
	 */
	public function option_defaults() {
		global $wpdb;
		/* @var wpdb $wpdb */
		
		$defaults = array(
			'dbdumpexclude'    => array(), 'dbdumpfile' => sanitize_file_name( DB_NAME ), 'dbdumptype' => 'sql', 'dbdumpfilecompression' => ''
		);
		//set only wordpress tables as default
		$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
		foreach ( $dbtables as $dbtable) {
			if ( $wpdb->prefix != substr( $dbtable[ 0 ], 0, strlen( $wpdb->prefix ) ) )
				$defaults[ 'dbdumpexclude' ][] = $dbtable[ 0 ];
		}

		return $defaults;
	}


	/**
	 * @param $jobid
	 */
	public function edit_tab( $jobid ) {
		global $wpdb;
		/* @var wpdb $wpdb */
		
		?>
        <input name="dbdumpwpony" type="hidden" value="1" />
        <h3 class="title"><?php _e( 'Settings for database backup', 'backwpup' ) ?></h3>
        <p></p>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e( 'Tables to backup', 'backwpup' ); ?></th>
                <td>
                    <input type="button" class="button-secondary" id="dball" value="<?php _e( 'all', 'backwpup' ); ?>">&nbsp;
					<input type="button" class="button-secondary" id="dbnone" value="<?php _e( 'none', 'backwpup' ); ?>">&nbsp;
                    <input type="button" class="button-secondary" id="dbwp" value="<?php echo $wpdb->prefix; ?>">
					<?php
					$tables = $wpdb->get_results( 'SHOW FULL TABLES FROM `' . DB_NAME . '`', ARRAY_N );
					$num_rows = count( $tables );
					echo '<table id="dbtables"><tr><td valign="top">';
					$next_row = round( $num_rows / 3, 0 );
					$counter = 0;
					foreach ( $tables as $table ) {
						$tabletype = '';
						if ( $table[ 1 ] != 'BASE TABLE' )
							$tabletype = ' <i>(' . strtolower( $table[ 1 ] ) . ')</i>';
						echo '<label for="idtabledb-' . rawurlencode( $table[ 0 ] ) . '""><input class="checkbox" type="checkbox"' . checked( ! in_array( $table[ 0 ], BackWPup_Option::get( $jobid, 'dbdumpexclude' ) ), TRUE, FALSE ) . ' name="tabledb[]" id="idtabledb-' . rawurlencode( $table[ 0 ] ) . '" value="' . rawurlencode( $table[ 0 ] ) . '"/> ' . $table[ 0 ] . $tabletype . '</label><br />';
						$counter++;
						if ($next_row <= $counter) {
							echo '</td><td valign="top">';
							$counter = 0;
						}
					}
					echo '</td></tr></table>';
					?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="iddbdumpfile"><?php _e( 'Dumpfile name', 'backwpup' ) ?></label></th>
                <td>
                    <input id="iddbdumpfile" name="dbdumpfile" type="text"
                           value="<?php echo BackWPup_Option::get( $jobid, 'dbdumpfile' );?>"
                           class="medium-text code"/>.sql
                </td>
            </tr>
			<tr>
				<th scope="row"><?php _e( 'Dumpfile compression', 'backwpup' ) ?></th>
				<td>
					<?php
					echo '<label for="iddbdumpfilecompression"><input class="radio" type="radio"' . checked( '', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression"  id="iddbdumpfilecompression" value="" /> ' . __( 'none', 'backwpup' ). '</label><br />';
					if ( function_exists( 'gzopen' ) )
						echo '<label for="iddbdumpfilecompression-gz"><input class="radio" type="radio"' . checked( '.gz', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-gz" value=".gz" /> ' . __( 'GZip', 'backwpup' ). '</label><br />';
					else
						echo '<label for="iddbdumpfilecompression-gz"><input class="radio" type="radio"' . checked( '.gz', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-gz" value=".gz" disabled="disabled" /> ' . __( 'GZip', 'backwpup' ). '</label><br />';
					if ( function_exists( 'bzopen' ) )
						echo '<label for="iddbdumpfilecompression-bz2"><input class="radio" type="radio"' . checked( '.bz2', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-bz2" value=".bz2" /> ' . __( 'BZip2', 'backwpup' ). '</label><br />';
					else
						echo '<label for="iddbdumpfilecompression-bz2"><input class="radio" type="radio"' . checked( '.bz2', BackWPup_Option::get( $jobid, 'dbdumpfilecompression' ), FALSE ) . ' name="dbdumpfilecompression" id="iddbdumpfilecompression-bz2" value=".bz2" disabled="disabled" /> ' . __( 'BZip2', 'backwpup' ). '</label><br />';
					?>
				</td>
			</tr>
        </table>
		<?php
	}


	/**
	 * @param $id
	 */
	public function edit_form_post_save( $id ) {
		global $wpdb;
		/* @var wpdb $wpdb */
		
		if ( $_POST[ 'dbdumpfilecompression' ] == '' || $_POST[ 'dbdumpfilecompression' ] == '.gz' || $_POST[ 'dbdumpfilecompression' ] == '.bz2' )
			BackWPup_Option::update( $id, 'dbdumpfilecompression', $_POST[ 'dbdumpfilecompression' ] );
		BackWPup_Option::update( $id, 'dbdumpfile',  sanitize_file_name( $_POST[ 'dbdumpfile' ]) );
		//selected tables
		$dbdumpexclude = array();
		$checked_db_tables = array();
		if ( isset( $_POST[ 'tabledb' ] ) ) {
			foreach ( $_POST[ 'tabledb' ] as $dbtable )
				$checked_db_tables[ ] = rawurldecode( $dbtable );
		}
		$dbtables = $wpdb->get_results( 'SHOW TABLES FROM `' . DB_NAME . '`', ARRAY_N );
		foreach ( $dbtables as $dbtable ) {
			if ( ! in_array( $dbtable[ 0 ], $checked_db_tables ) )
				$dbdumpexclude[ ] = $dbtable[ 0 ];
		}
		BackWPup_Option::update( $id, 'dbdumpexclude', $dbdumpexclude );

	}

	/**
	 * @param $job_object BackWPup_Job
	 * @return bool
	 */
	public function job_run( $job_object ) {

		$job_object->substeps_done = 0;
		$job_object->substeps_todo = 1;

		$job_object->log( sprintf( __( '%d. Try to dump database&#160;&hellip;', 'backwpup' ), $job_object->steps_data[ $job_object->step_working ][ 'STEP_TRY' ] ) );

		//build filename
		if ( empty( $job_object->temp[ 'dbdumpfile' ] ) )
			$job_object->temp[ 'dbdumpfile' ] = $job_object->generate_filename( $job_object->job[ 'dbdumpfile' ], 'sql' ) . $job_object->job[ 'dbdumpfilecompression' ];

		try {

			//Connect to Database
			$sql_dump = new BackWPup_MySQLDump( array( 'dumpfile' => BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) );

			if ( is_object( $sql_dump ) )
				$job_object->log( sprintf( __( 'Connected to database %1$s on %2$s', 'backwpup' ), DB_NAME, DB_HOST ) );

			//Exclude Tables
			foreach ( $sql_dump->tables_to_dump as $key => $table ) {
				if ( in_array( $table, $job_object->job[ 'dbdumpexclude' ] ) )
					unset( $sql_dump->tables_to_dump[ $key ] );
			}

			//set steps must done
			$job_object->substeps_todo = count( $sql_dump->tables_to_dump );

			if ( $job_object->substeps_todo == 0 ) {
				$job_object->log( __( 'No tables to dump.', 'backwpup' ), E_USER_WARNING );
				unset( $sql_dump );
				return TRUE;
			}

			//dump head
			$sql_dump->dump_head( TRUE );
			//dump tables
			foreach( $sql_dump->tables_to_dump as $table ) {
				$job_object->log( sprintf( __( 'Dump database table "%s"', 'backwpup' ), $table ) );
				$job_object->substeps_done ++;
				$sql_dump->dump_table( $table );
			}
			//dump footer
			$sql_dump->dump_footer();
			unset( $sql_dump );

		} catch ( Exception $e ) {
			$job_object->log( $e->getMessage(), E_USER_ERROR, $e->getFile(), $e->getLine() );
			unset( $sql_dump );
			return FALSE;
		}


		//add database file to backup files
		if ( is_readable( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ) ) {
			$job_object->additional_files_to_backup[ ] = BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ];
			$job_object->count_files ++;
			$job_object->count_filesize = $job_object->count_filesize + @filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] );
			$job_object->log( sprintf( __( 'Added database dump "%1$s" with %2$s to backup file list', 'backwpup' ), $job_object->temp[ 'dbdumpfile' ], size_format( filesize( BackWPup::get_plugin_data( 'TEMP' ) . $job_object->temp[ 'dbdumpfile' ] ), 2 ) ) );
		}

		$job_object->log( __( 'Database dump done!', 'backwpup' ) );

		return TRUE;
	}

	/**
	 *
	 */
	public function admin_print_scripts() {

		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_script( 'backwpupjobtypedbdump', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_jobtype_dbdump.dev.js', array('jquery'), time(), TRUE );
		} else {
			wp_enqueue_script( 'backwpupjobtypedbdump', BackWPup::get_plugin_data( 'URL' ) . '/js/page_edit_jobtype_dbdump.js', array('jquery'), BackWPup::get_plugin_data( 'Version' ), TRUE );
		}
	}


}
