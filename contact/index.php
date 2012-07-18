<?php $thisPage="Contact Me"; ?>
<?php include '../php/header.php';?>
		<!--main section of text-->
		<article id="content-main">
			<h1>Contact me</h1>
			<ul>
				<li><span>email:</span> <a href="mailto:ahudson@gmail.com">andy@big-andy.co.uk</a></li>
				<li><span>skype:</span> bigandy24</li>
				<li><span>twitter:</span> <a href="http://www.twitter.com/bigandy/">twitter.com/bigandy</a></li>
				<li><span>phone:</span> +44(0)77 36063 671</li>
			</ul>
			<p>Otherwise contact me via this form:</p>
			<section id="form">
				<form id="myform" class="cssform" action="sendemail.php" method="post">
					<fieldset>
						<ul>
							<li>
								<label for="user">Your Name:</label> <input type="text" name="user" id="user" value="" placeholder="Enter your name" required/>
							</li>
							<li>
								<label for="emailaddress">Your Email:</label> <input type="email" name="emailaddress" id="emailaddress" value="" placeholder="Enter your email address" required />
							</li>
							<li>
								<label for="comments" id="comments-label">Your Message:</label>	<textarea name="comments" id="comments" rows="5" cols="25" placeholder="Enter your comments" ></textarea>
							</li>
					</fieldset>		
                
					<fieldset>
						<!-- submit button -->
						<input type="submit" id="submit" name="submit" value="Send Message" />
					</fieldset>
				</form>
			</section>
		
		
		</article>
		<!--footer text-->
		<?php include '../php/footer.php'; ?>
</body>
</html>

