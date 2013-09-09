<?php
/**
 *
 */
final class BackWPup_Admin {

	private static $instance = NULL;
	public $page_hooks = array();

	/**
	 *
	 * Set needed filters and actions and load all needed
	 *
	 * @return \BackWPup_Admin
	 */
	public function __construct() {

		//Load text domain
		load_plugin_textdomain( 'backwpup', FALSE, BackWPup::get_plugin_data( 'BaseName' ) . '/languages' );

		//Add menu pages
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_jobs' ), 2 );
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_editjob' ), 3 );
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_logs' ), 4 );
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_backups' ), 5 );
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_settings' ), 6 );
		add_filter( 'backwpup_admin_pages', array( $this, 'admin_page_about' ), 20 );

		//Add Menu
		if ( is_multisite() )
			add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );
		else
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		//add Plugin links
		add_filter( 'plugin_row_meta', array( $this, 'plugin_links' ), 10, 2 );
		//add more actions
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		//add more actions
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		//Save Form posts general
		add_action( 'admin_post_backwpup', array( $this, 'save_post_form' ) );
		//Save Form posts wizard
		add_action( 'admin_post_backwpup_wizard', array( 'BackWPup_Page_Wizard', 'save_post_form' ) );
		//Admin Footer Text replacement
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 100 );
		add_filter( 'update_footer', array( $this, 'update_footer' ), 100 );
		//User Profile fields
		if ( current_user_can( 'administrator' ) || current_user_can( 'backwpup_admin' ) ) {
			add_action( 'show_user_profile', array( $this, 'user_profile_fields' ) );
			add_action( 'edit_user_profile',  array( $this, 'user_profile_fields' ) );
			add_action( 'profile_update',  array( $this, 'save_profile_update' ) );
		}
	}

	/**
	 * @static
	 * @return \BackWPup
	 */
	public static function getInstance() {

		if (NULL === self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __clone() {}

	/**
	 * Admin init function
	 */
	public function admin_init() {

		//only add action if ajax call
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && defined( 'WP_ADMIN' ) && WP_ADMIN ) {
			//ajax calls
			add_action( 'wp_ajax_backwpup_working', array( 'BackWPup_Page_Jobs', 'ajax_working' ) );
			add_action( 'wp_ajax_backwpup_cron_text', array( 'BackWPup_Page_Editjob', 'ajax_cron_text' ) );
			//ajax or view logs
			add_action( 'wp_ajax_backwpup_view_log', array( 'BackWPup_Page_Logs', 'ajax_view_log' ) );
			//ajax calls for job types
			if ( $jobtypes = BackWPup::get_job_types() ) {
				foreach ( $jobtypes as $id => $jobtypeclass ) {
					add_action( 'wp_ajax_backwpup_jobtype_' . strtolower( $id ), array( $jobtypeclass, 'edit_ajax' ) );
				}
			}
			//ajax calls for destinations
			if ( $dests = BackWPup::get_destinations() ) {
				foreach ( $dests as $id => $destclass ) {
					add_action( 'wp_ajax_backwpup_dest_' . strtolower( $id ), array( $destclass, 'edit_ajax' ) );
				}
			}
		}
		
		//display about page after Update
		if ( ! defined( 'DOING_AJAX' ) && ! get_site_option( 'backwpup_about_page', FALSE ) && ! isset( $_GET['activate-multi'] ) ) {
			update_site_option( 'backwpup_about_page', TRUE );
			wp_redirect( network_admin_url( 'admin.php' ) . '?page=backwpupabout' );
			exit();
		}
	}

	/**
	 * Admin init function
	 */
	public function admin_head() {

		//register js and css for BackWPup
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_enqueue_style( 'backwpupadmin', BackWPup::get_plugin_data( 'URL' ) . '/css/admin.dev.css', array(), time(), 'screen' );
		} else {
			wp_enqueue_style( 'backwpupadmin', BackWPup::get_plugin_data( 'URL' ) . '/css/admin.css', array( ), BackWPup::get_plugin_data( 'Version' ), 'screen' );
		}
	}

	/**
	 *
	 * Add Links in Plugins Menu to BackWPup
	 *
	 * @param $links
	 * @param $file
	 * @return array
	 */
	public function plugin_links( $links, $file ) {

		if ( $file == plugin_basename( BackWPup::get_plugin_data( 'MainFile' ) ) ) {
			$links[ ] = '<a href="' . __( 'https://marketpress.com/documentation/backwpup-pro/', 'backwpup' ) . '">' . __( 'Documentation', 'backwpup' ) . '</a>';
			if ( class_exists( 'BackWPup_Features', FALSE ) )
				$links[ ] = '<a href="' . __( 'https://marketpress.com/support/forum/plugins/backwpup-pro/', 'backwpup' ) . '">' . __( 'Pro Support', 'backwpup' ) . '</a>';
			else
				$links[ ] = '<a href="' . __( 'http://wordpress.org/support/plugin/backwpup/', 'backwpup' ) . '">' . __( 'Support', 'backwpup' ) . '</a>';

		}

		return $links;
	}

	/**
	 * Add menu entries
	 */
	public function admin_menu() {

		add_menu_page( BackWPup::get_plugin_data( 'name' ), BackWPup::get_plugin_data( 'name' ), 'backwpup', 'backwpup', array( 'BackWPup_Page_Backwpup', 'page' ), BackWPup::get_plugin_data( 'URL' ) . '/images/BackWPup16.png' );
		$this->page_hooks[ 'backwpup' ] = add_submenu_page( 'backwpup', __( 'BackWPup Dashboard', 'backwpup' ), __( 'Dashboard', 'backwpup' ), 'backwpup', 'backwpup', array( 'BackWPup_Page_Backwpup', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpup' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpup' ], array( 'BackWPup_Page_Backwpup', 'load' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpup' ], array( 'BackWPup_Page_Backwpup', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpup' ], array( 'BackWPup_Page_Backwpup', 'admin_print_scripts' ) );		

		//Add pages form plugins
		$this->page_hooks = apply_filters( 'backwpup_admin_pages' ,$this->page_hooks );
		
	}


	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_jobs( $page_hooks ) {

		$this->page_hooks[ 'backwpupjobs' ] = add_submenu_page( 'backwpup', __( 'Jobs', 'backwpup' ), __( 'Jobs', 'backwpup' ), 'backwpup_jobs', 'backwpupjobs', array( 'BackWPup_Page_Jobs', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupjobs' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupjobs' ], array( 'BackWPup_Page_Jobs', 'load' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpupjobs' ], array( 'BackWPup_Page_Jobs', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpupjobs' ], array( 'BackWPup_Page_Jobs', 'admin_print_scripts' ) );

		return $page_hooks;
	}

	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_editjob( $page_hooks ) {

		$this->page_hooks[ 'backwpupeditjob' ] = add_submenu_page( 'backwpup', __( 'Add New Job', 'backwpup' ), __( 'Add New Job', 'backwpup' ), 'backwpup_jobs_edit', 'backwpupeditjob', array( 'BackWPup_Page_Editjob', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupeditjob' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupeditjob' ], array( 'BackWPup_Page_Editjob', 'auth' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupeditjob' ], array( 'BackWPup_Page_Editjob', 'load' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpupeditjob' ], array( 'BackWPup_Page_Editjob', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpupeditjob' ], array( 'BackWPup_Page_Editjob', 'admin_print_scripts' ) );

		return $page_hooks;
	}

	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_logs( $page_hooks ) {

		$this->page_hooks[ 'backwpuplogs' ] = add_submenu_page( 'backwpup', __( 'Logs', 'backwpup' ), __( 'Logs', 'backwpup' ), 'backwpup_logs', 'backwpuplogs', array( 'BackWPup_Page_Logs', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpuplogs' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpuplogs' ], array( 'BackWPup_Page_Logs', 'load' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpuplogs' ], array( 'BackWPup_Page_Logs', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpuplogs' ], array( 'BackWPup_Page_Logs', 'admin_print_scripts' ) );

		return $page_hooks;
	}

	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_backups( $page_hooks ) {

		$this->page_hooks[ 'backwpupbackups' ] = add_submenu_page( 'backwpup', __( 'Backups', 'backwpup' ), __( 'Backups', 'backwpup' ), 'backwpup_backups', 'backwpupbackups', array( 'BackWPup_Page_Backups', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupbackups' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupbackups' ], array( 'BackWPup_Page_Backups', 'load' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpupbackups' ], array( 'BackWPup_Page_Backups', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpupbackups' ], array( 'BackWPup_Page_Backups', 'admin_print_scripts' ) );

		return $page_hooks;
	}

	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_settings( $page_hooks ) {

		$this->page_hooks[ 'backwpupsettings' ] = add_submenu_page( 'backwpup', __( 'Settings', 'backwpup' ), __( 'Settings', 'backwpup' ), 'backwpup_settings', 'backwpupsettings', array( 'BackWPup_Page_Settings', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupsettings' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpupsettings' ], array( 'BackWPup_Page_Settings', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpupsettings' ], array( 'BackWPup_Page_Settings', 'admin_print_scripts' ) );

		return $page_hooks;
	}

	/**
	 * @param $page_hooks
	 * @return mixed
	 */
	public function admin_page_about( $page_hooks ) {

		$this->page_hooks[ 'backwpupabout' ] = add_submenu_page( 'backwpup', __( 'About', 'backwpup' ), __( 'About', 'backwpup' ), 'install_plugins', 'backwpupabout', array( 'BackWPup_Page_About', 'page' ) );
		add_action( 'load-' . $this->page_hooks[ 'backwpupabout' ], array( 'BackWPup_Admin', 'init_generel' ) );
		add_action( 'admin_print_styles-' . $this->page_hooks[ 'backwpupabout' ], array( 'BackWPup_Page_About', 'admin_print_styles' ) );
		add_action( 'admin_print_scripts-' . $this->page_hooks[ 'backwpupabout' ], array( 'BackWPup_Page_About', 'admin_print_scripts' ) );

		return $page_hooks;
	}


	/**
	 * Load for all BackWPup pages
	 */
	public static function init_generel() {
		
		add_thickbox();

		//register js from tipTip
		wp_register_script( 'tiptip', BackWPup::get_plugin_data( 'URL' ) . '/js/jquery.tipTip.minified.js', array( 'jquery' ), '1.3', TRUE );

		//register js and css for BackWPup
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			wp_register_script( 'backwpupgeneral', BackWPup::get_plugin_data( 'URL' ) . '/js/general.dev.js', array( 'jquery', 'tiptip' ), time(), TRUE );
			wp_register_style( 'backwpupgeneral', BackWPup::get_plugin_data( 'URL' ) . '/css/general.dev.css', array( ), time(), 'screen' );
		} else {
			wp_register_script( 'backwpupgeneral', BackWPup::get_plugin_data( 'URL' ) . '/js/general.js', array( 'jquery', 'tiptip' ), BackWPup::get_plugin_data( 'Version' ), TRUE );
			wp_register_style( 'backwpupgeneral', BackWPup::get_plugin_data( 'URL' ) . '/css/general.css', array( ), BackWPup::get_plugin_data( 'Version' ), 'screen' );
		}

		//add Help
		BackWPup_Help::help();
	}


	/**
	 * Called on save form. Only POST allowed.
	 */
	public function save_post_form() {

		//Allowed Pages
		if ( ! in_array( $_POST[ 'page' ], array ( 'backwpupeditjob', 'backwpupinformation', 'backwpupsettings' ) ) )
			wp_die( __( 'Cheating, huh?', 'backwpup' ) );

		//nonce check
		check_admin_referer( $_POST[ 'page' ] . '_page' );

		if ( ! current_user_can( 'backwpup' ) )
			wp_die( __( 'Cheating, huh?', 'backwpup' ) );

		//build query for rederict
		if ( ! isset( $_POST[ 'anchor' ] ) )
			$_POST[ 'anchor' ] = NULL;
		$query_args=array();
		if ( isset( $_POST[ 'page' ] ) )
			$query_args[ 'page' ] = $_POST[ 'page' ];
		if ( isset( $_POST[ 'tab' ] ) )
			$query_args[ 'tab' ] = $_POST[ 'tab' ];
		if ( isset( $_POST[ 'tab' ] ) && isset( $_POST[ 'nexttab' ] ) && $_POST[ 'tab' ] != $_POST[ 'nexttab' ] )
			$query_args[ 'tab' ] = $_POST[ 'nexttab' ];

		$jobid = NULL;
		if ( isset( $_POST[ 'jobid' ] ) ) {
			$jobid = (int) $_POST[ 'jobid' ];
			$query_args[ 'jobid' ] = $jobid;
		}

		//Call method to save data
		$page_class = NULL;
		if ( $_POST[ 'page' ] == 'backwpupeditjob' )
			$page_class = 'BackWPup_Page_Editjob';
		elseif ( $_POST[ 'page' ] == 'backwpupinformation' )
			$page_class = 'BackWPup_Page_Information';
		elseif (  $_POST[ 'page' ] == 'backwpupsettings' ) {
			$page_class = 'BackWPup_Page_Settings';
			$_POST[ 'tab' ] = '';
		}
		$message = call_user_func( array( $page_class, 'save_post_form' ), $_POST[ 'tab' ], $jobid);

		if ( ! empty( $message ) ) {
			if ( $_POST[ 'page' ] == 'backwpupeditjob' ) {
				$url = BackWPup_Job::get_jobrun_url( 'runnowlink', BackWPup_Option::get( $jobid, 'jobid' ) );
				$message .= ' <a href="' . network_admin_url( 'admin.php' ) . '?page=backwpupjobs">' . __( 'Jobs overview', 'backwpup' ) . '</a> | <a href="' . $url[ 'url' ] . '">' . __( 'Run now', 'backwpup' ) . '</a>';
			}
			self::message( $message );
		}

		//Back to topic
		wp_safe_redirect(  add_query_arg( $query_args, network_admin_url( 'admin.php' ) ) . $_POST[ 'anchor' ] );
		exit;
	}

	/**
	 * Add Message
	 *
	 * @param $message
	 */
	public static function message( $message ) {

		
		$saved_message = self::get_message();

		if ( is_array( $message ) )
			$saved_message = array_merge( $saved_message, $message );
		else
			$saved_message[] = $message;
		
		update_site_option( 'backwpup_messages', $saved_message);

	}

	/**
	 * Get all Message that not displayed
	 *
	 * @return array
	 */
	public static function get_message() {

		return get_site_option( 'backwpup_messages', array(), FALSE );
	}

	/**
	 * Display Messages
	 *
	 * @param bool $echo
	 * @return string
	 */
	public static function display_messages( $echo = TRUE ) {

		$message = '';
		$saved_message = self::get_message();

		if ( ! empty( $saved_message ) ) {
			foreach( $saved_message as $saved)
				$message .= '<p>' .  $saved  . '</p>';
				//clean messages
				delete_site_option( 'backwpup_messages' );
		}

		if( empty( $message ) )
			return '';

		if ( $echo ) {
			echo '<div id="message" class="updated">' . $message . '</div>';
			return '';
		} else {
			return '<div id="message" class="updated">' . $message . '</div>';
		}
	}

	/**
	 * Overrides WordPress text in Footer
	 *
	 * @param $admin_footer_text string
	 * @return string
	 */
	public function admin_footer_text( $admin_footer_text ) {

		if ( isset( $_REQUEST[ 'page' ] ) && strstr( $_REQUEST[ 'page' ], 'backwpup' ) ) {
			$admin_footer_text = '<a href="' . __( 'http://marketpress.com', 'backwpup' ) . '" class="mp_logo" title="' . __( 'MarketPress', 'backwpup' ) . '">' . __( 'MarketPress', 'backwpup' ) . '</a>';
			if ( ! class_exists( 'BackWPup_Features', FALSE ) )
				$admin_footer_text .= '<span>'.sprintf( __( '<a href="%s">Get BackWPup Pro now.</a>', 'backwpup' ), __( 'http://marketpress.com/product/backwpup-pro/', 'backwpup' ) ). '</span>';
			$admin_footer_text .= '<span>'.sprintf( _x( 'Developer: %s', 'developer name, link text: Daniel Hüsken', 'backwpup' ), '<a href="http://danielhuesken.de">Daniel Hüsken</a>' ) . '</span>';
				
			return $admin_footer_text;
		}

		return $admin_footer_text;
	}

	/**
	 * Overrides WordPress Version in Footer
	 *
	 * @param $update_footer_text string
	 * @return string
	 */
	public function update_footer( $update_footer_text ) {

		if ( isset( $_REQUEST[ 'page' ] ) && strstr( $_REQUEST[ 'page' ], 'backwpup') ) {
			$update_footer_text  = '<a href="' . translate( BackWPup::get_plugin_data( 'PluginURI' ), 'backwpup' ) . '">' . BackWPup::get_plugin_data( 'Name' ) . '</a> '. sprintf( __( 'version %s' ,'backwpup'), BackWPup::get_plugin_data( 'Version' ) );

			return $update_footer_text;
		}

		return $update_footer_text;
	}


	/**
	 *  Add filed for selecting user role in user section
	 * 
	 * @param $user WP_User
	 */
	public function user_profile_fields( $user ) {
		global $wp_roles;
		
		?>
		    <h3><?php echo BackWPup::get_plugin_data( 'name' ); ?></h3>		    
		    <table class="form-table">
		        <tr>
		            <th>
		                <label for="backwpup_role"><?php _e('BackWPup Role', 'backwpup'); ?>
		            </label></th>
		            <td>
		                <select name="backwpup_role" id="backwpup_role" style="display:inline-block; float:none;">
							<option value=""><?php _e('&mdash; No role for BackWPup &mdash;', 'backwpup'); ?></option>
							<?php
							foreach ( $wp_roles->roles as $role => $rolevalue ) {
								if ( ! strstr( $role, 'backwpup_' ) )
									continue;
								echo '<option value="'.$role.'" '. selected( in_array( $role, $user->roles ), TRUE, FALSE ) .'>'. $rolevalue[ 'name' ] . '</option>';
							}
							?>						
		                </select>
						<br />
		                <span class="description"><?php _e('Role that the user have on BackWPup', 'backwpup'); ?></span>
		            </td>
		        </tr>
		    </table>
		<?php
	}

	/**
	 * Save for user role adding
	 * 
	 * @param $user_id int
	 */
	public function save_profile_update( $user_id ) {
		global $wp_roles;
		
		if ( empty( $user_id ) )
			return;
		
		if ( ! isset( $_POST['backwpup_role'] ) )
			return;
		
		// get BackWPup roles
		$backwpup_roles = array();
		foreach ( array_keys( $wp_roles->roles ) as $role ) {
			if ( ! strstr( $role, 'backwpup_' ) )
				continue;
			$backwpup_roles[] = $role;
		}
		
		//get user for adding/removing role
		$user = new WP_User( $user_id );
		//remove BackWPup role from user
		foreach ( $user->roles as $role ) {
			if ( ! strstr( $role, 'backwpup_' ) )
				continue;
			$user->remove_role( $role );
		}
		//add new role to user
		if ( ! empty( $_POST['backwpup_role'] ) && in_array( $_POST['backwpup_role'], $backwpup_roles ) )
			$user->add_role( $_POST['backwpup_role'] );
		
		return;		
	}
	
}
