<?php


if ( ! function_exists( 'setup' ) ) :

function setup() {

  add_theme_support( 'automatic-feed-links' );
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 2000, 9999 );

}
endif; //setup
add_action( 'after_setup_theme', 'setup' );


/**
 * script and stylesheet
 *
 *
**/
function my_scripts() {
  wp_enqueue_style( 'main-stylesheet', get_template_directory_uri() . '/assets/css/main.min.css', array(), '20170629' );

  wp_enqueue_script( 'mainscript', get_template_directory_uri() . '/assets/js/index.min.js', array(), '20170624', true );

}
add_action( 'wp_enqueue_scripts', 'my_scripts' );


/**
 * pagination
 *
 *
**/
function pagination($pages = '', $range = 2) {
  $showitems = ($range * 2)+1;
  global $paged;
  if( empty($paged) ) $paged = 1;

  if( $pages == '' ) {
    global $wp_query;
    $pages = $wp_query->max_num_pages;
    if( !$pages ) {
      $pages = 1;
    }
  }

  if( 1 != $pages ) {
    echo '<nav class="pagination"><h3>Page '.$paged.' of '.$pages.'</h3>';

    if( $paged > 2 && $paged > $range+1 && $showitems < $pages )  {
      echo '<a class="pjax" href="'.get_pagenum_link(1).'"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>';
    }

    if( $paged > 1 && $showitems < $pages ) {
      echo '<a class="pjax" href="'.get_pagenum_link($paged - 1).'"><i class="fa fa-angle-left" aria-hidden="true"></i></a>';
    }

    for( $i=1; $i <= $pages; $i++ ) {
      if( 1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ) ) {
        echo ($paged == $i)? '<span class="current">'.$i.'</span>':'<a class="pjax" href="'.get_pagenum_link($i).'">'.$i.'</a>';
      }
    }

    if ($paged < $pages && $showitems < $pages)  {
      echo '<a class="pjax" href="'.get_pagenum_link($paged + 1).'"><i class="fa fa-angle-right" aria-hidden="true"></i></a>';
    }

    if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) {
      echo '<a class="pjax" href="'.get_pagenum_link($pages).'"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>';
    }

    echo "</nav>\n" ;
  }
}




/**
 * indicate future post
 *
 *
**/
add_action('save_post', 'futuretopublish', 99);
add_action('edit_post', 'futuretopublish', 99);
function futuretopublish() {
  global $wpdb;
  $sql = 'UPDATE `'.$wpdb->prefix.'posts` ';
  $sql .= 'SET post_status = "publish" ';
  $sql .= 'WHERE post_status = "future"';
  $wpdb->get_results($sql);
}


/**
 * if mobile
 *
 *
**/
function is_mobile() {
  $useragents = array(
  'iPhone',          // iPhone
  'iPod',            // iPod touch
  'Android',         // 1.5+ Android
  'dream',           // Pre 1.5 Android
  'CUPCAKE',         // 1.5+ Android
  'blackberry9500',  // Storm
  'blackberry9530',  // Storm
  'blackberry9520',  // Storm v2
  'blackberry9550',  // Storm v2
  'blackberry9800',  // Torch
  'webOS',           // Palm Pre Experimental
  'incognito',       // Other iPhone browser
  'webmate'          // Other iPhone browser
  );
  $pattern = '/'.implode('|', $useragents).'/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}



function new_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more');


remove_filter('the_excerpt', 'wpautop');



/**
 * short cord image pass
 *
 *
**/
function short_url_images() {
  $imgUrl = get_template_directory_uri();
  return $imgUrl . '/assets/images';
}
add_shortcode('img_url', 'short_url_images');


/**
 * add post list thumbnail
 *
 *
**/
function add_posts_columns_thumbnail($columns) {
  $columns['thumbnail'] = 'eyecatch';
  return $columns;
}
function add_posts_columns_thumbnail_row($column_name, $post_id) {
  if ( 'thumbnail' == $column_name ) {
    $thumb = get_the_post_thumbnail($post_id, array(50,50), 'thumbnail');
    echo ( $thumb ) ? $thumb : 'none';
  }
}
add_filter( 'manage_posts_columns', 'add_posts_columns_thumbnail' );
add_action( 'manage_posts_custom_column', 'add_posts_columns_thumbnail_row', 10, 2 );


/**
 * hide notification
 *
 *
**/
add_filter( 'pre_site_transient_update_core', '__return_zero' );
remove_action( 'wp_version_check', 'wp_version_check' );
remove_action( 'admin_init', '_maybe_update_core' );
add_action('admin_menu', 'remove_counts');
function remove_counts(){
  global $menu,$submenu;
  $menu[65][0] = 'プラグイン';
  $submenu['index.php'][10][0] = '更新';
}
add_action( 'wp_before_admin_bar_render', 'hide_before_admin_bar_render' );
function hide_before_admin_bar_render() {
  global $wp_admin_bar;
  $wp_admin_bar->remove_menu( 'updates' );
}


/**
 * hide visual editor
 *
 *
**/
function disable_visual_editor_in_page(){
  add_filter('user_can_richedit', 'disable_visual_editor_filter');
}
function disable_visual_editor_filter(){
  return false;
}
add_action( 'load-post.php', 'disable_visual_editor_in_page' );
add_action( 'load-post-new.php', 'disable_visual_editor_in_page' );


/**
 * footer text
 *
 *
**/
function custom_footer_admin () {
  return 'thanks to...';
}
add_filter('admin_footer_text', 'custom_footer_admin');

function custom_footer_update () {
  return 'thanks to...';
}
add_filter('update_footer', 'custom_footer_update', 11);


/**
 * eyecatch text
 *
 *
**/
add_filter( 'admin_post_thumbnail_html', 'add_post_thumbnail_description' );
function add_post_thumbnail_description( $content ) {
  return $content .= '<p>ここに設定した画像が一覧に表示されます。</p>';
}



/**
 * dns prefetch
 */
function dns_prefetch() {

  // DNS prefetch を on にするタグを出力用変数に入れる
  $output = '<meta http-equiv="x-dns-prefetch-control" content="on">' . "\n";

  // prefetch するドメインのタグひな形
  $html    = '<link rel="dns-prefetch" href="//%s">' . "\n";
  $domains = array(
    // facebook
    //'connect.facebook.net',
    //'s-static.ak.facebook.com', 'static.ak.fbcdn.net', 'static.ak.facebook.com', 'www.facebook.com',
    //
    'maxcdn.bootstrapcdn.com',
    // twitter
    //'platform.twitter.com',
    //'cdn.api.twitter.com', 'p.twitter.com', 'twitter.com',
    //
    // Google+
    //'apis.google.com', 'oauth.googleusercontent.com', 'ssl.gstatic.com',
    'fonts.googleapis.com', 'ajax.googleapis.com',
    // pinterest
    //'assets.pinterest.com',
    // WordPress
    //'stats.wordpress.com', 'i0.wp.com', 'i1.wp.com', 'i2.wp.com', 's0.wp.com',
    // analytics
    'www.google-analytics.com'
    // 追加する場合 ↓ 先頭の // を消して書き込む
    //'', '', '', '', '', '', ''
  );
  // 上記 $domains 配列に入れたドメインをひな形 $html に入れ込み、ループで出力用変数に入れる
  foreach ( $domains as $domain ) {
    $output .= sprintf( $html, $domain );
  }
  // 書き出し
  echo $output;
}
// add_action( 'フック名', 'フックする関数名', 優先順位:今回は真っ先に実行したいので 1 を指定 )
add_action( 'wp_head', 'dns_prefetch', 1 );



function disable_scheduled_posting_func( $data, $postArray ) {
  if ( ( $data['post_type'] == 'event' && $data['post_status'] == 'future') && $postArray['post_status'] == 'publish' ) {
    $data['post_status'] = 'publish';
  }
  return $data;
};
add_filter( 'wp_insert_post_data', 'disable_scheduled_posting_func', 10, 2 );




/**
 * login
 *
 *
**/
function custom_login() { ?>
  <style>
    /*
       * background
      */

    .login {
      background: url(<?php echo get_stylesheet_directory_uri();
      ?>/screenshot.jpg) no-repeat center center;
      background-size: cover;
    }
    /*
       * logo
      */

    .login #login h1 a {
      display: block;
      width: 180px;
      height: 80px;
      margin-right: auto;
      margin-left: auto;
      background: url(<?php echo get_stylesheet_directory_uri();
      ?>/assets/images/logo.svg) no-repeat center center;
      background-size: contain;
    }
    /*
       * back to text
      */

    .login #nav,
    .login #backtoblog {
      display: none;
    }
    /*
       * background alpha
      */

    .login form,
    .login #login_error,
    .login .message {
      background-color: hsla(0, 0%, 100%, 0.8);
    }
    /*
       * layout
      */

    #login {
      position: absolute;
      top: 50%;
      left: 50%;
      padding: 0;
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);
    }
  </style>
  <?php }
add_action( 'login_enqueue_scripts', 'custom_login' );


/**
 * login logo url
 *
 *
**/
function custom_login_logo_url() {
  return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'custom_login_logo_url' );


/**
 * login remenber password
 *
 *
**/
function login_checked_rememberme() { ?>
    <script>
      jQuery(document).ready(function () {
        jQuery('#rememberme').prop('checked', true);
      });
    </script>
    <?php }
add_action( 'login_head', 'login_checked_rememberme' );


/**
 * btn hidden
 * excepting administrator
 *
**/
function my_admin_head(){
  if (!current_user_can('level_10')) { ?>
      <style>
        #contextual-help-link-wrap,
        #screen-options-link-wrap,
        #menu-posts {
          display: none;
        }
      </style>
      <?php
  }
}
add_action('admin_head', 'my_admin_head');



/**
 * header exit
 *
 *
**/
function deregister_plugin_files() {
    wp_dequeue_style( 'duplicate-post' );
}
add_action( 'wp_enqueue_scripts', 'deregister_plugin_files' );

remove_action('wp_head','rest_output_link_wp_head');
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');

remove_action('template_redirect', 'rest_output_link_header', 11 );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head','index_rel_link');
remove_action('wp_head','parent_post_rel_link',10);
remove_action('wp_head','start_post_rel_link',10);
remove_action('wp_head','adjacent_posts_rel_link_wp_head',10);
