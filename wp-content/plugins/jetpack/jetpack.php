<?php

/*
 * Plugin Name: Jetpack by WordPress.com
 * Plugin URI: http://jetpack.me
 * Description: Bring the power of the WordPress.com cloud to your self-hosted WordPress. Jetpack enables you to connect your blog to a WordPress.com account to use the powerful features normally only available to WordPress.com users.
 * Author: Automattic
<<<<<<< HEAD
 * Version: 3.9.1
=======
 * Version: 2.3.3
>>>>>>> origin/johndcoy
 * Author URI: http://jetpack.me
 * License: GPL2+
 * Text Domain: jetpack
 * Domain Path: /languages/
 */

<<<<<<< HEAD
define( 'JETPACK__MINIMUM_WP_VERSION', '4.3' );

define( 'JETPACK__VERSION',            '3.9.1' );
define( 'JETPACK_MASTER_USER',         true );
define( 'JETPACK__API_VERSION',        1 );
define( 'JETPACK__PLUGIN_DIR',         plugin_dir_path( __FILE__ ) );
define( 'JETPACK__PLUGIN_FILE',        __FILE__ );

defined( 'JETPACK_CLIENT__AUTH_LOCATION' )   or define( 'JETPACK_CLIENT__AUTH_LOCATION', 'header' );
defined( 'JETPACK_CLIENT__HTTPS' )           or define( 'JETPACK_CLIENT__HTTPS', 'AUTO' );
defined( 'JETPACK__GLOTPRESS_LOCALES_PATH' ) or define( 'JETPACK__GLOTPRESS_LOCALES_PATH', JETPACK__PLUGIN_DIR . 'locales.php' );
defined( 'JETPACK__API_BASE' )               or define( 'JETPACK__API_BASE', 'https://jetpack.wordpress.com/jetpack.' );
defined( 'JETPACK_PROTECT__API_HOST' )       or define( 'JETPACK_PROTECT__API_HOST', 'https://api.bruteprotect.com/' );
=======
defined( 'JETPACK__API_BASE' ) or define( 'JETPACK__API_BASE', 'https://jetpack.wordpress.com/jetpack.' );
define( 'JETPACK__API_VERSION', 1 );
define( 'JETPACK__MINIMUM_WP_VERSION', '3.3' );
defined( 'JETPACK_CLIENT__AUTH_LOCATION' ) or define( 'JETPACK_CLIENT__AUTH_LOCATION', 'header' );
defined( 'JETPACK_CLIENT__HTTPS' ) or define( 'JETPACK_CLIENT__HTTPS', 'AUTO' );
define( 'JETPACK__VERSION', '2.3.3' );
define( 'JETPACK__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'JETPACK__GLOTPRESS_LOCALES_PATH' ) or define( 'JETPACK__GLOTPRESS_LOCALES_PATH', JETPACK__PLUGIN_DIR . 'locales.php' );

define( 'JETPACK_MASTER_USER', true );

// Constants for expressing human-readable intervals
// in their respective number of seconds.
// Introduced in WordPress 3.5, specified here for backward compatability.
defined( 'MINUTE_IN_SECONDS' ) or define( 'MINUTE_IN_SECONDS', 60 );
defined( 'HOUR_IN_SECONDS' )   or define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
defined( 'DAY_IN_SECONDS' )    or define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
defined( 'WEEK_IN_SECONDS' )   or define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
defined( 'YEAR_IN_SECONDS' )   or define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );
>>>>>>> origin/johndcoy

// @todo: Abstract out the admin functions, and only include them if is_admin()
// @todo: Only include things like class.jetpack-sync.php if we're connected.
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack.php'               );
<<<<<<< HEAD
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-network.php'       );
=======
>>>>>>> origin/johndcoy
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-client.php'        );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-data.php'          );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-client-server.php' );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-sync.php'          );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-options.php'       );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-user-agent.php'    );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-post-images.php'   );
<<<<<<< HEAD
require_once( JETPACK__PLUGIN_DIR . 'class.media-extractor.php'       );
require_once( JETPACK__PLUGIN_DIR . 'class.media-summary.php'         );
=======
>>>>>>> origin/johndcoy
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-error.php'         );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-debugger.php'      );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-heartbeat.php'     );
require_once( JETPACK__PLUGIN_DIR . 'class.photon.php'                );
require_once( JETPACK__PLUGIN_DIR . 'functions.photon.php'            );
require_once( JETPACK__PLUGIN_DIR . 'functions.compat.php'            );
require_once( JETPACK__PLUGIN_DIR . 'functions.gallery.php'           );
<<<<<<< HEAD
require_once( JETPACK__PLUGIN_DIR . 'require-lib.php'                 );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-autoupdate.php'    );
require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-tracks.php'        );

if ( is_admin() ) {
	require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-admin.php'     );
	require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-jitm.php'      );
}

// Play nice with http://wp-cli.org/
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once( JETPACK__PLUGIN_DIR . 'class.jetpack-cli.php'       );
}

register_activation_hook( __FILE__, array( 'Jetpack', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Jetpack', 'plugin_deactivation' ) );
add_action( 'updating_jetpack_version', array( 'Jetpack', 'do_version_bump' ), 10, 2 );
add_action( 'init', array( 'Jetpack', 'init' ) );
add_action( 'plugins_loaded', array( 'Jetpack', 'load_modules' ), 100 );
add_filter( 'jetpack_static_url', array( 'Jetpack', 'staticize_subdomain' ) );
add_filter( 'is_jetpack_site', '__return_true' );

/**
 * Add an easy way to photon-ize a URL that is safe to call even if Jetpack isn't active.
 *
 * See: http://jetpack.me/2013/07/11/photon-and-themes/
 */
if ( Jetpack::is_module_active( 'photon' ) ) {
	add_filter( 'jetpack_photon_url', 'jetpack_photon_url', 10, 3 );
}
=======

register_activation_hook( __FILE__, array( 'Jetpack', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Jetpack', 'plugin_deactivation' ) );

add_action( 'init', array( 'Jetpack', 'init' ) );
add_action( 'init', array( 'Jetpack_Heartbeat', 'init' ), 100 );
add_action( 'plugins_loaded', array( 'Jetpack', 'load_modules' ), 100 );
add_filter( 'jetpack_static_url', array( 'Jetpack', 'staticize_subdomain' ) );
>>>>>>> origin/johndcoy

/*
if ( is_admin() && ! Jetpack::check_identity_crisis() ) {
	Jetpack_Sync::sync_options( __FILE__, 'db_version', 'jetpack_active_modules', 'active_plugins' );
}
*/

<<<<<<< HEAD
require_once( JETPACK__PLUGIN_DIR . '3rd-party/3rd-party.php' );
=======
Jetpack_Sync::sync_options( __FILE__, 'widget_twitter' );
>>>>>>> origin/johndcoy
