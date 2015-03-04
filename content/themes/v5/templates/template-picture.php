<?php
get_header();
/*
 * Template Name: Picture Template
 */
?>
<main class="picture-template">
	<div class="row content-container">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				?>
			    <article role="article" class="large-12 columns">
					<section class="post-content clearfix row">
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
	</div>
</main>
<?php
get_footer();
