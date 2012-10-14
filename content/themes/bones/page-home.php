<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix page-home">
			
				    <div id="main" class="first clearfix" role="main">
				    	<?php 
				    	$args = array(
				    		'posts_per_page' => 5,
							'tax_query' => array(
								array(
							    	'taxonomy' => 'post_format',
							      	'field' => 'slug',
							      	'terms' => array(
							      		'post-format-aside',
							      		'post-format-gallery',
							      		'post-format-audio'
							      	),
							      	'operator' => 'NOT IN'
							    )
							)
						); ?>
				    	<?php $my_query = new WP_Query( $args );  $c = 0; ?>
					    <?php if ($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post(); 
					    	$c++; 
					    	if ($c == 1) {
					    		$style = 'first ';
					    		$c = 0;
					    	} else {
					    		$style = '';
					    	}
					    ?>
						<div class="<?php echo $style; ?> threecol">
						        <h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
						    
						    <?php the_excerpt(); ?>
						</div>
					    <?php endwhile; ?>		
					
					    
					    <?php endif; wp_reset_postdata(); ?>
					    
					    
			
    				</div> <!-- end #main -->
    				
    				<?php the_content(); ?>
    
				    <?php // get_sidebar(); // sidebar 1 ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>

