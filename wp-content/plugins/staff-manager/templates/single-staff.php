<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Grove
 * @since Grove 1.0
 */

get_header(); ?>

		<div id="primary" class="full-page-primary">
			<div id="content-full" class="full-page-content" role="main">
			
			<?php do_action( 'grove_before_single_content' ); ?>
			<div class="staff-wrapper">
			<?php while ( have_posts() ) : the_post(); ?>

				<div class="staff">    

					<div class="container">	

					    <figure class="left">
				        	<?php the_post_thumbnail( array( 633, 9999999 ) ) ?>
				        </figure>
						
						<header>

							<i></i>
							<h1><?php the_title() ?></h1>
							<div class="meta">
								<div class="title"><?php echo get_post_meta( get_the_ID(), '_job_title', true ) ?></div>

								<?php if ($email = get_post_meta( get_the_ID(), '_contact_email', true )): ?>
								&nbsp;/&nbsp;<div class="title"><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></div>									
								<?php endif ?>
							</div>

						</header>

						<section class="staff-bio"><?php the_content() ?></section>

					</div>
				</div>


			<?php endwhile; // end of the loop. ?>

				<?php do_action( 'grove_after_single_content' ); 
				$args = array(
					'post_type' 			=> 'staff',
					'posts_per_page'		=> -1,
					'post_status'			=> 'publish',
					'orderby'				=> 'date',
					'order'					=> 'desc',
					'post__not_in'			=> array( get_the_ID() )
				);

				$staff = new WP_Query( $args );

				if ( $staff->have_posts() ) : ?>

					<?php while ( $staff->have_posts() ) : $staff->the_post(); ?>
					<section class="staff" data-href="<?php the_permalink() ?>">    

						<div class="container">	

						    <figure class="left">
					        	<?php the_post_thumbnail( array( 307, 9999999 ) ) ?>
					        </figure>
							
							<header>

								<i></i>
								<h1><?php the_title() ?></h1>
								<div class="meta">
									<div class="title"></div>			
								</div>

							</header>	

						</div>
					</section>
					<?php endwhile; // end of the loop. ?>
					<script>
					jQuery(document).on('click', '[data-href]', function(){
						window.location.href = jQuery(this).attr('data-href');
						return false;
					});
					</script>
				<?php endif; ?>

				<?php grove_content_nav( 'nav-below' ); ?>
				</div>
			</div><!-- #content .site-content -->
			
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>