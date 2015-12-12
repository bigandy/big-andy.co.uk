<?php
get_header();
/*
 * Template Name: Test Remote Get
 */
?>
<main>
	<div class="row content-container">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
				<article role="article" class="large-12 columns">
					<section class="post-content clearfix row">
					<?php
					the_content();
					?>
				</section>
			</article>
			<?php
			}
		}
		wp_reset_postdata();
		?>
	</div>
</main>
<?php
get_footer();
