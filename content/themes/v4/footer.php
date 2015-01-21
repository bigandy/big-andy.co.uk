<?php
if ( is_front_page() ) {
	$class = 'large-8 large-push-2 small-12';
} elseif ( is_page_template( 'templates/template-full.php' ) || has_category( 'picture' ) ) {
	$class = 'large-12';
} elseif ( is_single() ) {
	$class = $class = 'large-6 large-push-3 small-12';
} else {
	$class = 'large-8 large-push-2 small-12';
}
?>
<footer>
	<div class="row footer__row content-container">
		<div class="columns <?php echo esc_attr( $class ); ?>">
			<p class="left">&copy; <?php echo esc_attr( date( 'Y' ) ); ?> Andrew JD Hudson</p>
			<a href="/style-guide" class="right footer__link link--dark">Style Guide</a>
		</div>
	</div>
</footer>

<?php
if ( ! is_user_logged_in() ) {
	wp_register_script( 'main', $build . 'script.min.js', false, null, true );
	?>
	<script async src="<?php echo TEMPLATEURI . 'build/js/script.min.js'; ?>"></script>
	<?php
}
?>
<script>
<?php include_once( 'build/js/font-loader.js' ); ?>
</script>
<?php wp_footer(); ?>
</body>
</html>
