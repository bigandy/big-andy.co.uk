<?php get_header(); ?>
<main class="row content-container" id="main">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			if ( has_category( 'picture' ) ) {
				$class = 'large-12';
			} else {
				$class = 'large-6 large-push-3 small-12';
			}
			?>
			<div class="links">
				<div class="left">
					<?php previous_post_link( '&laquo; <span class="visuallyhidden">Previous Post: </span>%link' ); ?>
				</div>
				<div class="right">
					<?php next_post_link( '<span class="visuallyhidden">Next Post: </span>%link &raquo;' ); ?>
				</div>
			</div>

			<article role="article" class="columns <?php echo esc_attr( $class ); ?>">
				<header class="article__header">
					<?php
					if ( has_post_thumbnail() ) {
						ah_featured_resp_image_replacement( 'article__header__image' );
					}
					?>
					<h1 class="article__title">
						<?php the_title(); ?>
					</h1>
					<time class="article__time" datetime="<?php the_time( 'c' ); ?>">
						<?php the_time( 'd.m.Y' ); ?>
					</time>
				</header>
				<section class="post-content clearfix">
					<?php
					the_content();

					edit_post_link( 'Edit Post', '<p>', '</p>' );

					if ( is_user_logged_in() && function_exists( 'gutenberg_can_init' ) ) {
						echo '<p><a href="' . esc_url( get_admin_url( null, "admin.php?page=gutenberg&post_id=" . get_the_ID() ) ) . '">Edit with Gutenberg</a></p>';
					}
					?>
				</section>
			</article>
		<?php
		}
	}
	wp_reset_postdata();
	?>
</main>
<?php
get_footer();
