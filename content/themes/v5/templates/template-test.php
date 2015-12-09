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
					$api_url = 'http://api.wordpress.org/secret-key/1.0/';
					$response = wp_remote_get( $api_url );
					$header = wp_remote_retrieve_headers( $response );

					var_dump( $header );

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
