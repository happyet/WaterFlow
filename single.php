<?php get_header();?>
    <main class="main-content">
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="hentry hentry-archive box">
                <header class="archive-title text-center">
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
					<?php wp_link_pages( array(
							'before'      => '<div class="page-links text-center comment-navigation">',
							'after'       => '</div>',
							'link_before' => '<span class="page-link-item">',
							'link_after'  => '</span>',
							'pagelink'    => '%',
							'separator'   => '<span class="screen-reader-text">, </span>',
					) );?>
					<div class="text-center aligncenter">
						<script type="text/javascript">
							jQuery(document).ready(function(jQuery) {
								jQuery('#qrcode').qrcode({
									render	: "canvas",//也可以替换为table
									width   : 120,
									height  : 120,
									text	: '<?php the_permalink(); ?>'
								});
							});
						</script>
						<div id="qrcode"></div>
						<div class="text-muted small">扫描二维码分享本文</div>
					</div>
				</div>
				<div class="archive-footer">
					<?php
						$categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
						echo '<div class="post-cats"><span class="cats"><i class="iconfont icon-discount"></i>'. $categories_list . '</span></div>';
						the_tags('<div class="post-tags" itemprop="keywords"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span>', ', ', '</div>');
					?>
				</div>
			</div>
			<?php
				the_post_navigation( array(
					'next_text' => '<span class="meta-nav">Next</span> ' .
						'<span class="post-title">%title</span>',
					'prev_text' => '<span class="meta-nav">Previous</span> ' .
						'<span class="post-title">%title</span>',
				) );
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		<?php endwhile; ?>
    </main>
<?php get_footer();?>