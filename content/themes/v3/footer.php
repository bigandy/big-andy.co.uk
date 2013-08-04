			<footer class="footer" role="contentinfo">

				<div class="wrap inner-footer">

					<?php if( !is_page_template('page-templates/thoughts.php' ) ) {  ?>

					<p class="attribution">&copy; <?php echo date('Y'); ?> Andrew JD Hudson</p>

					<?php } ?>

				</div> <!-- end .inner-footer -->

			</footer> <!-- end footer -->

		</div> <!-- end .container -->

		<?php wp_footer(); // js scripts are inserted using this function ?>
		<?php if( !is_page( '4' ) ) { ?>
			<script type="text/javascript" src="/content/themes/v3/library/js/build/script.min.js"></script>
		<?php
		} ?>

	</body>

</html>