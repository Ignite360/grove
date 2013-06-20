<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Grove
 * @since Grove 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'grove' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php  ?>

<div id="page" class="hfeed site">
	<?php do_action( 'before' ); $header_image = get_header_image(); ?>
<div id="masthead-outer">
	<header id="masthead" class="site-header<?php if ( ! empty( $header_image ) ) { echo ' image'; }; ?>" role="banner" <?php if ( ! empty( $header_image ) ) { ?>style="height:<?php echo get_custom_header()->height; ?>px"<?php } ?>>

		<div class="masthead-inner">

		<?php
	if ( ! empty( $header_image ) ) { ?>
			<img class="header-banner" src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" />
	<?php } // if ( ! empty( $header_image ) ) ?>

	<?php if (display_header_text()) { ?>
		<hgroup>
			<?php $logo_image = get_option( 'custom_logo' );
			if ($logo_image) { ?>
			<h1 class="site-title site-logo"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo $logo_image; ?>" /></a></h1>
			<?php } else { ?>
			<h1 class="site-title"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php } ?>
		</hgroup>
	<?php } ?>

		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text ss-rows"></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'grove' ); ?>"><?php _e( 'Skip to content', 'grove' ); ?></a></div>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</nav><!-- .site-navigation .main-navigation -->

		<?php if (get_option( 'show_social_header' )) { if( function_exists( 'social_bartender' ) ){
			echo '<div class="social social-'.get_option( 'header_social_position' ).'">';
			social_bartender();
			echo '</div>'; } } ?>

		</div>

	</header><!-- #masthead .site-header -->
</div> <!-- #masthead-outer -->
	<div id="main" class="site-main">