<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Grove
 * @since Grove 1.0
 */

get_header(); ?>

		<?php do_action( 'grove_home_before_slider' ); ?>

			<?php get_template_part( 'inc/slider', 'home'); ?>

			<?php do_action( 'grove_home_after_slider' ); ?>

			<?php get_template_part('mini', 'features'); ?>

			<?php get_template_part('hot', 'buttons'); ?>  

			<?php do_action( 'grove_home_after_hotbuttons' ); ?>

			<?php get_template_part('inc/ticker') ?>

			<?php do_action( 'grove_home_after_ticker' ); ?>

			<?php if (count_sidebar_widgets('homepage-featured-content', false) > 0) { ?>
				<div class="homepage-featured-content-outer">
				<div class="homepage-featured-content widgets-<?php count_sidebar_widgets('homepage-featured-content') ?>">
				<?php if ( ! dynamic_sidebar( 'homepage-featured-content' ) ) : endif; ?>
				</div>
				</div>
			<?php } ?>

			<?php do_action( 'grove_home_after_widgets' ); ?>

<?php get_footer(); ?>