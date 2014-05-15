<?php
get_header();
?>
<main>
	<div class="row content-container">
		<?php
		if ( have_posts() ) {
    		while ( have_posts() ) {
    			the_post();
    			?>
			    <article role="article" class="large-6 columns">
					<header class="article-header">
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
