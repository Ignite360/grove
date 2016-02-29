<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Grove
 * @since Grove 1.0
 */
?>
		<div id="secondary" class="widget-area" role="complementary">

			<?php do_action( 'grove_before_sidebar' ); ?>

			<?php get_template_part('inc/sidebar', 'nav') ?>

			<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : endif; // end sidebar widget area ?>

			<?php do_action( 'grove_after_sidebar' ); ?>

		</div><!-- #secondary .widget-area -->
