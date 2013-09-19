<?php
/**
 * Grove functions and definitions
 *
 * @package Grove
 * @since Grove 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Grove 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'grove_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Grove 1.0
 */
function grove_setup() {

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	require( get_template_directory() . '/inc/metabox/functions.php' );

	require_once ( get_template_directory() . '/inc/make-content.php' );
	
	/* implementation of TGM Plugin activation */
	require_once ( get_template_directory() . '/inc/plugin-mgmt/pluginmgr.php' );


	/**
	 * Custom functions that act independently of the theme templates
	 */
	//require( get_template_directory() . '/inc/tweaks.php' );

	/**
	 * Custom Theme Options
	 */
	//require( get_template_directory() . '/inc/theme-options/theme-options.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Grove, use a find and replace
	 * to change 'grove' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'grove', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'gallery') );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'grove' ),
	) );
}
endif; // grove_setup
add_action( 'after_setup_theme', 'grove_setup' );

/**
 * Register widgetized areas
 *
 * @since Grove 1.0
 */
function grove_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'grove' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => __( 'Homepage Featured Content', 'grove' ),
		'id' => 'homepage-featured-content',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
	register_sidebar( array(
		'name' => __( 'Footer', 'grove' ),
		'id' => 'footer-widgets',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'grove_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function grove_scripts() {

	if (get_option('body-font') == "Open Sans") { wp_enqueue_style( 'Open Sans', 'http://fonts.googleapis.com/css?family=Open+Sans:400,700'); }
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'layout', get_template_directory_uri() . '/layouts/content-sidebar.css');

	// wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'ticker', get_template_directory_uri() . '/js/jquery.li-scroller.1.0.js', array( 'jquery' ), '1.1', false);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'grove_scripts' );

function insert_banner() {
   get_template_part( 'inc/banner', 'home' ); // calls banner-home.php
}
add_action( 'grove_home_after_slider', 'insert_banner' );
add_action('customize_register', 'child_customize');

//add some custom image sizes
add_image_size( '960', 960, 9999 );
add_image_size( '720', 720, 9999 );


$args = array(
	'flex-width'    => false,
	'width'         => 960,
	'flex-height'    => true,
	'height'        => 200,
	'default-image' => '',
	'uploads' => true
);
add_theme_support( 'custom-header', $args );

$defaults = array(
	'default-color'          => '',
	'default-image'          => '',
	'wp-head-callback'       => '',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);
add_theme_support( 'custom-background', $defaults );

add_action ('admin_menu', 'theme_customize');
function theme_customize() {
	add_menu_page( 'Grove', 'Grove', 'edit_theme_options', 'Grove', 'grove_parent', site_url().'/wp-content/mu-plugins/inc/icon-ignite.png', 3 );
	add_submenu_page('Grove', 'Customize', 'Customize', 'edit_theme_options', 'customize.php?', '');
}

function grove_parent() {
	echo '<div class="wrap"><h3>Enjoy your stay in the Grove.</h3><p style="max-width:600px">This site is built on Grove, a brand-new WordPress framework from Ignite360. While we\'re still kicking the tires, we\'re thrilled to have you in the family and hope your stay in Grove is a pleasant one. If you need any help, with anything, don\'t hesitate to <a href="#">contact us</a>.</p><a class="button" href="customize.php?">Customize Theme</a> <a class="button" href="admin.php?page=make-content">Make Content</a></div>';
	
}

require( get_template_directory() . '/inc/customizer.php' );

function wp_make_content() {
    global $wp_admin_bar, $wpdb;
    if ( !is_super_admin() || !is_admin_bar_showing() )
        return;
   
    $wp_admin_bar->add_menu( array( 'id' => 'make_content', 'title' => __( 'Make Content', 'textdomain' ), 'href' => '/wp-admin/admin.php?page=make-content' ) );

}
add_action( 'admin_bar_menu', 'wp_make_content', 1000 );

add_action('admin_init', function() {
  wp_register_style('grove_cust_st', get_template_directory_uri() . '/style-cust.css', null, "1.0.0", "all");
  wp_enqueue_style('grove_cust_st');
  
});
