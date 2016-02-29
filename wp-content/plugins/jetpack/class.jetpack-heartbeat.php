<?php

class Jetpack_Heartbeat {

	/**
<<<<<<< HEAD
	 * Holds the singleton instance of this class
	 *
	 * @since 2.3.3
	 * @var Jetpack_Heartbeat
	 */
	private static $instance = false;

	private $cron_name = 'jetpack_v2_heartbeat';

	/**
	 * Singleton
	 *
=======
	 * Jetpack object
	 * 
	 * @since 2.3.3
	 * @var Jetpack 
	 */
	var $jetpack = null;

	/**
	 * Holds the singleton instance of this class
	 * 
	 * @since 2.3.3
	 * @var Jetpack_Heartbeat 
	 */
	static $instance = false;

	private $cron_name = 'jetpack_heartbeat';

	/**
	 * Singleton
	 * 
>>>>>>> origin/johndcoy
	 * @since 2.3.3
	 * @static
	 * @return Jetpack_Heartbeat
	 */
	public static function init() {
		if ( ! self::$instance ) {
			self::$instance = new Jetpack_Heartbeat;
		}

		return self::$instance;
	}

	/**
	 * Constructor for singleton
<<<<<<< HEAD
	 *
	 * @since 2.3.3
	 * @return Jetpack_Heartbeat
	 */
	private function __construct() {
		if ( ! Jetpack::is_active() )
			return;
=======
	 * 
	 * @since 2.3.3
	 * @return Jetpack_Heartbeat 
	 */
	private function __construct() {
		$this->jetpack = Jetpack::init();

		// Add weekly interval for wp-cron
		add_filter('cron_schedules', array( $this, 'add_cron_intervals' ) );
>>>>>>> origin/johndcoy

		// Schedule the task
		add_action( $this->cron_name, array( $this, 'cron_exec' ) );

<<<<<<< HEAD
		if ( ! wp_next_scheduled( $this->cron_name ) ) {
			// Deal with the old pre-3.0 weekly one.
			if ( $timestamp = wp_next_scheduled( 'jetpack_heartbeat' ) ) {
				wp_unschedule_event( $timestamp, 'jetpack_heartbeat' );
			}

			wp_schedule_event( time(), 'daily', $this->cron_name );
		}

		add_filter( 'jetpack_xmlrpc_methods', array( __CLASS__, 'jetpack_xmlrpc_methods' ) );
	}

	/**
	 * Method that gets executed on the wp-cron call
	 *
	 * @since 2.3.3
	 * @global string $wp_version
	 */
	public function cron_exec() {

		$jetpack = Jetpack::init();

		/*
		 * This should run daily.  Figuring in for variances in
		 * WP_CRON, don't let it run more than every 23 hours at most.
		 *
		 * i.e. if it ran less than 23 hours ago, fail out.
		 */
		$last = (int) Jetpack_Options::get_option( 'last_heartbeat' );
		if ( $last && ( $last + DAY_IN_SECONDS - HOUR_IN_SECONDS > time() ) ) {
			return;
		}

		/*
		 * Check for an identity crisis
		 *
		 * If one exists:
		 * - Bump stat for ID crisis
		 * - Email site admin about potential ID crisis
		 */

		// Coming Soon!

		foreach ( self::generate_stats_array( 'v2-' ) as $key => $value ) {
			$jetpack->stat( $key, $value );
		}

		Jetpack_Options::update_option( 'last_heartbeat', time() );

		$jetpack->do_stats( 'server_side' );

		/**
		 * Fires when we synchronize all registered options on heartbeat.
		 *
		 * @since 3.3.0
		 */
		do_action( 'jetpack_heartbeat' );
	}

	public static function generate_stats_array( $prefix = '' ) {
		$return = array();

		$return["{$prefix}version"]        = JETPACK__VERSION;
		$return["{$prefix}wp-version"]     = get_bloginfo( 'version' );
		$return["{$prefix}php-version"]    = PHP_VERSION;
		$return["{$prefix}branch"]         = floatval( JETPACK__VERSION );
		$return["{$prefix}wp-branch"]      = floatval( get_bloginfo( 'version' ) );
		$return["{$prefix}php-branch"]     = floatval( PHP_VERSION );
		$return["{$prefix}public"]         = Jetpack_Options::get_option( 'public' );
		$return["{$prefix}ssl"]            = Jetpack::permit_ssl();
		$return["{$prefix}is-https"]       = is_ssl() ? 'https' : 'http';
		$return["{$prefix}language"]       = get_bloginfo( 'language' );
		$return["{$prefix}charset"]        = get_bloginfo( 'charset' );
		$return["{$prefix}is-multisite"]   = is_multisite() ? 'multisite' : 'singlesite';
		$return["{$prefix}identitycrisis"] = Jetpack::check_identity_crisis( 1 ) ? 'yes' : 'no';
		$return["{$prefix}plugins"]        = implode( ',', Jetpack::get_active_plugins() );

		$return["{$prefix}single-user-site"]= Jetpack::is_single_user_site();

		$return["{$prefix}manage-enabled"] = Jetpack::is_module_active( 'manage' );

		// is-multi-network can have three values, `single-site`, `single-network`, and `multi-network`
		$return["{$prefix}is-multi-network"] = 'single-site';
		if ( is_multisite() ) {
			$return["{$prefix}is-multi-network"] = Jetpack::is_multi_network() ? 'multi-network' : 'single-network';
		}

		if ( ! empty( $_SERVER['SERVER_ADDR'] ) || ! empty( $_SERVER['LOCAL_ADDR'] ) ) {
			$ip     = ! empty( $_SERVER['SERVER_ADDR'] ) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
			$ip_arr = array_map( 'intval', explode( '.', $ip ) );
			if ( 4 == count( $ip_arr ) ) {
				$return["{$prefix}ip-2-octets"] = implode( '.', array_slice( $ip_arr, 0, 2 ) );
			}
		}

		foreach ( Jetpack::get_available_modules() as $slug ) {
			$return["{$prefix}module-{$slug}"] = Jetpack::is_module_active( $slug ) ? 'on' : 'off';
		}

		return $return;
	}

	public static function jetpack_xmlrpc_methods( $methods ) {
		$methods['jetpack.getHeartbeatData'] = array( __CLASS__, 'generate_stats_array' );
		return $methods;
	}

	public function deactivate() {
		// Deal with the old pre-3.0 weekly one.
		if ( $timestamp = wp_next_scheduled( 'jetpack_heartbeat' ) ) {
			wp_unschedule_event( $timestamp, 'jetpack_heartbeat' );
		}

		$timestamp = wp_next_scheduled( $this->cron_name );
		wp_unschedule_event( $timestamp, $this->cron_name );
	}

}
=======
		if (!wp_next_scheduled( $this->cron_name ) ) {
			wp_schedule_event( time(), 'jetpack_weekly', $this->cron_name );
		}
	}
	
	/**
	 * Method that gets executed on the wp-cron call
	 * 
	 * @since 2.3.3
	 * @global string $wp_version 
	 */
	public function cron_exec() {

		/*
		 * Check for an identity crisis
		 * 
		 * If one exists:
		 * - Bump stat for ID crisis
		 * - Email site admin about potential ID crisis
		 */ 



		/**
		 * Setup an array of items that will eventually be stringified
		 * and sent off to the Jetpack API 
		 * 
		 * Associative array with format group => values
		 * - values should be an array that will be imploded to a string
		 */

		$jetpack = $this->jetpack;

		$jetpack->stat( 'active-modules', implode( ',', $this->jetpack->get_active_modules() ) );
		$jetpack->stat( 'active',         JETPACK__VERSION                                     );
		$jetpack->stat( 'wp-version',     get_bloginfo( 'version' )                            );
		$jetpack->stat( 'php-version',    PHP_VERSION                                          );
		$jetpack->stat( 'ssl',            $jetpack->permit_ssl()                               );
		$jetpack->stat( 'language',       get_bloginfo( 'language' )                           );
		$jetpack->stat( 'charset',        get_bloginfo( 'charset' )                            );
		$jetpack->stat( 'qty-posts',      wp_count_posts()->publish                            );
		$jetpack->stat( 'qty-pages',      wp_count_posts( 'page' )->publish                    );
		$jetpack->stat( 'qty-comments',   wp_count_comments()->approved                        );
		$jetpack->stat( 'is-multisite',   is_multisite() ? 'multisite' : 'singlesite'          );

		// Only check a few plugins, to see if they're currently active.
		$plugins_to_check = array(
			'vaultpress/vaultpress.php',
			'akismet/akismet.php',
			'wp-super-cache/wp-cache.php',
		);
		$plugins = array_intersect( $plugins_to_check, get_option( 'active_plugins', array() ) );
		foreach( $plugins as $plugin ) {
			$jetpack->stat( 'plugins', $plugin );
		}

		$jetpack->do_stats( 'server_side' );
	}

	/**
	 * Adds additional Jetpack specific intervals to wp-cron
	 * 
	 * @since 2.3.3
	 * @return array 
	 */
	public function add_cron_intervals( $schedules ) {
		$schedules['jetpack_weekly'] = array(
		    'interval' => WEEK_IN_SECONDS,
		    'display' => __('Jetpack weekly')
		);
		return $schedules;
	}

	public function deactivate() {
		$timestamp = wp_next_scheduled( $this->cron_name );
		wp_unschedule_event($timestamp, $this->cron_name );
	}

}// end class
>>>>>>> origin/johndcoy
