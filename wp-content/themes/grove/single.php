<?php
/**
 * The Template for displaying all single posts.
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

if ($banner!='hide') { if ($banner=='large' OR $sidebar=='hide') { the_post_thumbnail('960', $attr); } else {$image = get_the_post_thumbnail($post->ID, '720', $attr);} } } ?>

		<div id="primary" class="content-area">

			<div id="content" class="site-content" role="main">

			<?php do_action( 'grove_before_single_content' ); ?>

			<?php echo $image; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'single' ); ?>

				<?php grove_content_nav( 'nav-below' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template( '', true );
				?>

			<?php endwhile; // end of the loop. ?>

			<?php do_action( 'grove_after_single_content' ); ?>

			</div><!-- #content .site-content -->
			
		</div><!-- #primary .content-area -->


<?php if ($sidebar!='hide') { get_sidebar(); }; ?>
<?php get_footer(); ?>