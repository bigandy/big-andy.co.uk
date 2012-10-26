<?php
/**
 * @package WordPress
 * @subpackage Franklin_Street
 */
?>

<?php // Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) { ?>
			<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
			<?php return;
		}
	}
	/* This variable is for alternating comment background */
	$oddcomment = 'class="alt" ';
?>
<!-- Start editing here. -->
<div id='comments_container'>
	<header>
		<h1>The Discussion</h1>
		<h2><?php comments_number('No Comments', 'One Comment', '% Comments' );?></h2>
	</header>
<div id='comments_content'>
<?php if ($comments) : ?>
	<ul id="comments">
		
	<?php wp_list_comments( array( 'callback' => 'frank_comment' ) ); ?>	
		
	</ul>
	<?php paginate_comments_links(); ?>
	<?php else : // this is displayed if there are no comments so far ?>

		<?php if ('open' == $post->comment_status) : ?>
			<p class="no_comments">Be the first to leave a comment. Don&rsquo;t be shy.</p>
		<?php else : ?>
		<p class="comments_closed">Comments are closed.</p>
	<?php endif; ?>
<?php endif; ?>


<?php if ('open' == $post->comment_status) : ?>

	<div id="comment_form" class="clear">
		
		<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
		<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

		<?php else : ?>		
		<?php
		$fields =  array(
			'author' => '<div class="three columns" id="comment_form_info">' . '<label for="author">' . __( 'Name' ) . '' . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
			            '<input id="author" name="author" type="text" placeholder="Name (required)" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />',
			'email'  => '<label for="email">' . __( 'Email' ) . '' . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
			            '<input id="email" name="email" type="text" placeholder="Email (required)" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />',
			'url'    => '<label for="url">' . __( 'Website' ) . '</label>' .
			            '<input id="url" name="url" type="text" placeholder="Website" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>',
		); 
		
		$comment_field = '<div id="comment_form_comment" class="nine columns"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" placeholder="Your Comment" name="comment" aria-required="true"></textarea></div>';

		$logged_in_as = '<div class="logged-in-as three columns"><p>' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p></div>';
		
		$comment_notes_after = '<div class="row"><div class="form-allowed-tags nine columns push-three"><p class="">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p></div></div>';
		
		comment_form(array('id_form' => 'frm-comment', 'logged_in_as' => $logged_in_as, 'comment_notes_before' => '', 'comment_notes_after' => $comment_notes_after, 'title_reply' => 'Leave a Comment', 'fields' => $fields, 'comment_field' => $comment_field)); 
		?>
		
</div>
	<?php endif; endif; ?>
</div>
</div>