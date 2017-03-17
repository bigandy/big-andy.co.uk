<!doctype html>
<html lang="en-GB">
<head>
	<meta charset="utf-8">
	<meta name="robots" content="all" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<?php wp_head(); ?>
</head>
<body>
	<header class="header">
		<h1 class="header__title">
			<a href="<?php echo esc_url( HOMEURL ); ?>" class="header__link">Andrew Hudson</a>
		</h1>
		<nav class="header__nav">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				$menu_args = array(
					'theme_location'  => 'primary',
					'container'       => false,
					'container_id'    => false,
					'container_class' => false,
					'menu_class'      => 'header__menu',
					'menu_id'		  => '',
					// 'walker' => new AH_Walker_Nav_Menu(),
				);

				wp_nav_menu( $menu_args );
			}
			?>
		</nav>
	</header>
