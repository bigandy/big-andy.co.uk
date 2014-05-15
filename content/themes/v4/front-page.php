<?php
get_header();
?>
<main>
	<div class="row content-container">
		<?php

    	// hidden post class
	    $hide_id = 31;
	    // get paged
    	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$home_args = array(
			'posts_per_page' => 6,
			'paged' => $paged,
			'cat' => -$hide_id
		);

		$home_loop = new WP_Query( $home_args );

	    if ( $home_loop->have_posts() ) {
    		while ( $home_loop->have_posts() ) {
    			$home_loop->the_post();
    			?>
			    <article role="article" class="large-6 columns">
					<header class="article-header">
					    <h1>
					   		<a href="<?php the_permalink(); ?>">
					   			<?php the_title(); ?>
					   		</a>
						</h1>
			    	</header>
					<section class="post-content clearfix">
					    <?php the_excerpt(); ?>
				    </section>
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

