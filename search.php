<?php get_header(); ?>

<main class="main-content">
	<header class="page-header">
		<h1 class="page-title">
			<?php
			printf(
				/* translators: %s: Search term. */
				esc_html__( 'Results for "%s"', 'twentytwentyone' ),
				'<span class="page-description search-term">' . esc_html( get_search_query() ) . '</span>'
			);
			?>
		</h1>
		<div class="archive-description">
			<?php
			printf(
				esc_html(
					/* translators: %d: The number of search results. */
					_n(
						'We found %d result for your search.',
						'We found %d results for your search.',
						(int) $wp_query->found_posts,
						'twentytwentyone'
					)
				),
				(int) $wp_query->found_posts
			);
			?>
		</div>
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