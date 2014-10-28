<?php get_header(); ?>
<main class="row content-container">
	<div class="large-8 large-push-2 small-12 columns">
			<section class="home__intro">
				<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						?>
						<h2 class="home__intro">
							<?php echo get_the_content(); ?>
						</h2>
						<?php
					}
				}
				?>
		    </section>
		    <?php
			// hidden post
			$hide_id = 31;

			$home_args = array(
				'posts_per_page' => 6,
				'cat' => -$hide_id,
			);

			$home_loop = new WP_Query( $home_args );

			if ( $home_loop->have_posts() ) {
				while ( $home_loop->have_posts() ) {
					$home_loop->the_post();
					?>
					<article role="article" class="article--home">
						<header class="article__header article__header--front-page">
							<h2 class="article__title">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>
							<time class="article__time" datetime="<?php the_time( 'c' ); ?>">
								<?php the_time( 'jS F Y' ); ?>
							</time>
						</header>
						<div class="post-content clearfix article__content--front-page">
							<?php the_excerpt(); ?>
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
