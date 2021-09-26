<div class="footer-top box">
	<div class="row">
		<div class="col-md-4 text-center">
			<?php get_calendar(); ?>
		</div>
		<div class="col-md-8">
			<div class="mod">
				<h4 class="rc-title">Recent Posts</h4>
				<ul class="list-unstyled">
					<?php 
						$rc_posts=get_posts('numberposts=7&orderby=date'); 
						foreach($rc_posts as $post) :
							$title = get_the_title();
							if(!$title) $title = get_the_time('Y-m-d');
							echo '<li><a href="' . get_permalink() . '">' . $title . '</a><span class="small text-muted"> - ' . get_the_time('Y-m-d') . '</span></li>';
						endforeach;
						wp_reset_postdata();
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="mod">
			<ul class="rc-comments text-center">
				<?php	
					$email = get_bloginfo ('admin_email');
					$comments_args = array(
						'status'=>'approve',
						'post_status'=>'publish',
						'author__not_in' => 1,
						'number' => '14',
						//'post_type' => 'post'
					);
					$new_comments = get_comments($comments_args);
					$rc_comments = '';
					foreach ($new_comments as $rc_comment) {
						$rc_comments .= '
						<li>
							<div class="rc-comment">
								<div class="comment-content">
									<p>' . convert_smilies(mb_substr(strip_tags($rc_comment->comment_content),0,100)) . '...</p>
									<div><strong>'. $rc_comment->comment_author . '</strong> - '. date('Y.m.d',strtotime($rc_comment->comment_date_gmt)) . ' - 发表在 ' . get_post( $rc_comment->comment_post_ID )->post_title  .'</div>
								</div>
							</div>
							<a href="' . htmlspecialchars(get_comment_link( $rc_comment->comment_ID )) .'"><span class="rc-avatar">' . get_avatar( $rc_comment->comment_author_email, 45 ) . '</span></a>
						</li>';
					}
					echo $rc_comments;
				?>	
			</ul>
		</div>
	</div>
</div>
<footer class="site-footer ">
   	<div class="footer-branding">
        <p>&copy; <?php the_date('Y'); ?> <i class="iconfont icon-fabulous red"></i> <a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php bloginfo( 'name' ); ?></a> <?php if(is_user_logged_in()){ ?><a href="<?php echo admin_url('/'); ?>"><i class="iconfont icon-setting"></i> 网站后台</a> <a href="<?php echo wp_logout_url( home_url() ); ?>"><i class="iconfont icon-unlock"></i> 退出登陆</a><?php }else{ ?><span id="toggle-login" ><i class="iconfont icon-user"></i> 登录</span><?php } ?></p>
    </div>
    <div class="footer-copy">
		<p>主题由 <a href="http://lms.im" target="_blank" title="自娱自乐，不亦乐乎！">LMS</a> 倾情制作</p>
    </div>
</footer>
</div>
<div class="back-to-top hide"><i class="iconfont icon-direction-up"></i></div>
<div class="offcanvas offcanvas-top hidden" tabindex="-1" id="search-panel" aria-labelledby="offcanvasTopLabel">
	<div class="offcanvas-main">
  <div class="offcanvas-header">
    <h5>搜索</h5>
    <button type="button" class="btn-close text-reset"><i class="iconfont icon-close search-close"></i></button>
  </div>
  <div class="offcanvas-body">
  <?php get_search_form(); ?>
  </div>
</div>
</div>
<div class="modal fade hidden" id="login-form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close-modal" ><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">登陆</h4>
      </div>
      <div class="modal-body">
       <form class="form-horizontal" action="<?php echo get_bloginfo('url'); ?>/wp-login.php" method="post">
		  <p>
			<label for="inputEmail3" class="control-label">用户名</label>
			<input type="text" class="form-control" id="inputEmail3" name="log"id="log" required placeholder="用户名">
			</p>
		  <p>
			<label for="inputPassword3" class="control-label">密码</label>
			<input type="password" class="form-control" id="inputPassword3" name="pwd" id="pwd" required placeholder="密码">
		  </p>
		  <p>
			  <div class="checkbox">
				<label>
				  <input type="checkbox" name="rememberme" id="rememberme" value="forever"> 记住我的登录信息
				</label>
			  </div>
		  </p>
		  <p>
			  <button type="submit" name="submit" class="btn">登录</button>
		  </p>
		  <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
		</form>

	  </div>
  </div>
</div>
<?php wp_footer();?>
</body>
</html>