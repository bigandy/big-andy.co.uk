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

	<script type="text/javascript">
	  (function() {
	    var config = {
	      kitId: 'bhs0nmm',
	      scriptTimeout: 3000
	    };
	    var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
	  })();
	</script>
	<meta name="author" content="Andrew JD Hudson" />
	<meta name="keywords" content="Big Andy, bigandy, big-andy, Andrew JD Hudson, andrew, hudson, running, photography, css, php, blog, photos, web-design, ciw, running, css, php, wordpress" />
	<meta name="description" content="The personal website of Andrew JD Hudson." />
	<meta name="robots" content="all" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">				
	<?php wp_head(); ?>
</head>
	
<body>
	
	<div id="container">
		<header class="header" role="banner">
		
			<div id="inner-header" class="wrap clearfix">					
				
				<?php if( !is_page_template('page-templates/thoughts.php' ) ) {  ?>
				
				<p id="logo" class="h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p>
								
				<nav role="navigation" class="nav top-nav clearfix">
						<?php  bones_main_nav(); ?>						
				</nav>
				<?php } ?>
				
			</div> <!-- end #inner-header -->
		
		</header> <!-- end header -->
