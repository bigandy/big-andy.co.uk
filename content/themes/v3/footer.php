			<footer class="footer" role="contentinfo">
			
				<div id="inner-footer" class="wrap">
	                		
					<?php if( !is_page_template('page-templates/thoughts.php' ) ) {  ?>
					
					<p class="attribution">&copy; <?php echo date('Y'); ?> Andrew JD Hudson</p>
					
					<?php } ?>
									
				</div> <!-- end #inner-footer -->

			</footer> <!-- end footer -->
		
		</div> <!-- end #container -->
		
		<?php wp_footer(); // js scripts are inserted using this function ?>

	</body>

</html>