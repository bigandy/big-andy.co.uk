<?php get_header(); ?>
<main>
	<div class="container">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
				<article class="article">
					<header class="article__header">
						<h1 class="article__title">
							<?php the_title(); ?>
						</h1>
						<time class="article__date" datetime="<?php the_time( 'c' ); ?>">
							<?php the_time( 'd/m/Y' ); ?>
						</time>
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
	</div>
</main>
<?php
get_footer();
