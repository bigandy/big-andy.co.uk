<?php 
/**
 * Template Name: CV
 *
 */

get_header(); ?>

<div id="primary" class="green-template">
	<div id="content" role="main">
		
		
	<header>	
		<?php the_post(); ?>
	</header>
		<section>
			<h1><?php the_title(); ?></h1>
		
			<?php the_content(); ?>
		</section>
	</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer('no-widgets'); ?>