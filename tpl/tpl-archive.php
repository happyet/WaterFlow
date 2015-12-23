<?php 
/*
Template Name: 文章归档模版
*/
get_header();
?>
<main class="main-content">
        
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="hentry hentry-archive">
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
				</div>
			</div>
		<?php endwhile; ?>
		<div class="fancy-archive">
            <?php $args = array(
			'post_type' => 'post', //如果你有多个 post type，可以这样 array('post', 'product', 'news')  
			'posts_per_page' => -1, //全部 posts
			'ignore_sticky_posts' => 1 //忽略 sticky posts

		);
		$the_query = new WP_Query( $args );
		$posts_rebuild = array();
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$post_year = get_the_time('Y');
			$post_mon = get_the_time('m');
			$posts_rebuild[$post_year][$post_mon][] = '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .')</em></li>';
		endwhile;
		wp_reset_postdata();
		foreach ($posts_rebuild as $key => $value) {
			$output .= '<h3 class="archive-year">' . $key . '</h3>';
			$year = $key;
				foreach ($value as $key_m => $value_m) {
					$output .= '<h3 class="archive-month">' . $year . ' - ' . $key_m . '</h3><ul class="fancy-ul">';
					foreach ($value_m as $key => $value_d) {
						$output .=  $value_d;
					}
					$output .= '</ul>';
				}
			# code...
		}

		echo $output;
            ?>
        </div>
</main>
<?php get_footer();?>