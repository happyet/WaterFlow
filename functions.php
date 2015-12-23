<?php
require get_template_directory() . '/inc/classic-smilies.php';
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

function HuI_setup() {
    register_nav_menu( 'monkeyking', '主题菜单' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );
}
add_action( 'after_setup_theme', 'HuI_setup' );

function HuI_load_static_files(){
	$static_dir = get_template_directory_uri() . '/static/';
	wp_enqueue_style( 'bootstrap', $static_dir . 'css/bootstrap.min.css', array(), '3.3.5' );
	wp_enqueue_style('HuI-style', $static_dir . 'css/main.css' , array(), '20150726' , 'screen');
	wp_enqueue_script( 'bootstrap', $static_dir . 'js/bootstrap.min.js', array(), '3.3.5', true );
    wp_enqueue_script( 'HuI', $static_dir . 'js/main.js' , array( 'jquery' ), '20151122', true );
    wp_localize_script( 'HuI', 'HuI', array(
        'ajax_url'   => admin_url('admin-ajax.php')
    ) );
}
add_action( 'wp_enqueue_scripts', 'HuI_load_static_files' );

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
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

function link_to_menu_editor( $args )
{
    if ( ! current_user_can( 'manage_options' ) )
    {
        return;
    }

    extract( $args );

    $link = $link_before
        . '<a href="' .admin_url( 'nav-menus.php' ) . '">' . $before . 'Add a menu' . $after . '</a>'
        . $link_after;

    if ( FALSE !== stripos( $items_wrap, '<ul' )
        or FALSE !== stripos( $items_wrap, '<ol' )
    )
    {
        $link = "<li>$link</li>";
    }

    $output = sprintf( $items_wrap, $menu_id, $menu_class, $link );
    if ( ! empty ( $container ) )
    {
        $output  = "<$container class='$container_class' id='$container_id'>$output</$container>";
    }

    if ( $echo )
    {
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
	$contactmethods['sina-weibo'] = 'Weibo';
	$contactmethods['renren'] = 'RenRen';
	$contactmethods['github'] = 'Github';
	$contactmethods['twitter'] = 'Twitter';
	$contactmethods['facebook'] = 'FaceBook';
	$contactmethods['flickr'] = 'Flickr';
    $contactmethods['instagram'] = 'Instagram';
    return $contactmethods;
}
add_filter('user_contactmethods','add_remove_contactmethods',10,1);

function show_social(){
	$rss = get_bloginfo('rss_url');
	$qqid = get_user_meta( 1, 'tencent-qq', true);
	$wburl = get_user_meta( 1, 'sina-weibo', true);
	$rrurl = get_user_meta( 1, 'renren', true);
	$gturl = get_user_meta( 1, 'github', true);
	$tturl = get_user_meta( 1, 'twitter', true);
	$fburl = get_user_meta( 1, 'facebook', true);
	$flurl = get_user_meta( 1, 'flickr', true);
	$insta = get_user_meta( 1, 'instagram', true);
	$email = 'mailto:' . get_user_by('id', 1)->user_email;
	/**/

	$array = array('rss' => $rss, 'qq' => $qqid, 'weibo' => $wburl, 'renren' => $rrurl, 'twitter' => $tturl, 'facebook' => $fburl, 'git' => $gturl, 'flickr' => $flurl, 'instagram' => $insta, 'mail' => $email,);
	$social = '<div class="social-icon">';
	foreach($array as $arr=>$key){
		if(!empty($key)) {
			if($arr == 'qq') $key = 'http://wpa.qq.com/msgrd?v=3&uin=' . $key . '&site=qq&menu=yes';
			$social .= '<a href="' . $key . '" rel="nofollow" target="_blank"><span class="' . $arr . '"></span></a>';
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
			<div class="comment-avatar pull-left">
				<?php if( $comment->comment_parent > 0) { echo get_avatar( $comment->comment_author_email, 36 );}else{ echo get_avatar( $comment->comment_author_email, 64 );} ?>
			</div>
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
add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'ajax_comment_callback');
function ajax_comment_callback(){
    global $wpdb;
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    $post_author = $post->post_author;
    if ( empty($post->comment_status) ) {
        do_action('comment_id_not_found', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('Sorry, comments are closed for this item.');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('Password Protected');
    } else {
        do_action('pre_comment_on_post', $comment_post_ID);
    }
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
    $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
    $user = wp_get_current_user();
    if ( $user->exists() ) {
        if ( empty( $user->display_name ) )
            $user->display_name=$user->user_login;
        $comment_author       = esc_sql($user->display_name);
        $comment_author_email = esc_sql($user->user_email);
        $comment_author_url   = esc_sql($user->user_url);
        $user_ID              = esc_sql($user->ID);
        if ( current_user_can('unfiltered_html') ) {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
                kses_remove_filters();
                kses_init_filters();
            }
        }
    } else {
        if ( get_option('comment_registration') || 'private' == $status )
            ajax_comment_err('Sorry, you must be logged in to post a comment.');
    }
    $comment_type = '';
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( 'Error: please fill the required fields (name, email).' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( 'Error: please enter a valid email address.' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( 'Error: please type a comment.' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('Duplicate comment detected; it looks as though you&#8217;ve already said that!');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('You are posting comments too quickly.  Slow down.');
        }
    }
    $comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

    $comment_id = wp_new_comment( $commentdata );


    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);
    $comment_depth = 1;
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
        $comment_depth++;
        $tmp_c = get_comment($tmp_c->comment_parent);
    }
    $GLOBALS['comment'] = $comment;
    //这里修改成你的评论结构
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
function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function HuI_comment_nav() {
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


function HuI_post_section(){
    global $post;
    $post_section = '<article class="hentry hentry-archive"><div class="archive-title text-center clearfix"><h2 itemprop="headline"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2><div class="archive-meta small">'.get_bluefly_posted_on(). '</div></div><div class="archive-content" itemprop="about">';

    if(has_post_thumbnail()) {
        $post_section .= '<p class="with-img">' . get_the_post_thumbnail() . '</p>';
    }
    $post_section .= apply_filters('the_content', get_the_content(''));

    $post_section .= '</div><div class="archive-footer text-center small clearfix">';
	$categories_list = get_the_category_list( _x( ', ', ' ', 'lmsim' ) );
	$post_section .= '<div class="post-cats"><span class="cats"><span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>'. $categories_list . '</span></div>';
	$post_section .= get_the_tag_list('<div class="post-tags" itemprop="keywords"><span class="glyphicon glyphicon-tags" aria-hidden="true"></span> ',', ','</div>');
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
        $postlist .= HuI_post_section();
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
	$time_string = '<span class="glyphicon glyphicon-time" aria-hidden="true"></span><time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		bluefly_rel_post_date());
	$byline = '<span class="author vcard"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		$num_comments = get_comments_number();
		if ( $num_comments == 0 ) {
			$comments = 'No Reply';
		} elseif ( $num_comments > 1 ) {
			$comments = $num_comments . ' Replies';
		} else {
			$comments = '1 Reply';
		}
		$post_comments = '<span class="comment-nums"><a href="' . get_comments_link() .'"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>'. $comments.'</a></span>';
	}
	return $byline . '<span class="posted-on">' . $time_string . '</span>'. $post_comments;
}