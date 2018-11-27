<?php
declare(strict_types=1);
namespace AT_Gear;

/*
Plugin Name: AT Gear
Plugin URI: 
Description: custom post type: Gear
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function AT_Gear() {
	return AT_Gear::get_instance();
}
AT_Gear();


final class AT_Gear {
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
		define( 'AT_GEAR_POST_TYPE', 'gear' );
		
		define( 'AT_GEAR_PLUGIN_FILE', __FILE__ );
		define( 'AT_GEAR_PLUGIN_DIR', untrailingslashit( dirname( AT_GEAR_PLUGIN_FILE ) ) );
		define( 'AT_GEAR_ABSPATH', dirname( __FILE__ ) . '/' );
	}
	
	
	private function includes() {
		require AT_GEAR_ABSPATH . '/inc/class-post-types.php';
		require AT_GEAR_ABSPATH . '/inc/class-taxonomies.php';
		
		//require AT_GEAR_ABSPATH . '/inc/class-data-helper-base.php';		
		require AT_GEAR_ABSPATH . '/inc/class-gear-data.php';
		require AT_GEAR_ABSPATH . '/inc/class-taxonomy-hierarchy-base.php';
		
		require AT_GEAR_ABSPATH . '/inc/class-gear-view.php';
		require AT_GEAR_ABSPATH . '/inc/class-shortcode.php';
		
		if ( is_admin() ) {
			//add_action( 'pre_get_terms', [&$this, 'modify_ctx_query'] );
			require AT_GEAR_ABSPATH . '/inc/admin/class-gear-type-admin-metaboxes.php';
			require AT_GEAR_ABSPATH . '/inc/admin/class-admin-metaboxes.php';
		}
	}
	
	
	private function init_hooks() {
		register_activation_hook( __FILE__, function () {
			//$posts = new PostTypes();
			PostTypes::init();
			
			//$taxonomy = new Taxonomies();
			Taxonomies::init();
			
			flush_rewrite_rules();
		});
		
		add_action( 'init', [ '\AT_Gear\PostTypes', 'init' ] );
		add_action( 'init', [ '\AT_Gear\Taxonomies', 'init' ] );
		
		if ( is_admin() ) {
			$mb = new AdminMetaboxes();
		}
	}
	
	
	//function modify_ctx_query( $query ) {
	//}
}