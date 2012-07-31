<?php 
/**
 * Template Name: CV
 *
 */
?>
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
<body class="cv-template">
<!-- whole page wrapper-->
<div class="container">
		<header>		
			<a href="<?php bloginfo( 'url' ); ?>">
				<h1>bigandy.co.uk</h1>
			</a>
	</header>
	<section class="cv-content">
		<?php if ( have_posts() ) : ?>
	
	
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
						
						<h1><?php the_title(); ?></h1>
						<?php the_content(); ?>
	
					<?php endwhile; ?>
	
					<?php endif; ?>
	</section>
	<?php get_footer(); ?>