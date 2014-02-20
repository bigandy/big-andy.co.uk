<!doctype html>
<html lang="en" <?php if( is_user_logged_in() ) { echo 'class="logged-in"'; } ?>>
<head>
	<meta charset="utf-8">
	<title><?php wp_title(); ?></title>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/requirejs/require.js" data-main="<?php echo get_stylesheet_directory_uri(); ?>/build/js/script.min.js"></script>
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<div class="header__row content-container">
			<h1>
				<a href="<?php echo home_url(); ?>">
					Andrew Hudson
				</a>
			</h1>
		</div>
	</header>