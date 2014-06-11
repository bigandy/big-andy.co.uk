<?php
get_header();
?>
<main>
	<div class="row content-container">
		<?php
		if ( have_posts() ) {
    		while ( have_posts() ) {
    			the_post();

    			if (has_category( 'picture' )) {
    				$class = 'large-12';
    			} else {
    				$class = 'large-6';
    			}

    			?>
			    <article role="article" class="columns <?php echo $class; ?>">
					<header class="article-header">
						<?php
						if (has_post_thumbnail()) {
							ah_featured_picture_replacement();
						}
						?>
					    <h1><?php the_title(); ?></h1>
			    	</header>
					<section class="post-content clearfix">
					    <?php the_content(); ?>
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
