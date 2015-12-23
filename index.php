<?php get_header();?>
<main class="main-content">
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