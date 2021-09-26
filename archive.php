<?php 
	get_header();
	$description = get_the_archive_description();
?>

<main class="main-content">
	<header class="page-header">
		<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
	</header>
	<div class="post-archives">
		<?php if (have_posts()):
			while (have_posts()): the_post();
				get_template_part('content', 'list');
			endwhile;
		endif;?>
	</div>
	<nav class="posts-nav text-center">
		<?php echo fa_load_postlist_button();?>
	</nav>
</main>
<?php get_footer();?>