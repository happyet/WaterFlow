<article class="hentry hentry-archive box">
    <div class="archive-title text-center">
		<h2 itemprop="headline"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
		<div class="archive-meta small">
			<?php echo get_bluefly_posted_on(); ?>
		</div>
	</div>
    <div class="archive-content" itemprop="about">
        <?php if(has_post_thumbnail()):?>
            <p class="with-img"><?php the_post_thumbnail( 'full' ); ?></p>
		<?php endif;?>
        <?php the_excerpt();?>
		<p class="more-link"><a href="<?php the_permalink();?>">...继续阅读 (+<?php lmsim_theme_views(); ?>)...</a></p>
    </div>
    <div class="archive-footer">
		<?php
			$categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
			echo '<div class="post-cats"><span class="cats"><i class="iconfont icon-discount"></i>'. $categories_list . '</span></div>';
			the_tags('<div class="post-tags" itemprop="keywords"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>', ', ', '</div>');
		?>
    </div>
</article>