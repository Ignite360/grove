<?php if (get_option( 'small_slider' )) { ?>

	<div class="small-slider-outer">
	<div class="slider half">
	<?php echo do_shortcode('[wooslider slide_page="'.get_option("slide_page").'" slider_type="slides" direction="horizontal" layout="text-'.get_option('slide_text_layout').'" overlay="'.get_option('slide_overlay').'" limit="'.get_option("slide_num").'" thumbnails="'.get_option("slide_pagination").'" link_slide="true" display_title="'.get_option('slide_title').'" imageslide="true" order="'.get_option("slide_order_by").'" order_by="'.get_option("slide_sort_by").'" size="full"]') ?>
	</div>
	
	<div class="homepage-features">
	<?php echo get_option('slide_feature_static') ?>
	</div>
	</div>

<?php } else {?>

	<div class="slider-outer">
	<div class="slider">
	<?php  ?>
    	<?php echo do_shortcode('[wooslider slide_page="'.get_option("slide_page").'" slider_type="slides" direction="horizontal" layout="text-'.get_option('slide_text_layout').'" overlay="'.get_option('slide_overlay').'" limit="'.get_option("slide_num").'" thumbnails="'.get_option("slide_pagination").'" link_slide="true" display_title="'.get_option('slide_title').'" imageslide="true" order="'.get_option("slide_order_by").'" order_by="'.get_option("slide_sort_by").'" size="full"]') ?>    
	</div>
	</div>
	
<?php } ?>