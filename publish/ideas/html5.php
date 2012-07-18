<?php $thisPage="HTML5"; ?>
<?php include '../php/header.php';?>
<body>
<!-- whole page wrapper-->
<div id="container">
		<!-- header wrapper-->
		<header id="header">
		<a href="../index.php" id="banner">
			<img src="../img/bigandy-header.png" alt="header banner with text 'big andy's place'" />
		</a>
		</header>

		
		<!-- breadcrumb navigation -->
		<nav id="navigation">
			<?php
			if ($_SERVER["SERVER_NAME"] == "localhost"){ include'../php/xampp-navigation.php';} else {include'../php/navigation.php';}
			?>		
		</nav>
<!-- main navigation -->
		<nav id="breadcrumb">
			<strong>you are:</strong> <a href="../index.php">Home</a> &#187; <a href="index.php">Ideas</a> &#187; html5
        </nav>
		<!--main section of text-->
		<div id="content-main">
			
			<article>
			<header>
				<h2>HTML5</h2>
			</header>
			<section>
			<p>HTML5 is the next version of the HTML family and is substantially improved upon the last/current version HTML 4.01. Some of the improvements
			can already be implemented onto live web sites and I am using these to teach myself the beauty of HTML5.</p>
			<header><h3>What are these improvements?</h3></header>
			<p>Attending a talk on HTML5 at Reading Geek Night given by HTML5 doctor Mike Robinson I have discovered that these are:
			<ul><li>semantics</li> <li>findability</li><li>support for "non-web" websites</li></ul></p>
		
			</section>
			</article>
		</div>
		<div id="sub-content">
			<h3>HTML5 Resources</h3>
		  <ul>
			  <li><a href="http://html5doctor.com/" title="html5 doctor"><mark>HTML5</mark> doctor</a></li>
			  <li><a href="http://huffduffer.com/adactio/18906" title="Adactio on The Big Web Show">Adactio talking <mark>HTML5</mark> on the Big Web Show</a></li>
			  <li><a href="http://apirocks.com/html5/html5.html#slide1" title="html5 slide show"><mark>HTML5</mark> slideshow</a></li>
			  <li><a href="http://html5gallery.com/" title="html5 gallery - examples of sites that are currently using html5"><mark>HTML5</mark> Gallery - sites using HTML5</a></li>
			  <li><a href="http://www.alistapart.com/articles/previewofhtml5" title="a list apart - preview of HTML5"><mark>HTML5</mark> Preview - A list Apart</a></li>
		  </ul>

		</div>
		<!--footer text-->
			<footer id="footer">
	<p>&copy;2004-2010 Andrew JD Hudson . <a href="mailto:andy@big-andy.co.uk">email me</a></p>
	</footer>





</div>

<!--typekit code -->
<script src="../js/typekit.js" type="text/javascript"></script>
</body>
</html>
