<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,minimal-ui" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title><?php wp_title( '-', true, 'right' ); ?></title>
    <?php wp_head();?>
</head>
<body <?php body_class();?>>
<div class="page container">
	<div class="row">
		<div class="page-inner site-header col-md-10 col-md-offset-1">
			<header id="masthead" role="banner">
				<div class="row">
					<div class="col-md-4 text-center">
						<div class="row">
							<div class="site-banner"></div>
							<nav class="main-navigation navbar navbar-default" role="navigation">
								<div class="navbar-header">
									<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#site-navigation" aria-expanded="false">
										<span class="sr-only">Toggle navigation</span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
								<div id="site-navigation" class="collapse navbar-collapse">
									<ul class="list-unstyled list-inline">
										<?php wp_nav_menu( array( 
											'theme_location' => 'monkeyking', 
											'container' => '', 
											'items_wrap' => '%3$s<li><a class="search-trigger" href="javascript:void(0)"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a></li>' 
										) ); ?>	
									</ul>
								</div>
								<?php get_search_form(); ?>
							</nav>
						</div>
					</div>
					<div class="site-branding col-md-8">
						<h1 class="site-title">
							<a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php bloginfo( 'name' ); ?></a>
						</h1>
						<?php $description = get_bloginfo( 'description', 'display' );
								if ( $description || is_customize_preview() ) : ?>
									<p class="site-description"><?php echo $description; ?></p>
						<?php endif; ?>
						<?php show_social(); ?>
						<div class="about-site">
							<?php get_template_part('about'); ?>
						</div>
					</div>
				</div>
			</header>
		</div>

		<div class="page-inner col-md-10 col-md-offset-1">