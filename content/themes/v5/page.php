<?php
/**
 * Page.php
 *
 * @package bigandy
 */

get_header();
?>
<main class="row content-container" id="main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			?>
			<article role="article" class="large-8 large-push-2 small-12 columns">
				<section class="post-content clearfix">
					<?php
					if ( has_post_thumbnail() ) {
						ah_featured_picture_replacement();
					}
					the_content();
					?>
				</section>
			</article>
		<?php
		}
	}
	wp_reset_postdata();
	?>
</main>
<?php
get_footer();
