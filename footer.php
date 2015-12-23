</div>
<div class="page-inner col-md-10 col-md-offset-1">
	<div class="row">
		<div class="col-sm-6 text-center">
			<?php get_calendar(); ?>
		</div>
		<div class="col-sm-6">
			<div class="mod">
				<h4 class="rc-title">Recent Posts</h4>
				<ul class="list-unstyled">
					<?php 
						$rc_posts=get_posts('numberposts=5&orderby=date'); 
						foreach($rc_posts as $post) :
							$title = get_the_title();
							if(!$title) $title = get_the_time('Y-m-d');
							echo '<li><a href="' . get_permalink() . '">' . $title . '</a><span class="small text-muted"> / ' . get_the_time('Y-m-d') . '</span></li>';
						endforeach;
						wp_reset_query();
					?>
				</ul>
			</div>
			<div class="mod">
				<h4 class="rc-title">Recent Comments</h4>
				<ul class="rc-comments list-unstyled list-inline">
					<?php	
						$email = get_bloginfo ('admin_email');	
						global $wpdb;
						$sql = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type,comment_author_url,comment_author_email,comment_content FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID ) WHERE comment_approved = '1' AND comment_type = '' AND comment_author_email != '$email' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT 5";
						$comments = $wpdb->get_results($sql);
						$gravatar_status = 'on';
						$rc_comments = '';
						foreach ($comments as $rc_comment) {
							if(wp_is_mobile()){ $data_trigger = 'focus'; }else{ $data_trigger = 'hover'; }
							$rc_comments .= '
							<li>
								<div class="newcomment-title hide"><a class="text-muted small" href="' . htmlspecialchars(get_comment_link( $rc_comment->comment_ID )) .'">'. date('Y年m月d日',strtotime($rc_comment->comment_date_gmt)) . ' 发表在 ' . get_post( $rc_comment->comment_post_ID )->post_title  .'</a></div>
								<div class="comment-cotent hide">
									<p>' . convert_smilies(mb_substr(strip_tags($rc_comment->comment_content),0,100)) . '...</p>
								</div>
								<span class="rc-avatar" tabindex="0" role="button" data-trigger="' . $data_trigger . '" data-placement="top">' . get_avatar( $rc_comment->comment_author_email, 65 ) . '<em>'. $rc_comment->comment_author . '</em></span>
							</li>';
						}
						echo $rc_comments;
					?>	
				</ul>
			</div>
		</div>
	</div>
</div>
	<footer class="site-footer col-md-10 col-md-offset-1 text-center">
    	<div class="footer-branding">
            <p>&copy; 2014-2015 <span class="glyphicon glyphicon-heart red"></span> <a href="<?php echo home_url();?>" title="<?php echo get_bloginfo( 'name', 'display' ); ?>"><?php bloginfo( 'name' ); ?></a> <?php if(is_user_logged_in()){ ?><a href="<?php echo admin_url('/'); ?>"><i class="fa fa-tachometer"></i> 网站后台</a> <a href="<?php echo wp_logout_url( home_url() ); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> 退出登陆</a><?php }else{ ?><span data-toggle="modal" data-target="#login-form" data-tooltip="true" data-placement="top" data-original-title="登陆"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span> 登录</span><?php } ?></p>
			
         </div>
        <div class="footer-copy">
			<p>主题由 <a href="http://lms.im" target="_blank" title="自娱自乐，不亦乐乎！">LMS</a> 倾情制作</p>
        </div>
    </footer>
	</div>
	</div>
	<div class="back-to-top hide" onclick="backToTop();"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span></div>

<div class="blog-bg"></div>
<div class="modal fade" id="login-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">登陆</h4>
      </div>
      <div class="modal-body">


       <form class="form-horizontal" action="<?php echo get_bloginfo('url'); ?>/wp-login.php" method="post">
		  <div class="form-group">
			<label for="inputEmail3" class="col-sm-3 control-label">用户名</label>
			<div class="col-sm-6">
			  <input type="text" class="form-control" id="inputEmail3" name="log"id="log" required placeholder="用户名">
			</div>
		  </div>
		  <div class="form-group">
			<label for="inputPassword3" class="col-sm-3 control-label">密码</label>
			<div class="col-sm-6">
			  <input type="password" class="form-control" id="inputPassword3" name="pwd" id="pwd" required placeholder="密码">
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-3 col-sm-6">
			  <div class="checkbox">
				<label>
				  <input type="checkbox" name="rememberme" id="rememberme" value="forever"> 记住我的登录信息
				</label>
			  </div>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-3 col-sm-6">
			  <button type="submit" name="submit" class="btn btn-primary pull-right">登录</button>
			</div>
		  </div>
		  <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>"/>
		</form>

	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php wp_footer();?>
</body>
</html>