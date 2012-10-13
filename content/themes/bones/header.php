<!doctype html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]>
<!--><html class="no-js" lang="en"><!--<![endif]-->
	
<head>
	<meta charset="utf-8">		
	<title>Bigandy.co.uk - Andrew Hudson's Online Home</title>

	<script type="text/javascript">
	  (function() {
	    var config = {
	      kitId: 'bhs0nmm',
	      scriptTimeout: 3000
	    };
	    var h=document.getElementsByTagName("html")[0];h.className+=" wf-loading";var t=setTimeout(function(){h.className=h.className.replace(/(\s|^)wf-loading(\s|$)/g," ");h.className+=" wf-inactive"},config.scriptTimeout);var tk=document.createElement("script"),d=false;tk.src='//use.typekit.net/'+config.kitId+'.js';tk.type="text/javascript";tk.async="true";tk.onload=tk.onreadystatechange=function(){var a=this.readyState;if(d||a&&a!="complete"&&a!="loaded")return;d=true;clearTimeout(t);try{Typekit.load(config)}catch(b){}};var s=document.getElementsByTagName("script")[0];s.parentNode.insertBefore(tk,s)
	  })();
	</script>
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">				
	<?php wp_head(); ?>
</head>
	
<body <?php // body_class(); ?>>
	
	<div id="container">
		<header class="header" role="banner">
		
			<div id="inner-header" class="wrap clearfix">					
				
				<?php if( !is_page_template('page-templates/thoughts.php' ) ) {  ?>
				
				<p id="logo" class="h1"><a href="<?php echo home_url(); ?>" rel="nofollow"><?php bloginfo('name'); ?></a></p>
				<?php  bloginfo('description'); ?>
				
				<nav role="navigation" class="nav top-nav clearfix">
						<?php  bones_main_nav(); ?>						
				</nav>
				<?php } ?>
				
			</div> <!-- end #inner-header -->
		
		</header> <!-- end header -->
