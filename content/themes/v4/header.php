<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="dns-prefetch" href="//cloud.typography.com">
	<link rel="stylesheet" type="text/css" href="//cloud.typography.com/7502092/707264/css/fonts.css" />
	<meta name="robots" content="all" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>
		<?php wp_title(); ?>
	</title>
	<?php wp_head(); ?>
</head>
<body>
	<header class="header">
		<div class="header__row content-container row">
			<div class="large-12 columns">
				<h1>
					<a href="<?php echo home_url(); ?>" class="header__link">Andrew JD Hudson - Front-end Developer</a>
				</h1>
				<nav role="navigation" class="header__nav nav top-nav clearfix" id="top-nav">
					<?php
					$menu_args = array(
						'theme_location'  => 'primary',
						'container'       => false,
						'container_id'    => false,
						'container_class' => 'header__nav',
						'menu_class'      => 'list--inline header__menu',
						'menu_id'		  => '',
					);
					wp_nav_menu( $menu_args );
					?>
				</nav>
			</div>
		</div>
	</header>
