<?php
if ( version_compare( $GLOBALS['wp_version'], '5.3', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'pre_post_update', 'wp_save_post_revision' );
function hide_admin_bar($flag) {
	return false;
}
add_filter( 'show_admin_bar','hide_admin_bar'); 
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
add_filter( 'use_default_gallery_style', '__return_false' );
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );

function lmsim_setup() {
    add_theme_support( 'title-tag' );
    register_nav_menu( 'monkeyking', '主题菜单' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );
    add_theme_support( 'custom-logo', array(
		'height'      => 172,
		'width'       => 318,
		'flex-height' => true,
	) );
    add_theme_support( 'custom-background' );
}
add_action( 'after_setup_theme', 'lmsim_setup' );

function lmsim_load_static_files(){
	$static_dir = get_template_directory_uri() . '/static/';
    wp_enqueue_style( 'lmsim', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style('iconfont', $static_dir . 'css/iconfont.css' , array(), '20210916');
	wp_enqueue_style('lmsim-main', $static_dir . 'css/main.css' , array(), '20210916');
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' );
    wp_enqueue_script( 'lmsim', $static_dir . 'js/main.js' , array( 'jquery' ), '20151122', true );
    wp_localize_script( 'lmsim', 'lmsim', array(
        'ajax_url'   => admin_url('admin-ajax.php'),
        'order' => get_option('comment_order'),
        'formpostion' => 'top', //默认为bottom，如果你的表单在顶部则设置为top。
    ) );
}
add_action( 'wp_enqueue_scripts', 'lmsim_load_static_files' );
function lmsim_widgets_init() {
    register_sidebar(array(
    	'id' => 'sidebar-1',
        'name' => 'Widget Area',
        'before_widget' => '<div class="side-box my-4 pb-3 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action( 'widgets_init', 'lmsim_widgets_init' );
function lms_auto_excerpt_more( $more ) {
    return ' &hellip;';
}
add_filter( 'excerpt_more', 'lms_auto_excerpt_more' );
function lmsim_excerpt_length( $length ) {
	return 120;
}
add_filter( 'excerpt_length', 'lmsim_excerpt_length' );
function twentythirteen_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentythirteen' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'twentythirteen_wp_title', 10, 2 );

function my_more_link($link){
	$link = preg_replace('/#more-\d+/i', '', $link);
	return $link;
}
add_filter('the_content_more_link','my_more_link');

function get_ssl_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cravatar.cn", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

function link_to_menu_editor( $args ){
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    extract( $args );

    $link = $link_before
        . '<a href="' .admin_url( 'nav-menus.php' ) . '">' . $before . 'Add a menu' . $after . '</a>'
        . $link_after;

    if ( FALSE !== stripos( $items_wrap, '<ul' ) or FALSE !== stripos( $items_wrap, '<ol' ) ) {
        $link = "<li>$link</li>";
    }

    $output = sprintf( $items_wrap, $menu_id, $menu_class, $link );
    if ( ! empty ( $container ) ) {
        $output  = "<$container class='$container_class' id='$container_id'>$output</$container>";
    }

    if ( $echo ) {
        echo $output;
    }

    return $output;
}

function fa_get_the_term_list( $id, $taxonomy ) {
    $terms = get_the_terms( $id, $taxonomy );
    $term_links = "";
    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) )
            return $link;
        $term_links .= '<a href="' . esc_url( $link ) . '" class="post--keyword" data-title="' . $term->name . '" data-type="'. $taxonomy .'" data-term-id="' . $term->term_id . '">' . $term->name . '<sup>['. $term->count .']</sup></a>';
    }

    return $term_links;
}

function add_remove_contactmethods( $contactmethods ) {
	// Remove Contact Methods
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);
    // Add 
	$contactmethods['tencent-qq'] = 'QQ';
    $contactmethods['weixin'] = '微信';
    $contactmethods['alipay'] = '支付宝';
	$contactmethods['sina-weibo'] = '微博';
	$contactmethods['github'] = 'Github';
	$contactmethods['twitter'] = 'Twitter';
	$contactmethods['facebook'] = 'FaceBook';
    return $contactmethods;
}
add_filter('user_contactmethods','add_remove_contactmethods',10,1);
function show_social(){
	$rss = get_bloginfo('rss_url');
	$qq = get_user_meta( 1, 'tencent-qq', true);
	$wb = get_user_meta( 1, 'sina-weibo', true);
	$gt = get_user_meta( 1, 'github', true);
	$tw = get_user_meta( 1, 'twitter', true);
	$fb = get_user_meta( 1, 'facebook', true);
	$wx = get_user_meta( 1, 'weixin', true);
    $ap = get_user_meta( 1, 'alipay', true);
	/**/

	$array = array('qq' => $qq, 'wechat' => $wx, 'alipay' => $ap, 'weibo' => $wb, 'twitter' => $tw, 'facebook' => $fb, 'github' => $gt, 'rss' => $rss);
	$social = '<div class="social-icon">';
	foreach($array as $key=>$link){
		if(!empty($link)) {
            if($key == 'wechat' || $key == 'alipay'){
                $social .= '<span class="has-qrcode"><i class="iconfont icon-' . $key . '"></i><img src="'.$link.'" class="qrcode"></span>';
            }else{
                if($key == 'qq') $link = 'http://wpa.qq.com/msgrd?v=3&uin=' . $link . '&site=qq&menu=yes';
                $social .= '<span><a href="' . $link . '" rel="nofollow" target="_blank"><i class="iconfont icon-' . $key . '"></i></a></span>';
            }
		}
	}
	$social .= '</div>';
	echo $social;
}
function lmsim_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $commentcount, $post_id, $comment_depth, $page, $wpdb;
	
	switch ($comment->comment_type) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		• <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)' ), '<span class="edit-link">', '</span>' ); ?>
	<?php
		break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" itemtype="http://schema.org/Comment" itemscope itemprop="comment">
		<div class="comment-holder">
			<?php if( $comment->comment_parent > 0) { echo get_avatar( $comment->comment_author_email, 36 );}else{ echo get_avatar( $comment->comment_author_email, 64 );} ?>
			<div id="comment-<?php comment_ID(); ?>" class="comment-body">
				<?php if( $comment->comment_parent > 0) { ?>
					<div class="comment-meta small">
						<strong><span itemprop="author"><?php echo get_comment_author_link(); ?></span></strong>
						<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
						<span><?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
						<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => ' 回复', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
					</div>
				<?php }else{ ?>
					<h4 class="media-heading">
						<span itemprop="author"><?php echo get_comment_author_link(); ?></span> 
					</h4>
					<div class="comment-meta small">
						<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
						<span><?php edit_comment_link(__('(Edit)'),'  ','') ?></span>
					</div>
				<?php } ?>
				<div class="comment-main" itemprop="description">
					<?php comment_text() ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Your comment is awaiting moderation.') ?></em>
					<?php endif; ?>
				</div>
				<?php if( $comment->comment_parent == 0) { ?>
					<p><span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => ' 回复', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span></p>
				<?php } ?>
			</div>
		</div>
<?php
		break;
	endswitch;
}
function mytheme_end_comment() {
	echo '</li>';
}
function fa_ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}
function fa_ajax_comment_callback(){
    $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
    if ( is_wp_error( $comment ) ) {
        $data = $comment->get_error_data();
        if ( ! empty( $data ) ) {
            fa_ajax_comment_err($comment->get_error_message());
        } else {
            exit;
        }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment; 
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>" itemtype="http://schema.org/Comment" itemscope itemprop="comment">
		<div class="comment-holder">
			<div class="pull-left">
				<?php if( $comment->comment_parent > 0) { echo get_avatar( $comment->comment_author_email, 36 );}else{ echo get_avatar( $comment->comment_author_email, 64 );} ?>
			</div>
			<div id="comment-<?php comment_ID(); ?>" class="comment-body">
				<?php if( $comment->comment_parent > 0) { ?>
					<div class="comment-meta small">
						<strong><span itemprop="author"><?php echo get_comment_author_link(); ?></span></strong>
						<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
					</div>
				<?php }else{ ?>
					<h4 class="media-heading">
						<span itemprop="author"><?php echo get_comment_author_link(); ?></span> 
					</h4>
					<div class="comment-meta small">
						<span><?php printf(__('%1$s %2$s'), get_comment_date(),  get_comment_time()) ?></span>
						<span class="country-flag"><?php if (function_exists("get_useragent")) { get_useragent($comment->comment_agent); } ?></span>
					</div>
				<?php } ?>
				<div class="comment-main" itemprop="description">
					<?php comment_text() ?>
					<?php if ($comment->comment_approved == '0') : ?>
						<em><?php _e('Your comment is awaiting moderation.') ?></em>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</li>
    <?php die();
}
add_action('wp_ajax_nopriv_ajax_comment', 'fa_ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'fa_ajax_comment_callback');

function lmsim_comment_nav() {
    // Are there comments to navigate through?
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
    ?>
    <nav class="navigation comment-navigation text-center clearfix" role="navigation">
        <div class="nav-links">
            <?php
                if ( $prev_link = get_previous_comments_link(  '上一页' ) ) :
                    printf( '<div class="nav-previous pull-left">%s</div>', $prev_link );
                endif;

                if ( $next_link = get_next_comments_link( '下一页' ) ) :
                    printf( '<div class="nav-next pull-right">%s</div>', $next_link );
                endif;
            ?>
        </div><!-- .nav-links -->
    </nav><!-- .comment-navigation -->
    <?php
    endif;
}

function fa_load_postlist_button(){
    global $wp_query;
    if (2 > $GLOBALS["wp_query"]->max_num_pages) {
        return;
    } else {
        $button = '<button id="fa-loadmore" class="btn btn-info"';
        if (is_category()) $button .= ' data-category="' . get_query_var('cat') . '"';

        if (is_author()) $button .=  ' data-author="' . get_query_var('author') . '"';

        if (is_tag()) $button .=  ' data-tag="' . get_query_var('tag') . '"';

        if (is_search()) $button .=  ' data-search="' . get_query_var('s') . '"';

        if (is_date() ) $button .=  ' data-year="' . get_query_var('year') . '" data-month="' . get_query_var('monthnum') . '" data-day="' . get_query_var('day') . '"';

        $button .= ' data-paged="2" data-action="fa_load_postlist" data-total="' . $GLOBALS["wp_query"]->max_num_pages . '">加载更多</button>';

        return $button;
    }
}


function lmsim_post_section(){
    global $post;
    $post_section = '<article class="hentry hentry-archive box"><div class="archive-title text-center"><h2 itemprop="headline"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2><div class="archive-meta small">'.get_bluefly_posted_on(). '</div></div><div class="archive-content" itemprop="about">';

    if(has_post_thumbnail()) {
        $post_section .= '<p class="with-img">' . get_the_post_thumbnail() . '</p>';
    }
    $post_section .= apply_filters('the_content', get_the_excerpt());

    $post_section .= '<p class="more-link"><a href="' . get_permalink() . '">...继续阅读 (+'.post_views('','','').')...</a></p>';
    $post_section .= '</div><div class="archive-footer text-center small">';
	$categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
	$post_section .= '<div class="post-cats"><span class="cats"><i class="iconfont icon-discount"></i>'. $categories_list . '</span></div>';
	$post_section .= get_the_tag_list('<div class="post-tags" itemprop="keywords">',', ','</div>');
	$post_section .= '</div></article>';
    return $post_section;
}

add_action('wp_ajax_nopriv_fa_load_postlist', 'fa_load_postlist_callback');
add_action('wp_ajax_fa_load_postlist', 'fa_load_postlist_callback');
function fa_load_postlist_callback(){
    $postlist = '';
    $paged = $_POST["paged"];
    $total = $_POST["total"];
    $category = $_POST["category"];
    $author = $_POST["author"];
    $tag = $_POST["tag"];
    $search = $_POST["search"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $query_args = array(
        "posts_per_page" => get_option('posts_per_page'),
        "cat" => $category,
        "tag" => $tag,
        "author" => $author,
        "post_status" => "publish",
        "post_type" => "post",
        "paged" => $paged,
        "s" => $search,
        "year" => $year,
        "monthnum" => $month,
        "day" => $day
    );
    $the_query = new WP_Query( $query_args );
    while ( $the_query->have_posts() ){
        $the_query->the_post();
        $postlist .= lmsim_post_section();
    }
    $code = $postlist ? 200 : 500;
    wp_reset_postdata();
    $next = ( $total > $paged )  ? ( $paged + 1 ) : '' ;
    echo json_encode(array('code'=>$code,'postlist'=>$postlist,'next'=> $next));
    die;
}

/**
 * 作用: 计算时间差
 * 来源: 破袜子原创
 */
function bluefly_timediff( $from, $to, $before, $after) {
	if ( empty($from) || empty($to) )
		return '';
	if( empty($before) )
		$before = '';
	if( empty($after) )
		$after = '';
	$from_int = strtotime($from) ;
	$to_int = strtotime($to) ;
	$diff_time = abs($to_int - $from_int) ;
	if ( $diff_time > 60 * 60 * 24 * 365 ){//年
		$num = round($diff_time / (60 * 60 * 24 * 365));
		$uni = ' 年';
	}
	else if ( $diff_time > 60 * 60 * 24 * 31 ) {//月
		$num = round($diff_time / (60 * 60 * 24 * 30));
		$uni = ' 月';
	}
	else if ( $diff_time > 60 * 60 * 24 ) {//天
		$num = round($diff_time / (60 * 60 * 24));
		$uni = ' 天';
	}
	else if ( $diff_time > 60 * 60 ) { //小时
		$num = round($diff_time / 3600);
		$uni = ' 小时';
	}
	else { //分钟
		$num = round($diff_time / 60);
		$uni = ' 分钟';
	}
	$return = $before.$num.$uni.$after ;
	return $return;
}
/**
 * 作用: post的时间差
 * 来源: 破袜子原创
 */
function bluefly_rel_post_date() {
	global $post;
	$post_date_time = mysql2date('j-n-Y H:i:s', $post->post_date, false);
	$current_time = current_time('timestamp');
	$date_today_time = gmdate('j-n-Y H:i:s', $current_time);
	return bluefly_timediff( $post_date_time, $date_today_time ,'','前' ) ;
}
/**
 * 作用: 显示日期(作者隐藏)
 */
function get_bluefly_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		bluefly_rel_post_date());
	$byline = '<span class="author vcard">'.get_avatar( get_the_author_meta( 'user_email' ), 28 ).'<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	$post_comments = '';
    if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		$comments = get_comments_number();
		$post_comments = '<span class="comment-nums"><a href="' . get_comments_link() .'"><i class="iconfont icon-chat"></i> '. $comments.'</a></span>';
	}
	return $byline . '<span class="posted-on">' . $time_string . '</span>'. $post_comments;
}
function lmsim_record_views() {
    if (is_singular()) {
        global $post, $user_ID;
        $post_ID = $post->ID;
        if (empty($_COOKIE[USER_COOKIE]) && intval($user_ID) == 0) {
            if ($post_ID) {
                $post_views = (int)get_post_meta($post_ID, 'views', true);
                if (!update_post_meta($post_ID, 'views', ($post_views + 1))) {
                    add_post_meta($post_ID, 'views', 1, true);
                }
            }
        }
    }
}
add_action('wp_head', 'lmsim_record_views');
function post_views($before = '', $after = '', $echo = 1) {
    global $post;
    $post_ID = $post->ID;
    $views = (int)get_post_meta($post_ID, 'views', true);
    if ($echo) {
        echo $before, number_format($views) , $after;
    } else {
        return $views;
    }
}
function lmsim_theme_views(){
	if(function_exists('the_views')) { 
		echo the_views(false); 
	}else{ 
		post_views();
	}
}
/*
默认侧栏最新评论排除博主
查看wp-includes/comment.php中WP_Comment_Query::query部分
根据传入参数完善查询条件
*/
add_filter( 'comments_clauses', 'wpdit_comments_clauses', 2, 10);
function wpdit_comments_clauses( $clauses, $comments ) {
    global $wpdb;
    if ( isset( $comments->query_vars['not_in__user'] ) && ( $user_id = $comments->query_vars['not_in__user'] ) ) {
         
        if ( is_array( $user_id ) ) {
            $clauses['where'] .= ' AND user_id NOT IN (' . implode( ',', array_map( 'absint', $user_id ) ) . ')';
        } elseif ( '' !== $user_id ) {
            $clauses['where'] .= $wpdb->prepare( ' AND user_id <> %d', $user_id );
        }
    }
    //var_dump($clauses);
    return $clauses;
}
/*
默认侧栏最新评论排除博主
详细查看wp-includes/default-widgets.php中 WP_Widget_Recent_Comments 部分
增加参数not_in__user
*/
add_filter( 'widget_comments_args', 'wpdit_widget_comments_args' );
function wpdit_widget_comments_args( $args ){
    $args['not_in__user'] = array(1); //这里放你的ID；
    return $args;
}
function lmsim_noself_ping(&$links){
    $home = get_option('home');
    foreach ($links as $l => $link) {
      if (0 === strpos($link, $home)) {
        unset($links[$l]);
      }
    }
}
function search_filter_page($query){
    if ($query->is_search) {
      $query->set('post_type', 'post');
    }
    return $query;
}
function comment_links_in_new_tab($text) {
      $return = str_replace('<a', '<a target="_blank"', $text);
      return $return;
}
add_filter('get_comment_author_link', 'comment_links_in_new_tab');
add_action('pre_ping', 'lmsim_noself_ping');
add_filter('pre_get_posts', 'search_filter_page');
add_filter('use_default_gallery_style', '__return_false');
add_filter('pre_option_link_manager_enabled', '__return_true');
function lmsim_custom_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$description = get_bloginfo( 'description', 'display' );
	if($custom_logo_id){
		$html = sprintf(
			'<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
			esc_url( home_url( '/' ) ),
			wp_get_attachment_image( $custom_logo_id, 'full', false, array(
				'class'    => 'custom-logo',
				'itemprop' => 'logo',
				'alt'			 => get_bloginfo( 'name' ),
				'title'		 => $description
			) )
		);
	}else{
		$html = sprintf(
			'<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url"><img src="%2$s"></a>',
			esc_url( home_url( '/' ) ),
            get_template_directory_uri() . '/static/img/logo.jpg'
        );
	}
	echo '<div class="logo img-logo">'.$html.'</div>';
}