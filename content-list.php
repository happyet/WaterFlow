<article class="hentry hentry-archive">
    <div class="archive-title text-center clearfix">
		<h2 itemprop="headline">
			<a href="<?php the_permalink();?>"><?php the_title();?></a>
		</h2>
		<div class="archive-meta small">
			<?php echo get_bluefly_posted_on(); ?>
		</div>
	</div>
    <div class="archive-content" itemprop="about">
        <?php if(has_post_thumbnail()):?>
            <p class="with-img"><?php the_post_thumbnail( 'full' ); ?></p>
		<?php endif;?>
        <?php the_content('<span class="btn btn-default">Continue Reading ...</span>');?>
    </div>
    <div class="archive-footer text-center small clearfix">
		<?php
			$categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
			echo '<div class="post-cats"><span class="cats"><span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>'. $categories_list . '</span></div>';
			the_tags('<div class="post-tags" itemprop="keywords"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>', ', ', '</div>');
		?>
    </div>
</article>