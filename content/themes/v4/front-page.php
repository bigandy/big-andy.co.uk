<?php
get_header();
?>
<main>
	<div class="main__row content-container">
		<?php
		$front_args = array(
			'posts_per_page' => 1,
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

		<div class="flexbox-container">

			<div class="flexbox-inner">1</div>
			<div class="flexbox-inner">2</div>
			<div class="flexbox-inner">3</div>
			<div class="flexbox-inner">4</div>
			<div class="flexbox-inner">5</div>


		</div>
</main>
<?php
get_footer();

