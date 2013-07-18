<?php
/*
Plugin Name: Subscriber Gallery
Plugin URI: http://Ignite360.com
Description: Custom registration form and Subscriber Gallery.
Version: 1.0
Author: valiik (Valik Rudd)
Author URI: http://flynewmedia.com/
Donate link: http://bit.ly/A3SfBN
*/

define('SG_VERSION', '1.0');
define('SG_DIR', dirname(__FILE__));

register_activation_hook(__FILE__,'sg_install'); 

//redirect on plugin activation
add_action('admin_init', 'sg_redirect');
function sg_redirect() {
    if (get_option('sg_do_activation_redirect') == true) {
        delete_option('sg_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("admin.php?page=subscriber-gallery/subscriber-gallery.php");
        }
    }
}

register_deactivation_hook( __FILE__, 'sg_remove' );



function sg_show_authors_without_posts($sg_template) {
		global $wp_query;
		if( !is_author() && get_query_var('author') && (0 == $wp_query->posts->post) ) {
			// debug
			// echo 'Overwrite default 404 template...';
			return get_author_template();
		}
		return $sg_template;
	}
	
add_filter('404_template', 'sg_show_authors_without_posts');
	
	
// add jquery & custom scripts
function sg_adds_to_the_head() { 
    wp_enqueue_script("jquery");
	
	wp_register_script( 'add-cg-custom-js', plugins_url( 'cg.js' , __FILE__ ), '', null,''  ); // Register our second custom script for CG
	wp_register_style( 'add-cg-css', plugins_url( 'cg.css' , __FILE__ ),'','', 'screen' ); // Register the CG Stylsheet
	
    wp_enqueue_script( 'add-cg-custom-js' );
    wp_enqueue_style( 'add-cg-css' );
	
}
add_action( 'wp_enqueue_scripts', 'sg_adds_to_the_head' );


// redirects back to custom registration page after user registered
function sg_possibly_redirect(){
  global $pagenow;
  if( 'wp-login.php' == $pagenow ) {
	if(isset($_GET['checkemail']) && $_GET['checkemail']=='registered') {
		wp_redirect( home_url().'/register/?cg-reg=1' );
    	exit();
	} else {
		return;	
	}
  }
}
add_action('init','sg_possibly_redirect');


// innitiate onload javascript function
function sg_init_function() {
    echo '<script>sg_initing();</script>';
}
add_action('wp_footer', 'sg_init_function');


// gravatar URL from gravatar
function sg_get_avatar_url($sg_get_avatar){
    preg_match("/src='(.*?)'/i", $sg_get_avatar, $sg_matches);
    return $sg_matches[1];
}

// [subscriber_gallery sortby="registered"] (Sort by 'ID', 'login', 'nicename', 'email', 'url', 'registered', 'display_name', or 'post_count'.)
function sg_subscriber_gallery_func( $atts ) {
	
	extract( shortcode_atts( array(
		'sortby' => 'registered'
	), $atts ) );
	
    $blogusers = get_users('orderby={$sortby}&role=subscriber');

    foreach ($blogusers as $user) {
		
		$sg_member_gravatar = sg_get_avatar_url(get_avatar( $user->user_email, 200 ));
		
		if (  get_user_meta( $user->ID, 'biz_name', true ) ) {
			$sg_title = get_user_meta( $user->ID, 'biz_name', true );
		} else {
			$sg_title = $user->display_name;
		}
     	
		$sg_members .= '<div class="sg_subscriber"><a href="'.get_author_posts_url( $user->ID ).'"><div class="sg_subscriber_gravatar" style="background:url('.$sg_member_gravatar.') top center no-repeat;"></div><div class="sg_subscriber_title">' . $sg_title  . '</div></a></div>';
    }
	
	$sg_members .= '<div style="clear:both;"></div>';

	return $sg_members;
}
add_shortcode( 'subscriber_gallery', 'sg_subscriber_gallery_func' );


///////////////////////////
// BUSINESS NAME FIELD

add_action( 'show_user_profile', 'sg_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'sg_show_extra_profile_fields' );

function sg_show_extra_profile_fields( $user ) { ?>

	<h3>Business Information</h3>

	<table class="form-table">

		<tr>
			<th><label for="biz_name">Business Name</label></th>

			<td>
				<input type="text" name="biz_name" id="biz_name" value="<?php echo esc_attr( get_the_author_meta( 'biz_name', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please add your Business name.</span>
			</td>
		</tr>

	</table>
<?php }


add_action( 'personal_options_update', 'sg_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'sg_save_extra_profile_fields' );

function sg_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. Make sure to change 'twitter' to the field ID. */
	update_usermeta( $user_id, 'biz_name', $_POST['biz_name'] );
}

add_action('register_form','sg_myplugin_register_form');
    function sg_myplugin_register_form (){
        $biz_name = ( isset( $_POST['biz_name'] ) ) ? $_POST['biz_name']: '';
        ?>
        <p>
            <label for="biz_name"><?php _e('Business Name','mydomain') ?><br />
                <input type="text" name="biz_name" id="biz_name" class="input" value="<?php echo esc_attr(stripslashes($biz_name)); ?>" size="25" /></label>
        </p>
        <?php
    }
	
add_filter('registration_errors', 'sg_myplugin_registration_errors', 10, 3);
    function sg_myplugin_registration_errors ($errors, $sanitized_user_login, $user_email) {

        if ( empty( $_POST['biz_name'] ) )
            $errors->add( 'biz_name_error', __('<strong>ERROR</strong>: You must include a business name.','mydomain') );

        return $errors;
    }
	
    add_action('user_register', 'sg_myplugin_user_register');
    function sg_myplugin_user_register ($user_id) {
        if ( isset( $_POST['biz_name'] ) )
            update_user_meta($user_id, 'biz_name', $_POST['biz_name']);
    }

// END BUSINESS NAME FIELD
///////////////////////////



///////////////////////////
// MAIN PLUGIN ACTIVATION SCRIPT

function sg_install() {
	
add_option('sg_do_activation_redirect', true);

update_option( 'users_can_register', 1 );
update_option( 'show_avatars', 1 );	

ob_start(); ?>

<div id="sg_reg_form"> <!-- Registration -->
		<div id="sg-register-form">
		<div class="title">
			<div id="sg-statement" class="sg-statement">A password will be e-mailed to you.</div>
			<h1>Register your Account</h1>
			<span>If you do not have a Gravatar set up yet, please set it up <a href="http://gravatar.com" target="_blank">here</a>.</span>
		</div>
			<form action="<?php echo site_url("wp-login.php?action=register", "login_post"); ?>" method="post">
			<input type="text" name="biz_name" value="Business Title" id="biz_name" class="input" onfocus="repl_titl()" onblur="repl_titl()" />
			<input type="text" name="user_login" value="Username" id="user_login" class="input" onfocus="repl_user()" onblur="repl_user()" />
			<input type="text" name="user_email" value="E-Mail" id="user_email" class="input" onfocus="repl_email()" onblur="repl_email()"  />
				<input type="submit" value="Register" id="register" />
			
			</form>
		</div>
</div>

<?php
$r_new_page_content = ob_get_contents();
  ob_end_clean();

$m_new_page_content = '<div id="sg_members">[subscriber_gallery sortby="registered"]</div>';



	if (is_admin()){
		
		// Registration page setup
        $r_new_page_title = 'Register';
        $r_new_page_content = $r_new_page_content;
        $r_new_page_template = ''; //ex. template-custom.php. Leave blank if you don't want a custom page template.
        //don't change the code bellow, unless you know what you're doing
        $r_page_check = get_page_by_title($r_new_page_title);
        $r_new_page = array(
                'post_type' => 'page',
                'post_title' => $r_new_page_title,
                'post_content' => $r_new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
		
        if(!isset($r_page_check->ID)){
                $r_new_page_id = wp_insert_post($r_new_page);
                if(!empty($r_new_page_template)){
                        update_post_meta($r_new_page_id, '_wp_page_template', $r_new_page_template);
                }
        }
		
		// Members page setup
        $m_new_page_title = 'Members';
        $m_new_page_content = $m_new_page_content;
        $m_new_page_template = ''; //ex. template-custom.php. Leave blank if you don't want a custom page template.
        //don't change the code bellow, unless you know what you're doing
        $m_page_check = get_page_by_title($m_new_page_title);
        $m_new_page = array(
                'post_type' => 'page',
                'post_title' => $m_new_page_title,
                'post_content' => $m_new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
		
        if(!isset($m_page_check->ID)){
                $m_new_page_id = wp_insert_post($m_new_page);
                if(!empty($m_new_page_template)){
                        update_post_meta($m_new_page_id, '_wp_page_template', $m_new_page_template);
                }
        }
		
		
	}
	
	
}


// END MAIN PLUGIN ACTIVATION SCRIPT
///////////////////////////



///////////////////////////
// MAIN ADMIN AREA

if ( is_admin() ){

	add_action('admin_menu', 'sg_create_menu');

	function sg_create_menu() {

		add_menu_page('Subscriber Gallery', 'Subscriber Ga', 'administrator', __FILE__, 'sg_settings_page',plugins_url('/images/sgicon.png', __FILE__));

		add_action( 'admin_init', 'sg_register_mysettings' );
	}


	function sg_register_mysettings() {
		register_setting( 'sg-settings-group', 'sg_title' );
		register_setting( 'sg-settings-group', 'sg_show_number' );
	}

	function sg_settings_page() { ?>

		<div class="wrap">
		
        	<h2>Subscriber Gallery</h2>

			<p>A Registration and a Members page has been created automatically.</p>
            
            <p>You still need to create a template for individual profiles. <strong>It's easy!</strong></p>
            
            <p>All you need to do is copy the author.php file from this plugin's directory,<br />right into your theme's directory <strong>OR</strong> create a new file called author.php<br />inside your theme's directory and insert the following code into it:</p>
            
            <p><textarea style="color:#09F;width:600px;height:200px;"><?php echo file_get_contents(plugins_url('inc/author.txt', __FILE__)); ?></textarea></p>
            
            <p>Upload the file and that's it.</p>
            
            <p>&nbsp;</p>
            
            <h2>Your Pages</strong></h2>
            
            <p>&nbsp;</p>
            
            <p><strong>Registration Page:</strong> <a href="<?php echo site_url("register", "login_post"); ?>" target="_blank"><?php echo site_url("register", "login_post"); ?></a></p>
            
            <p>In case you need to recreate your Registration page, just add this into the content area of your Registration page:</p>
            
            <p><textarea style="color:#09F;width:600px;height:200px;"><div id="sg_reg_form"> <!-- Registration -->
		<div id="sg-register-form">
		<div class="title">
			<div id="sg-statement" class="sg-statement">A password will be e-mailed to you.</div>
			<h1>Register your Account</h1>
			<span><?php echo $sg_auth_template; ?> If you do not have a Gravatar set up yet, please set it up <a href="http://gravatar.com" target="_blank">here</a>.</span>
		</div>
			<form action="<?php echo site_url("wp-login.php?action=register", "login_post"); ?>" method="post">
			<input type="text" name="biz_name" value="Business Title" id="biz_name" class="input" onfocus="repl_titl()" onblur="repl_titl()" />
			<input type="text" name="user_login" value="Username" id="user_login" class="input" onfocus="repl_user()" onblur="repl_user()" />
			<input type="text" name="user_email" value="E-Mail" id="user_email" class="input" onfocus="repl_email()" onblur="repl_email()"  />
				<input type="submit" value="Register" id="register" />
			
			</form>
		</div>
</div></textarea></p>
            
            <p>&nbsp;</p>
            
            
            <p><strong>Members Page:</strong> <a href="<?php echo site_url("members", "login_post"); ?>" target="_blank"><?php echo site_url("members", "login_post"); ?></a></p>
            
            <p>In case you need to recreate your Members page, just add this into the content area of your Members page:</p>
            
            <p><textarea style="color:#09F;width:600px;height:50px;"><div id="sg_members">[subscriber_gallery sortby="registered"]</div></textarea></p>
            
            
            
		</div><?php 
	
	} 


}


// END ADMIN AREA
///////////////////////////

?>