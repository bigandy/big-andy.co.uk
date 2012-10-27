<?php
/*
Template Name: Thoughts
*/
?>

<?php get_header(); ?>
			
			
					    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
					    <article <?php post_class('thoughts-template'); ?> role="article">				<?php
								echo get_option('current_page_template');
							?>		
						   	<h1><?php the_title(); ?></h1>
						    
							<?php the_content(); ?>
					
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
			
<?php get_footer(); ?>