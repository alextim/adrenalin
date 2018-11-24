<?php
namespace AT_Trip;

final class PostTypes {
	
	public static function init() {
		self::register_trip();
	}
	
	
	private static function register_trip() {
		//$permalink = at_trip_get_permalink_structure();
		$labels = [
			'name'               => 'Мероприятия',
			'singular_name'      => 'Мероприятие', 
			'menu_name'          => 'Мероприятия',
			'name_admin_bar'     => 'Мероприятие', 
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Trip',
			'new_item'           => 'New Trip',
			'edit_item'          => 'Edit Trip',
			'view_item'          => 'View Trip',
			'all_items'          => 'All Trips',
			'search_items'       => 'Search Trips',
			'parent_item_colon'  => 'Parent Trips:',
			'not_found'          => 'No Trips found.',
			'not_found_in_trash' => 'No Trips found in Trash.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Description.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'trips', 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'excerpt', 'thumbnail' ),
			'menu_icon'          => 'dashicons-location',
		];
		
		register_post_type( AT_TRIP_POST_TYPE, $args );
	}

}