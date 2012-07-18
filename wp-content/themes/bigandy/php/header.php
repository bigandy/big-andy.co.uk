<!doctype html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Big Andy's Place - <?php if ($thisPage!="") echo"$thisPage";?></title>

	<!-- CSS: implied media="all" -->
<!-- for live	<link rel="stylesheet" href="/style/style.css"> -->
	<link rel="stylesheet" href="/css/style.css">

	<!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
	<script src="/js/libs/modernizr-1.7.min.js"></script>

	<!-- All other meta information-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
	<meta name="author" content="Andrew JD Hudson" />
	<meta name="keywords" content="Big Andy, bigandy, big-andy, Andrew JD Hudson, andrew, hudson, running, photography, css, php, blog, photos, web-design, ciw, running, css, php, wordpress" />
	<meta name="description" content="The personal website of Andrew JD Hudson." />
	<meta name="robots" content="all" />
	<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
<!-- whole page wrapper-->
<div id="container">
	<!-- header wrapper-->
	<header id="header">
		<!-- use for live <a href="/index.php" id="banner">
			<img src="/img/bigandy-header.png" alt="header banner with text 'big andy's place'" height="100" width="960" />
		</a>-->
		
		<a href="/index.php" id="banner">
			<img src="/img/bigandy-header.png" alt="header banner with text 'big andy's place'" height="100" width="960" />
		</a>
		
	</header><!--end of header-->
	<!-- navigation -->
	<nav id="navigation">
	<!-- php conditional to get navigation based on where page is hosted: localhost or online -->
		<?php include'navigation.php'; ?>
	</nav>