<?php
/*
Template Name: 友情链接
*/
    get_header();
?>
<main class="main-content">
	<?php while ( have_posts() ) : the_post(); ?>
		<div class="hentry hentry-archive box">
            <header class="archive-title text-center clearfix">
				<h2 itemprop="headline"><?php the_title(); ?></h2>
				<div class="archive-meta small">
					<?php echo get_bluefly_posted_on(); ?>
					<?php edit_post_link('Edit', '<span class="edit-link"><span class="glyphicon glyphicon-pencil"></span>', '</span>' ); ?>
				</div>
			</header>
            <div class="archive-content">
                <?php if(has_post_thumbnail()):?>
                    <p class="with-img"><?php the_post_thumbnail( 'full' ); ?></p>
                <?php endif;?>
                <?php the_content();?>
                <ul class="blogroll-links"><?php wp_list_bookmarks(); ?></ul>
                <?php wp_link_pages( array(
					'before'      => '<div class="page-links text-center comment-navigation">',
					'after'       => '</div>',
					'link_before' => '<span class="page-link-item">',
					'link_after'  => '</span>',
					'pagelink'    => '%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );?>
			</div>
		</div>
		<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		?>
	<?php endwhile; ?>
</main>
<?php get_footer();?>