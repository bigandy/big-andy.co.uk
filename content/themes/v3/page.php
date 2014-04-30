<?php get_header(); ?>
			<div class="content">
				<div class="wrap inner-content">
					<div class="twelvecol first clearfix main" role="main">
						<?php
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post(); ?>
								<article <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
									<header class="article-header">
										<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
										<p class="meta"><?php _e("Posted", "bonestheme"); ?> <time datetime="<?php echo the_time('Y-m-j'); ?>" pubdate><?php the_time('F jS, Y'); ?></time> <?php _e("by", "bonestheme"); ?> <?php the_author_posts_link(); ?>.</p>
									</header>
									<section class="post-content clearfix" itemprop="articleBody">
										<?php the_content(); ?>
									</section>
								</article>
							<?php
							}
						}
						?>
					</div>
				</div>
			</div>
<?php get_footer();
