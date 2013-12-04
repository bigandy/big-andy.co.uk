<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="dns-prefetch" href="//fonts.googleapis.com">
	<script async defer>
	  (function() {
	    var h=document.getElementsByTagName("html")[0];h.className+=" js";
	  })();
	</script>
	<link href='http://fonts.googleapis.com/css?family=Bitter:700' rel='stylesheet' type='text/css'>
	<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '-', true, 'right' );

	?></title>
	<meta name="robots" content="all" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link rel='stylesheet' href='/content/themes/v3/style.css' />
	<link rel="shortcut icon" href="/content/themes/v3/favicon.ico" />
	<link rel="alternate" type="application/rss+xml" title="bigandy &raquo; Feed" href="/feed/" />
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

	<div class="container">

		<?php if( !is_page_template('page-templates/no-header.php' ) && !is_page_template('page-templates/thoughts.php' ) ) {  ?>
			<header class="header" role="banner" id="triggerContainer">
				<div class="wrap clearfix inner-header">
					<p class="logo h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p>

					<nav role="navigation" class="nav top-nav clearfix" id="top-nav">
						<?php
							wp_nav_menu( array(
					    	   'container'=> false,
					    	   'depth' => 0,
					    	   'items_wrap' => '%3$s',  // hides containing ul
					    	   'walker' => new ah_description_walker()
					    	   )
					        );
					    ?>
					</nav>
				</div> <!-- end .inner-header -->
			</header> <!-- end header -->
		<?php } ?>


