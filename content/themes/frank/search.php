<?php
/**
 * @package WordPress
 * @subpackage Frank
 */
?>
<?php get_header(); ?>
<div id="content" class="archive">
	<div class="row">
	<div id="content_primary" class='nine columns'>
	<?php if(have_posts()) : ?>
	<header>
		<hgroup>
		<h1>Search Results for &#8216;<?php the_search_query(); ?>&#8217;</h1>
		<h2>Click on one of the items below to go to the post</h2>
		</hgroup>
	</header>
	<div class="posts">
		<?php while(have_posts()) : the_post(); ?>
			<article itemscope itemtype="http://schema.org/BlogPosting" class='clear'>
				<header>
					<h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
				</header>
				<div class="row">
					<section class='nine columns push-three'>
						<?php the_content('Read On&hellip;'); ?>
					</section>
					<div class='three columns pull-nine post-info'>
						<ul class='metadata vertical'>
							<li class="date"><time datetime="<?php the_time('Y-m-d'); ?>" itemprop="datePublished"><?php the_time(get_option('date_format')); ?></time></li>
							<li class="author">By <?php the_author_link(); ?></li>
							<li class="categories"><?php the_category(', '); ?></li>
							<li class="tags"><?php the_tags('', ', '); ?></li>
							<li class="comments"><?php comments_popup_link('No comments', '1 comment', '% comments'); ?></li>	
						</ul>
					</div>
					</div>
			</article>
		<?php endwhile; ?>
		<?php
		$next = get_next_posts_link();
		$prev = get_previous_posts_link();
		?>
		<?php if($next || $prev): ?>	
		<div class="pagination">			
			<?php if($next): ?>
				<a href='<?php echo next_posts( $max_page, false ); ?>' class='button'>Older Entries</a>
			<?php endif; ?>
			<?php if($prev): ?>
				<a href='<?php echo previous_posts( $max_page, false ); ?>' class='button'>Newer Entries</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php else : ?>
		<div class="post">
		
			<h2>Page Not Found</h2>
			
			<p>Looks like the page you're looking for isn't here anymore. Try using the search box below.</p>
			
			<?php get_search_form(true); ?>		
		</div>
		<?php endif; ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
	</div>
</div>

<?php get_footer(); ?>