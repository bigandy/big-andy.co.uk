<?php
/**
 * Class to create a MYSQL dump with mysqli as sql file
 */
class BackWPup_MySQLDump {

	/**
	 * Holder for mysqli recourse
	 */
	private $mysqli = NULL;

	/**
	 * Holder for dump file handle
	 */
	private $handle = NULL;

	/**
	 * Table names of Tables in Database
	 */
	public $tables_to_dump = array();

	/**
	 * Table names of Tables in Database
	 */
	private $table_types = array();

	/**
	 * Table information of Tables in Database
	 */
	private $table_status = array();

	/**
	 * Database name
	 */
	private $dbname = '';

	/**
	 * Compression to use
	 * empty for none
	 * gz for Gzip
	 * bz2 for Bzip
	 */
	private $compression = '';

	/**
	 * Check params and makes confections
	 * gets the table information too
	 *
	 * @param  $args array with arguments
	 * @throws BackWPup_MySQLDump_Exception
	 * @global $wpdb wpdb
	 */
	public function __construct( $args = array() ) {
		
		if ( ! class_exists( 'mysqli' ) )
			throw new BackWPup_MySQLDump_Exception( __( 'No MySQLi extension found. Please install it.', 'backwpup' ) );

		$default_args = array(
			'dbhost' 	  => DB_HOST,
			'dbname' 	  => DB_NAME,
			'dbuser' 	  => DB_USER,
			'dbpassword'  => DB_PASSWORD,
			'dbcharset'   => defined( 'DB_CHARSET' ) ? DB_CHARSET : '',
			'dumpfilehandle' => fopen( 'php://output', 'wb' ),
			'dumpfile' 	  => NULL,
			'compression' => ''
		);

		$args = wp_parse_args( $args , $default_args );
		
		//set empty host to localhost
		if ( empty( $args[ 'dbhost' ] ) )
			$args[ 'dbhost' ] = 'localhost';

		//check if port or socket in hostname and set port and socket
		$args[ 'dbport' ]   = NULL;
		$args[ 'dbsocket' ] = NULL;
		if ( strstr( $args[ 'dbhost' ], ':' ) ) {
			$hostparts = explode( ':', $args[ 'dbhost' ], 2 );
			$args[ 'dbhost' ] = $hostparts[ 0 ];
			if ( is_numeric( $hostparts[ 1 ] ) )
				$args[ 'dbport' ] = (int) $hostparts[ 1 ];
			else
				$args[ 'dbsocket' ] = $hostparts[ 1 ] ;
		}

		//connect to Database
		$this->mysqli = new mysqli( $args[ 'dbhost' ], $args[ 'dbuser' ], $args[ 'dbpassword' ], $args[ 'dbname' ], $args[ 'dbport' ], $args[ 'dbsocket' ] );
		if ( $this->mysqli->connect_error )
			throw new BackWPup_MySQLDump_Exception( sprintf( __( 'Cannot connect to MySQL database %1$d: %2$s', 'backwpup' ), $this->mysqli->connect_errno, $this->mysqli->connect_error ) );

		//set charset
		if ( ! empty( $args[ 'dbcharset' ] ) && method_exists( $this->mysqli, 'set_charset' ) ) {
			$res = $this->mysqli->set_charset( $args[ 'dbcharset' ] );
			if ( ! $res )
				throw new BackWPup_MySQLDump_Exception( sprintf( _x( 'Cannot set DB charset to %s','Database Charset', 'backwpup' ), $args[ 'dbcharset' ] ) );
		}

		//set db name
		$this->dbname = $args[ 'dbname' ];

		//set compression
		if ( ! empty( $args[ 'compression' ] ) && in_array( $args[ 'compression' ], array( 'gz', 'bz2' ) ) )
			$this->compression = $args[ 'compression' ];

		//open file if set
		if ( $args[ 'dumpfile' ] ) {
			if ( substr( strtolower( $args[ 'dumpfile' ] ), -3 ) == '.gz' ) {
				if ( ! function_exists( 'gzencode' ) )
					throw new BackWPup_MySQLDump_Exception( __( 'Functions for gz compression not available', 'backwpup' ) );
				$this->compression = 'gz';
			} elseif ( substr( strtolower( $args[ 'dumpfile' ] ), -4 ) == '.bz2' ) {
				if ( ! function_exists( 'bzcompress' ) )
					throw new BackWPup_MySQLDump_Exception( __( 'Functions for bz2 compression not available', 'backwpup' ) );
				$this->compression = 'bz2';
			} else {
				$this->compression = '';
			}
			$this->handle = fopen( $args[ 'dumpfile' ], 'wb' );
		} else {
			$this->handle = $args[ 'dumpfilehandle' ];
		}

		//check file handle
		if ( ! is_resource( $this->handle ) )
			throw new BackWPup_MySQLDump_Exception( __( 'Cannot open dump file', 'backwpup' ) );

		//get table names and types from Database
		$res = $this->mysqli->query( 'SHOW FULL TABLES FROM `' . $this->dbname . '`' );
		$GLOBALS[ 'wpdb' ]->num_queries ++;
		if ( $this->mysqli->error )
			throw new BackWPup_MySQLDump_Exception( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, 'SHOW FULL TABLES FROM `' . $this->dbname . '`' ) );
		while ( $table = $res->fetch_array( MYSQLI_NUM ) ) {
			$this->tables_to_dump[] = $table[ 0 ];
			$this->table_types[ $table[ 0 ] ] = $table[ 1 ];
		}
		$res->close();

		//get table info
		$res = $this->mysqli->query( "SHOW TABLE STATUS FROM `" . $this->dbname . "`" );
		$GLOBALS[ 'wpdb' ]->num_queries ++;
		if ( $this->mysqli->error )
			throw new BackWPup_MySQLDump_Exception( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW TABLE STATUS FROM `" .$this->dbname . "`" ) );
		while ( $tablestatus = $res->fetch_assoc() ) {
			$this->table_status[ $tablestatus[ 'Name' ] ] = $tablestatus;
		}
		$res->close();
	}

	/**
	 * Start the dump
	 */
	public function execute() {

		//increase time limit
		@set_time_limit( 0 );
		//write dump head
		$this->dump_head();
		//write tables
		foreach( $this->tables_to_dump as $table )
			$this->dump_table( $table );
		//write footer
		$this->dump_footer();

	}

	/**
	 * Write Dump Header
	 *
	 * @param bool $wp_info Dump WordPress info in dump head
	 */
	public function dump_head( $wp_info = FALSE ) {

		// get sql timezone
		$res = $this->mysqli->query( "SELECT @@time_zone" );
		$GLOBALS[ 'wpdb' ]->num_queries ++;
		$mysqltimezone = $res->fetch_row();
		$mysqltimezone = $mysqltimezone[0];
		$res->close();


		//For SQL always use \n as MySQL wants this on all platforms.
		$dbdumpheader  = "-- ---------------------------------------------------------\n";
		$dbdumpheader .= "-- Dumped with BackWPup ver.: " . BackWPup::get_plugin_data( 'Version' ) . "\n";
		$dbdumpheader .= "-- https://marketpress.de/product/backwpup-pro/\n";
		if ( $wp_info ) {
			$dbdumpheader .= "-- Blog Name: " . get_bloginfo( 'name' ) . "\n";
			$dbdumpheader .= "-- Blog URL: " . trailingslashit( get_bloginfo( 'url' ) ) . "\n";
			$dbdumpheader .= "-- Blog ABSPATH: " . trailingslashit( str_replace( '\\', '/', ABSPATH ) ) . "\n";
			$dbdumpheader .= "-- Blog Charset: " . get_bloginfo( 'charset' ) . "\n";
			$dbdumpheader .= "-- Table Prefix: " . $GLOBALS[ 'wpdb' ]->prefix . "\n";
		}
		$dbdumpheader .= "-- Database Name: " . $this->dbname . "\n";
		$dbdumpheader .= "-- Dumped on: " . date_i18n( 'Y-m-d H:i.s' ) . "\n";
		$dbdumpheader .= "-- ---------------------------------------------------------\n\n";
		//for better import with mysql client
		$dbdumpheader .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
		$dbdumpheader .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
		$dbdumpheader .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
		$dbdumpheader .= "/*!40101 SET NAMES " . $this->mysqli->character_set_name() . " */;\n";
		$dbdumpheader .= "/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;\n";
		$dbdumpheader .= "/*!40103 SET TIME_ZONE='" . $mysqltimezone . "' */;\n";
		$dbdumpheader .= "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;\n";
		$dbdumpheader .= "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;\n";
		$dbdumpheader .= "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;\n";
		$dbdumpheader .= "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;\n\n";
		$this->write( $dbdumpheader );
	}

	/**
	 * Write Dump Footer with dump of functions and procedures
	 */
	public function dump_footer() {

		//dump procedures and functions
		$this->write( "\n--\n-- Dumping routines for database '" . $this->dbname . "'\n--\n" );

		//dump Functions
		$res = $this->mysqli->query( "SHOW FUNCTION STATUS" );
		$GLOBALS[ 'wpdb' ]->num_queries ++;
		if ( $this->mysqli->error ) {
			trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW FUNCTION STATUS" ), E_USER_WARNING );
		} else {
			while ( $function_status = $res->fetch_assoc() ) {
				if ( $this->dbname != $function_status[ 'Db' ] )
					continue;
				$create = "\n--\n-- Function structure for " . $function_status[ 'Name' ] . "\n--\n\n";
				$create .= "DROP FUNCTION IF EXISTS `" . $function_status[ 'Name' ] . "`;\n";
				$create .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
				$create .= "/*!40101 SET character_set_client = '" . $this->mysqli->character_set_name() . "' */;\n";
				//Dump the view structure
				$res2 = $this->mysqli->query( "SHOW CREATE FUNCTION `" .  $function_status[ 'Db' ] . "`.`" . $function_status[ 'Name' ] . "`" );
				$GLOBALS[ 'wpdb' ]->num_queries ++;
				if ( $this->mysqli->error )
					trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW CREATE FUNCTION `" .  $function_status[ 'Db' ] . "`.`" . $function_status[ 'Name' ] . "`" ), E_USER_WARNING );
				$create_function = $res2->fetch_assoc();
				$res2->close();
				$create .= $create_function[ 'Create Function' ] . ";\n";
				$create .= "/*!40101 SET character_set_client = @saved_cs_client */;\n";
				$this->write( $create );
			}
			$res->close();
		}

		//dump Procedures
		$res = $this->mysqli->query( "SHOW PROCEDURE STATUS" );
		$GLOBALS[ 'wpdb' ]->num_queries ++;
		if ( $this->mysqli->error ) {
			trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW PROCEDURE STATUS" ), E_USER_WARNING );
		} else {
			while ( $procedure_status = $res->fetch_assoc() ) {
				if ( $this->dbname != $procedure_status[ 'Db' ] )
					continue;
				$create = "\n--\n-- Procedure structure for " . $procedure_status[ 'Name' ] . "\n--\n\n";
				$create .= "DROP PROCEDURE IF EXISTS `" . $procedure_status[ 'Name' ] . "`;\n";
				$create .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
				$create .= "/*!40101 SET character_set_client = '" . $this->mysqli->character_set_name() . "' */;\n";
				//Dump the view structure
				$res2 = $this->mysqli->query( "SHOW CREATE PROCEDURE `" . $procedure_status[ 'Db' ] . "`.`" . $procedure_status[ 'Name' ] . "`" );
				$GLOBALS[ 'wpdb' ]->num_queries ++;
				if ( $this->mysqli->error )
					trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW CREATE PROCEDURE `" . $procedure_status[ 'Db' ] . "`.`" . $procedure_status[ 'Name' ] . "`" ), E_USER_WARNING );
				$create_procedure = $res2->fetch_assoc();
				$res2->close();
				$create .= $create_procedure[ 'Create Procedure' ] . ";\n";
				$create .= "/*!40101 SET character_set_client = @saved_cs_client */;\n";
				$this->write( $create );
			}
			$res->close();
		}

		//for better import with mysql client
		$dbdumpfooter  = "\n/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;\n";
		$dbdumpfooter .= "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;\n";
		$dbdumpfooter .= "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;\n";
		$dbdumpfooter .= "/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;\n";
		$dbdumpfooter .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
		$dbdumpfooter .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
		$dbdumpfooter .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";
		$dbdumpfooter .= "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;\n";
		$dbdumpfooter .= "\n-- Dump completed on " . date_i18n( 'Y-m-d H:i:s' ). "\n";
		$this->write( $dbdumpfooter );
	}

	/**
	 * Dump table structure and Data
	 *
	 * @param string $table name of Table to dump
	 */
	public function dump_table( $table ) {

			//dump View
			if ( $this->table_types[ $table ] == 'VIEW' ) {
				$tablecreate = "\n--\n-- View structure for `" . $table . "`\n--\n\n";
				$tablecreate .= "DROP VIEW IF EXISTS `" . $table . "`;\n";
				$tablecreate .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
				$tablecreate .= "/*!40101 SET character_set_client = '" . $this->mysqli->character_set_name() . "' */;\n";
				//Dump the view structure
				$res = $this->mysqli->query( "SHOW CREATE VIEW `" . $table . "`" );
				$GLOBALS[ 'wpdb' ]->num_queries ++;
				if ( $this->mysqli->error )
					trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW CREATE VIEW `" . $table . "`" ), E_USER_WARNING );
				$createview = $res->fetch_assoc();
				$res->close();
				$tablecreate .= $createview[ 'Create View' ] . ";\n";
				$tablecreate .= "/*!40101 SET character_set_client = @saved_cs_client */;\n";
				$this->write( $tablecreate );

				return;
			}

			if ( $this->table_types[ $table ] != 'BASE TABLE' )
				return;

			//dump normal Table
			$tablecreate = "\n--\n-- Table structure for `" . $table . "`\n--\n\n";
			$tablecreate .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
			$tablecreate .= "/*!40101 SET @saved_cs_client     = @@character_set_client */;\n";
			$tablecreate .= "/*!40101 SET character_set_client = '" . $this->mysqli->character_set_name() . "' */;\n";
			//Dump the table structure
			$res = $this->mysqli->query( "SHOW CREATE TABLE `" . $table . "`" );
			$GLOBALS[ 'wpdb' ]->num_queries ++;
			if ( $this->mysqli->error )
				trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SHOW CREATE TABLE `" . $table . "`" ), E_USER_WARNING );
			$createtable = $res->fetch_assoc();
			$res->close();
			$tablecreate .= $createtable[ 'Create Table' ] . ";\n";
			$tablecreate .= "/*!40101 SET character_set_client = @saved_cs_client */;\n";
			$this->write( $tablecreate );


			//Dump Table data
			$this->write( "\n--\n-- Dumping data for table `" . $table . "`\n--\n\nLOCK TABLES `" . $table . "` WRITE;\n/*!40000 ALTER TABLE `" . $table . "` DISABLE KEYS */;\n" );

			//get data from table
			$res = $this->mysqli->query( "SELECT * FROM `" . $table . "`", MYSQLI_USE_RESULT );
			$GLOBALS[ 'wpdb' ]->num_queries ++;
			if ( $this->mysqli->error )
				trigger_error( sprintf( __( 'Database error %1$s for query %2$s', 'backwpup' ), $this->mysqli->error, "SELECT * FROM `" . $table . "`" ), E_USER_WARNING );

			$fieldsarray = array();
			$fieldinfo   = array();
			$fields      = $res->fetch_fields();
			$i = 0;
			foreach ( $fields as $filed ) {
				$fieldsarray[ $i ]               = $filed->orgname;
				$fieldinfo[ $fieldsarray[ $i ] ] = $filed;
				$i ++;
			}

			$dump = '';
			while ( $data = $res->fetch_assoc() ) {
				$values = array();
				foreach ( $data as $key => $value ) {
					if ( is_null( $value ) || ! isset( $value ) ) // Make Value NULL to string NULL
						$value = "NULL";
					elseif ( in_array($fieldinfo[ $key ]->type, array( MYSQLI_TYPE_DECIMAL, MYSQLI_TYPE_TINY, MYSQLI_TYPE_SHORT, MYSQLI_TYPE_LONG,  MYSQLI_TYPE_FLOAT, MYSQLI_TYPE_DOUBLE, MYSQLI_TYPE_LONGLONG, MYSQLI_TYPE_INT24 ) ) ) //is value numeric no esc
						$value = empty( $value ) ? 0 : $value;
					else
						$value = "'" . $this->mysqli->real_escape_string( $value ) . "'";
					$values[ ] = $value;
				}
				 //new query in dump on more than 50000 chars.
				if ( empty( $dump ) )
					$dump = "INSERT INTO `" . $table . "` (`" . implode( "`, `", $fieldsarray ) . "`) VALUES \n";
				if ( strlen( $dump ) <= 50000  ) {
					$dump .= "(" . implode( ", ", $values ) . "),\n";
				} else {
					$dump .= "(" . implode( ", ", $values ) . ");\n";
					$this->write( $dump );
					$dump = '';
				}
			}
			if ( ! empty( $dump ) ) {
				$dump = substr( $dump, 0, -2 ) . ";\n" ;
				$this->write( $dump );
			}
			$res->close();

			$this->write( "/*!40000 ALTER TABLE `" . $table . "` ENABLE KEYS */;\nUNLOCK TABLES;\n" );


	}

	/**
	 * Writes data to handle and compress
	 *
	 * @param $data string to write
	 * @throws BackWPup_MySQLDump_Exception
	 */
	private function write( $data ) {

		if ( $this->compression == 'gz' )
			$written = fwrite( $this->handle, gzencode( $data ) );
		elseif ( $this->compression == 'bz2' )
			$written = fwrite( $this->handle, bzcompress( $data ) );
		else
			$written = fwrite( $this->handle, $data );

		if ( ! $written )
			throw new BackWPup_MySQLDump_Exception( __( 'Error while writing file!', 'backwpup' ) );
	}

	/**
	 * Closes all confections on shutdown.
	 */
	public function __destruct() {

		//close MySQL connection
		$this->mysqli->close();
		//close file handle
		fclose( $this->handle );
	}

}

/**
 * Exception Handler
 */
class BackWPup_MySQLDump_Exception extends Exception { }
