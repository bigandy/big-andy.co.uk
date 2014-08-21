<?php
if ( is_front_page() ) {
	$class = 'large-8 large-push-2 small-12';
	$condition = 'condition one';
} elseif ( is_page_template( 'templates/template-full.php' ) || has_category( 'picture' ) ) {
	$class = 'large-12';
	$condition = 'condition two';
} elseif ( is_single() ) {
	$class = $class = 'large-6 large-push-3 small-12';
	$condition = 'condition three';
} else {
	$class = 'large-8 large-push-2 small-12';
	$condition = 'condition four';
}
?>
<footer>
	<div class="row footer__row content-container">
		<div class="columns <?php echo $class; ?>">
			<p class="left">&copy; <?php echo date( 'Y' ); ?> Andrew JD Hudson</p>
			<a href="/style-guide" class="right footer__link link--dark">Style Guide</a>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
<link rel="stylesheet" href="/content/themes/v4/style.css">
</body>
</html>
