<?php $thisPage="Home"; ?>
<?php include 'php/header.php';?>
		<!--main section of text-->
	<section id="content-main">
		<h1>welcome to big-andy.co.uk</h1>
		<p>Greetings and I hope that you're well! I am Andrew JD Hudson aka bigandy and this is my personal site where I hope to showcase my burgeoning web-design skills.</p>
		
			<p>While you're here you can can check out my 
			<a href="/photos/index.php" title="photos">photos</a>, 
			<a href="http://www.blog.big-andy.co.uk" title="blog">blog</a>,
			and find out how to <a href="/contact/index.php" title="contact">contact</a> me.
			</p>
			<p>First of all you might want to find what to read <a href="/about/index.php" title="about">about</a> me.</p>
			<img src="/big-andy.co.uk/img/bigandy.jpg" alt="image of bigandy" id="photo-me" height="350" width="522" />
	
	</section><!--end of content-main-->
	<aside id="sub-content">

		<section id="blog-posts">
			<h2><a href="http://blog.big-andy.co.uk/" title="visit my blog">Blog</a></h2>
			<h3>latest posts</h3>
			<!-- php to get top 3 blog posts from wordpress blog; returns 'have a nice day' if on localhost -->
			<?php
			if ($_SERVER["SERVER_NAME"] == "localhost") {echo "<p>this is a nice day</p>";}
			else {include 'php/latest-blog-posts.php';}
			?>
		</section><!--end of blog posts-->
	
	
	</aside><!--end of sub-content-->
	<!-- twitter; put at the bottom to speed up page-load-->
	<section id="twitter">
		<script src="/big-andy.co.uk/js/typekit-twitter.min.js"></script>
	</section>
	<!--end of twitter-->
	
			<!--footer text-->
	<footer id="footer">
		<p>&copy;2004-<?php echo date('Y');?> Andrew JD Hudson . <a href="mailto:andy@big-andy.co.uk">email me</a></p>
	</footer><!--end of footer-->
</div><!--end of container-->
<!--google tracking javascript-->
<?php
include'php/google-code.php';
?>
</body>
</html>

