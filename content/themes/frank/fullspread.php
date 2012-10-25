<?php
/**
 * @package WordPress
 * @subpackage Frank
 */
/*
Template Name: Full-spread Template
*/
?>
<?php get_header(); ?>
<div id="content" class="page fullspread">
	<div class="row">
	<div id="content_primary">
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>		
		<article class="post" id="p<?php the_ID(); ?>">
			<header>
				<h1><?php the_title(); ?></h1>
			</header>
			<section>
				<?php the_content(); ?>
			</section>
		</article>
		<?php endwhile; endif; ?>
	</div>
	</div>
</div>
<?php get_footer(); ?>