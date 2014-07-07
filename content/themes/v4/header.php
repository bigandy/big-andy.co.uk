<!doctype html>
<html lang="en" <?php if( is_user_logged_in() ) { echo 'class="logged-in"'; } ?>>
<head>
	<meta charset="utf-8">
	<meta name="robots" content="all" />
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

	<title><?php wp_title(); ?></title>

	<link rel="stylesheet" type="text/css" href="//cloud.typography.com/7502092/707264/css/fonts.css" />

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>

	<style>
	article,footer,header,main,nav,section{display:block}html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}a{background:0 0}a:focus{outline:thin dotted}a:active,a:hover{outline:0}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0}body,html{height:100%}*,:after,:before{-moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box}body,html{font-size:100%}body{background:#fff;color:#222;padding:0;margin:0;font-family:'Open Sans',sans-serif;font-weight:400;font-style:normal;line-height:1;position:relative;cursor:default}a:hover{cursor:pointer}.clearfix{*zoom:1}.clearfix:after,.clearfix:before{content:" ";display:table}.clearfix:after{clear:both}.content-container,.row{width:100%;margin-left:auto;margin-right:auto;margin-top:0;margin-bottom:0;max-width:90rem;*zoom:1}.content-container:after,.content-container:before,.row:after,.row:before{content:" ";display:table}.content-container:after,.row:after{clear:both}.columns{padding-left:.9375rem;padding-right:.9375rem;width:100%;float:left}@media only screen{.columns{position:relative;padding-left:.9375rem;padding-right:.9375rem;float:left}}@media only screen and (min-width:40.063em){.columns{position:relative;padding-left:.9375rem;padding-right:.9375rem;float:left}}@media only screen and (min-width:64.063em){.columns{position:relative;padding-left:.9375rem;padding-right:.9375rem;float:left}.large-6{width:50%}.large-12{width:100%}}div,h1,li,p,ul{margin:0;padding:0}a{color:red;text-decoration:none;line-height:inherit}a:focus,a:hover{color:#db0000}p{font-family:inherit;font-weight:400;font-size:1rem;line-height:1.6;margin-bottom:1.25rem;text-rendering:optimizeLegibility}h1{font-family:'Sentinel A','Sentinel B','Open Sans',sans-serif;font-weight:800;font-style:normal;color:#222;text-rendering:optimizeLegibility;margin-top:.2rem;margin-bottom:.5rem;line-height:1.4;font-size:2.125rem}ul{font-size:1rem;line-height:1.6;margin-bottom:1.25rem;list-style-position:outside;font-family:inherit;margin-left:1.1rem}@media only screen and (min-width:40.063em){h1{line-height:1.4;font-size:2.75rem}}@media print{@page{margin:.5cm}*{background:transparent!important;color:#000!important;box-shadow:none!important;text-shadow:none!important}a,a:visited{text-decoration:underline}a[href]:after{content:" (" attr(href) ")"}p{orphans:3;widows:3}}body>header{margin-bottom:1rem;background:#000}.header__row{padding:1.5rem 0 1rem}.header__row h1{text-align:center}.header__row h1 a{color:#fff}.header__nav{text-align:center;margin-bottom:1rem}.header__menu{display:inline-block}.header__menu li{padding-right:20px}.header__menu a{display:inline-block}.list--inline{margin:0;list-style:none}.list--inline li{float:left}.header__menu a,.post-content a{border-bottom:3px solid transparent;margin-bottom:-3px}.header__menu a:focus,.header__menu a:hover,.post-content a:focus,.post-content a:hover{border-bottom-color:red}.header__menu .current-menu-item>a,.header__menu .current_page_item>a{border-bottom:3px solid red}article:nth-child(odd){clear:left}
	</style>
	<?php wp_head(); ?>
</head>
<body>
	<header>
		<div class="header__row content-container row">
			<div class="large-12 columns">
				<h1>
					<a href="<?php echo home_url(); ?>">
						Andrew Hudson - Front-end Developer
					</a>
				</h1>
				<nav role="navigation" class="header__nav nav top-nav clearfix" id="top-nav">
					<?php
					$menu_args = array(
						'theme_location'  => 'primary',
						'container'       => false,
						'container_id'    => false,
						'container_class' => 'header__nav',
						'menu_class'      => 'list--inline header__menu',
						'menu_id'		  => '',
					);

					wp_nav_menu( $menu_args );
					?>
				</nav>
			</div>
		</div>
	</header>
