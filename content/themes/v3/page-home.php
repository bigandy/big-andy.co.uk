<?php get_header(); ?>
	<div class="content">
		<div class="wrap clearfix page-home inner-content">
		    <div class="first clearfix main" role="main">
			    <section class="sevencol first">
			    	<?php
		    		if ( have_posts() ) {
		    			while ( have_posts() ) {
		    				the_post();
	    					the_content();

	    					echo "this is the content";
		    			}
		    		}
		    		wp_reset_postdata();
			    	?>
			    </section>
			    <aside class="fivecol">
			    	<?php
			    	$homepage_loop_args = array(
			    		'posts_per_page' => 2,
			    		'tax_query' => array(
					        array(
					            'taxonomy' => 'post_format',
					            'field' => 'slug',
					            'terms' => array(
					            	'post-format-aside',
					            	'post-format-gallery',
					            	'post-format-audio',
					            	'post-format-image'
					            ),
					            'operator' => 'NOT IN'
					        )
					    ),
					    'ignore_sticky_posts' => 1,
					    'cat' => -31
					); ?>
			    	<?php
			    	$homepage_query = new WP_Query( $homepage_loop_args );
			    	if ( $homepage_query->have_posts() ) {
			    		while ( $homepage_query->have_posts() ) {
			    			$homepage_query->the_post();
						    ?>
							<article>
						        <h2 class="h1">
						        	<a href="<?php the_permalink(); ?>">
						        		<?php the_title(); ?>
						        	</a>
						        </h2>
						    	<?php
						    	the_excerpt();
						    	?>
							</article>
			    		<?php
			    		}
			    	}
			    	wp_reset_postdata();
			    	?>
			    </aside>
			</div>
		</div>
	</div>
<?php get_footer();
