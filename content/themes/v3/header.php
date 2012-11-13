<!doctype html>
<html lang="en">	
<head>
	<meta charset="utf-8">		
	<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '-', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " - $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' - ' . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );

	?></title>
	
	<meta name="robots" content="all" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel='stylesheet' href='/content/themes/v3/style.css' />
	<link rel="shortcut icon" href="/content/themes/v3/favicon.ico" />				
	<link rel="alternate" type="application/rss+xml" title="bigandy &raquo; Feed" href="/feed/" />
	<link rel="alternate" type="application/rss+xml" title="bigandy &raquo; Comments Feed" href="/comments/feed/" />
	<?php wp_head(); ?>
</head>
	
<body>
	
	<div class="container">
		<header class="header" role="banner">
		
			<div class="wrap clearfix inner-header">					
				
				<?php if( !is_page_template('page-templates/no-header.php' ) && !is_page_template('page-templates/thoughts.php' ) ) {  ?>
				
				<p class="logo h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p>
								
				<nav role="navigation" class="nav top-nav clearfix">
					<?php bones_main_nav(); ?>						
				</nav>
				<?php } ?>
				
			</div> <!-- end .inner-header -->
		
		</header> <!-- end header -->
