<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Grove
 * @since Grove 1.0
 */
?>
<?php  




$grove_sidebar_pos = get_option('grove_sidebar_pos');

if($grove_sidebar_pos != '') {


	add_filter('body_class','grove_sidebar_pos_function');
	function grove_sidebar_pos_function($classes) {
		
		$grove_sidebar_pos = get_option('grove_sidebar_pos');
	
		$sidebar_allignment_set = 0;
		
		if((in_array("right-sidebar",$classes)) || (in_array("left-sidebar",$classes))) {
			$sidebar_allignment_set = 1;
		}
		
		if($sidebar_allignment_set == 0) {
			$classes[] = $grove_sidebar_pos.'-sidebar';
		}
		
		return $classes;
	}

}

?>
<!DOCTYPE html>
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
<link href="<?php echo get_template_directory_uri(); ?>/webfonts/ss-standard.css" rel="stylesheet" />
<link href="<?php echo get_template_directory_uri(); ?>/webfonts/ss-social-regular.css" rel="stylesheet" />
<link href="<?php echo get_template_directory_uri(); ?>/webfonts/ss-social-circle.css" rel="stylesheet" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
<script src="<?php echo get_template_directory_uri(); ?>/js/small-menu.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.countdown.js" type="text/javascript"></script>

<?php if(get_option( 'trackcode' ) != '') { echo get_option( 'trackcode' ); } ?>

</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<?php do_action( 'before' ); $header_image = get_header_image(); ?>
<div id="masthead-outer">
	<header id="masthead" class="site-header<?php if ( ! empty( $header_image ) ) { echo ' image'; }; ?>" role="banner" <?php if ( ! empty( $header_image ) ) { ?>style="height:<?php echo get_custom_header()->height; ?>px"<?php } ?>>

		<div class="masthead-inner">
        
        <?php 
		
		
			if(get_option('header_countdown_settings') != 'No Coundown') {
			
				if(get_option('header_countdown_settings')) { 
				
				
			
					$cdown_cat = get_option('header_countdown_settings');
					
					
					$cdown_event_args = array( 'posts_per_page' => 100, 'category' => $cdown_cat, 'order'=> 'DESC', 'orderby' => 'post_date' );
					
					
					$cdown_events = tribe_get_events(
						array(
							'eventDisplay'=>'all',
							'posts_per_page'=>100,
							'tax_query'=> array(
								array(
									'taxonomy' => 'tribe_events_cat',
									'field' => 'term_id',
									'terms' => $cdown_cat
								)
							)
						)
					);
				
					function personSort( $a, $b ) {
						return $a->EventStartDate == $b->EventStartDate ? 0 : ( $a->EventStartDate > $b->EventStartDate ) ? 1 : -1;
					}
		
					usort( $cdown_events, 'personSort' );
		
					$cdown_i = 0;
					$cdown_i_x = 'x';
		
					foreach($cdown_events as $cdown_event) {
						
						//print_r($cdown_event);
						
						$cdown_event_date = strtotime($cdown_event->EventStartDate);
						$cdown_now_date = strtotime(gmdate('r', time()));
						
						//echo $cdown_event->post_title.' '.$cdown_event->EventStartDate.' '.strtotime($cdown_event->EventStartDate).'='.$cdown_now_date.' ';
						
						if($cdown_event_date > $cdown_now_date) {
							if($cdown_i_x == 'x') { $cdown_i_x = $cdown_i; }
						}
						
						$cdown_i++;
						
					}
			
					
					$cdown_date['y'] = date('Y',strtotime($cdown_events[$cdown_i_x]->EventStartDate));
					$cdown_date['m'] = date('m',strtotime($cdown_events[$cdown_i_x]->EventStartDate))-1;
					$cdown_date['d'] = date('j',strtotime($cdown_events[$cdown_i_x]->EventStartDate));
					$cdown_date['h'] = date('H',strtotime($cdown_events[$cdown_i_x]->EventStartDate));
					$cdown_date['i'] = date('i',strtotime($cdown_events[$cdown_i_x]->EventStartDate));
					$cdown_date['s'] = date('s',strtotime($cdown_events[$cdown_i_x]->EventStartDate));
					
				
				}
				
			}
	
	
					?>
			
					
					
						<?php /*
					
						<span id="fafas"></span>
					
						<a href="/watch-live/"><div id="timer-title">Next Live Stream</div>
					
						<div id="thetimer">test</div></a>
					
						<script>
					
						var $ = jQuery.noConflict();
					
						var liftoffTime = new Date(Date.UTC(<?php echo $cdown_date['y']; ?>, <?php echo $cdown_date['m']; ?>, <?php echo $cdown_date['d']; ?>, <?php echo $cdown_date['h']; ?>, <?php echo $cdown_date['i']; ?>, <?php echo $cdown_date['s']; ?>));

						<?php   // This is for local timers, but has not been completed. 
							// Continue work here is this feature needs to be added.
						
							if($local_timer == 1) { ?>
						
								liftoffTime = new Date(<?php echo $cdown_date['y']; ?>, <?php echo $cdown_date['m']; ?>, <?php echo $cdown_date['d']; ?>, <?php echo $cdown_date['h']; ?>, <?php echo $cdown_date['i']; ?>, <?php echo $cdown_date['s']; ?>); <?php
							
							}
						
						?>

						//liftoffTime.setDate(liftoffTime);
					
						//$('#fafas').html(liftoffTime);

						<?php //$strtime = strtotime('2014-10-28 15:00:00'); ?>
						//alert(liftoffTime+' <?php echo $strtime.' -> '.$cdown_date['h']; ?>');

						$('#thetimer').countdown({until: liftoffTime, 
							format: 'HMS', expiryUrl: '<?php echo curPageURL(); ?>', compact: true, 
							layout: '{hnn}{sep}{mnn}{sep}{snn}</b> {desc}', description: ''});
					
						</script>
                        
                        */ ?>
                        
                        <?php if ( !dynamic_sidebar('headerbox-widget') ) : endif; ?>
					
		

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
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'grove' ); ?>"><?php _e( 'Skip to content', 'grove' ); ?></a></div>
			<div id="gr_mob_nav_trg">MENU<i id="gr_mob_nav_trg_icon" class="ss-icon ss-standard">&#xE9A1;</i></div>
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