<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_action( 'cmb_render_image_radio', 'rrh_cmb_render_image_radio', 10, 2 );
function rrh_cmb_render_image_radio( $field, $meta ) {
    if( empty( $meta ) && !empty( $field['std'] ) ) $meta = $field['std'];
					echo '<div class="cmb_radio_inline">';
					$i = 1;
					foreach ($field['options'] as $option) {
						echo '<div class="cmb_radio_inline_option"><input type="radio" name="', $field['id'], '" id="', $field['id'], $i, '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' /><label for="', $field['id'], $i, '">', $option['name'], '</label></div>';
						$i++;
					}
					echo '</div>';
					echo '<p class="cmb_metabox_description">', $field['desc'], '</p>';
}

add_filter( 'cmb_meta_boxes', 'cmb_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_grove_';

	$meta_boxes[] = array(
		'id'         => 'sidebar_options',
		'title'      => 'Sidebar Options',
		'pages'      => array( 'post', 'page', 'tribe_events' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			/*array(
				'name'    => 'Show sidebar?',
				'desc'    => 'Should this entry show the sidebar?',
				'id'      => $prefix . 'hide_sidebar',
				'type'    => 'image_radio',
				'options' => array(
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-sidebar-right.png" width="72" title="Sidebar on right">', 'value' => 'right', ),
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-sidebar-left.png" width="72" title="Sidebar on left">', 'value' => 'left', ),
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-no-sidebar.png" width="72" title="No sidebar">', 'value' => 'hide', ),
				),
			),   */
			array(
				'name'    => 'Banner size',
				'desc'    => 'How large should the main image be?',
				'id'      => $prefix . 'banner_size',
				'type'    => 'image_radio',
				'options' => array(
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-regular-banner.png" width="72" title="Regular banner image">', 'value' => 'regular', ),
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-large-banner.png" width="72" title="Large banner image">', 'value' => 'large', ),
					array( 'name' => '<img src="'.site_url().'/wp-content/mu-plugins/inc/images/layout-no-banner.png" width="72" title="Hide banner image">', 'value' => 'hide', ),
				),
			),
			
		),
	);

	$meta_boxes[] = array(
		'id'         => 'blog_page_options',
		'title'      => 'Blog Page Options',
		'pages'      => array('page'), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name'    => 'Category',
				'desc'    => 'Which category would you like displayed?',
				'id'      => $prefix . 'category',
				'type'    => 'text'
			),
			array(
				'name'    => 'Tags',
				'desc'    => 'Which tags would you like displayed?',
				'id'      => $prefix . 'tags',
				'type'    => 'text'
			),
			
		),
	);

	$meta_boxes[] = array(
		'id'         => 'background_options',
		'title'      => 'Custom Background',
		'pages'      => array('page'),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
			array(
				'name'    => 'Image',
				'desc'    => 'Should this page have a custom background?',
				'id'      => $prefix . 'background_image',
				'type'    => 'file'
			),
			array(
			            'name' => 'Color',
			            'desc' => 'Select a background color',
			            'id'   => $prefix . 'background_color',
			            'type' => 'colorpicker'
			        )
			
		),
	);

	

	return $meta_boxes;
}

add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 9999 );
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'init.php';

}

add_action('admin_head', 'metabox_manager');

function metabox_manager () {

$screen = get_current_screen();
if ( $screen->id == 'page' )

{ 
if (get_post_meta($_GET['post'], '_wp_page_template', true) != 'page-blog.php') { echo '<style>#blog_page_options{display:none;}</style>';}
 }
}
