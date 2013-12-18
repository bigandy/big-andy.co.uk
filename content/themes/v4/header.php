<!doctype html>
<html lang="en" <?php if( is_user_logged_in() ) { echo 'class="logged-in"'; } ?>>
<head>
	<meta charset="utf-8">
	<title><?php wp_title(); ?></title>
	<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bower-components/requirejs/require.js" data-main="<?php echo get_stylesheet_directory_uri(); ?>/js/build/app.min.js"></script>
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<div class="header__row">
			<h1>
				<a href="<?php echo home_url(); ?>">
					Andrew Hudson
				</a>
			</h1>
			<nav>
				<a href="">Home</a>
				<a href="">Blog</a>
				<a href="">About</a>
				<a href="">Portfolio</a>
				<a href="">Contact</a>
			</nav>
		</div>
	</header>