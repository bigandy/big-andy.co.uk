		<ul>
			<?php // http://www.alistapart.com/articles/keepingcurrent/ for details about this nav scheme ?>
			<?php 
if ($thisPage !== "index") {echo "<li><a href=\"../index.php\" title=\"home\" accesskey=\"h\">Home</a></li>\n";}
else {echo "<li class=\"selected\"><a href=\"index.php\" title=\"home\" accesskey=\"h\">Home</a></li>\n";}
?>
			<li<?php if ($thisPage=="About Me") echo " class=\"selected\"";?>><a href="/big-andy.co.uk/about/index.php" title="About Big-Andy" accesskey="a">About</a></li>
			<li><a href="http://www.blog.big-andy.co.uk" title="My Thoughts" accesskey="b">Blog</a></li>
			<li<?php if ($thisPage=="Ideas") echo " class=\"selected\"";?>><a href="/big-andy.co.uk/ideas/index.php" title="Portfolio + Experiments" accesskey="i">Ideas</a></li>
			<li<?php if ($thisPage=="Photos") echo " class=\"selected\"";?>><a href="/big-andy.co.uk/photos/index.php" title="Photo Galleries" accesskey="p">Photos</a></li>
			<li<?php if ($thisPage=="Contact Me") echo " class=\"selected\"";?> id="last"><a href="/big-andy.co.uk/contact/index.php" title="Connect with Me" accesskey="c">Contact</a></li>
		</ul>