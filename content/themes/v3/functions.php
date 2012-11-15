<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images, 
sidebars, comments, ect.
*/

/************* INCLUDE NEEDED FILES ***************/

/*
1. library/bones.php
    - head cleanup (remove rsd, uri links, junk css, ect)
	- enqueueing scripts & styles
	- theme support functions
    - custom menu output & fallbacks
	- related post function
	- page-navi function
	- removing <p> from around images
	- customizing the post excerpt
	- custom google+ integration
	- adding custom fields to user profiles
*/
require_once('library/bones.php'); // if you remove this, bones will break

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
/* 
to add more sizes, simply copy a line from above 
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image, 
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
    register_sidebar(array(
    	'id' => 'sidebar1',
    	'name' => 'Sidebar 1',
    	'description' => 'The first (primary) sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));
    
    /* 
    to add more sidebars or widgetized areas, just copy
    and edit the above sidebar code. In order to call 
    your new sidebar just use the following code:
    
    Just change the name to whatever your new
    sidebar's id is, for example:
    
    register_sidebar(array(
    	'id' => 'sidebar2',
    	'name' => 'Sidebar 2',
    	'description' => 'The second (secondary) sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>',
    	'before_title' => '<h4 class="widgettitle">',
    	'after_title' => '</h4>',
    ));
    
    To call the sidebar in your template, you can just copy
    the sidebar.php file and rename it to your sidebar's name.
    So using the above example, it would be:
    sidebar-sidebar2.php
    
    */
} // don't remove this bracket!

/************* COMMENT LAYOUT *********************/
		
// Comment Layout
function bones_comments($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?>>
		<article id="comment-<?php comment_ID(); ?>" class="clearfix">
			<header class="comment-author vcard">
			    <?php /*
			        this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
			        echo get_avatar($comment,$size='32',$default='<path_to_url>' );
			    */ ?>
			    <!-- custom gravatar call -->
          <?php $bgauthemail = get_comment_author_email(); ?>   
			    <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>&s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />			    <!-- end custom gravatar call -->
				<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
				<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
				<?php edit_comment_link(__('(Edit)', 'bonestheme'),'  ','') ?>
			</header>
			<?php if ($comment->comment_approved == '0') : ?>
       			<div class="alert info">
          			<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
          		</div>
			<?php endif; ?>
			<section class="comment_content clearfix">
				<?php comment_text() ?>
			</section>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</article>
    <!-- </li> is added by WordPress automatically -->
<?php
} // don't remove this bracket!

/************* SEARCH FORM LAYOUT *****************/

// Search Form
function bones_wpsearch($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </form>';
    return $form;
} // don't remove this bracket!


// walker class!
// http://www.kriesi.at/archives/improve-your-wordpress-navigation-menu-output
class description_walker extends Walker_Nav_Menu
{
          
      function start_el(&$output, $item, $depth, $args)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           
            $item_output .= '<a'. $attributes . $class_names .'>';
            $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= '</a>';

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            
      }

      function end_el( &$output, $category, $depth = 0, $args = array() ) {
          $output .= "\n";
      }


}

// deregister style and scripts so can combine in one .css and one .js file

add_action( 'init', 'sd_add_speakerdeck_oembed' );
function sd_add_speakerdeck_oembed() {
  wp_oembed_add_provider( 'http://speakerdeck.com/u/*/p/*', 'http://speakerdeck.com/oembed.json' );
}





/* Imported from exp.big-andy.co.uk theme */

/* 
* Add function to remove message on login fail saying username is invalid
* http://wordpress.stackexchange.com/questions/25099/change-login-error-messages
*/

add_filter('login_errors','login_error_message');

function login_error_message($error){
    //check if that's the error you are looking for
    $pos = strpos($error, 'incorrect');
    if ($pos === false) {
        //its the right error so you can overwrite it
        $error = "<p><strong>Wrong username or password. TRY AGAIN!</strong></p>";
    }
    return $error;
}
// http://php.net/manual/en/datetime.diff.php
date_default_timezone_set('Europe/London');
function dateDiff($start, $end, $upOrDown = 'down') {
        
        
        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        $diff = $end_ts - $start_ts;
        
        if($upOrDown === 'down') {
                return ceil($diff / 86400);// ceil() rounds up. round() rounds up from .5 and down from .4 floor() rounds down.
        } else {
                return floor($diff / 86400);// ceil() rounds up. round() rounds up from .5 and down from .4 floor() rounds down.
        }

}

/* 
* Convert time in the format hr:min:sec into fractions of minute. 
* e.g. 42:57 is 42 + (57/60) = 42.95mins 
*/
function ah_minutes_from_time($str){
        
        $chars = preg_split('#(?<!\\\)\:#', $str);

        if ( strlen($str) <= 3) {
                $seconds = $chars[0];
                $minutes_from_seconds = $seconds / 60;
                
                $total_minutes = $minutes_from_seconds;
        }
        elseif (strlen($str) <= 5) {
                $minutes = $chars[0];
                $seconds = $chars[1];
                $minutes_from_seconds = $seconds / 60;
                
                $total_minutes = $minutes_from_seconds + $minutes;
        }
        else {
                $hours = $chars[0];
                $minutes = $chars[1];
                $seconds = $chars[2];
                $minutes_from_hour = $hours * 60;
                $minutes_from_seconds = $seconds / 60;
                
                $total_minutes = $minutes_from_hour + $minutes_from_seconds + $minutes;
        }
        
        return $total_minutes;
}

/*
* Pace = minutes / miles 
* number of minutes / number of miles = pace
* 
*/
function ah_pace_calculator($minutes,$distance) {

                $real_minutes = ah_minutes_from_time($minutes);
                
                $pace = round(($real_minutes / $distance),2);
                                                        
                list($minutes,$seconds)=explode('.', $pace); 
                $new_seconds = round($seconds*60/100); 
                
                $new_pace = $minutes.':'.$new_seconds;
                return $new_pace;
}