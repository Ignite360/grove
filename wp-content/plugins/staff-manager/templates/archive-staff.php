<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Grove
 * @since Grove 1.0
 */

get_header(); ?>
<section id="primary" class="full-page-primary">
	<div id="content-full" class="full-page-content" role="main">
		<?php echo $image; ?>

	<?php if ( have_posts() ) : ?>

		<header class="entry-header">
			<h1 class="entry-title">Our Staff</h1>
		</header><!-- .entry-header -->

		<?php if ( function_exists('grove_content_nav') ) grove_content_nav( 'nav-above' ); ?>
		<div class="staff-wrapper">
		<?php /* Start the Loop */ ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<section class="staff" data-href="<?php the_permalink() ?>">    

				<div class="container">	

				    <figure class="left">
			        	<?php the_post_thumbnail( array( 307, 9999999 ) ) ?>
			        </figure>
					
					<header>

						<i><img src="<?php echo get_option( 'icon_url' ); ?>" alt=""></i>
						<h1><?php the_title() ?></h1>
						<div class="meta">
							<div class="title"><?php echo get_post_meta( get_the_ID(), '_job_title', true ) ?></div>			
						</div>

					</header>	

				</div>
			</section>

		<?php endwhile; ?>

		<?php if ( function_exists('grove_content_nav') ) grove_content_nav( 'nav-below' ); ?>
		</div>

	<?php else : ?>

		<?php get_template_part( 'no-results', 'archive' ); ?>

	<?php endif; ?>

	</div><!-- #content .site-content -->
</section><!-- #primary .content-area -->
<script>
jQuery(document).on('click', '[data-href]', function(){
	window.location.href = jQuery(this).attr('data-href');
	return false;
});
</script>
<?php get_footer(); ?>