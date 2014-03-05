<?php get_header(); ?>
			<div class="content">
				<div class="wrap clearfix inner-content">
					<div class="twelvecol first clearfix main" role="main">
						<?php
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post(); ?>
								<article <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
									<header class="article-header">
										<h1 class="h2 single-title" itemprop="headline">
											<?php the_title(); ?>
										</h1>
										<p class="meta">
											<?php _e("Posted", "bonestheme"); ?>
											<time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate>
												<?php the_time('F jS, Y'); ?>
											</time>
											by <a href="<?php echo home_url(); ?>/author/andrew-hudson/">
												Andrew</a>
											<span class="amp">&amp;</span> filed under <?php the_category(', '); ?>.</p>
									</header>
									<section class="post-content clearfix" itemprop="articleBody">
										<?php the_content(); ?>
									</section>
									<footer class="article-footer">
										<?php the_tags('<p class="tags"><span class="tags-title">Tags:</span> ', ', ', '</p>'); ?>
									</footer>
									<?php if( !has_post_format('aside' ) ) {  // hiding comments for aside posts
										comments_template();
									} // comments should go inside the article element ?>
								</article>
							<?php
							}

						} ?>
					</div>
				</div>
			</div>
<?php get_footer();
