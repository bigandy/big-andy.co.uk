<?php $thisPage="404 Error"; ?>
<?php include '../php/header.php';?>
<body>
<!-- whole page wrapper-->
<div id="container">
	<!-- header wrapper-->
	<header id="header">
		<h1>
		<a href="/index.php" id="banner">
			<img src="/img/bigandy-header.png" alt="header banner with text 'big andy's place'" height="100" width="960" />
		</a>
		</h1>
	</header><!--end of header-->
	<!-- navigation -->
	<nav id="navigation">
		<?php include'../php/navigation.php';?>
	</nav>
	<!--main section of text-->
	<section id="content-main">
		<h1>Cannot Find what you are looking for - Sorry</h1>
		<p>The page or folder you were looking for has not been found and for this I am very sorry. Try returning to the <a href="/index.php">homepage</a>. If you'd like to send me an email then my address is <a href="mailtp:andy@big-andy.co.uk">andy@big-andy.co.uk</a>. Sorry for the inconvenience this has caused you.</p>
	</section><!--end of content-main-->
	<!--footer text-->
	<footer id="footer">
		<p>&copy;2004-2010 Andrew JD Hudson . <a href="mailto:andy@big-andy.co.uk">email me</a></p>
	</footer><!--end of footer-->
</div>
<!--google tracking and typekit javascript-->
<?php
include'../php/google-code.php';
?>
</body>
</html>

