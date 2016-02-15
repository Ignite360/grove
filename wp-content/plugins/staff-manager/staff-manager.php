<?
/*
Plugin Name: Staff Page Manager
Plugin URI: http://www.faithhighway.com
Description: Creates a custom post type to insert and manage your staff
Author: Faith Highway
Author URI: http://www.faithhighway.com
Version: 1.2
*/

if ( !class_exists('fh_staff_manager' ) ):
class fh_staff_manager {

	function __construct() {

		register_activation_hook( __FILE__, array( __CLASS__, 'run_activation_hook') );
		register_deactivation_hook( __FILE__, array( __CLASS__, 'run_deactivation_hook') );

		add_action( 'admin_menu', array( __CLASS__, 'settings_page' ) );
		add_action( 'admin_init', array( __CLASS__, 'save_settings' ) );
		add_action( 'admin_head', array( __CLASS__, 'admin_head' ) );

		add_action( 'wp_head', array( __CLASS__, 'custom_styles' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_staff_css' ) );
		add_action( 'init', array( __CLASS__, 'register_staff_type' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'staff_add_meta_box' ) );
		add_action( 'save_post', array( __CLASS__, 'staff_save_meta_box_data' ) );

		add_filter( 'single_template', array( __CLASS__, 'staff_single' ) );
		add_filter( 'archive_template', array( __CLASS__, 'staff_archive' ) );
		add_filter( 'post_thumbnail_html', array( __CLASS__, 'post_thumbnail_html' ), 10, 5 );

		add_shortcode( 'staff', array( __CLASS__, 'display_staff_archive' ) );

	}

	static function run_activation_hook() {

		add_option( 'staff_base', 'leadership' );
		add_option( 'icon_url', plugins_url( 'assets/images/chevron.png', __FILE__ ) );
		add_option( 'small_image_width', 307 );
		add_option( 'small_image_height', 350 );
		add_option( 'single_image_width', 633 );
		add_option( 'single_image_height', 'auto' );

		self::register_staff_type();

		flush_rewrite_rules();
	}

	static function run_deactivation_hook() {

		flush_rewrite_rules();
	}

	static function settings_page() {

		add_options_page( 'Staff Manager Settings', 'Staff Manager', 'administrator', 'staff-manager', array( __CLASS__, 'plugin_options') );
	}

	static function plugin_options()
	{
		?>
		<div class="wrap">
			<h2>Staff Manager Settings</h2>
			
			<form method="post">
			<table class="form-table">
				<tfoot>
					<tr>
						<td colspan="2">
							<input type="submit" name="save_staff_settings" value="Update Settings" class="button-primary">
						</td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<th scope="row"><label for="staff_base">Staff Base</label></th>
						<td><input type="text" name="staff_base" value="<?php echo get_option( 'staff_base' ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="banner_id">Staff featured image ID</label></th>
						<td>
							<input type="text" name="banner_id" id="banner_id" value="<?php echo get_option( 'banner_id' ); ?>" class="regular-text"> <button id="banner_media">Find image</button>
							<div><small class="description"><i style="font-weight: bold; color: red;">Enter the ID number of the media file to use as the featured image</i></small></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="icon_url">Custom Icon</label></th>
						<td>
							<input type="text" name="icon_url" id="icon_url" value="<?php echo get_option( 'icon_url' ); ?>" class="regular-text"> <button id="icon_media">Find image</button>
							<div><small class="description"><i>Image should be 31px by 31px</i></small></div>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="small_image_width">Small Image Width</label></th>
						<td><input type="text" name="small_image_width" value="<?php echo get_option( 'small_image_width' ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="small_image_height">Small Image Height</label></th>
						<td><input type="text" name="small_image_height" value="<?php echo get_option( 'small_image_height' ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="single_image_width">Single Image Width</label></th>
						<td><input type="text" name="single_image_width" value="<?php echo get_option( 'single_image_width' ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="single_image_height">Single Image Width</label></th>
						<td><input type="text" name="single_image_height" value="<?php echo get_option( 'single_image_height' ); ?>" class="regular-text"></td>
					</tr>
					<tr>
						<th scope="row"><label for="single_image_height">Customize styles</label></th>
						<td><textarea class="large-text" name="staff_css" rows="30"><?php echo file_get_contents( plugin_dir_path(__FILE__). '/assets/staff.css' ); ?></textarea></td>
					</tr>
				</tbody>
			</table>

		</div>
		<?php
	}

	static function save_settings()	{

		if ( isset($_POST['save_staff_settings'] ) ) {

			update_option( 'staff_base', $_POST['staff_base'] );
			update_option( 'icon_url', $_POST['icon_url'] );
			update_option( 'banner_id', $_POST['banner_id'] );
			update_option( 'small_image_width', $_POST['small_image_width'] );
			update_option( 'small_image_height', $_POST['small_image_height'] );
			update_option( 'single_image_width', $_POST['single_image_width'] );
			update_option( 'single_image_height', $_POST['single_image_height'] );

			file_put_contents( plugin_dir_path(__FILE__). '/assets/staff.css', $_POST['staff_css'] );

			flush_rewrite_rules();

			wp_redirect( admin_url( 'options-general.php?page=staff-manager' ) );
		}
	}

	static function admin_head() {

		wp_enqueue_media();
		wp_enqueue_script( 'staff', plugins_url( 'assets/staff.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	}

	static function custom_styles() {

		?>
		<style>
			section.staff figure {
				width: <?php echo get_option('small_image_width'); ?>px;
				height: <?php echo get_option('small_image_height'); ?>px;
				overflow: hidden;
			}
			div.staff {
				width: <?php echo get_option('single_image_width'); ?>px;
			}
			div.staff figure {
				height: <?php echo get_option('single_image_height'); ?>px;
				overflow: hidden;
			}
		</style>
		<?php
	}	

	static function enqueue_staff_css() {

		wp_enqueue_style( 'sumit-staf-style', plugins_url( 'assets/staff.css', __FILE__ ) );
	}


	static function register_staff_type() {

		$pt_labels = array(
			'name'			   => _x( 'Our Staff', 'post type general name', 'your-plugin-textdomain' ),
			'singular_name'	  => _x( 'Staff Member', 'post type singular name', 'your-plugin-textdomain' ),
			'menu_name'		  => _x( 'Our Staff', 'admin menu', 'your-plugin-textdomain' ),
			'name_admin_bar'	 => _x( 'Our Staff', 'add new on admin bar', 'your-plugin-textdomain' ),
			'add_new'			=> _x( 'Add Staff Member', 'job', 'your-plugin-textdomain' ),
			'add_new_item'	   => __( 'Add Staff Member', 'your-plugin-textdomain' ),
			'new_item'		   => __( 'New Staff Member', 'your-plugin-textdomain' ),
			'edit_item'		  => __( 'Edit Staff Member', 'your-plugin-textdomain' ),
			'view_item'		  => __( 'View Staff Member', 'your-plugin-textdomain' ),
			'all_items'		  => __( 'All Staff Members', 'your-plugin-textdomain' ),
			'search_items'	   => __( 'Search Staff Members', 'your-plugin-textdomain' ),
			'parent_item_colon'  => __( 'Parent Staff Members:', 'your-plugin-textdomain' ),
			'not_found'		  => __( 'No staff found.', 'your-plugin-textdomain' ),
			'not_found_in_trash' => __( 'No staff found in Trash.', 'your-plugin-textdomain' )
		);

		$args = array(
			'labels'				=> $pt_labels,
			'supports'				=> array( 'title', 'editor', 'author', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'thumbnail' ),
			'hierarchical'			=> false,
			'public'				=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'		=> false,
			'show_in_admin_bar'		=> true,
			'menu_position'			=> 59,
			'can_export'			=> true,
			'has_archive'			=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'rewrite'				=> array( 'slug' => get_option( 'staff_base' ), 'with_front' => false ),
			'capability_type'		=> 'post',
			'taxonomies' 			=> array()
		);

		register_post_type( 'staff', $args );
		flush_rewrite_rules();
	}

	function staff_single( $single_template ) {

		global $post;

		$found = locate_template('single-staff.php');

		if( $post->post_type == 'staff' && ~ strlen($found) ) {

			$single_template = dirname(__FILE__).'/templates/single-staff.php';
		}

		return $single_template;
	}

	//route archive- template
	function staff_archive( $template ) {

		if(is_post_type_archive('staff')) {

			$theme_files = array('archive-staff.php');
			$exists_in_theme = locate_template($theme_files, false);

			if($exists_in_theme == '') {

				return plugin_dir_path(__FILE__) . '/templates/archive-staff.php';
			}
		}

		return $template;
	}

	static function display_staff_archive() {

		$args = array(
			'post_type' 			=> 'staff',
			'posts_per_page'		=> -1,
			'post_status'			=> 'publish',
			'orderby'				=> 'date',
			'order'					=> 'desc'
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
			jQuery(document).on('click', '[data-href]', static function(){
				window.location.href = jQuery(this).attr('data-href');
				return false;
			});
			</script>
		<?php endif;

		wp_reset_postdata();
	}


	static function staff_add_meta_box() {

		add_meta_box(
			'staff_sectionid',
			__( 'Staff Details', 'staff_textdomain' ),
			array( __CLASS__, 'staff_meta_box_callback' ),
			'staff',
			'side',
			'core'
		);
	}

	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	static function staff_meta_box_callback( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'staff_meta_box', 'staff_meta_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$job_title = get_post_meta( $post->ID, '_job_title', true );
		$contact_email = get_post_meta( $post->ID, '_contact_email', true );

		?>
		<p>
			<label for="job_title">Job Title</label>
			<br>
			<input type="text" name="job_title" id="job_title" value="<?php echo $job_title; ?>" class="widefat">
		</p>
		<p>
			<label for="contact_email">Contact Email</label>
			<br>
			<input type="text" name="contact_email" id="contact_email" value="<?php echo $contact_email; ?>" class="widefat">
		</p>
		<?php
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */

	static function staff_save_meta_box_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
		// Check if our nonce is set.
		if ( ! isset( $_POST['staff_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['staff_meta_box_nonce'], 'staff_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize user input.
		$job_title = sanitize_text_field( $_POST['job_title'] );
		$contact_email = sanitize_text_field( $_POST['contact_email'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, '_job_title', $job_title );
		update_post_meta( $post_id, '_contact_email', $contact_email );
	}

	function post_thumbnail_html( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

		if( (is_archive( get_option( 'staff_base' ) ) && !in_the_loop()) && $banner_id = get_option( 'banner_id' ) )
			return wp_get_attachment_image( $banner_id, $size, false, $attr );
		

		return $html;
	}
}

$fh_staff_manager = new fh_staff_manager;
endif;