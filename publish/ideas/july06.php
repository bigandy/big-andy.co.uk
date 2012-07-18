<!DOCTYPE html>

<html dir="ltr" lang="en-GB"> 

<head>
	<title>Big Andy's Place - Ideas - Grid 960</title>
<meta charset="utf-8"/>  
	<meta name="author" content="Andrew JD Hudson" />
	<meta name="keywords" content="Big Andy, bigandy, big-andy, Andrew JD Hudson, andrew, hudson, running, photography, css, php, blog, photos, web-design, ciw, running, css, php, wordpress" />
	<meta name="description" content="Big Andy's Ideas" />
	<meta name="robots" content="all" />
	<meta name="viewport" content="width=399" />
	<link rel="stylesheet" type="text/css" href="../style/july06.css" media="screen" />
<link rel="Shortcut Icon" href="ba2.ico" type="image/x-icon" />
	
<!--  hack to make IE able to apply CSS to elements that it doesn't usually know about. See http://remysharp.com/2009/01/07/html5-enabling-script/ -->
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

</head>


<body>

<!-- whole page wrapper-->
<div id="container">

		<!-- header wrapper-->
	<header id="header">
		<a href="../index.php" id="banner">
			<img src="../img/ba11.gif" alt="header banner with text 'big andy's place'" />
		</a>
	</header><!--end of header-->
		
		<nav id="navigation">
			<?php
			if ($_SERVER["SERVER_NAME"] == "localhost"){ include'../php/xampp-navigation.php';} else {include'../php/navigation.php';}
			?>	
		</nav>
		
		<div id="breadcrumb">

        <strong>you are:</strong> <a href="../index.php">Home</a> &#187; <a href="index.php">Ideas</a> &#187; july-06-2010
        </div>
		

		
		<!--main section of text-->
		<article id="content-main">
			<header>
				<h1>Media Queries + Responsive Web Design</h1>
			</header>
				<p>Tonight I have been reading up about responsive web design following the recent discussion on <a href="http://5by5.tv/bigwebshow/9" title="podcast from 5by5">the Big Web Show</a>, an <a href="
				http://www.alistapart.com/articles/responsive-web-design/" title="article by ethan marcote">article entitled "responsive web design"</a>
				on a list apart as well as site re-designs by <a href="http://www.colly.com/" title="simon collison's site">Simon Collison</a> and
				<a href="http://hicksdesign.co.uk/journal/" title="john hicks site">John Hicks</a>.</p>
			
		</article>
		
		<aside id="sub-content">
			<h3>Get things Done</h3>
		  <ol>
			  <li>Watch Eng vs. Ger</li>
			  <li>Study</li>
			  <li>More Study</li>
			  <li>Even more study</li>
			  <li>Work on grids</li>
		  </ol>


		</aside>
		
		<!--footer text-->
		<footer id="footer">
			<p>&copy;2004-2010 Andrew JD Hudson . <a href="mailto:andy@big-andy.co.uk">email me</a></p>
		</footer>

		</div>
</body>
</html>
