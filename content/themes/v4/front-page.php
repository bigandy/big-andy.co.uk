<?php
get_header();
?>
<main>
	<div class="main__row content-container">
		<?php
		$home_args = array(
			'posts_per_page' => 5
		);

		$home_loop = new WP_Query( $home_args );

	    if ( $home_loop->have_posts() ) {
    		while ( $home_loop->have_posts() ) {
    			$home_loop->the_post();
    			?>
			    <article role="article" class="large-6 columns">
					<header class="article-header">
					    <h1>
					   		<a href="<?php the_permalink(); ?>">
					   			<?php the_title(); ?>
					   		</a>
						</h1>
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

