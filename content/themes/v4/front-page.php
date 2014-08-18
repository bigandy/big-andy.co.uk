<?php get_header(); ?>
<main>
	<div class="row content-container">
		<article role="article" class="large-8 large-push-2 small-12 columns">
			<section class="post-content clearfix article__content--front-page">
				<?php

				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						the_content();
					}
				}
				?>
		    </section>
	    </article>
	</div>
</main>
<?php
get_footer();
