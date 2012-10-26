<?php
/**
 * @package Frank
 */
?>
<?php get_header(); ?>
<div id="content" class="fullspread clear fourohfour">
	<div id="content_primary">
		<header>
			<h1>Page Not Found</h1>
		</header>
		<div class='container'>
			<div class='main clear'>
				<div class="span-6">
					<p class='default-message large'>Unfortunately, the page you are looking for no longer exists or never existed in the first place. If you reached this page in error, you can go <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">home</a> and start over.</p>
				</div>
				<div class="span-6 search last">
					<p class='large'>If you believe this page exists, please try searching for the page in the search input below.</p>
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>