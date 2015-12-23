<?php
if ( post_password_required() )
    return;
?>
<div id="comments" class="row">
	<?php 
		if(comments_open()) :
			$fields =  array(
			  'author' => '<div class="form-group col-md-4"><input id="author" class="form-control" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" ' . $aria_req . ' placeholder="Name' . ( $req ? '*' : '' ) .'" /></div>',
			  'email' => '<div class="form-group col-md-4"><input id="email" class="form-control" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" ' . $aria_req . ' placeholder="Email' . ( $req ? '*' : '' ) . '" /></div>',
			  'url' => '<div class="form-group col-md-4"><input id="url" class="form-control" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="Website" /></div>',
			);
			comment_form(array(
				'comment_notes_before' => '',
				'comment_notes_after'=>'',
				'comment_field' =>  '<div class="form-group col-md-12"><textarea id="comment" name="comment" class="form-control" rows="3" aria-required="true"></textarea></div>',
				'fields' => apply_filters( 'comment_form_default_fields', $fields ),
				'class_submit' => 'btn btn-info',
				
			));
		endif;
	?>
	<?php if(have_comments()): ?>
		<meta content="UserComments:<?php echo number_format_i18n( get_comments_number() );?>" itemprop="interactionCount">
		<h3 class="comments-title page-header">共有 <span class="commentCount"><?php echo number_format_i18n( get_comments_number() );?></span> 条评论</h3>
		<ol class="commentlist">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 48,
					'callback' => lmsim_comment,
					'end-callback' => mytheme_end_comment
				) );
			?>
		</ol>
		<?php HuI_comment_nav(); ?>
	<?php endif; ?>
</div>