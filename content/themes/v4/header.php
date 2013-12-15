<!doctype html>
<html lang="en" <?php if( is_user_logged_in() ) { echo 'class="logged-in"'; } ?>>
<head>
	<meta charset="UTF-8">
	<title><?php wp_title(); ?></title>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" />
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<div class="header__row content-container">
			<h1>Andrew Hudson</h1>
		</div>
	</header>