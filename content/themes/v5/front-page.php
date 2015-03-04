<?php get_header(); ?>
<main class="row content-container">
	<div class="large-12 small-12 columns">
	    <section class="home__articles flex-container">
    	    <?php
			// hidden post
			$hide_id = 31;

			$home_args = array(
				'cat' => -$hide_id,
				'paged' => get_query_var( 'page' )
			);

			$home_loop = new WP_Query( $home_args );

			if ( $home_loop->have_posts() ) {
				while ( $home_loop->have_posts() ) {
					$home_loop->the_post();
					?>
					<article role="article" class="article--home flex-item">
						<header class="article__header article__header--front-page">
							<h2 class="article__title">
								<a href="<?php the_permalink(); ?>">
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

			wp_reset_postdata();
			?>
	    </section>
	</div>

</main>
<?php
get_footer();
