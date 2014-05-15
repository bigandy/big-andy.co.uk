<!doctype html>
<html lang="en" <?php if( is_user_logged_in() ) { echo 'class="logged-in"'; } ?>>
<head>
	<meta charset="utf-8">
	<title><?php wp_title(); ?></title>

	<link rel="stylesheet" type="text/css" href="//cloud.typography.com/7502092/707264/css/fonts.css" />

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

	<script src="<?php echo get_stylesheet_directory_uri(); ?>/bower_components/requirejs/require.js" data-main="<?php echo get_stylesheet_directory_uri(); ?>/build/js/script.min.js"></script>
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<div class="header__row content-container row">
			<h1>
				<a href="<?php echo home_url(); ?>">
					Andrew Hudson - Front-end Developer
				</a>
			</h1>
			<nav role="navigation" class="nav top-nav clearfix" id="top-nav">
				<?php
				$menu_args = array(
					'theme_location'  => 'primary',
					'container'       => 'nav',
					'container_id'    => false,
					'container_class' => 'large-12 columns header__nav',
					'menu_class'      => 'list--inline header__menu',
					'menu_id'		  => '',
				);

				wp_nav_menu( $menu_args );
				?>
			</nav>
		</div>
	</header>
