<?php
declare(strict_types=1);
namespace AT_Trip;

/*
Plugin Name: AT Trip Dates
Plugin URI: 
Description: Dates for Trip
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $at_trip_db_version;
$at_trip_db_version = '1.0.0';

TripDates::get_instance();

final class TripDates {
	private static $instance = null;
	
	public static function get_instance() {
        if ( null === self::$instance ) {
			self::$instance = new self;
        }
        return self::$instance;
    } 	
	
	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();	
	}
	
	private function define_constants() {
		
		define( 'AT_TRIP_DATES_PLUGIN_NAME',  'AT_TripDates' );
		define( 'AT_TRIP_DATES_DB_VERSION',   'at_trip_db_version' );
		define( 'AT_TRIP_DATES_DATES_TABLE',  'at_trip_dates' );		
		
		define( 'AT_TRIP_DATES_PLUGIN_FILE', __FILE__ );
		define( 'AT_TRIP_DATES_PLUGIN_DIR', untrailingslashit( dirname( AT_TRIP_DATES_PLUGIN_FILE ) ) );
		define( 'AT_TRIP_DATES_ABSPATH', dirname( __FILE__ ) . '/' );
		
		define( 'AT_TRIP_DATES_OPTIONS_NAME', 'at_trip_options');

	}
	
	private function includes() {
		//require AT_TRIP_DATES_ABSPATH . '/inc/class-db.php';
		
		if ( is_admin() ) {

		}
	}
	

	function my_custom_meta_get( $check, $post_id, $meta_key ) {
		if ( $meta_key != 'trip_dates' ) {
			return $check;
		}

		if ( get_post_type($post_id) == AT_TRIP_POST_TYPE )  {
			\AT_Lib\writeLog($post_id);
			return $check;
		}
		
		return self::getDates($post_id);
	}
	
	static function getDates($post_id) : array {
		global $wpdb;
		$sql = 'SELECT date FROM ' . $wpdb->prefix . AT_TRIP_DATES_DATES_TABLE . ' WHERE post_id = %d';
		$result = $wpdb->get_results( sprintf($sql, $post_id), ARRAY_N );
		
		if (empty($result)) {
			return [];
		}

		$a = [];
		$i = 0;
		foreach ($result as $item) {
			$val = $item[0];
			$val = absint($val);

			if ( $val > 0 ) {
				$a[] = ['startdate' => $val];
			}
		}

		return $a;
	}
	
	
	
	function my_custom_meta_update( $check, $post_id, $meta_key, $meta_value ) {
		if ( get_post_type( $post_id) == AT_TRIP_POST_TYPE && $meta_key == 'trip_dates' ) {

			global $wpdb;
			$i = 0;
			$n = 0;
			$deleted = 0;
			try {
				$deleted = $wpdb->delete($wpdb->prefix . AT_TRIP_DATES_DATES_TABLE, ['post_id' => $post_id], ['%d']);
	
				if (!empty($meta_value)) :
					$n = count($meta_value);
					foreach($meta_value as $item) :
					
						foreach($item as $key=>$value) :
							if ( (int)($item['startdate']) > 0) {
								$ok = $wpdb->insert($wpdb->prefix . AT_TRIP_DATES_DATES_TABLE,
									[ 
										'post_id' => $post_id,
										'date' => (int)($item['startdate']), 
									],
									[ 
										'%d',
										'%d',
									]
								);
								if ($ok) {
									$i++;
								}
							} else {
								$n--;
							}
							break;
						endforeach;
						
					endforeach;
				endif;
			} catch(Exception $e) {
				\AT_Lib\writeLog($e->getMessage());	
			}
			finally {
				return $i > 0;
			}
			
		} else {
			return $check;
		}
	}	


	function my_custom_meta_delete( $check, $post_id, $meta_key ) {
		if ( get_post_type( $post_id) == AT_TRIP_POST_TYPE && $meta_key == 'trip_dates' ) {			
			global $wpdb;
			$deleted = $wpdb->delete($wpdb->prefix . AT_TRIP_DATES_DATES_TABLE, ['post_id' => $post_id], ['%d']);
			return true;
		} else {
			return $check;
		}
	}
	
	
	private function init_hooks() {
		register_activation_hook( __FILE__, [&$this, 'install']);
		register_uninstall_hook( __FILE__, [AT_TRIP_DATES_PLUGIN_NAME, 'uninstall'] );
		
		add_action( 'plugins_loaded', [&$this, 'update_db_check'] );
		
		
		if ( is_admin() ) {
			add_action( 'admin_init', function() {
				add_filter( 'update_post_metadata', [&$this, 'my_custom_meta_update'], 0, 4 );
				add_filter( 'add_post_metadata',    [&$this, 'my_custom_meta_update'], 0, 4 );
				add_filter( 'get_post_metadata',    [&$this, 'my_custom_meta_get'],    0, 3 );
				add_filter( 'delete_post_metadata', [&$this, 'my_custom_meta_delete'], 0, 3 );
			});
		}

	}
	
	
	function update_db_check() {
		global $at_trip_db_version;
		if ( get_site_option( AT_TRIP_DATES_DB_VERSION ) != $at_trip_db_version ) {
			$this->install();
		}
	}	
	
	
	function install() {
		global $at_trip_db_version;

		$installed_ver = get_option( AT_TRIP_DATES_DB_VERSION );

		if ( $installed_ver != $at_trip_db_version ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();

			
			$sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . AT_TRIP_DATES_DATES_TABLE . ' (
						id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
						date        INT UNSIGNED NOT NULL,
						post_id BIGINT(20) UNSIGNED NOT NULL,
						PRIMARY KEY (id),
						INDEX (post_id, date), 
						INDEX (date, post_id), 
						FOREIGN KEY (post_id)
							REFERENCES ' . $wpdb->prefix . "posts(ID)
							ON DELETE CASCADE
			) ENGINE=InnoDB $charset_collate;";
			dbDelta( $sql );
			
			update_option( AT_TRIP_DATES_DB_VERSION, $at_trip_db_version );
		}		
	}
	
	
	static function uninstall() {
		global $wpdb;

		$wpdb->query( sprintf('DROP TABLE IF EXISTS %s', $wpdb->prefix . AT_TRIP_DATES_DATES_TABLE ) );

		delete_option( AT_TRIP_DATES_DB_VERSION );
		delete_option( AT_TRIP_DATES_OPTIONS_NAME );
	}
}