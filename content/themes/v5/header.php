<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="all" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="manifest" href="<?php echo esc_url( TEMPLATEURI ); ?>manifest.json">
	<link rel="icon" sizes="192x192" href="<?php echo esc_url( TEMPLATEURI ); ?>images/ba.png" type="image/png" rel="preload" as="image">
	<style><?php
	if ( ! is_user_logged_in() ) {
		$css = 'style.css';
	} else if ( is_page() || is_front_page() ) {
		$css = 'build/css/critical.css';
	} else {
		$css = 'build/css/post.css';
	}
	include_once( $css ); ?></style>
	
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="<?php echo esc_url( get_permalink() ); ?>">
	<meta name="twitter:title" content="<?php echo the_title(); ?>">
	<meta name="twitter:image:src" content="https://bigandy.pw/images/manifest/ba-512.png">
	<?php wp_head(); ?>
</head>
<body>
	<header class="header">
		<div class="header__row content-container">
			<h1 class="header__title">
				<a href="<?php echo esc_url( HOMEURL ); ?>" class="header__link">Andrew Hudson</a>
			</h1>
			<nav role="navigation" class="header__nav nav top-nav clearfix" id="top-nav">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					$menu_args = array(
						'theme_location'  => 'primary',
						'container'       => false,
						'container_id'    => false,
						'container_class' => 'header__nav',
						'menu_class'      => 'list--inline header__menu',
						'menu_id'		  => '',
						'walker' => new AH_Walker_Nav_Menu(),
					);

					wp_nav_menu( $menu_args );
				}
				?>
			</nav>
		</div>
	</header>
