<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="all" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="manifest" href="<?php echo esc_url( TEMPLATEURI ); ?>manifest.json">
	<link rel="icon" sizes="192x192" href="<?php echo esc_url( TEMPLATEURI ); ?>images/ba.png" type="image/png">
	<style><?php
	if ( ! is_user_logged_in() ) {
		$css = 'build/css/critical.css';
	} else {
		$css = 'style.css';
	}
	include_once( $css ); ?></style>
	<?php wp_head(); ?>
</head>
<body>
	<header class="header">
		<div class="header__row content-container row">
			<div class="large-8 large-push-2 small-12 columns">
				<h1 class="header__title">
					<a href="<?php echo esc_url( HOMEURL ); ?>" class="header__link">A<span>ndrew</span> H<span>udson</span></a>
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
						);
						wp_nav_menu( $menu_args );
					}
					?>
				</nav>
			</div>
		</div>
	</header>
