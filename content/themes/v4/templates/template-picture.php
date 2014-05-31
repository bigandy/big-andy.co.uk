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
					    if (has_post_thumbnail()) {

							$sizes = ba_get_extra_thumbnail_sizes();
							$post_thumbnail_id = get_post_thumbnail_id( $post_id );

							echo '<picture>';

						    	foreach ($sizes as $size => $key) {


							    	$thumb = wp_get_attachment_image_src($post_thumbnail_id, $size);

							    	// ba_preit($thumb[0]);
						    		echo '<source media="(min-width: '.$key.'px)" srcset="'.$thumb[0].'"></source>';


						    	}

						    	$fallback_thumb = wp_get_attachment_image_src($post_thumbnail_id, 'large');
						    	echo '<img src="'.$fallback_thumb[0].'" />';
					    	echo '</picture>';
					    }

					    the_content(); ?>
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
