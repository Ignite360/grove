<?php

// support dashboard widget
function support_dashboard_widget() {
	echo "<p><a href='http://support.ignite360.com/index.php?/Tickets/Submit' target='_blank'><img src='/wp-content/mu-plugins/inc/images/need-help.png' style='max-width:100%;'></a></p>";
}
function add_support_dashboard_widget() {
	wp_add_dashboard_widget('support_dashboard_widget', 'Grove Support', 'support_dashboard_widget');
}
add_action('wp_dashboard_setup', 'add_support_dashboard_widget');

// disable default dashboard widgets
function disable_default_dashboard_widgets() {

	remove_meta_box('dashboard_primary', 'dashboard', 'core');
	remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
	remove_meta_box('tribe_dashboard_widget', 'dashboard', 'core');
	remove_meta_box('welcome_panel', 'dashboard', 'core');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');

}
add_action('admin_menu', 'disable_default_dashboard_widgets');

add_action('load-index.php', 'hide_welcome_screen' );

function hide_welcome_screen() {
    $user_id = get_current_user_id();

    if ( 1 == get_user_meta( $user_id, 'show_welcome_panel', true ) )
        update_user_meta( $user_id, 'show_welcome_panel', 0 );
}

?>