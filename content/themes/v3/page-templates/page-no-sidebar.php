<?php
/*
Template Name: No Sidebar, publish info
*/
?>

<?php get_header(); ?>
			
			<div class="content">
			
				<div class="wrap clearfix inner-content">
			
				    <div class="eightcol first clearfix main" role="main">

					    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					    <article <?php post_class('clearfix no-sidebar'); ?> role="article">							
					
						    <section class="post-content">
							    <?php the_content(); ?>
						    </section> <!-- end article section -->
						
						    <footer class="article-footer">
			
							    <p class="clearfix"><?php the_tags('<span class="tags">Tags: ', ', ', '</span>'); ?></p>
							
						    </footer> <!-- end article footer -->
						    
						    <?php // comments_template(); ?>
					
					    </article> <!-- end article -->
					
					    <?php endwhile; ?>	
					
					    <?php else : ?>
					
        					<article class="hentry clearfix post-not-found">
        					    <header class="article-header">
        						    <h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
        						</header>
        					    <section class="post-content">
        						    <p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
        						</section>
        						<footer class="article-footer">
        						    <p><?php _e("This is the error message in the page-custom.php template.", "bonestheme"); ?></p>
        						</footer>
        					</article>
					
					    <?php endif; ?>
			
				    </div> <!-- end .main -->
    
				    
				</div> <!-- end .inner-content -->
    
			</div> <!-- end .content -->

<?php get_footer(); ?>