<?php get_header(); ?>
<div class="content">
	<div class="wrap clearfix inner-content">
	    <div class="eightcol first clearfix main" role="main">
	    	<?php
	    	// hidden post class
		    $hide_id = 31;
		    // get paged
	    	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    		if ( $paged < 1 )
			{
				$sticky = get_option( 'sticky_posts' );
				/* Sort the stickies with the newest ones at the top */
				rsort( $sticky );

	            /* Get the 1 newest stickies (change 1 for a different number) */
	            $sticky = array_slice( $sticky, 0, 1 );

				$sticky_loop_args = array(
					'cat' => -$hide_id,
					'posts_per_page' => 1,
					'post__in' => $sticky,
	                'ignore_sticky_posts' => 1,
				);

		    	$sticky_loop = new WP_Query( $sticky_loop_args );

				if( $sticky_loop->have_posts() ) {
					while ( $sticky_loop->have_posts() ) {
						$sticky_loop->the_post(); ?>

						<article role="article">
							<h2>
								<a href="<?php the_permalink(); ?>" rel="bookmark">
									<?php the_title(); ?>
								</a>
							</h2>
							<section class="post-content clearfix">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						</article>

					<?php
					}
				}
				wp_reset_postdata(); // end of sticky_loop
			} // end if $paged > 2

			$non_hide_loop_args = array(
				'cat' => -$hide_id,
				'post__not_in' => $sticky,
                'ignore_sticky_posts' => 1,
                'paged' => $paged
			);

			$non_hide_loop = new WP_Query( $non_hide_loop_args );
		    if ( $non_hide_loop->have_posts() ) {
		    		while ( $non_hide_loop->have_posts() ) {
		    			$non_hide_loop->the_post();
		    			?>

					    <article <?php post_class('clearfix'); ?> role="article">
							<?php
							if( !has_post_format( 'aside' ) ) {
								?>
								<header class="article-header">
								    <h1 class="h2">
								    	<a href="<?php the_permalink() ?>" rel="bookmark">
								    		<?php the_title(); ?>
								    	</a>
								    </h1>
							    </header>
								<section class="post-content clearfix">
								    <?php the_content(); ?>
							    </section> <!-- end article section -->
							    <?php
							} else {
								?>
								<section class="post-content clearfix aside">
								    <p>
								    	<?php the_content(); ?>
								    </p>
							    </section>
							<?php
							} ?>
					    </article>
	    			<?php
	    	 		}
	    	 		wp_reset_postdata(); // end non_hide_loop()

	    	 		if (function_exists('bones_page_navi')) { // if experimental feature is active ?>
			        <?php bones_page_navi(); // use the page navi function ?>
		        <?php
		    	}
		    }
		    ?>
	    </div>
	</div>
</div>

<?php get_footer();
