<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix page-home">
			
				    <div id="main" class="first clearfix" role="main">
				    
					    <section class="sevencol first">
					    	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					    	<?php the_content(); endwhile; endif; wp_reset_postdata(); ?>
					    </section>
					    <aside class="fivecol">
					    	<?php 
					    	$args = array(
					    		'posts_per_page' => 4,
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
					    	<?php $my_query = new WP_Query( $args );  $c = 0; ?>
						    <?php if ($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post(); 
						    	 
						    	if ($c == 0 || $c % 2 == 0) {
						    		$style = 'first ';
						    		$c = 0;
						    	} else {
						    		$style = '';
						    	}
						    	$c++;
						    ?>
							<article class="<?php echo $style; ?>sixcol">
							        <h2 class="h1"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							    
							    <?php the_excerpt(); ?>
							</article>
						    <?php endwhile; ?>		
						
						    
						    <?php endif; wp_reset_postdata(); ?>
					    </aside>
					    
			
    				</div> <!-- end #main -->
    				
    				
    
				    <?php // get_sidebar(); // sidebar 1 ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
