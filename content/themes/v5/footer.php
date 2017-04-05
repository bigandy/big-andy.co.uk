<footer>
	<div class="row footer__row content-container">
		<p class="left">&copy; 2006 - <?php echo esc_attr( date( 'Y' ) ); ?> Andrew JD Hudson</p>
		<a class="right" href="/style-guide" class="right footer__link link--dark">Style Guide</a>
	</div>
</footer>

<?php
if ( ! is_user_logged_in() ) {
	?>
	<script async src="/content/themes/v5/build/js/script.min.js"></script>
	<?php
}
?>
<?php wp_footer(); ?>
</body>
</html>
