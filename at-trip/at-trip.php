<?php
declare(strict_types=1);
namespace AT_Trip;

/*
Plugin Name: AT Trip
Plugin URI: 
Description: custom post type: Trip
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function AT_Trip() {
	return AT_Trip::get_instance();
}
AT_Trip();

final class AT_Trip {
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
		define ('AT_TRIP_USE_GOOGLE_FORMS', true);
		
		define( 'AT_TRIP_PLUGIN_NAME',  'AT_Trip' );
		
		define( 'AT_TRIP_POST_TYPE', 'trip' );
		
		define( 'AT_TRIP_ITEMS_PER_ROW', 3 );
		define( 'AT_TRIP_ITEMS_PER_PAGE', AT_TRIP_ITEMS_PER_ROW * 2 );
		
		define( 'AT_TRIP_PLUGIN_FILE', __FILE__ );
		define( 'AT_TRIP_PLUGIN_DIR', untrailingslashit( dirname( AT_TRIP_PLUGIN_FILE ) ) );
		define( 'AT_TRIP_ABSPATH', dirname( __FILE__ ) . '/' );
	}
	
	private function includes() {
		require AT_TRIP_ABSPATH . '/inc/class-post-types.php';
		require AT_TRIP_ABSPATH . '/inc/class-taxonomies.php';
		
		require AT_TRIP_ABSPATH . '/inc/fields/class-field.php';
		require AT_TRIP_ABSPATH . '/inc/fields/class-repeater-field-base.php';
		require AT_TRIP_ABSPATH . '/inc/fields/class-date-range-field.php';
		require AT_TRIP_ABSPATH . '/inc/fields/class-outline-days-field.php';
		require AT_TRIP_ABSPATH . '/inc/fields/class-price-list-field.php';
		require AT_TRIP_ABSPATH . '/inc/fields/class-related-trips-field.php';		
		
		require AT_TRIP_ABSPATH . '/inc/search/class-search-form.php';		
		
		require AT_TRIP_ABSPATH . '/inc/class-tab.php';
		require AT_TRIP_ABSPATH . '/inc/class-trip-data.php';
		require AT_TRIP_ABSPATH . '/inc/class-trip-view.php';
		
		//include AT_TRIP_ABSPATH . '/inc/class-frontend-assets.php';
		
		if ( is_admin() ) {
			require AT_TRIP_ABSPATH . '/inc/admin/admin-helper.php';
			require AT_TRIP_ABSPATH . '/inc/admin/class-admin-metaboxes.php';
			require AT_TRIP_ABSPATH . '/inc/admin/class-season-admin-metaboxes.php';
			require AT_TRIP_ABSPATH . '/inc/admin/class-admin-assets.php';
		}
	}
	
	private function init_hooks() {
		add_action( 'pre_get_posts', [&$this, 'modify_cpt_query'] );	
		
		register_activation_hook( __FILE__, function () {
			//$posts = new PostTypes();
			PostTypes::init();
			
			//$taxonomy = new Taxonomies();
			Taxonomies::init();
			
			flush_rewrite_rules();
		});
		
		add_action( 'init', [ '\AT_Trip\PostTypes',  'init' ] );
		add_action( 'init', [ '\AT_Trip\Taxonomies', 'init' ] );
		
		if ( is_admin() ) {
			$mb = new AdminMetaboxes();
		}
	}
	
		// Custom Post types: trip
	// Поддержка сортировки  в admin панели
	// Подстройка количества постов на странице в архивах	
	//if ( isset( $wp_query->query_vars['post_type'] ) && ( ( is_string( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== '' ) || ( is_array( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== array() ) ) ) {
	//	$post_type = $wp_query->query_vars['post_type'];
	public function modify_cpt_query( $query ) {
		global $wp_the_query;
		if ( $query === $wp_the_query && count($query->query) > 0 ) {
			
			if ( isset($query->query['post_type']) ) {
				if ( AT_TRIP_POST_TYPE === $query->query['post_type'] ) {
					if ( is_admin() ) {
						if( 'trip_price' === $query->get( 'orderby') ) {
							$query->set( 'meta_key', 'trip_price' );
							$query->set( 'orderby', 'meta_value_num' );								
						} elseif( 'trip_sticky' === $query->get( 'orderby') ) {
							$query->set( 'meta_key', 'trip_sticky' );
							$query->set( 'orderby', 'meta_value_num' );								
						}						
					} else {
						$query->set( 'posts_per_page', AT_TRIP_ITEMS_PER_PAGE );
					}

				}
			} elseif ( $query->is_tax ) {
				if ( isset($query->query['activity']) ) {
					$query->set( 'posts_per_page', AT_TRIP_ITEMS_PER_PAGE );					
				}
			}			
		}
		return $query;
	}
}