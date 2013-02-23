<?php get_header(); ?>
			
			<div class="content">
			
				<div class="wrap clearfix page-home inner-content">
			
				    <div class="first clearfix main" role="main">
				    
					    <section class="sevencol first">
					    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					    	<?php the_content(); endwhile; endif; wp_reset_postdata(); ?>
					    </section>
					    <aside class="fivecol">
					    	<?php 
					    	$args = array(
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
							    )
								
							); ?>
					    	<?php $my_query = new WP_Query( $args ); ?>
						    <?php 
						    	if ($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post(); 
						    ?>
							<article>
						        <h2 class="h1"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						    	<?php the_excerpt(); ?>
							</article>
						    <?php endwhile; ?>		
						    <?php endif; wp_reset_postdata(); ?>
					    </aside>
					    
			
    				</div> <!-- end .main -->
    								    
				</div> <!-- end .inner-content -->
    
			</div> <!-- end .content -->

<?php get_footer(); ?>

