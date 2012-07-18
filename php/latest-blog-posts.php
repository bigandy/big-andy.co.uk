<?php
// Include Wordpress 
define('WP_USE_THEMES', false);
require('../blog.big-andy.co.uk/wp-load.php');
query_posts('showposts=3');
?>
<ul>
<?php while (have_posts()): the_post(); ?>
<li class="post"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>	
<?php endwhile; ?>
</ul>	

