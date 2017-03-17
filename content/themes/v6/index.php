<?php get_header(); ?>
<main>
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
		    <article class="article">
				<header class="article__header">
				    <h1>
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
				   		</a>
					</h1>
		    	</header>
				<div class="article__content">
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
