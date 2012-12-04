<?php
$odb_version      = '1.3.3';
$odb_release_date = '12/01/2012';
/**
 * @package Optimize Database after Deleting Revisions
 * @version 1.3.3
 */
/*
Plugin Name: Optimize Database after Deleting Revisions
Plugin URI: http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin/
Description: Optimizes the Wordpress Database after Deleting Revisions - <a href="options-general.php?page=rvg_odb_admin"><strong>plug in options</strong></a>
Author: Rolf van Gelder, Eindhoven, The Netherlands
Version: 1.3.3
Author URI: http://cagewebdev.com
*/
?>
<?php
/********************************************************************************************

	ADD THE 'OPTIMIZE DATABASE' ITEM TO THE TOOLS MENU

*********************************************************************************************/
function optimize_db_main()
{	if (function_exists('add_management_page'))
	{	add_management_page(__('Optimize Database'), __('Optimize Database'),'administrator' ,'rvg-optimize-db.php', 'rvg_optimize_db');
    }
}
add_action('admin_menu', 'optimize_db_main');


/********************************************************************************************

	ADD THE 'OPTIMIZE DB OPTIONS' ITEM TO THE SETTINGS MENU

*********************************************************************************************/
function rvg_odb_admin_menu()
{	if (function_exists('add_options_page'))
	{	add_options_page(__('Optimize DB Options'), __('Optimize DB Options'), 'manage_options', 'rvg_odb_admin', 'rvg_odb_options_page');
    }
}
add_action( 'admin_menu', 'rvg_odb_admin_menu' );


/********************************************************************************************

	CREATE THE OPTIONS PAGE

*********************************************************************************************/
function rvg_odb_options_page() {
	global $odb_version, $odb_release_date;
	
	// SAVE THE OPTIONS
 	if ( isset( $_POST['info_update'] ) ) {
		check_admin_referer();

		$rvg_odb_number = trim($_POST['rvg_odb_number']);
		update_option('rvg_odb_number', $rvg_odb_number);
		
		$rvg_clear_trash = 'N';
		if($_POST['rvg_clear_trash'] == 'Y') $rvg_clear_trash = 'Y';
		update_option('rvg_clear_trash', $rvg_clear_trash);
		
		$rvg_clear_spam = 'N';
		if($_POST['rvg_clear_spam'] == 'Y') $rvg_clear_spam = 'Y';
		update_option('rvg_clear_spam', $rvg_clear_spam);
		
		$rvg_wp_only = 'N';
		if($_POST['rvg_wp_only'] == 'Y') $rvg_wp_only = 'Y';
		update_option('rvg_wp_only', $rvg_wp_only);			

		// UPDATED MESSAGE
		echo "<div class='updated'><p><strong>Optimize Database after Deleting Revisions options updated</strong> - Click <a href='tools.php?page=rvg-optimize-db.php&action=run' style='font-weight:bold'>HERE</a> to run the optimization</p></div>";
	}
	$rvg_odb_number = get_option('rvg_odb_number');
	if(!$rvg_odb_number) $rvg_odb_number = '0';
	
	$rvg_clear_trash = get_option('rvg_clear_trash');
	if(!$rvg_clear_trash) $rvg_clear_trash = 'N';
	
	$rvg_clear_spam = get_option('rvg_clear_spam');
	if(!$rvg_clear_spam) $rvg_clear_spam = 'N';
	
	$rvg_wp_only = get_option('rvg_wp_only');
	if(!$rvg_wp_only) $rvg_wp_only = 'N';		

	// CREATE THE OPTIONS PAGE
	?>

<form method="post" action="">
  <div class="wrap">
    <h2>Using Optimize Database after Deleting Revisions</h2>
    <blockquote>
      <p><strong>'<em>Optimize Database after Deleting Revisions</em>' is an one-click plugin to optimize your WordPress database.<br />
        It deletes redundant revisions of posts and pages, trashed and/or spammed items and, after that, optimizes all database tables.</strong></p>
      <p>Below you can define the <u>maximum number</u> of - most recent - revisions you want to <u>keep</u> per post or page.<br />
        If you set the maximum number to '<strong>0</strong>' it means <strong>ALL REVISIONS</strong> will be deleted for all posts and pages.</p>
      <p>You also can choose if you want to <u>delete</u> all <u>trashed items</u> and/or <u>spammed items</u> during the optimization.</p>
      <p>To start the optimization:<br />
        In the WordPress Dashboard go to &lsquo;<strong>Tools</strong>&lsquo;.<br />
        Click on &lsquo;<strong>Optimize Database</strong>&lsquo;, then click on the '<strong>Start Optimization</strong>'-button. Et voila! </p>
      <p>Plugin version:<br />
        <strong>v<?php echo $odb_version ?> (<?php echo $odb_release_date?>)</strong> </p>
      <p>Author:<br />
        <strong><a href="http://cage.nl/" target="_blank">Rolf van Gelder</a>, <a href="http://cagewebdev.com/" target="_blank">CAGE Web Design</a></strong>, Eindhoven, The Netherlands<br />
        <br />
        Plugin URL:<br />
        <a href="http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin/" target="_blank"><strong>http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin/</strong></a><strong><br />
        </strong><br />
        Download URL:<br />
        <strong><a href="http://wordpress.org/extend/plugins/rvg-optimize-database/" target="_blank">http://wordpress.org/extend/plugins/rvg-optimize-database/</a></strong></p>
      <p>&nbsp;</p>
    </blockquote>
    <h2>Optimize Database after Deleting Revisions - Options</h2>
    <blockquote>
      <fieldset class='options'>
        <table class="editform" cellspacing="2" cellpadding="5">
          <tr>
            <td align="right"><label for="<?php echo rvg_odb_number; ?>" style="font-weight:bold;">Maximum number of - most recent - revisions to keep per post / page<br />
                ('0' means: delete ALL revisions) </label></td>
            <td><input type="text" size="5" name="rvg_odb_number" id="rvg_odb_number" value="<?php echo $rvg_odb_number?>" style="font-weight:bold;color:#00F;" /></td>
          </tr>
          <?php
if($rvg_clear_trash == 'Y') $rvg_clear_trash_checked = ' checked="checked"'; else $rvg_clear_trash_checked = '';
if($rvg_clear_spam == 'Y')  $rvg_clear_spam_checked  = ' checked="checked"'; else $rvg_clear_spam_checked = '';
if($rvg_wp_only == 'Y')     $rvg_wp_only_checked     = ' checked="checked"'; else $rvg_wp_only_checked = '';
?>
          <tr>
            <td align="right"><label for="rvg_clear_trash" style="font-weight:bold;">Delete all trashed items<br />
              </label></td>
            <td><input name="rvg_clear_trash" type="checkbox" value="Y" <?php echo $rvg_clear_trash_checked?> /></td>
          </tr>
          <tr>
            <td align="right"><label for="rvg_clear_spam" style="font-weight:bold;">Delete all spammed items<br />
              </label></td>
            <td><input name="rvg_clear_spam" type="checkbox" value="Y" <?php echo $rvg_clear_spam_checked?> /></td>
          </tr>
          <tr>
            <td align="right"><label for="rvg_wp_only" style="font-weight:bold;">Only optimize WordPress tables<br />
                (if not checked ALL tables in the database will be optimized)<br />
              </label></td>
            <td><input name="rvg_wp_only" type="checkbox" value="Y" <?php echo $rvg_wp_only_checked?> /></td>
          </tr>
        </table>
      </fieldset>
    </blockquote>
    <p class="submit">
      <input type='submit' name='info_update' value='Save Options' style="font-weight:bold;" />
    </p>
  </div>
</form>
<?php
}


/********************************************************************************************

	MAIN FUNCTION FOR DELETING REVISIONS, TRASH, SPAM AND OPTIMIZING DATABASE TABLES

*********************************************************************************************/
function rvg_optimize_db()
{
	global $wpdb, $odb_version, $table_prefix;

	/****************************************************************************************
	
		DELETE REVISIONS
	
	******************************************************************************************/
	
	// GET OPTIONS AND SET DEFAULT VALUES
	$max_revisions = get_option('rvg_odb_number');
	if(!$max_revisions)
	{	$max_revisions = 0;
		update_option('rvg_odb_number', $max_revisions);
	}
	
	$clear_trash = get_option('rvg_clear_trash');
	if(!$clear_trash)
	{	$clear_trash = 'N';
		update_option('rvg_clear_trash', $clear_trash);
	}
	$clear_trash_yn = ($clear_trash == 'N') ? 'NO' : 'YES';
	
	$clear_spam = get_option('rvg_clear_spam');
	if(!$clear_spam)
	{	$clear_spam = 'N';
		update_option('rvg_clear_spam', $clear_spam);
	}
	$clear_spam_yn = ($clear_spam == 'N') ? 'NO' : 'YES';
	
	$wp_only = get_option('rvg_wp_only');
	if(!$wp_only)
	{	$wp_only = 'N';
		update_option('rvg_wp_only', $wp_only);
	}
	$wp_only_yn = ($wp_only == 'N') ? 'NO' : 'YES';	
?>
<div style="padding-left:8px;">
  <h2>Optimize your WordPress Database</h2>
  <p><span style="font-style:italic;"><a href="http://cagewebdev.com/index.php/optimize-database-after-deleting-revisions-wordpress-plugin/" target="_blank" style="font-weight:bold;">Optimize Database after Deleting Revisions v<?php echo $odb_version?></a> - A WordPress Plugin by <a href="http://cagewebdev.com/" target="_blank" style="font-weight:bold;">Rolf van Gelder</a>, Eindhoven, The Netherlands</span></p>
  <p>Current options:<br />
    <strong>Maximum number of - most recent - revisions to keep per post / page:</strong> <span style="font-weight:bold;color:#00F;"><?php echo $max_revisions?></span><br />
    <strong>Delete trashed items:</strong> <span style="font-weight:bold;color:#00F;"><?php echo $clear_trash_yn?></span><br />
    <strong>Delete spammed items:</strong> <span style="font-weight:bold;color:#00F;"><?php echo $clear_spam_yn?></span><br />
    <strong>Only optimize WordPress tables:</strong> <span style="font-weight:bold;color:#00F;"><?php echo $wp_only_yn?></span>
    <?php
if($_REQUEST['action'] != 'run')
{
?>
  <p class="submit">
    <input type='button' name='change_options' value='Change Options' onclick="self.location='options-general.php?page=rvg_odb_admin'" style="font-weight:normal;" />
    &nbsp;
    <input class="button-primary" type='button' name='start_optimization' value='Start Optimization' onclick="self.location='tools.php?page=rvg-optimize-db.php&action=run'" style="font-weight:bold;" />
  </p>
  <?php
}
?>
</div>
<?php
if($_REQUEST['action'] != 'run')
{	return;
}
?>
<br />
<h2 style="padding-left:8px;">Starting Optimization...</h2>
<?php
	// GET THE SIZE OF THE DATABASE BEFORE OPTIMIZATION
	$start_size = rvg_get_db_size();

	$sql = "
	SELECT	`post_parent`, `post_title`, COUNT(*) cnt
	FROM	$wpdb->posts
	WHERE	`post_type` = 'revision'
	GROUP	BY `post_parent`
	HAVING	COUNT(*) > ".$max_revisions."
	ORDER	BY UCASE(`post_title`)	
	";
	$results = $wpdb -> get_results($sql);
	
	if(count($results)>0)
	{	// WE HAVE REVISIONS TO DELETE!
?>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td colspan="4" style="font-weight:bold;color:#00F;">DELETING REVISIONS:</td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:solid 1px #999;">#</th>
    <th align="left" style="border-bottom:solid 1px #999;">post / page</th>
    <th align="left" style="border-bottom:solid 1px #999;">revision date</th>
    <th align="right" style="border-bottom:solid 1px #999;">revisions deleted</th>
  </tr>
  <?php	
		$nr = 1;
		$total_deleted = 0;
		for($i=0; $i<count($results); $i++)
		{	$nr_to_delete = $results[$i]->cnt - $max_revisions;
			$total_deleted += $nr_to_delete;
?>
  <tr>
    <td align="right" valign="top"><?php echo $nr?>.</td>
    <td valign="top" style="font-weight:bold;"><?php echo $results[$i]->post_title?></td>
    <td valign="top"><?php			
			$sql_get_posts = "
			SELECT	`ID`, `post_modified`
			FROM	$wpdb->posts
			WHERE	`post_parent`=".$results[$i]->post_parent."
			AND		`post_type`='revision'
			ORDER	BY `post_modified` ASC		
			";
			$results_get_posts = $wpdb -> get_results($sql_get_posts);
			
			for($j=0; $j<$nr_to_delete; $j++)
			{
				echo $results_get_posts[$j]->post_modified.'<br />';
				
				$sql_delete = "
				DELETE FROM $wpdb->posts
				WHERE `ID` = ".$results_get_posts[$j]->ID."
				";
				$wpdb -> get_results($sql_delete);
				
			} // for($j=0; $j<$nr_to_delete; $j++)
			
			$nr++;
?></td>
    <td align="right" valign="top" style="font-weight:bold;"><?php echo $nr_to_delete?></td>
  </tr>
  <?php			
		}
?>
  <tr>
    <td colspan="3" align="right" style="border-top:solid 1px #999;font-weight:bold;">total number of revisions deleted</td>
    <td align="right" style="border-top:solid 1px #999;font-weight:bold;"><?php echo $total_deleted?></td>
  </tr>
</table>
<?php		
	}
	else
	{
?>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td style="font-weight:bold;color:#21759b;">No REVISIONS found to delete...</td>
  </tr>
</table>
<?php		
	} // if(count($results)>0)
?>
<?php
	/****************************************************************************************
	
		DELETE TRASHED ITEMS
	
	******************************************************************************************/
?>
<?php
	if($clear_trash == 'Y')
	{
		// GET TRASHED POSTS / PAGES AND COMMENTS
		$sql = "
		SELECT	`ID` AS id, 'post' AS post_type, `post_title` AS title, `post_modified` AS modified
		FROM	$wpdb->posts
		WHERE	`post_status` = 'trash'
		UNION ALL
		SELECT	`comment_ID` AS id, 'comment' AS post_type, `comment_author_IP` AS title, `comment_date` AS modified
		FROM	$wpdb->comments
		WHERE	`comment_approved` = 'trash'
		ORDER	BY post_type, UCASE(title)		
		";
		$results = $wpdb -> get_results($sql);
		
		if(count($results)>0)
		{	// WE HAVE TRASH TO DELETE!
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td colspan="4" style="font-weight:bold;color:#00F;">DELETING TRASHED ITEMS:</td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:solid 1px #999;">#</th>
    <th align="left" style="border-bottom:solid 1px #999;">type</th>
    <th align="left" style="border-bottom:solid 1px #999;">IP address / title</th>
    <th align="left" nowrap="nowrap" style="border-bottom:solid 1px #999;">date</th>
  </tr>
  <?php	
			$nr = 1;
			$total_deleted = count($results);
			for($i=0; $i<count($results); $i++)
			{
?>
  <tr>
    <td align="right" valign="top"><?php echo $nr; ?></td>
    <td valign="top"><?php echo $results[$i]->post_type; ?></td>
    <td valign="top"><?php echo $results[$i]->title; ?></td>
    <td valign="top" nowrap="nowrap"><?php echo $results[$i]->modified; ?></td>
  </tr>
  <?php	
  				if($results[$i]->post_type == 'comment')
				{	// DELETE META DATA (IF ANY...)
					$sql_delete = "
					DELETE FROM $wpdb->commentmeta WHERE `comment_id` = ".$results[$i]->id."
					";
					$wpdb -> get_results($sql_delete);  
				}
				
				$nr++;
			} // for($i=0; $i<count($results); $i++)
			
			// DELETE TRASHED POSTS / PAGES
			$sql_delete = "
			DELETE FROM $wpdb->posts WHERE `post_status` = 'trash'			
			";
			$wpdb -> get_results($sql_delete);
			
			// DELETE TRASHED COMMENTS
			$sql_delete = "
			DELETE FROM $wpdb->comments WHERE `comment_approved` = 'trash'
			";
			$wpdb -> get_results($sql_delete);			
?>
</table>
<?php			
		}
		else
		{
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td style="font-weight:bold;color:#21759b;">No TRASHED ITEMS found to delete...</td>
  </tr>
</table>
<?php		
		} // if(count($results)>0)
		
	} // if($clear_trash == 'Y')
?>
<?php
	/****************************************************************************************
	
		DELETE SPAMMED ITEMS
	
	******************************************************************************************/
?>
<?php
	if($clear_spam == 'Y')
	{
		$sql = "
		SELECT	`comment_ID`, `comment_author`, `comment_author_email`, `comment_date`
		FROM	$wpdb->comments
		WHERE	`comment_approved` = 'spam'
		ORDER	BY UCASE(`comment_author`)
		";
		$results = $wpdb -> get_results($sql);
		
		if(count($results)>0)
		{	// WE HAVE SPAM TO DELETE!
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td colspan="4" style="font-weight:bold;color:#00F;">DELETING SPAMMED ITEMS:</td>
  </tr>
  <tr>
    <th align="right" style="border-bottom:solid 1px #999;">#</th>
    <th align="left" style="border-bottom:solid 1px #999;">comment author</th>
    <th align="left" style="border-bottom:solid 1px #999;">comment author email</th>
    <th align="left" nowrap="nowrap" style="border-bottom:solid 1px #999;">comment date</th>
  </tr>
  <?php	
			$nr = 1;
			$total_deleted = count($results);
			for($i=0; $i<count($results); $i++)
			{
?>
  <tr>
    <td align="right" valign="top"><?php echo $nr; ?></td>
    <td valign="top"><?php echo $results[$i]->comment_author; ?></td>
    <td valign="top"><?php echo $results[$i]->comment_author_email; ?></td>
    <td valign="top" nowrap="nowrap"><?php echo $results[$i]->comment_date; ?></td>
  </tr>
  <?php
				$sql_delete = "
				DELETE FROM $wpdb->commentmeta WHERE `comment_id` = ".$results[$i]->comment_ID."
				";
				$wpdb -> get_results($sql_delete);
				$nr++;				
			} // for($i=0; $i<count($results); $i++)
			
			$sql_delete = "
			DELETE FROM $wpdb->comments WHERE `comment_approved` = 'spam'
			";
			$wpdb -> get_results($sql_delete);			
?>
</table>
<?php			
		}
		else
		{
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td style="font-weight:bold;color:#21759b;">No SPAMMED ITEMS found to delete...</td>
  </tr>
</table>
<?php		
		} // if(count($results)>0)
		
	} // if($clear_spam == 'Y')
?>
<?php
	/****************************************************************************************
	
		OPTIMIZE DATABASE TABLES
	
	******************************************************************************************/
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td colspan="4" style="font-weight:bold;color:#00F;">OPTIMIZING DATABASE TABLES:</td>
  </tr>
  <tr>
    <th style="border-bottom:solid 1px #999;" align="right">#</th>
    <th style="border-bottom:solid 1px #999;" align="left">table name</th>
    <th style="border-bottom:solid 1px #999;" align="left">optimization result</th>
    <th style="border-bottom:solid 1px #999;" align="left">engine</th>
    <th style="border-bottom:solid 1px #999;" align="right">table rows</th>
    <th style="border-bottom:solid 1px #999;" align="right">table size</th>
  </tr>
  <?php
	# GET TABLE NAMES
	$names = mysql_list_tables(DB_NAME);
	$cnt   = 0;
	while($row = mysql_fetch_row($names))
	{
		if($wp_only == 'N' || ($wp_only == 'Y' && substr($row[0],0,strlen($table_prefix)) == $table_prefix))
		{	# ALL TABLES OR THIS IS A WORDPRESS TABLE
			$cnt++;
			$query  = "OPTIMIZE TABLE ".$row[0];
			$result = $wpdb -> get_results($query);
			
			$sql = "
			SELECT	engine, SUM(data_length + index_length) AS size, table_rows
			FROM	information_schema.TABLES
			WHERE	table_schema = '".strtolower(DB_NAME)."'
			AND		table_name   = '".$row[0]."'
			";

			$table_info = $wpdb -> get_results($sql);
?>
  <tr>
    <td align="right" valign="top"><?php echo $cnt?>.</td>
    <td valign="top" style="font-weight:bold;"><?php echo $row[0] ?></td>
    <td valign="top"><?php echo $result[0]->Msg_text ?></td>
    <td valign="top"><?php echo $table_info[0]->engine ?></td>
    <td align="right" valign="top"><?php echo $table_info[0]->table_rows ?></td>
    <td align="right" valign="top"><?php echo rvg_format_size($table_info[0]->size) ?></td>
  </tr>
  <?php
		} // if($wp_only == 'N' || ($wp_only == 'Y' && substr($row[0],0,strlen($table_prefix)) == $table_prefix))
	} // while($row = mysql_fetch_row($names))
?>
</table>
<?php
$end_size = rvg_get_db_size();
?>
<span style="font-weight:bold;color:#000;padding-left:8px;">~~~~~</span>
<table border="0" cellspacing="8" cellpadding="2">
  <tr>
    <td colspan="2" style="font-weight:bold;color:#00F;">SAVINGS:</td>
  </tr>
  <tr>
    <th>&nbsp;</th>
    <th style="border-bottom:solid 1px #999;">size of the database</th>
  </tr>
  <tr>
    <td align="right">BEFORE optimization</td>
    <td align="right" style="font-weight:bold;"><?php echo rvg_format_size($start_size,3); ?></td>
  </tr>
  <tr>
    <td align="right">AFTER optimization</td>
    <td align="right" style="font-weight:bold;"><?php echo rvg_format_size($end_size,3); ?></td>
  </tr>
  <tr>
    <td align="right" style="font-weight:bold;">TOTAL SAVINGS</td>
    <td align="right" style="font-weight:bold;border-top:solid 1px #999;"><?php echo rvg_format_size(($start_size - $end_size),3); ?></td>
  </tr>
</table>
<br />
<span style="font-weight:bold;color:#00F;padding-left:8px;">D O N E !</span>
<?php	
} // rvg_optimize_db()
?>
<?php
/********************************************************************************************

	CALCULATE THE SIZE OF THE WORDPRESS DATABASE (IN BYTES)

*********************************************************************************************/
function rvg_get_db_size()
{
	global $wpdb;
	
	$sql = "
	SELECT	SUM(data_length + index_length) AS size
	FROM	information_schema.TABLES
	WHERE	table_schema = '".strtolower(DB_NAME)."'
	";
	
	$res = $wpdb -> get_results($sql);
	
	return $res[0]->size;
	
} // rvg_get_db_size()
?>
<?php
/********************************************************************************************

	FORMAT SIZES FROM BYTES TO KB OR MB

*********************************************************************************************/
function rvg_format_size($size, $precision=1)
{
	if($size>1024*1024)
		$table_size = (round($size / (1024*1024),$precision)).' MB';
	else
		$table_size = (round($size / 1024,$precision)).' KB';
		
	return $table_size;
} // rvg_format_size()
?>
