<?php get_header(); ?>
<main>
	<div class="container">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
				<article class="article">
					<div class="article__content">
						<?php the_content(); ?>
					</div>
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
