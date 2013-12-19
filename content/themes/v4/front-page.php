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
					<h1>
						<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
					</h1>
					<?php
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
			<div class="flexbox-inner">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ipsam, possimus dignissimos reprehenderit sequi aliquid molestias. Vero, velit, cupiditate quis aperiam sapiente iste soluta veritatis quasi asperiores nemo illo unde ratione tempora! Atque, molestias, architecto reprehenderit optio perspiciatis temporibus veritatis corporis magnam laudantium labore nobis laboriosam nulla assumenda dolore nam incidunt illum eaque officia quo ut sint inventore delectus explicabo? Sapiente, asperiores libero hic at facilis odio tempore nam nemo quam quasi unde nostrum facere in! Repellendus, sint sit voluptatum distinctio molestias eum excepturi rerum ipsam itaque ad labore quidem saepe beatae facilis quos natus consequatur placeat iusto unde quo vitae.</p>
			</div>
			<div class="flexbox-inner">3</div>


		</div>
</main>
<?php
get_footer();

