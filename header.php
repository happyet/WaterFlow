<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,minimal-ui" />
    <meta name="description" content="">
    <meta name="keywords" content="">
    <?php wp_head();?>
</head>
<body <?php body_class();?>>
<div class="page">
	<div class="site-header box">
		<header id="masthead" role="banner">
			<div class="row">
				<div class="col-md-4">
					<div class="site-banner"><?php lmsim_custom_logo(); ?></div>
					<nav class="main-navigation navbar" role="navigation">
						<div id="site-navigation" class="navbar-collapse">
							<button type="button" class="btn-close text-reset"><i class="iconfont icon-close nav-close"></i></button>
							<ul class="menu-ul">
								<?php wp_nav_menu( array( 
									'theme_location' => 'monkeyking', 
									'container' => '', 
									'items_wrap' => '%3$s' 
								) ); ?>	
							</ul>
						</div>
						<button type="button" class="navbar-toggler">
							<i class="iconfont icon-menu"></i>
						</button>
						<button type="button" class="search-trigger">
							<i class="iconfont icon-search"></i>
						</button>
					</nav>
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