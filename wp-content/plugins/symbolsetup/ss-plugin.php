<?php
/*
Plugin Name: Symbolsetup
Description: A quick and easy plugin for adding Symbolset fonts to your WordPress theme.
Version: 0.2
Author: ClarkLab
Author URI: http://clarklab.com
*/

// I know there is lot of code here for simply adding some fonts, but don't worry, it's about 85% options/admin/styling. Just haaaad to make it pretty.

$ss_standard = dirname(__FILE__).'/webfonts/ss-standard.css' ;
$ss_social = dirname(__FILE__).'/webfonts/ss-social.css' ;

if (file_exists($ss_standard)) {
wp_register_style( 'ss-standard', plugins_url( 'webfonts/ss-standard.css' , __FILE__ ) );
}
if (file_exists($ss_social)) {
wp_register_style( 'ss-social', plugins_url( 'webfonts/ss-social.css' , __FILE__ ) );
}
if (is_admin) {
wp_register_style( 'ss-admin-styles', plugins_url( 'inc/style.css' , __FILE__ ) );
}

function ss_styles()  
{ 
if (get_option('ss_social')){
  wp_enqueue_style( 'ss-social' );
}
if (get_option('ss_regular')){
  wp_enqueue_style( 'ss-standard' );
}
}
add_action('wp_enqueue_scripts', 'ss_styles');

function ss_settings (){
$ss = add_management_page('Symbolset Settings', 'Symbolset', 'manage_options', 'ss_setting_file', 'ss_setup');
add_action( 'admin_print_styles-' . $ss, 'symbolesetup_admin_styles' );
}

add_action('admin_menu', 'ss_settings');

function symbolesetup_admin_styles() {
	     wp_enqueue_style( 'ss-social' );
       wp_enqueue_style( 'ss-standard' );
       wp_enqueue_style( 'ss-admin-styles' );

   }

function ss_setup (){

$ss_regular = get_option('ss_regular');
$ss_social = get_option('ss_social');
	    
if(isset($_POST['Submit'])) 	{

  if(isset($_POST['ss_regular']) && $_POST['ss_regular'] == 'true') {
    update_option( 'ss_regular', 'true' );
  } else {
    delete_option( 'ss_regular' );
    echo $_POST["ss_regular"];
}

if(isset($_POST['ss_social']) && $_POST['ss_social'] == 'true') {
    update_option( 'ss_social', 'true' );
} else {
    delete_option( 'ss_social' );
    echo $_POST["ss_social"];
}

?>

<div class="updated"><p><strong><i class="ss-icon">check</i><?php _e('Options saved.'); ?></strong></p></div>

<?php  }  ?>


   
<div class="wrap ss_settings">
<form method="post" name="options" target="_self">

<h2>Which sets do you want?</h2>

<table width="100%" cellpadding="10" class="form-table">

<tr>
<td align="left" scope="row">

      <?php 

      $ss_standard = dirname(__FILE__).'/webfonts/ss-standard.css' ;
      $ss_social = dirname(__FILE__).'/webfonts/ss-social.css' ;

if (file_exists($ss_standard)) { ?>
<div class="ss_regular_wrap">
    <input type="checkbox" name="ss_regular" id="ss_regular" value="true"<?php if (get_option('ss_regular')) echo ' checked'; ?> /><label for="ss_regular">SS Standard</label>
    <?php $reg_words = array("send", "map", "downloadcloud", "notebook", "globe", "attach", "home", "view", "tag", "thumbsup"); shuffle($reg_words); $rand_reg_words = array_rand($reg_words, 5); ?>
    <i class="ss-icon"><?php echo $reg_words[$rand_reg_words[0]]; ?></i>
    <i class="ss-icon"><?php echo $reg_words[$rand_reg_words[1]]; ?></i>
    <i class="ss-icon"><?php echo $reg_words[$rand_reg_words[2]]; ?></i>
    <i class="ss-icon"><?php echo $reg_words[$rand_reg_words[3]]; ?></i>
    <i class="ss-icon"><?php echo $reg_words[$rand_reg_words[4]]; ?></i>
</div>
<?php } 

if (file_exists($ss_social)) { ?>
<div class="ss_social_wrap">
    <input type="checkbox" name="ss_social" id="ss_social" value="true"<?php if (get_option('ss_social')) echo ' checked'; ?> /><label for="ss_social">SS Social</label>
    <?php $social_words = array("twitter", "instagram", "vimeo", "facebook", "youtube", "spotify", "pinterest", "rdio", "dribbble", "wordpress"); shuffle($social_words); $rand_social_words = array_rand($social_words, 5); ?>
    <i class="ss-icon ss-social"><?php echo $social_words[$rand_social_words[0]]; ?></i>
    <i class="ss-icon ss-social"><?php echo $social_words[$rand_social_words[1]]; ?></i>
    <i class="ss-icon ss-social"><?php echo $social_words[$rand_social_words[2]]; ?></i>
    <i class="ss-icon ss-social"><?php echo $social_words[$rand_social_words[3]]; ?></i>
    <i class="ss-icon ss-social"><?php echo $social_words[$rand_social_words[4]]; ?></i>
</div>
<?php
    
}

if (!file_exists($ss_standard) OR !file_exists($ss_social)) {
	echo "<p>Please add your Symbolset files to the <strong>/webfonts</strong> folder of this plugin.</p>";
} ?>  
 	

</td>  	
</tr>
</table>

<p class="submit">
<input type="submit" name="Submit" value="Update" id="submit" />
</p>

<?php if (file_exists($ss_standard) AND file_exists($ss_social)) {
	echo "If you're using more than one set, make sure you <a href='http://symbolset.com/blog/using-multiple-sets/'>know what you're doing</a>.";
}  ?>


<div id="icons"></div>



</form>
</div>

<?php 
}
?>