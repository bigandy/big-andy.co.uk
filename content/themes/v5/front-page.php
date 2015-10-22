<?php
get_header();
$paged = get_query_var( 'page' );
?>
<main class="row content-container">
	<div class="large-8 large-push-2 small-12 columns">
		<section class="home__intro">
			<?php
			if ( have_posts() && '' === $paged ) {
				?>
					<?php
					while ( have_posts() ) {
						the_post();
						?>
						<h2 class="home__intro">
							<?php echo wp_kses_post( do_shortcode( get_the_content() ) ); ?>
						</h2>
						<?php
					}
					?>

				<?php
			} else {
				?>
				<h2 class="home__intro--archive">Post Archive: Page <?php echo esc_html( $paged ); ?></h2>
				<?php
			}
			?>
		</section>

		<section class="home__articles">
			<?php
			$home_args = array(
				'cat' => -31,
				'paged' => $paged,
			);

			$home_loop = new WP_Query( $home_args );

			if ( $home_loop->have_posts() ) {
				while ( $home_loop->have_posts() ) {
					$home_loop->the_post();
					?>
					<article role="article" class="article--home">
						<header class="article__header article__header--front-page">
							<h2 class="article__title">
								<a href="<?php the_permalink(); ?>" class="article__link">
									<?php the_title(); ?>
								</a>
							</h2>
							<time class="article__time" datetime="<?php the_time( 'c' ); ?>">
								<?php the_time( 'd/m/Y' ); ?>
							</time>
						</header>
						<div class="post-content clearfix article__content--front-page">
							<?php the_excerpt(); ?>
						</div>
					</article>
					<?php
				}
			}

			if ( function_exists( 'wp_pagenavi' ) ) {
				$pagenavi_args = array(
					'query' => $home_loop,
				);


				wp_pagenavi( $pagenavi_args );
			}

			wp_reset_postdata();
			?>
		</section>
	</div>

</main>
<?php
get_footer();
