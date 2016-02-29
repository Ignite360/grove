<?php
/*
	Plugin Name: Simple Ecards
	Description: Users may send a card and add a custom message. <strong>[simple_ecards]</strong>
	Version: 2.0
	Author: Ignite360, Inc.
	License: GPL2

	Copyright 2013  Ignite360, Inc.  (email : support@ignite360.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Enqueue the Javascript
add_action('wp_enqueue_scripts', 'simple_ecards_scripts');
function simple_ecards_scripts()
{
	wp_register_script('simple_ecards', plugins_url('js/simple_ecards.js', __FILE__), array('jquery'));

	wp_localize_script('simple_ecards', 'ajax_data', array('ajax_url' => admin_url('admin-ajax.php')));
	wp_enqueue_script('simple_ecards');

	wp_enqueue_style('simple_cards_style', plugins_url('css/simple_ecards.css', __FILE__));
}

// Register the post type, set up some shortcodes and ajax callbacks
add_action('init', 'simple_ecards_init');
function simple_ecards_init()
{
	simple_ecards_register();
	simple_ecards_register_shortcodes();
	simple_ecards_register_callbacks();

	$new_general_setting = new new_general_setting();
 

}
class new_general_setting {
    function new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    function register_fields() {
        register_setting( 'general', 'simple_ecard_from_email', 'esc_attr' );
        add_settings_field('simple_ecard_from_emailaddr', '<label for="simple_ecard_from_email">'.__('Simple Ecard From Email' , 'simple_ecard_from_email' ).'</label>' , array(&$this, 'fields_html') , 'general' );
    }
    function fields_html() {
        $value = get_option( 'simple_ecard_from_email', '' );
        echo '<input style="width:300px" type="text" id="simple_ecard_from_email" name="simple_ecard_from_email" value="' . $value . '" />';
    }
}

function simple_ecards_register()
{
	// First we register the ecards
	register_post_type('simple_ecard', array(
		'labels' => array(
			'name' => 'Ecards',
			'singular_name' => 'Ecard',
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Ecard',
			'edit_item' => 'Edit Ecard',
			'new_item' => 'New Ecard',
			'all_items' => 'All Ecards',
			'view_item' => 'View Ecard',
			'search_items' => 'Search Ecards',
			'not_found' => 'No Ecards Found',
			'not_found_in_trash' => 'No Ecards found in Trash',
			'menu_name' => 'Ecards'
		),
		'show_ui' => true,
		'show_in_menu' => true,
		'supports' => array(
			'title',
			'thumbnail',
			'page-attributes'
		)
	));
}

function simple_ecards_register_shortcodes()
{
	add_shortcode('simple_ecards', 'simple_ecards_shortcode');
}

function simple_ecards_shortcode($atts)
{
	$all_cards = array();
	$all_card_posts = get_posts('post_type=simple_ecard&posts_per_page=-1&orderby=menu_order&order=ASC');
	foreach($all_card_posts as $card)
	{
		if(has_post_thumbnail($card->ID))
		{
			$current_card_img_url = wp_get_attachment_image_src(get_post_thumbnail_id($card->ID), 'full');
			$current_card_img_url = $current_card_img_url[0];
			
			$current_card_alt = $card->post_title;
	
			$all_cards[] = array('img_url' => $current_card_img_url, 'alt' => $current_card_alt, 'card_id' => $card->ID);
		}
	}
	
	// reCaptcha
	require_once(plugin_dir_path(__FILE__) . 'recaptcha/recaptchalib.php');
	$public_key = '6Lf8U-4SAAAAAJIKHOalWm17jO7xH_tDv2Y8eAqz';
	
	// Preloader
	$return = "<div id='simple_ecards_preloader'>";
	foreach($all_cards as $card)
	{
		$return .= "<img src='" . $card['img_url'] . "' alt='' id='card_" . $card['card_id'] . "' />";
	}
	$return .= "</div><!-- #simple_ecards_preloader -->";

	// Form
	$return .= "<form id='simple_ecards_send_form' method='post'>";
		$return .= "<fieldset id='simple_ecards_image_select'>";
			$return .= "<legend id='simple_ecards_image_select_legend'><strong>Select a Card</strong></legend>";
			$return .= "<div id='simple_ecards_card_wrapper'>";
				$return .= "<img src='" . $all_cards[0]['img_url'] . "' alt='" . $all_cards[0]['alt'] . "' />";
			$return .= "</div><!-- #simple_ecards_card_wrapper -->";
			$return .= "<label for='simple_ecards_card_select'>Select a Card</label>";
			$return .= "<select name='card_select' id='simple_ecards_card_select'>";
				foreach($all_cards as $card)
				{
					$return .= "<option value='" . $card['card_id'] . "'>" . $card['alt'] . "</option>";
				}
			$return .= "</select><!-- #simple_ecards_card_select -->";
		$return .= "</fieldset><!-- #simple_ecards_image_select -->";

		$return .= "<fieldset id='simple_ecards_mail_info'>";
			$return .= "<legend>Address information</legend>";
			$return .= "<label for='simple_ecards_send_to_name'><em class='simple_ecards_required'>*</em><strong>To name:</strong>";
			$return .= "<input type='text' class='required' name='simple_ecards_send_to_name' id='simple_ecards_send_to_name'></input></label>";
			$return .= "<label for='simple_ecards_send_to'><em class='simple_ecards_required'>*</em><strong>To email:</strong>";
			$return .= "<input type='email' class='required email' name='send_to' id='simple_ecards_send_to' required></input></label>";
			$return .= "<label for='simple_ecards_from_name'><em class='simple_ecards_required'>*</em><strong>Your name:</strong>";
			$return .= "<input type='text' class='required' name='simple_ecards_from_name' id='simple_ecards_from_name'></input></label>";
			$return .= "<label for='simple_ecards_from'><em class='simple_ecards_required'>*</em><strong>Your email:</strong>";
			$return .= "<input type='email' class='required email' name='send_from' id='simple_ecards_from' required></input></label>";
			$return .= "<label for='simple_ecards_subject'><em class='simple_ecards_required'>*</em><strong>Subject Line:</strong>";
			$return .= "<input type='text' class='required' name='simple_ecards_subject' id='simple_ecards_subject'></input></label>";
			$return .= "<label for='simple_ecards_message'><strong>Message:</strong>";
			$return .= "<textarea name='message' id='simple_ecards_message'></textarea></label>";
		$return .= "</fieldset><!-- #simple_ecards_mail_info -->";
		$return .= "<fieldset id='simple_ecards_recaptcha'>";
			$return .= recaptcha_get_html($public_key);
		$return .= "</fieldset><!-- #simple_ecards_recaptcha -->";
		$return .= "<fieldset id='simple_ecards_submit_section'>";
			$return .= "<input type='submit' id='simple_ecards_submit' value='Send' />";
		$return .= "</fieldset><!-- #simple_ecards_submit_section -->";
	$return .= "</form><!-- .simple_ecards_send_form -->";

	return $return;
}
	
function simple_ecards_register_callbacks()
{
	add_action('wp_ajax_simple_ecards_send', 'simple_ecards_send_callback');
	add_action('wp_ajax_nopriv_simple_ecards_send', 'simple_ecards_send_callback');
}

function simple_ecards_send_callback()
{
	$card_info = array(
		'card' => $_GET['card'],
		'send_to' => $_GET['send_to'],
		'send_to_name' => $_GET['send_to_name'],
		'send_from' => $_GET['send_from'],
		'send_from_name' => $_GET['send_from_name'],
		'subject' => $_GET['subject'],
		'message' => $_GET['message']
	);

	// reCaptcha
	require_once(plugin_dir_path(__FILE__) . 'recaptcha/recaptchalib.php');
	$private_key = '6Lf8U-4SAAAAAHg4-Qd3OIyMzssb1tPayo1kPiAX';
	$resp = recaptcha_check_answer(
		$private_key,
		$_SERVER['REMOTE_ADDR'],
		$_GET['recaptcha_challenge_field'],
		$_GET['recaptcha_response_field']
	);

	if(!$resp->is_valid)
	{
		die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")");
	}

	if(validate_cardinfo($card_info))
	{

		add_filter('wp_mail_content_type', 'set_html_content_type');
		$body = generate_ecard($card_info['card'], $card_info['message']);
		$fromtemp = get_option('simple_ecard_from_email');
		$headers = '';

		if ($fromtemp != ''){
			$headers = 'From: E-Invite Card <'.$fromtemp.'>' . "\r\n";
		}

		elseif(!empty($card_info['send_from']) && !empty($card_info['send_from_name']))
		{
			$headers = 'From: ' . $card_info['send_from_name'] . '<' . $card_info['send_from'] . '>' . "\r\n";
		}
		
		$sent = wp_mail($card_info['send_to'], stripslashes($card_info['subject']), $body, $headers);
		remove_filter('wp_mail_content_type', 'set_html_content_type');
		if($sent)
		{
			add_post_meta($card_info['card'], 'send_log', $card_info['send_to']);
			die("We've sent your card to " . $card_info['send_to'] . ". Thanks!");
		}
		else die('There was an error sending your card. Please try again later.');
	}

	else
	{
		die("Some of that info didn't look right! Please try again.");
	}
}

add_action('dashboard_glance_items', 'simple_ecards_right_now');
function simple_ecards_right_now()
{
	if(!post_type_exists('simple_ecard'))
		return;

	$num_posts = wp_count_posts('simple_ecard');
	$num = number_format_i18n($num_posts->publish);
	$text = _n('Ecard', 'Ecards', intval($num_posts->publish));
	if(current_user_can('edit_posts'))
	{
		$num = "<a href='edit.php?post_type=simple_ecard'>$num";
		$text = "$text</a>";
	}
	echo "<li class='ecard-count'>$num $text</li>";

	if ($num_posts->pending > 0) {
		$num = number_format_i18n( $num_posts->pending );
		$text = _n( 'Ecard Pending', 'Ecards Pending', intval($num_posts->pending) );
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_status=pending&post_type=simple_ecard'>$num";
			$text = "$text</a>";
		}
		echo "<li class='ecard-count'>$num $text</li>";
		
        }
}

function simple_ecards_add_default()
{
	for($i = 1; $i <= 4; $i ++)
	{
		$post_args = array(
			'menu_order' => $i,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'simple_ecard',
			'post_title' => "Ecard $i"
		);

		$post_id = wp_insert_post($post_args);
		
		// We've made the post, now we add the featured image
		// But first, we have to 'upload' the images
		$filename = plugins_url("/default_cards/card$i.jpg", __FILE__);
		$upload = wp_upload_bits(basename($filename), null, file_get_contents($filename));

		if($upload['error']) die($upload['error']);
		
		$wp_upload_dir = wp_upload_dir();
		$new_filename = $wp_upload_dir['url'] . '/' . basename($filename);
		$new_abs_filename = $wp_upload_dir['path'] . '/' . basename($filename);
		$wp_filetype = wp_check_filetype(basename($filename));
		$attachment = array(
			'guid' => $new_filename,
			'post_mime_type' => $wp_filetype['type'],
			'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
			'post_content' => '',
			'post_status' => 'inherit'
		);

		$attach_id = wp_insert_attachment($attachment, $new_abs_filename, $post_id);

		require_once(ABSPATH . 'wp-admin/includes/image.php');
		$attach_data = wp_generate_attachment_metadata( $attach_id, $new_abs_filename );

		// TODO: Make this acutally work
		wp_update_attachment_metadata( $attach_id,  $attach_data );

		if(!set_post_thumbnail($post_id, $attach_id)) die('error!');
	}
}

function generate_ecard($card_id, $message = '')
{
	$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($card_id), array(999, 999));
	$image_url = $image_url[0];

	if($message != '') $message = htmlspecialchars($message);
	$body =
"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
</head>
<body leftmargin='0' marginwidth='0' topmargin='0' marginheight='0' offset='0' style='margin: 0;padding: 20px; background:#f4f4f4;'>
	<center style='padding:0 20px;'>
		<table border='0' cellpadding='10' cellspacing='0' height='100%' width='100%' style='border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;margin:20px 0 0 0;padding:0;height:100% !important;width:100% !important;max-width:600px; background:#fff; border-right:5px solid #eaeaea;border-bottom:5px solid #eaeaea;'>
			<tr>
				<td align='center' valign='top' style='border-collapse:collapse;mso-table-lspace:0pt;mso-table-rspace:0pt;'>";
	$body .= "<a href='http://".$_SERVER['HTTP_HOST']."'><img src='$image_url' alt border='0' style='width:100%;margin:0;padding:0;border:0;height:auto;line-height:100%;outline:none;text-decoration:none;'></a>";
	if($message != '') $body .= "<p style='font-family:Helvetica, Arial, sans-serif; font-size:18px; line-height:26px; text-align:left; padding:10px;'>".stripslashes($message)."</p>";
	$body .= 
				"</td>
			</tr>
		</table>
	</center>
</body>
</html>";
	
	return $body;
}

function validate_cardinfo($card_info)
{
	// isset returns false for null
	if(
		isset($card_info['card']) &&
		isset($card_info['send_to']) &&
		isset($card_info['send_to_name']) &&
		isset($card_info['send_from']) &&
		isset($card_info['send_from_name']) &&
		isset($card_info['subject'])
	)
	{
		// Now that we know data exists in all the required fields, validate it
		if(
			is_numeric($card_info['card']) &&
			is_email($card_info['send_to']) &&
			is_email($card_info['send_from'])
		)
		{
			return true;
		}
	}
}

// Admin Columns
add_filter('manage_simple_ecard_posts_columns', 'simple_ecards_admin_column');
function simple_ecards_admin_column($column)
{
	$column['send_log'] = 'Cards Sent';
	
	return $column;
}

add_filter('manage_simple_ecard_posts_custom_column', 'simple_ecards_admin_row', 10, 2);
function simple_ecards_admin_row($column_name, $post_id)
{
	$cf = get_post_custom($post_id);
	switch($column_name)
	{
	case 'send_log' :
		echo count($cf['send_log']);
	break;
	}
}

function set_html_content_type()
{	
	return 'text/html';
}

register_activation_hook(__FILE__, 'simple_ecards_activate');
register_deactivation_hook(__FILE__, 'simple_ecards_deactivate');
function simple_ecards_activate()
{
	// We register the ecards here as well, because we will be adding default ecards if there are no ecards in the system
	simple_ecards_register();
	$current_ecards = get_posts('post_type=simple_ecard');
	if(count($current_ecards) == 0)
	{
		simple_ecards_add_default();
	}
	
	flush_rewrite_rules();
}

function simple_ecards_deactivate()
{
	flush_rewrite_rules();
}

function db($data)
{
	echo "<pre>";
	var_dump($data);
	echo "</pre>";
	echo "<hr />";
}

