<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="all" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="preload" href="<?php echo esc_url( TEMPLATEURI . 'content/themes/v5/fonts/open-sans-v13-latin-regular.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( TEMPLATEURI . 'content/themes/v5/fonts/open-sans-v13-latin-800.woff2' ); ?>" as="font" type="font/woff2" crossorigin>

	<link rel="manifest" href="<?php echo esc_url( TEMPLATEURI ); ?>manifest.json">
	<link rel="icon" sizes="192x192" href="<?php echo esc_url( TEMPLATEURI ); ?>images/ba.png">
	<link href="https://twitter.com/bigandy" rel="me">
	<link href="https://github.com/bigandy" rel="me">
	<link href="https://instagram.com/bigandyhudson" rel="me">
	<link rel="authorization_endpoint" href="https://indieauth.com/auth">
	<link rel="token_endpoint" href="https://big-andy.co.uk/token">
	<?php
	if ( ! is_user_logged_in() ) {
		?>
		<style><?php include_once( 'style.css' ); ?></style>
		<?php
	}
	wp_head();
	?>
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
