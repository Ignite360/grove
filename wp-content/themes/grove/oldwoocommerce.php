<?php
/**
 * Template name: WooCommerce
 *
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Grove
 * @since Grove 1.0
 */

get_header(); ?>

<?php	$banner = get_post_meta($post->ID, '_ignite_banner_size', true);
$sidebar = get_post_meta($post->ID, '_grove_hide_sidebar', true);

if (has_post_thumbnail()) {
			$attr = array(
			'alt'	=> trim(strip_tags( get_the_title() )),
			'title'	=> trim(strip_tags( get_the_title() )),
		);

if ($banner!='hide') { if ($banner=='large' OR $sidebar=='hide') { the_post_thumbnail('960', $attr); } else {$image = get_the_post_thumbnail($post->ID, '720', $attr);} } } ?>

		<div id="primary" class="content-area <? if ($sidebar=='hide'){?> full-page-primary <?}?>">
			<div id="content" class="site-content <? if ($sidebar=='hide'){?> full-page-content <?}?>" role="main">

				<?php do_action( 'grove_before_page_content' ); ?>

				<?php woocommerce_content(); ?>

				<?php do_action( 'grove_after_page_content' ); ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->


<?php if ($sidebar!='hide') { get_sidebar(); }?>
<?php get_footer(); ?>