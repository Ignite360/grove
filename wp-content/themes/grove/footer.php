<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Grove
 * @since Grove 1.0
 */
?>

	</div><!-- #main .site-main -->

	<footer id="colophon" class="site-footer" role="contentinfo">

			<?php do_action( 'grove_before_footer' ); ?>

		<div class="site-info">
			<?php do_action( 'grove_credits' ); ?>

			<?php 
			if (get_option( 'footer_social_position' ) == 'top') { ?>

                <div class="footer-bar top">
                    <div class="footer-text"><?php echo get_option('footer_text') ?></div>
                
                    <?php if (get_option( 'show_social_footer' )) { if( function_exists( 'social_bartender' ) ){
                    echo '<div class="social">';
                    social_bartender();
                    echo '</div>'; } } ?>
                </div>
    
                <div class="footer-widgets widgets-<?php count_sidebar_widgets('footer-widgets') ?>">
                <?php if ( ! dynamic_sidebar( 'footer-widgets' ) ) : endif; ?>
                </div>
    
                <?php } else { ?>
    
                <div class="footer-widgets widgets-<?php count_sidebar_widgets('footer-widgets') ?>">
                <?php if ( ! dynamic_sidebar( 'footer-widgets' ) ) : endif; ?>
                </div>
    
                <div class="footer-bar bottom">
                    <div class="footer-text"><?php echo get_option('footer_text') ?></div>
    
                    <?php if (get_option( 'show_social_footer' )) { if( function_exists( 'social_bartender' ) ){ ?>
                    <?php echo '<div class="social">';
                    social_bartender();
                    echo '</div>'; } } ?>
                </div>
			
			<?php 
			} ?>
            
            <?php if ( has_nav_menu('footer-nav' ) ) { ?>
            
                <div class="footer-nav"><?php 
                            
                    wp_nav_menu( array('theme_location' => 'footer-nav' )); ?>
                                                   
                    <div style="clear:both;"></div>
                            
                </div>
            
			<?php }  ?>

			

		</div><!-- .site-info -->

		<?php do_action( 'grove_after_footer' ); ?>
        
        <div class="credit">
		<p>Designed and built by <a href="http://faithhighway.com">faithHighway</a>.</p>
		</div>

	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>


<?php if(get_option( 'trackcodef' ) != '') { echo get_option( 'trackcodef' ); } ?>

</body>
</html>