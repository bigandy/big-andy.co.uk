<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Big Andy's Place - <?php the_title(); ?></title>

	<!-- CSS: implied media="all" -->
<!-- for live	<link rel="stylesheet" href="/style/style.css"> -->
	<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/style.css">

	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
	<script src="<?php $template_directory; ?>/js/libs/modernizr-1.7.min.js"></script>

	<!-- All other meta information-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="Andrew JD Hudson" />
	<meta name="keywords" content="Big Andy, bigandy, big-andy, Andrew JD Hudson, andrew, hudson, running, photography, css, php, blog, photos, web-design, ciw, running, css, php, wordpress" />
	<meta name="description" content="The personal website of Andrew JD Hudson." />
	<meta name="robots" content="all" />
	<link rel="shortcut icon" href="<?php bloginfo( 'template_url' ); ?>/favicon.ico" />
	<link type="text/plain" rel="author" href="/humans.txt" />
	<?php wp_head(); ?>
</head>
<body class="<?php body_class();  ?>">
<!-- whole page wrapper-->
<div class="container">
	<!-- header wrapper-->
	<header class="main-header">
		<!-- use for live <a href="/index.php" id="banner">
			<img src="/img/bigandy-header.png" alt="header banner with text 'big andy's place'" height="100" width="960" />
		</a>-->
		
		<a href="/index.php" id="banner">
			<h1>bigandy.co.uk</h1>
		</a>
		
	</header><!--end of header-->
	<!-- navigation -->
	<nav id="navigation">
	<!-- php conditional to get navigation based on where page is hosted: localhost or online -->
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	</nav>