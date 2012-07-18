<ul role="navigation">
			<li<?php if ($thisPage=="Home") echo " class=\"selected\"";?>><a href="/index.php" title="home" accesskey="h">Home</a></li>
			<li<?php if ($thisPage=="About Me") echo " class=\"selected\"";?>><a href="/about/index.php" title="About Big-Andy" accesskey="a">About</a></li>
			<li><a href="http://www.blog.big-andy.co.uk" title="My Thoughts" accesskey="b">Blog</a></li>
			<li<?php if ($thisPage=="Ideas") echo " class=\"selected\"";?>><a href="/ideas/index.php" title="Portfolio + Experiments" accesskey="i">Ideas</a></li>
			<li<?php if ($thisPage=="Photos") echo " class=\"selected\"";?>><a href="/photos/index.php" title="Photo Galleries" accesskey="p">Photos</a></li>
			<li<?php if ($thisPage=="Contact Me") echo " class=\"selected\"";?> id="last"><a href="/contact/index.php" title="Connect with Me" accesskey="c">Contact</a></li>
		</ul>
