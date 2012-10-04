<?php get_header(); ?>
			
			<div id="content">
			
				<div id="inner-content" class="wrap clearfix">
			
				    <div id="main" class="eightcol first clearfix" role="main">
				    
				    	<!-- Hidden Post Class -->
				    	
				    	<?php 

				    	$idObj = get_category_by_slug('hide');
					    $hide_id = $idObj->term_id;

				    	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

						

				    	if(current_user_can('update_core')) {

				    		if ( $paged < 2 ) 

						{
				    	$first_article_loop = new WP_Query('cat='.$hide_id.'&posts_per_page=1'); 
						if($first_article_loop->have_posts()) : while ( $first_article_loop->have_posts() ) : $first_article_loop->the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
							<?php the_title( '<h2><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>' ); ?>
							<section class="post-content clearfix">
							    <?php // the_content(); ?>
						    </section> <!-- end article section -->
						</article>
										
						<?php 
							endwhile; endif;
							wp_reset_postdata(); 
							} 

						} 

						?>
						
					    <?php 

					    


					    $non_hide_loop = new WP_Query('cat=-'.$hide_id.'&paged='.get_query_var('paged'));
					    if ($non_hide_loop->have_posts()) : while ($non_hide_loop->have_posts()) : $non_hide_loop->the_post(); ?>
					
					    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">
						
						    <header class="article-header">
							
							    <h1 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h1>
													
						    </header> <!-- end article header -->
					
						    <section class="post-content clearfix">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						
						    
						    
						    <?php // comments_template(); // uncomment if you want to use them ?>
					
					    </article> <!-- end article -->
					
					    <?php endwhile; wp_reset_postdata(); ?>	
					
					        <?php if (function_exists('bones_page_navi')) { // if experimental feature is active ?>
						
						        <?php bones_page_navi(); // use the page navi function ?>
						
					        <?php } else { // if it is disabled, display regular wp prev & next links ?>
						        <nav class="wp-prev-next">
							        <ul class="clearfix">
								        <li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', 'bonestheme')) ?></li>
								        <li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', 'bonestheme')) ?></li>
							        </ul>
						        </nav>
					        <?php } ?>		
					
					    <?php else : ?>
					    
					        
					
					    <?php endif; ?>
			
				    </div> <!-- end #main -->
    
				    <?php // get_sidebar(); // sidebar 1 ?>
				    
				</div> <!-- end #inner-content -->
    
			</div> <!-- end #content -->

<?php get_footer(); ?>
