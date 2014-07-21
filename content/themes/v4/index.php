<?php get_header(); ?>
<main>
	<div class="row content-container">
		<?php
		if ( have_posts() ) {
    		while ( have_posts() ) {
    			the_post();
    			?>
			    <article role="article" class="large-6 columns">
					<header class="article__header">
					    <h1>
					   		<a href="<?php the_permalink(); ?>">
					   			<?php the_title(); ?>
					   		</a>
						</h1>
			    	</header>
					<section class="post-content clearfix">
					    <?php the_content(); ?>
				    </section>
			    </article>
			<?php
	 		}
	 	}
	 	wp_reset_postdata();
	 	?>
	</div>
</main>
<?php
get_footer();
