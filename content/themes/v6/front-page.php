<?php
get_header();
$paged = get_query_var( 'page' );
?>
<main>
	<div class="container">
		<section class="front-page__articles">
			<?php
			$front_page_args = array(
				'posts_per_page' => 20,
			);

			$front_page_loop = new WP_Query( $front_page_args );

			if ( $front_page_loop->have_posts() ) {
				while ( $front_page_loop->have_posts() ) {
					$front_page_loop->the_post();
					?>
					<article class="article">
						<header class="article__header">
							<h2 class="article__title">
								<a href="<?php the_permalink(); ?>" class="article__link">
									<?php
									the_title();
									?>
								</a>
							</h2>
							<time class="article__date" datetime="<?php the_time( 'c' ); ?>">
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
					'query' => $front_page_loop,
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
