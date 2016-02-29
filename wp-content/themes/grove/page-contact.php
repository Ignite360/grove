<?php
/*
 * Template name: Contact Page
 *
 * @package Grove
 * @since Grove 1.0
 */

get_header(); ?>

<?php $banner = get_post_meta($post->ID, '_grove_banner_size', true);

if (has_post_thumbnail()) {
			$attr = array(
			'alt'	=> trim(strip_tags( get_the_title() )),
			'title'	=> trim(strip_tags( get_the_title() )),
		);

if ($banner!='hide') { if ($banner=='large' OR $sidebar=='hide') { the_post_thumbnail('full', $attr); } else {$image = get_the_post_thumbnail($post->ID, '1170', $attr);} } } ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				<?php echo $image; ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'page' ); ?>

					<?php add_action('grove_before_sidebar', 'sidebar_map');

					 function sidebar_map() {
						if (get_option('address')) { echo '<div class="location">'.do_shortcode('[gmap height="240px"]'.get_option('address').'[/gmap]'); echo '<strong>'.get_option('address').'</strong></div>'; } else { }
					} ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php if ($sidebar!='hide') { get_sidebar(); }; ?>
<?php get_footer(); ?>