		<?php
		if ( is_page_template('templates/template-full.php') || has_category( 'picture' ) ) {
			$class = 'large-12';
		} elseif (is_single()) {
			$class = $class = 'large-6 large-push-3 small-12';
		}

		else {
			$class = 'large-8 large-push-2 small-12';
		}
		?>

		<footer>
			<div class="row footer__row content-container">
				<div class="columns <?php echo $class; ?>">
					<p>&copy; <?php echo date('Y'); ?> Andrew JD Hudson</p>
				</div>
			</div>
		</footer>
		<?php wp_footer(); ?>
	</body>
</html>
