<?php
/**
 * @package Grove
 * @since Grove 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'grove' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php grove_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if (has_post_thumbnail() AND !has_post_thumbnail()) {
			$attr = array(
			'class' => 'alignleft',
			'alt'	=> trim(strip_tags( get_the_title() )),
			'title'	=> trim(strip_tags( get_the_title() )),
		);
		the_post_thumbnail('thumbnail', $attr); } ?>

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
    	<?php
				
				$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		
			if ($feat_image != '') {	 ?>
				
                <img class="post_featured_img" src="<?php echo $feat_image; ?>" />
                
			<?php
			}
		
		?>
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
    	<?php
				
				$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		
			if ($feat_image != '') {	 ?>
				
                <img class="post_featured_img" src="<?php echo $feat_image; ?>" />
                
			<?php
			}
		
		?>
		<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'grove' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'grove' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'grove' ) );
				if ( $categories_list && grove_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'grove' ), $categories_list ); ?>
			</span>
			
			<?php endif; // End if categories ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'grove' ) );
				if ( $tags_list ) :
			?>
			<span class="sep"> | </span>
			<span class="tag-links">
				<?php printf( __( 'Tagged %1$s', 'grove' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="sep"> | </span>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'grove' ), __( '1 Comment', 'grove' ), __( '% Comments', 'grove' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'grove' ), '<span class="sep"> | </span><span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
