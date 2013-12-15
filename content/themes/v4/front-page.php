<?php
get_header();
?>
<main>
	<div class="main__row content-container">
		<?php
		$front_args = array(
			'posts_per_page' => 5,
		);

		$front_loop = new WP_Query( $front_args );


		if( $front_loop->have_posts() ) {
			while( $front_loop->have_posts() ) {
				$front_loop->the_post();
				?>
				<article>
					<?php
					the_title( '<h1>', '</h1>' );
					the_content();
					?>
				</article>
				<?php
			}
		}
		?>
	</div>
</main>
<?php
get_footer();

