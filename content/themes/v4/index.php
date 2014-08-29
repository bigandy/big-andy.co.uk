<?php get_header(); ?>
<main class="row content-container">
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
				<div class="post-content clearfix">
					<?php the_content(); ?>
			    </div>
		    </article>
		<?php
		}
	}
	wp_reset_postdata();
	?>
</main>
<?php
get_footer();
