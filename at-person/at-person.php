<?php
/*
Plugin Name: AT Person
Plugin URI: 
Description: Declares a plugin that will create a custom post type "person"
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function AT_Person() {
	return AT_Person::get_instance();
}
AT_Person();	


final class AT_Person {
	private static $instance;
	
	public static function get_instance() {
        if ( null == self::$instance ) {
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
		define( 'AT_PERSON_POST_TYPE', 'person' );
		
		define( 'AT_PERSON_ITEMS_PER_ROW', 4 );
		define( 'AT_PERSON_ITEMS_PER_PAGE', AT_PERSON_ITEMS_PER_ROW * 2 );	
		
		define( 'AT_PERSON_PLUGIN_FILE', __FILE__ );
		define( 'AT_PERSON_PLUGIN_DIR', untrailingslashit( dirname( AT_PERSON_PLUGIN_FILE ) ) );	
		define( 'AT_PERSON_ABSPATH', dirname( __FILE__ ) . '/' );
	}
	
	private function includes() {
		require AT_PERSON_ABSPATH . '/inc/class-post-types.php';
		require AT_PERSON_ABSPATH . '/inc/class-taxonomies.php';

		require AT_PERSON_ABSPATH . '/inc/class-person.php';
		
		if ( is_admin() ) {
			require AT_PERSON_ABSPATH . '/inc/admin/admin-helper.php';
			require AT_PERSON_ABSPATH . '/inc/admin/class-admin-metaboxes.php';
		}
	}
	
	private function init_hooks() {
		
		register_activation_hook( __FILE__, function () {
			$posts = new AT_Person_Post_Types();
			$posts::init();
			
			$taxonomy = new AT_Person_Taxonomies();
			$taxonomy::init();
			
			flush_rewrite_rules();
		});
		
		add_action( 'init', [ 'AT_Person_Post_Types', 'init' ] );
		add_action( 'init', [ 'AT_Person_Taxonomies', 'init' ] );
		
		add_action( 'pre_get_posts', [&$this, 'modify_cpt_query'] );
		
		if ( is_admin() ) {
			$mb = new AT_Person_Admin_Metaboxes();
		} 
	}
	
	// Custom Post types: person
	// Поддержка сортировки  в admin панели
	// Подстройка количества постов на странице в архивах	
	//if ( isset( $wp_query->query_vars['post_type'] ) && ( ( is_string( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== '' ) || ( is_array( $wp_query->query_vars['post_type'] ) && $wp_query->query_vars['post_type'] !== array() ) ) ) {
	//	$post_type = $wp_query->query_vars['post_type'];
	function modify_cpt_query( $query ) {
		global $wp_the_query;
		if ( $query === $wp_the_query && count($query->query) > 0 ) {
			
			if ( isset($query->query['post_type']) ) {
				if ( AT_PERSON_POST_TYPE === $query->query['post_type'] ) {
					if ( is_admin() ) {
						if( 'person_sort_order' === $query->get( 'orderby') ) {
							$query->set( 'meta_key','person_sort_order' );
							$query->set( 'orderby','meta_value_num' );								
						}
					} else {
						$query->set( 'posts_per_page', AT_PERSON_ITEMS_PER_PAGE );
						$query->set( 'meta_key','person_sort_order' );
						$query->set( 'orderby','meta_value_num' );						
						$query->set( 'order', 'DESC' );
					}
					//add_filter('posts_orderby', function($orderby) { return '(wp_posts.person_sort_order+0) DESC'; } );
				}
			} elseif ( $query->is_tax ) {
				if ( isset($query->query['person_type']) ) {
					$query->set( 'posts_per_page', AT_PERSON_ITEMS_PER_PAGE );
					$query->set( 'meta_key','person_sort_order' );
					$query->set( 'orderby','meta_value_num' );						
					$query->set( 'order', 'DESC' );
				}
			}			
		}
		return $query;
	}	
}