<?php
/*
Template Name: Frank Blank Template

http://codex.wordpress.org/Function_Reference/wp_insert_post
http://codex.wordpress.org/Function_Reference/update_post_meta
http://codex.wordpress.org/Function_Reference/post_meta_Function_Examples
http://hungred.com/how-to/wordpress-templatepage-meta-key-updatepostmeta/
http://www.emanueleferonato.com/2010/04/01/loading-wordpress-posts-with-ajax-and-jquery/

*/
?>
<?php if(have_posts()) : while(have_posts()) : the_post(); ?><?php the_content(); ?><?php endwhile; endif; ?>