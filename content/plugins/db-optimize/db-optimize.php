<?php
/*
Plugin Name: DB-Optimize
Plugin URI: 
Description: You can use DB - Optimize to reclaim the unused space and to defragment the database.
Version: 1.1
Author: Markus M&#252;ller
Author URI: http://www.dreams.de/
Lizenz: GPL2
*/

if ( is_admin() ) {	
	
	class optimize_table {

		function format_size($size) {
			$measure = "Byte";
	
			if ($size >= 1024) {
				$size = $size / 1024;
				$measure = "kiB";
			}
 
			if ($size >= 1024) {
				$size = $size / 1024;
				$measure = "MiB";
			}

		$return = sprintf('%0.4s',$size);

		if (substr($return, -1) == "." ) $return = substr($return, 0, -1);


		return $return . " ". $measure;
		}

		function backend_output() {
		?>
	<div class="wrap">
	<div id="icon-tools" class="icon32"><br /></div>
	<h2><?php _e("Optimize All Tables",'db-optimize'); ?></h2>
	<br />

	<div class="clear"></div>

	<table class="widefat fixed" cellspacing="0">
		<thead>
		<tr>
		<th scope="col" id="name" class="manage-column column-name" style=""><?php _e("Table",'db-optimize'); ?></th>
		<th scope="col" id="status" class="manage-column column-desc" style=""><?php _e("Status",'db-optimize'); ?></th>
		<th scope="col" id="size" class="manage-column column-name" style="width:8em;text-align:right;"><?php _e("Size",'db-optimize'); ?></th>
		<th scope="col" id="overhead" class="manage-column column-name" style="width:8em;text-align:right;"><?php _e("Overhead",'db-optimize'); ?></th>

		</tr>
		</thead>

		<tfoot>
		<tr>
		<th scope="col" id="name" class="manage-column column-name" style=""><?php _e("Table",'db-optimize'); ?></th>
		<th scope="col" id="status" class="manage-column column-desc" style=""><?php _e("Status",'db-optimize'); ?></th>
		<th scope="col" id="size" class="manage-column column-name" style="width:8em;text-align:right;"><?php _e("Size",'db-optimize'); ?></th>
		<th scope="col" id="overhead" class="manage-column column-name" style="width:8em;text-align:right;"><?php _e("Overhead",'db-optimize'); ?></th>
		</tr>
		</tfoot>

		<tbody>

		<?php
		global $wpdb; // um die class auch in der funktion zu haben

		$all_tables = array();
		$all_tables = $wpdb->get_results('SHOW TABLE STATUS',ARRAY_A);

		foreach ($all_tables as $key => $value){

		$result = $wpdb->get_results("OPTIMIZE TABLE ".$value[Name],ARRAY_A);
			if ($result != false) {
				$result = $result[0];

		echo "			<tr class=\"alternate\">
				<td class='row-title'>$result[Table]<br /></td>
				<td style=''>$result[Msg_type] : $result[Msg_text]<br /></td>
				<td style=\"text-align:right;\">".$this->format_size($value[Data_length])."<br /></td>
				<td style=\"text-align:right;\">".$this->format_size($value[Data_free])."<br /></td>
				</tr>";
				}
			}
			?>
		</tbody>
	</table>
	</div>
	<?php
		}

		function help_menu(){
			return __('<strong>Will I have to configure the plugin ?</strong><br />No, automatic detection of the database tables and optimization.','db-optimize');
		}

		function admin_menu() {
			$_page_hook = add_management_page(__('Optimize All Tables','db-optimize'),__('DB - Optimize','db-optimize'), 8, __FILE__, array(&$this, 'backend_output')); //optionenseite hinzufügen

			if ( function_exists('add_contextual_help') ) {
				add_contextual_help($_page_hook, $this->help_menu());
			}
		}

		function optimize_table() {
		// wird ausgeführt wenn class geladen wird

			add_action('admin_menu', array(&$this, 'admin_menu'));
			load_plugin_textdomain('db-optimize', false, dirname(plugin_basename(__FILE__)));
		}

	} // class

	new optimize_table();

} // is_admin
?>