<?php

/* Page Slug Body Class */
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/* Return the current URL */
function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

/* Change the 'author' base for all users */
function new_author_base() {
    global $wp_rewrite;
    $author_slug = 'user';
    $wp_rewrite->author_base = $author_slug;
}
// add_action('init', 'new_author_base');

function count_sidebar_widgets( $sidebar_id, $echo = true ) {
    $the_sidebars = wp_get_sidebars_widgets();
    if( !isset( $the_sidebars[$sidebar_id] ) )
        return __( 'Invalid sidebar ID' );
    if( $echo )
        echo count( $the_sidebars[$sidebar_id] );
    else
        return count( $the_sidebars[$sidebar_id] );
}

add_action('admin_enqueue_scripts', 'chromeless');

function chromeless () {

    $screen = get_current_screen();

    if ( $_GET["chromeless"] )
    {
    wp_enqueue_style('chromeless', site_url().'/wp-content/mu-plugins/inc/chromeless.css', '', '', 'all');
  }
}

function grove_update_complete(){
  if ( $_GET["chromeless"] ) {
    echo '<a href="#" onclick="window.close(); return false;" class="simple">When you\'re done making changes, simply close this window</a>';
  }
}
add_action('admin_notices', 'grove_update_complete');

function sidebar_status($classes) {
  global $post;
  global $sidebar;
  $sidebar = get_post_meta(get_the_ID(), '_grove_hide_sidebar', true); if ($sidebar) {
  $classes[] = $sidebar.'-sidebar';
   }
   return $classes;
}
add_filter('body_class', 'sidebar_status');

//hook the administrative header output
add_action('admin_head', 'my_custom_logo');

function my_custom_logo() {
  echo ' <style type="text/css"> #wp-admin-bar-wp-logo .ab-icon { background: url(/wp-content/mu-plugins/inc/icon-ignite.png) no-repeat top center !important; } </style> '; }

//turn comments OFF be default on pages
function page_comments_off_please() {
  if(isset($_REQUEST['post_type'])) {
    if ($_REQUEST['post_type'] == "page") {
      $fixit = <<<ENDIT
        <script>
          if (document.post) {
            var the_comment = document.post.comment_status;
            var the_ping = document.post.ping_status;
            if (the_comment && the_ping) {
              the_comment.checked = false;
              the_ping.checked = false;
            }
          }                 
        </script>"
ENDIT;
        echo $fixit;
    }
  }
}

add_action ( 'admin_footer', 'page_comments_off_please' );

if ( ! isset( $content_width ) )
    $content_width = 720;
?>
