<?php get_header(); ?>
			
			<div class="content">

				<div class="wrap clearfix inner-content">
			
					<div class="twelvecol first clearfix main" role="main">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					
							<article <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
						
								<header class="article-header">
							
									<h1 class="single-title" itemprop="headline">
										<?php the_title(); ?>
									</h1>
							
									
						
								</header> <!-- end article header -->
					
								<section class="post-content clearfix" itemprop="articleBody">
									<?php the_content(); ?>
								</section> <!-- end article section -->
						
								<footer class="article-footer">
			
									<?php the_tags('<p class="tags"><span class="tags-title">Tags:</span> ', ', ', '</p>'); ?>
									
									<p class="meta">
										<?php _e("Posted", "bonestheme"); ?>
										<time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate>
											<?php the_time('F jS, Y'); ?>
										</time>
									</p>
								</footer> <!-- end article footer -->
					
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
					    		    <p><?php _e("This is the error message in the single.php template.", "bonestheme"); ?></p>
					    		</footer>
							</article>
					
						<?php endif; ?>
			
					</div> <!-- end .main -->

				</div> <!-- end .inner-content -->
    
			</div> <!-- end .content -->

<?php get_footer(); ?>