<?php
get_header();
$paged = get_query_var( 'page' );
?>
<main>
	<section class="home__articles">
		<?php
		$home_args = array(
			'cat' => -31,
			'paged' => $paged,
			'posts_per_page' => 20,
		);

		$home_loop = new WP_Query( $home_args );

		if ( $home_loop->have_posts() ) {
			while ( $home_loop->have_posts() ) {
				$home_loop->the_post();
				?>
				<article>
					<header class="article__header">
						<h2 class="article__title">
							<a href="<?php the_permalink(); ?>" class="article__link">
								<?php
								the_title();
								?>
							</a>
						</h2>
						<time class="article__time" datetime="<?php the_time( 'c' ); ?>">
							<?php the_time( 'd/m/Y' ); ?>
						</time>
					</header>

					<div class="article__content">
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
</main>
<?php
get_footer();
