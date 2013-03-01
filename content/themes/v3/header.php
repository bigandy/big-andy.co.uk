<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<script>
	  (function() {
	    var config = {
	      kitId: 'bhs0nmm',
	      scriptTimeout: 3000
	    };
	    var h=document.getElementsByTagName("html")[0];h.className+=" js wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
	  })();
	</script>
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
	<link rel="alternate" type="application/rss+xml" title="bigandy &raquo; Comments Feed" href="/comments/feed/" />
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

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
