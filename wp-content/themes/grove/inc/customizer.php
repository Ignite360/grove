<?php

add_action('customize_register', 'grove_customize');
function grove_customize($wp_customize) {

	class WP_Customize_Social_Config extends WP_Customize_Control {
	public $type = 'social_setup';

	public function render_content() { ?>
	<script>	
	function popupwindow(url, title, w, h) {
  	var left = (screen.width/2)-(w/2);
  	var top = (screen.height/2)-(h/2);
  	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
	} 
	</script>
	<style type="text/css">select{margin-bottom:20px;}</style>
		<a target="_blank" onclick="popupwindow('/wp-admin/options-general.php?page=sh_sb_settings_page&chromeless=true', 'Social Settings', 980, 640); return false;" href="/wp-admin/options-general.php?page=sh_sb_settings_page" style="padding:10px 0; text-align:center; background:#00a99d; color:#fff; border-radius:4px; display:block; margin:10px 0;">Configure Social Links</a>
	<?php }
	}

	class WP_Customize_Widget_Config extends WP_Customize_Control {
	public $type = 'widget_setup';

	public function render_content() { ?>
	<style type="text/css">select{margin-bottom:20px;}#customize-section-grove_footer ul{padding-top:70px; position: relative;}</style>
		<a target="_blank" onclick="popupwindow('/wp-admin/widgets.php?chromeless=true', 'Widgets', 980, 800); return false;" href="/wp-admin/widgets.php" style="padding:10px 0; text-align:center; background:#00a99d; color:#fff; border-radius:4px; display:block; margin:10px 0; position:absolute; top:5px;left:20px; width:258px;">Configure Widgets</a>
	<?php }
	}

	class WP_Customize_Menu_Config extends WP_Customize_Control {
	public $type = 'menu_setup';

	public function render_content() { ?>
	<style type="text/css">select{margin-bottom:20px;}#customize-section-nav ul{padding-top:70px; position: relative;}</style>
		<a target="_blank" onclick="popupwindow('/wp-admin/nav-menus.php?chromeless=true', 'Menus', 980, 800); return false;" href="/wp-admin/widgets.php" style="padding:10px 0; text-align:center; background:#00a99d; color:#fff; border-radius:4px; display:block; margin:10px 0; position:absolute; top:5px;left:20px; width:258px;">Configure Navigation Links</a>
	<?php }
	}

	class WP_Customize_Textarea_Control extends WP_Customize_Control {
    	public $type = 'textarea';
 
	public function render_content() {
	        ?>
	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}

class WP_Customize_MiniFeature extends WP_Customize_Control {
	public $type = 'minifeature';

	public function render_content() {
		?>
		<label>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>

		<script type="text/javascript">
		jQuery(document).ready(function() {

		jQuery("#clear").live("click", function(){
		  	jQuery(this).parents(".accordion-section-content").find('input:eq(1), textarea').val('');
		  	jQuery(this).parents(".accordion-section-content").find('input:eq(2)').val('http://').trigger('change');
		  	jQuery(this).parents(".accordion-section-content").find(".remove").click();
		  	return false;
		  });

		  jQuery("#mini a").click(function() {
		  	var minicount = jQuery(this).text();
		  	jQuery(".accordion-section.open").removeClass("open").hide();
		  	jQuery("#accordion-section-grove_minifeature_" + minicount + "_settings").show();
		  	jQuery("#accordion-section-grove_minifeature_" + minicount + "_settings").addClass("open");
		  	return false;
		  });


		});
		</script>
		<nav id="mini">
			<a href="#">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
		</nav>

		<a href="#clear" id="clear">Clear</a>

		<?php
	}

	
}

	$wp_customize->add_section( 'grove_minifeature_1_settings', array(
		'title'          => 'Mini Features',
		'description'	 => 'Manage the buttons/links in the middle of the homepage.',
		'priority'       => 43,
	) );

	$wp_customize->add_setting( 'mini_1_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mini_1_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_minifeature_1_settings',
		'settings'   => 'mini_1_image',
	) ) );

	$wp_customize->add_setting( 'mini_1_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_1_title', array(
		'label'   => 'Title',
		'section' => 'grove_minifeature_1_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_1_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_1_link', array(
		'label'   => 'Link',
		'section' => 'grove_minifeature_1_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_1_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_minifeature( $wp_customize, 'mini_1_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_minifeature_1_settings',
	'settings'   => 'mini_1_excerpt',
	) ) );


	$wp_customize->add_section( 'grove_minifeature_2_settings', array(
		'title'          => 'Mini Features 2',
		'priority'       => 43,
	) );

	$wp_customize->add_setting( 'mini_2_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mini_2_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_minifeature_2_settings',
		'settings'   => 'mini_2_image',
	) ) );

	$wp_customize->add_setting( 'mini_2_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_2_title', array(
		'label'   => 'Title',
		'section' => 'grove_minifeature_2_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_2_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_2_link', array(
		'label'   => 'Link',
		'section' => 'grove_minifeature_2_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_2_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_minifeature( $wp_customize, 'mini_2_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_minifeature_2_settings',
	'settings'   => 'mini_2_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_minifeature_3_settings', array(
		'title'          => 'Mini Features 3',
		'priority'       => 43,
	) );

	$wp_customize->add_setting( 'mini_3_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mini_3_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_minifeature_3_settings',
		'settings'   => 'mini_3_image',
	) ) );

	$wp_customize->add_setting( 'mini_3_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_3_title', array(
		'label'   => 'Title',
		'section' => 'grove_minifeature_3_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_3_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_3_link', array(
		'label'   => 'Link',
		'section' => 'grove_minifeature_3_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_3_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_minifeature( $wp_customize, 'mini_3_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_minifeature_3_settings',
	'settings'   => 'mini_3_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_minifeature_4_settings', array(
		'title'          => 'Mini Features 4',
		'priority'       => 43,
	) );

	$wp_customize->add_setting( 'mini_4_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mini_4_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_minifeature_4_settings',
		'settings'   => 'mini_4_image',
	) ) );

	$wp_customize->add_setting( 'mini_4_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_4_title', array(
		'label'   => 'Title',
		'section' => 'grove_minifeature_4_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_4_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_4_link', array(
		'label'   => 'Link',
		'section' => 'grove_minifeature_4_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_4_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_minifeature( $wp_customize, 'mini_4_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_minifeature_4_settings',
	'settings'   => 'mini_4_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_minifeature_5_settings', array(
		'title'          => 'Mini Features 5',
		'priority'       => 43,
	) );

	$wp_customize->add_setting( 'mini_5_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'mini_5_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_minifeature_5_settings',
		'settings'   => 'mini_5_image',
	) ) );

	$wp_customize->add_setting( 'mini_5_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_5_title', array(
		'label'   => 'Title',
		'section' => 'grove_minifeature_5_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_5_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'mini_5_link', array(
		'label'   => 'Link',
		'section' => 'grove_minifeature_5_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'mini_5_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_minifeature( $wp_customize, 'mini_5_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_minifeature_5_settings',
	'settings'   => 'mini_5_excerpt',
	) ) );


	class WP_Customize_Hotbutton extends WP_Customize_Control {
	public $type = 'hotbutton';

	public function render_content() {
		?>
		<label>
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>

		

		<script type="text/javascript">
		jQuery(document).ready(function() {

		jQuery("#clear").live("click", function(){
		  	jQuery(this).parents(".accordion-section-content").find('input:eq(1), textarea').val('');
		  	jQuery(this).parents(".accordion-section-content").find('input:eq(2)').val('http://').trigger('change');
		  	jQuery(this).parents(".accordion-section-content").find(".remove").click();
		  	return false;
		  });

		  jQuery("#hb a").click(function() {
		  	var hbcount = jQuery(this).text();
		  	jQuery(".accordion-section.open").removeClass("open").hide();
		  	jQuery("#accordion-section-grove_hotbutton_" + hbcount + "_settings").show();
		  	jQuery("#accordion-section-grove_hotbutton_" + hbcount + "_settings").addClass("open");
		  	return false;
		  });


		});
		</script>
		<nav id="hb">
			<a href="#">1</a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
		</nav>

		<a href="#clear" id="clear">Clear</a>

		<?php
	}

	
}

	$wp_customize->add_section( 'grove_hotbutton_1_settings', array(
		'title'          => 'Hot Buttons',
		'description'	 => 'Manage the buttons/links in the middle of the homepage.',
		'priority'       => 44,
	) );

	$wp_customize->add_setting( 'hb_1_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hb_1_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_hotbutton_1_settings',
		'settings'   => 'hb_1_image',
	) ) );

	$wp_customize->add_setting( 'hb_1_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_1_title', array(
		'label'   => 'Title',
		'section' => 'grove_hotbutton_1_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_1_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_1_link', array(
		'label'   => 'Link',
		'section' => 'grove_hotbutton_1_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_1_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Hotbutton( $wp_customize, 'hb_1_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_hotbutton_1_settings',
	'settings'   => 'hb_1_excerpt',
	) ) );


	$wp_customize->add_section( 'grove_hotbutton_2_settings', array(
		'title'          => 'Hot Button 2',
		'priority'       => 44,
	) );

	$wp_customize->add_setting( 'hb_2_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hb_2_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_hotbutton_2_settings',
		'settings'   => 'hb_2_image',
	) ) );

	$wp_customize->add_setting( 'hb_2_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_2_title', array(
		'label'   => 'Title',
		'section' => 'grove_hotbutton_2_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_2_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_2_link', array(
		'label'   => 'Link',
		'section' => 'grove_hotbutton_2_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_2_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Hotbutton( $wp_customize, 'hb_2_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_hotbutton_2_settings',
	'settings'   => 'hb_2_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_hotbutton_3_settings', array(
		'title'          => 'Hot Button 3',
		'priority'       => 44,
	) );

	$wp_customize->add_setting( 'hb_3_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hb_3_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_hotbutton_3_settings',
		'settings'   => 'hb_3_image',
	) ) );

	$wp_customize->add_setting( 'hb_3_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_3_title', array(
		'label'   => 'Title',
		'section' => 'grove_hotbutton_3_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_3_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_3_link', array(
		'label'   => 'Link',
		'section' => 'grove_hotbutton_3_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_3_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Hotbutton( $wp_customize, 'hb_3_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_hotbutton_3_settings',
	'settings'   => 'hb_3_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_hotbutton_4_settings', array(
		'title'          => 'Hot Button 4',
		'priority'       => 44,
	) );

	$wp_customize->add_setting( 'hb_4_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hb_4_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_hotbutton_4_settings',
		'settings'   => 'hb_4_image',
	) ) );

	$wp_customize->add_setting( 'hb_4_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_4_title', array(
		'label'   => 'Title',
		'section' => 'grove_hotbutton_4_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_4_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_4_link', array(
		'label'   => 'Link',
		'section' => 'grove_hotbutton_4_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_4_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Hotbutton( $wp_customize, 'hb_4_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_hotbutton_4_settings',
	'settings'   => 'hb_4_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_hotbutton_5_settings', array(
		'title'          => 'Hot Button 5',
		'priority'       => 44,
	) );

	$wp_customize->add_setting( 'hb_5_image', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'hb_5_image', array(
		'label'   => 'Image Setting',
		'section' => 'grove_hotbutton_5_settings',
		'settings'   => 'hb_5_image',
	) ) );

	$wp_customize->add_setting( 'hb_5_title', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_5_title', array(
		'label'   => 'Title',
		'section' => 'grove_hotbutton_5_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_5_link', array(
		'default'        => 'http://',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'hb_5_link', array(
		'label'   => 'Link',
		'section' => 'grove_hotbutton_5_settings',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hb_5_excerpt', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Hotbutton( $wp_customize, 'hb_5_excerpt', array(
	'label'   => 'Excerpt (optional)',
	'section' => 'grove_hotbutton_5_settings',
	'settings'   => 'hb_5_excerpt',
	) ) );

	$wp_customize->add_section( 'grove_home_banner_text', array(
		'title'          => 'Homepage banner text',
		'description'	 => 'What should the large banner say?',
		'priority'       => 45,
	) );

	$wp_customize->add_setting( 'grove_home_banner_text_setting', array(
		'default'        => '',
		'type'		=> 'option',
	) );

	$wp_customize->add_control( 'grove_home_banner_text_setting', array(
		'label'   => 'Homepage text',
		'section' => 'grove_home_banner_text',
		'type'    => 'text',
	) );

	$wp_customize->add_section( 'grove_slider_settings', array(
		'title'          => 'Slider (Homepage)',
		'description'	 => 'Manage the main slider on the homepage.',
		'priority'       => 42,
	) );

	$wp_customize->add_setting( 'slide_type', array(
		'default'        => '',
		'type'	=> 'option',
	) );
	
	$wp_customize->add_control( 'slide_type', array(
	'label'   => 'Pull Slide Show From',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => array('sliderstype'=>'Slideshows','posttype'=>'Posts'),
	'priority' => 1, 
	) );
	
	
	$wp_customize->add_setting( 'slide_pcat', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$post_categories = get_terms( 'category', 'orderby=name' ); 
			foreach ( $post_categories as $pcat ) {
			$pcats[$pcat->slug] = $pcat->name;
			 };

	$wp_customize->add_control( 'slide_pcat', array(
	'label'   => 'If From Posts, Choose Category',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => $pcats,
	'priority' => 2, 
	) );
	

	$wp_customize->add_setting( 'slide_text_side', array(
		'default'        => '',
		'type'	=> 'option',
	) );
	
	$wp_customize->add_control( 'slide_text_side', array(
	'label'   => 'Slide Text Align',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => array('left'=>'Align Left','right'=>'Align Right','top'=>'Align Top','bottom'=>'Align Bottom'),
	'priority' => 3, 
	) );
	

	$wp_customize->add_setting( 'slide_text_overlay', array(
		'default'        => '',
		'type'	=> 'option',
	) );
	
	$wp_customize->add_control( 'slide_text_overlay', array(
	'label'   => 'Overlay',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => array('none'=>'None','full'=>'Full','natural'=>'Natural'),
	'priority' => 4, 
	) );

	$wp_customize->add_setting( 'slide_page', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$slide_categories = get_terms("slide-page"); 
			foreach ( $slide_categories as $cat ) {
			$slides[$cat->slug] = $cat->name;
			 };

	$wp_customize->add_control( 'slide_page', array(
	'label'   => 'If From Slideshow, Choose Slide Group',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => $slides,
	'priority' => 5, 
	) );
	
	

	$wp_customize->add_setting( 'slide_num', array(
		'default'        => '',
		'type'	=> 'option',
	) );
	
	$wp_customize->add_control( 'slide_num', array(
	'label'   => 'How Many Slides To Display?',
	'section' => 'grove_slider_settings',
	'type'    => 'select',
	'choices'    => array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','11'=>'11','12'=>'12'),
	'priority' => 6, 
	) );
	
	$wp_customize->add_setting( 'slider_linking', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'slider_linking', array(
		'label'   => 'Link Slide To It\'s Post?',
		'section' => 'grove_slider_settings',
		'type'    => 'checkbox',
		'priority' => 7, 
	) );
	
	$wp_customize->add_setting( 'slide_custom_id', array(
		'default'        => '',
		'type'	=> 'option',
	) );
	
	$wp_customize->add_control( 'slide_custom_id', array(
	'label'   => 'Custom ID',
	'section' => 'grove_slider_settings',
	'type'    => 'text',
	'priority' => 8, 
	) );

	$wp_customize->add_setting( 'small_slider', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'small_slider', array(
		'label'   => 'Small slider?',
		'section' => 'grove_slider_settings',
		'type'    => 'checkbox',
		'priority' => 9, 
	) );

	$wp_customize->add_setting( 'slide_feature_static', array(
	'default'        => '',
	'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'slide_feature_static', array(
	'label'   => 'Feature content (for use with small slider)',
	'section' => 'grove_slider_settings',
	'settings'   => 'slide_feature_static',
	'priority' => 10, 
	) ) );

	$wp_customize->add_section( 'grove_social_settings', array(
		'title'          => 'Social Settings',
		'description'	 => 'Where should the social links be shown?',
		'priority'       => 41,
	) );

	$wp_customize->add_setting( 'social_config', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Social_Config( $wp_customize, 'social_config', array(
	'section' => 'grove_social_settings',
	'settings'   => 'social_config',
	) ) );

	$wp_customize->add_setting( 'show_social_header', array(
		'default'        => 1,
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'show_social_header', array(
		'label'   => 'Show social links in header?',
		'section' => 'grove_social_settings',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'header_social_position', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'header_social_position', array(
	'label'   => 'Social Position (Header)',
	'section' => 'grove_social_settings',
	'type'    => 'select',
	'choices'    => array(
		'top' => 'top',
		'middle' => 'middle',
		'bottom' => 'bottom',
		),
	) );

	$wp_customize->add_setting( 'show_social_footer', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'show_social_footer', array(
		'label'   => 'Show social links in footer?',
		'section' => 'grove_social_settings',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'footer_social_position', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'footer_social_position', array(
	'label'   => 'Social Position (Footer)',
	'section' => 'grove_social_settings',
	'type'    => 'select',
	'choices'    => array(
		'top' => 'top',
		'bottom' => 'bottom',
		),
	) );

	$wp_customize->add_section( 'grove_tweet_ticker', array(
		'title'          => 'Tweet Ticker',
		'description'	 => 'Show scrolling status updates on the homepage',
		'priority'       => 45,
	) );

	$wp_customize->add_setting( 'show_tweet_ticker', array(
		'default'        => '',
		'type'		=> 'option',
	) );

	$wp_customize->add_control( 'show_tweet_ticker', array(
		'label'   => 'Show tweet ticker on homepage?',
		'section' => 'grove_tweet_ticker',
		'type'    => 'checkbox',
	) );

	$wp_customize->add_setting( 'tweet_ticker_user', array(
		'default'        => '',
		'type'		=> 'option',
	) );

	$wp_customize->add_control( 'tweet_ticker_user', array(
		'label'   => 'Twitter user (without @)',
		'section' => 'grove_tweet_ticker',
		'type'    => 'text',
	) );
	
	
	
	
	
	
	
	
	
	$wp_customize->add_section( 'grove_tracking_code', array(
		'title'          => 'Tracking Code',
		'description'	 => 'Add yout tracking code here',
		'priority'       => 24,
	) );

	$wp_customize->add_setting( 'trackcode', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'trackcode', array(
	'label'   => 'Tracking Code (inside head area)',
	'section' => 'grove_tracking_code',
	'settings'   => 'trackcode',
	) ) );
	
	

	$wp_customize->add_setting( 'trackcodef', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'trackcodef', array(
	'label'   => 'Tracking Code (bottom of footer)',
	'section' => 'grove_tracking_code',
	'settings'   => 'trackcodef',
	) ) );
	
	
	
	
	
	

	$wp_customize->add_section( 'grove_custom_logo', array(
		'title'          => 'Logo',
		'description'	 => 'Display a custom logo?',
		'priority'       => 25,
	) );

	$wp_customize->add_setting( 'custom_logo', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'custom_logo', array(
		'label'   => 'Custom logo',
		'section' => 'grove_custom_logo',
		'settings'   => 'custom_logo',
	) ) );

	$wp_customize->add_setting( 'link_color', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
	'label'   => 'Link Color',
	'section' => 'colors',
	'settings'   => 'link_color',
) ) );

	$wp_customize->add_setting( 'header_nav_x', array(
		'default'        => 'left',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'header_nav_x', array(
	'label'   => 'Navigation position (X-axis)',
	'section' => 'nav',
	'type'    => 'select',
	'choices'    => array(
		'left' => 'left',
		'center' => 'center',
		'right' => 'right',
		),
	) );

	$wp_customize->add_setting( 'header_nav_y', array(
		'default'        => 'bottom',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'header_nav_y', array(
	'label'   => 'Navigation position (Y-axis)',
	'section' => 'nav',
	'type'    => 'select',
	'choices'    => array(
		'top' => 'top',
		'middle' => 'middle',
		'bottom' => 'bottom',
		),
	) );

	$wp_customize->add_setting( 'menu_config', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Menu_Config( $wp_customize, 'menu_config', array(
	'section' => 'nav',
	'settings'   => 'menu_config',
	) ) );

	$wp_customize->add_section( 'grove_footer', array(
		'title'          => 'Footer',
		'priority'       => 105,
	) );

	$wp_customize->add_setting( 'footer_text', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'footer_text', array(
		'label'   => 'Footer Tagline',
		'section' => 'grove_footer',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'widget_config_footer', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Widget_Config( $wp_customize, 'widget_config_footer', array(
	'section' => 'grove_footer',
	'settings'   => 'widget_config_footer',
	) ) );

	$wp_customize->add_section( 'grove_font_settings', array(
		'title'          => 'Font Settings',
		'description'	 => 'Which font style would you like to use?',
		'priority'       => 48,
	) );

	$wp_customize->add_setting( 'body-font', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'body-font', array(
	'label'   => 'Body font',
	'section' => 'grove_font_settings',
	'type'    => 'select',
	'choices'    => array(
		'Arial' => 'Arial',
		'Open Sans' => 'Open Sans',
		'Georgia' => 'Georgia',
		),
	) );

	$wp_customize->add_setting( 'typekit', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'typekit', array(
	'label'   => 'Typekit Script',
	'section' => 'grove_font_settings',
	'settings'   => 'typekit',
	) ) );

	$wp_customize->add_section( 'grove_address', array(
		'title'          => 'Address',
		'priority'       => 105,
	) );

	$wp_customize->add_setting( 'address', array(
		'default'        => '',
		'type'	=> 'option',
	) );

	$wp_customize->add_control( 'address', array(
		'label'   => 'Address',
		'section' => 'grove_address',
		'type'    => 'text',
	) );

}

function grove_insert_css() {

	$typekit = get_option( 'typekit' ); if ($typekit) { echo $typekit;	};

	global $post;

	$style = '<style type="text/css">';

	if (get_theme_mod( 'background_image' ) OR get_theme_mod( 'background_position_x') OR get_theme_mod( 'background_repeat' ) OR get_theme_mod( 'background_color' ) OR get_option( 'body-font' )) {
	$style .= 'body {';
	if (get_theme_mod( 'background_image' )) { $style .= 'background-image:url('.get_theme_mod( 'background_image' ).');'; };
	if (get_theme_mod( 'background_position_x' )) { $style .= 'background-position:top '.get_theme_mod( 'background_position_x' ).';'; };
	if (get_theme_mod( 'background_repeat' )) { $style .= 'background-repeat:'.get_theme_mod( 'background_repeat' ).';'; };
	if (get_theme_mod( 'background_color' )) { $style .= 'background-color:#'.get_theme_mod('background_color').';'; };
	if (get_theme_mod( 'background_attachment' )) { $style .= 'background-attachment:'.get_theme_mod('background_attachment').';'; };
	if (get_option( 'body-font' )) { $style .= 'font-family:'.get_option( 'body-font' ).' !important;'; };
	$style .= '}';
	}

	$background_image=get_post_meta( $post->ID, '_grove_background_image', true );
	if($background_image) {
	$style .= 'body {background-image:url('.$background_image.');}';
	}

	$background_color=get_post_meta( $post->ID, '_grove_background_color', true );
	if($background_color) {
	$style .= 'body {background-color:'.$background_color.';}';
	}

	if(get_option('link_color')){
	$style .= 'a, .follow-button{color:'.get_option('link_color').'}';
	$style .= 'a.button{background:'.get_option('link_color').';}';
	$style .= '.wooslider-control-paging li a.wooslider-active{background:'.get_option('link_color').';}';
	$style .= '.wooslider-control-paging li a:hover{background:'.get_option('link_color').'; opacity:0.5}';
	 };
	
	if (get_option('get_header_textcolor')) $style .= '#masthead{color:#'.get_header_textcolor().'}';
	if (get_option('header_nav_y') == 'top') $style .='#masthead .site-navigation{top:0;}#masthead{padding-top:60px;}';
	if (get_option('header_nav_y') == 'middle') $style .= '#masthead .site-navigation{top:50%; margin-top:-20px;}';
	if (get_option('header_nav_y') == 'bottom') $style .= '#masthead .site-navigation{bottom:0;}';
	if (get_option('header_nav_x') == 'left') $style .= '#masthead .site-navigation{left:0;}';
	if (get_option('header_nav_x') == 'center') $style .= '#masthead .site-navigation{left:50%;}';
	if (get_option('header_nav_x') == 'right') $style .= '#masthead .site-navigation{right:0;}';

	$style .= '</style>';

	echo $style;
};

add_action('wp_head', 'grove_insert_css');