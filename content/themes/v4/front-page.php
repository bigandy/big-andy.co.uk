<?php get_header(); ?>
<main class="row content-container content-container--home">
    <?php
	// hidden post
	$hide_id = 31;

	$home_args = array(
		'posts_per_page' => 6,
		'cat' => -$hide_id,
	);

	$home_loop = new WP_Query( $home_args );

	if ( $home_loop->have_posts() ) {
		?>
		<div class="large-push-1 large-10 columns">
		<?php
		while ( $home_loop->have_posts() ) {
			$home_loop->the_post();
			?>
			<article role="article" class="">


				<header class="article__header article__header--front-page">
					<time class="article__time" datetime="<?php the_time( 'c' ); ?>">
						<?php the_time( 'jS F Y' ); ?>
					</time>
					<h2 class="article__title">
						<a href="<?php the_permalink(); ?>">
							<?php the_title(); ?>
						</a>
					</h2>
				</header>

				<div class="post-content clearfix article__content--front-page">
					<?php the_excerpt(); ?>
				</div>
			</article>
			<?php
		}
		?>
		</div>
		<?php
	}
	wp_reset_postdata();
	?>
</main>
<?php
get_footer();
