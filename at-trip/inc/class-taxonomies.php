<?php
namespace AT_Trip;

final class Taxonomies {

	public static function init() {
		self::register_activity_taxonomy();
		self::register_destination_taxonomy();
		self::register_trip_type_taxonomy();
		self::register_keyword_taxonomy();
	}
	
	
	private static function register_activity_taxonomy() {
		$labels = [
			'name'              => 'Активности',
			'singular_name'     => 'Активность',
			'search_items'      => __( 'Search Activities', 'at-trip' ),
			'all_items'         => __( 'All Activities', 'at-trip' ),
			'parent_item'       => __( 'Parent Activity', 'at-trip' ),
			'parent_item_colon' => __( 'Parent Activity:', 'at-trip' ),
			'edit_item'         => __( 'Edit Activity', 'at-trip' ),
			'update_item'       => __( 'Update Activity', 'at-trip' ),
			'add_new_item'      => __( 'Add New Activity', 'at-trip' ),
			'new_item_name'     => __( 'New Activity', 'at-trip' ),
			'menu_name'         => __( 'Activities', 'at-trip' ),
		];

		$args = [
			'public'			=> true,
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			//'rewrite'           => ['slug' => 'activity', 'with_front' => false, 'ep_mask' => EP_CATEGORIES ],
		];

		register_taxonomy( 'activity', [ AT_TRIP_POST_TYPE ], $args );
	}

	
	private static function register_destination_taxonomy() {
		$labels = [
			'name'              => 'Направления',
			'singular_name'     => 'Направление',
			'search_items'      => __( 'Search Destinations', 'at-trip' ),
			'all_items'         => __( 'All Destinations', 'at-trip' ),
			'parent_item'       => __( 'Parent Destination', 'at-trip' ),
			'parent_item_colon' => __( 'Parent Destination:', 'at-trip' ),
			'edit_item'         => __( 'Edit Destination', 'at-trip' ),
			'update_item'       => __( 'Update Destination', 'at-trip' ),
			'add_new_item'      => __( 'Add New Destination', 'at-trip' ),
			'new_item_name'     => __( 'New Destination', 'at-trip' ),
			'menu_name'         => __( 'Destinations', 'at-trip' ),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			//'rewrite'           => [ 'slug' => ? ],
		];

		register_taxonomy( 'destination', [ AT_TRIP_POST_TYPE ], $args );
	}	
	
	
	private static function register_trip_type_taxonomy() {
		$labels = [
			'name'              => 'Типы мероприятий',
			'singular_name'     => 'Тип мероприятия',
			'search_items'      => __( 'Search Trip Types', 'at-trip' ),
			'all_items'         => __( 'All Trip Types', 'at-trip' ),
			'parent_item'       => __( 'Parent Trip Type', 'at-trip' ),
			'parent_item_colon' => __( 'Parent Trip Type:', 'at-trip' ),
			'edit_item'         => __( 'Edit Trip Type', 'at-trip' ),
			'update_item'       => __( 'Update Trip Type', 'at-trip' ),
			'add_new_item'      => __( 'Add New Trip Type', 'at-trip' ),
			'new_item_name'     => __( 'New Tour Trip Name', 'at-trip' ),
			'menu_name'         => __( 'Trip Types', 'at-trip' ),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'trip-type' ],
		];

		register_taxonomy( 'trip_type', [ AT_TRIP_POST_TYPE ], $args );
	}	


	
	private static function register_keyword_taxonomy() {
		
		$labels = [
			'name'              => 'Ключевые слова',
			'singular_name'     => 'Ключевое слово',
			'search_items'      => __( 'Search Keywords', 'at-trip' ),
			'all_items'         => __( 'All Keywords', 'at-trip' ),
			'parent_item'       => __( 'Parent Keyword', 'at-trip' ),
			'parent_item_colon' => __( 'Parent Keyword:', 'at-trip' ),
			'edit_item'         => __( 'Edit Keyword', 'at-trip' ),
			'update_item'       => __( 'Update Keyword', 'at-trip' ),
			'add_new_item'      => __( 'Add New Keyword', 'at-trip' ),
			'new_item_name'     => __( 'New Keyword', 'at-trip' ),
			'menu_name'         => __( 'Keywords', 'at-trip' ),
		];

		$args = [
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'trip-keyword' ],
		];

		register_taxonomy( 'trip_keyword', [ AT_TRIP_POST_TYPE ], $args );
	}
}