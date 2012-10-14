<?php
/**
 * The Header for our theme.
 */
 
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Big Andy's Place - <?php the_title(); ?></title>

	<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/style.css">


	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="Andrew JD Hudson" />
	<meta name="keywords" content="Big Andy, bigandy, big-andy, Andrew JD Hudson, andrew, hudson, running, photography, css, php, blog, photos, web-design, ciw, running, css, php, wordpress" />
	<meta name="description" content="The personal website of Andrew JD Hudson." />
	<meta name="robots" content="all" />
	<link rel="shortcut icon" href="<?php bloginfo( 'template_url' ); ?>/favicon.ico" />
	<link type="text/plain" rel="author" href="/humans.txt" />
	

	<?php wp_head(); ?>

	
</head>
<body <?php body_class(); ?>>

<div class="container">

	<header class="main-header">
		
		<a href="/index.php" id="banner">
			<h1>bigandy.co.uk</h1>
		</a>
		
	</header>
	
	<nav>
	<!-- php conditional to get navigation based on where page is hosted: localhost or online -->
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	</nav>