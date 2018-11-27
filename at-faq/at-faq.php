<?php
declare(strict_types=1);
namespace AT_Faq;
/*
Plugin Name: AT FAQ
Plugin URI: 
Description: Declares a plugin that will create a custom post type "faq"
Version: 1.0
Author: Alex Tim
Author URI: 
License: GPLv2
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function AT_Faq() {
	return AT_Faq::get_instance();
}
AT_Faq();	


final class AT_Faq {
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
		define( 'AT_FAQ_POST_TYPE', 'faq' );
		define( 'AT_FAQ_PLUGIN_FILE', __FILE__ );
		define( 'AT_FAQ_PLUGIN_DIR', untrailingslashit( dirname( AT_FAQ_PLUGIN_FILE ) ) );	
		define( 'AT_FAQ_ABSPATH', dirname( __FILE__ ) . '/' );
	}
	
	
	private function includes() {
		require AT_FAQ_ABSPATH . '/inc/class-post-types.php';
		require AT_FAQ_ABSPATH . '/inc/class-taxonomies.php';

		require AT_FAQ_ABSPATH . '/inc/class-faq-data.php';
		require AT_FAQ_ABSPATH . '/inc/class-shortcode.php';
		
		if ( is_admin() ) {
			require AT_FAQ_ABSPATH . '/inc/admin/admin-helper.php';
			require AT_FAQ_ABSPATH . '/inc/admin/class-admin-metaboxes.php';
		}
	}
	
	
	private function init_hooks() {
		
		register_activation_hook( __FILE__, function () {
			PostTypes::init();
			Taxonomies::init();	
			
			flush_rewrite_rules();
		});
		
		add_action( 'init', [ '\AT_Faq\PostTypes', 'init' ] );
		add_action( 'init', [ '\AT_Faq\Taxonomies', 'init' ] );	
		if ( is_admin() ) {
			add_action( 'pre_get_posts', [&$this, 'modify_cpt_query'] );
			$mb = new AdminMetaboxes();
		} 
		
	

		add_action('wp_enqueue_scripts', function () {
			wp_register_style( 'myCSS', plugins_url( '/css/at-faq.css', __FILE__ ) );
			wp_enqueue_style( 'myCSS' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery_scrollTo', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js', false );
			wp_enqueue_script( 'jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', false );
			wp_enqueue_script( 'myJS', plugins_url( '/js/at-faq.js', __FILE__ ), false );
		});
	}
	
	// Custom Post types: faq
	// Поддержка сортировки  в admin панели
	function modify_cpt_query( $query ) {
		global $wp_the_query;
		if ( $query === $wp_the_query && count($query->query) > 0 ) {
			if ( isset($query->query['post_type']) ) {
				if ( AT_FAQ_POST_TYPE === $query->query['post_type'] ) {
					if ( is_admin() ) {
						if( 'faq_sort_order' === $query->get( 'orderby') ) {
							$query->set( 'meta_key','faq_sort_order' );
							$query->set( 'orderby','meta_value_num' );								
						}
					} 
				}
			} 
		}
		return $query;
	}
}