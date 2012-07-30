		<?php
// I found the code on this page : http://www.webcheatsheet.com/php/get_current_page_url.php //	

function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

?>

		
		
		<div id="blog-navigation">
		<ul>
			<?php 
if (curPageName() !== 'index.php') {echo "<li><a href=\"../index.php\" title=\"home\"><span>Home</span></a></li>";}
else {echo "<li id=\"home\"><a href=\"index.php\" title=\"home\"><span>Home</span></a></li>";}
?>
			<li><a href="http://www.big-andy.co.uk/about/index.php" title="about"><span>About</span></a></li>
			<li><a href="http://www.blog.big-andy.co.uk" title="blog"><span>Blog</span></a></li>
			<li><a href="http://www.big-andy.co.uk/ideas/index.php" title="ideas"><span>Ideas</span></a></li>
			<li><a href="http://www.big-andy.co.uk/photos/index.php" title="photos"><span>Photos</span></a></li>
			<li><a href="http://www.big-andy.co.uk/contact/index.php"title="contact"><span>Contact</span></a></li>
		</ul>
		</div><!--end of navigation-->


	

