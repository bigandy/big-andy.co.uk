<?php get_header(); ?>
<main>
	<div class="row content-container">
		<section class="large-10 large-push-1 small-12 columns home__intro">
			<?php
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					the_content();
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
	    			    <article role="article" class="large-8 large-push-2 small-12 columns">
	    					<header class="article__header article__header--front-page">
	    						<time class="article__time" datetime="<?php the_time( 'c' ); ?>" pubdate>
	    							<?php the_time( 'd M' ); ?>
	    						</time>
	    					    <h1>
	    					   		<a href="<?php the_permalink(); ?>">
	    					   			<?php the_title(); ?>
	    					   		</a>
	    						</h1>
	    			    	</header>
	    					<section class="post-content clearfix article__content--front-page">
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
