<?php
declare(strict_types=1);

final class AT_Person_Post_Types {

	public function __construct() {
	}

	public static function init() {
		self::register_person();
	}
	
	private static function register_person() {
		$labels = [
			'name' => 'Наша команда',
			'singular_name' => 'Член команды',
			'menu_name'          => 'Наша команда',
			'name_admin_bar'     => 'Член команды', 			
			'add_new' => 'Add New',
			'add_new_item' => 'Add New Person',
			'edit' => 'Edit',
			'edit_item' => 'Edit Person',
			'new_item' => 'New Person',
			'view' => 'View',
			'view_item' => 'View Person',
			'search_items' => 'Search Persons',
			'not_found' => 'No person found',
			'not_found_in_trash' =>
			'No person found in Trash',
			'parent' => 'Parent Person'
		];
	
	
		$args = [
			'labels'             => $labels,
	        //'description'        => __( 'Description.', 'wp-travel-engine' ),
			'public'             => true,
			'menu_icon' 		 => 'dashicons-businessman',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite' 			 => array( 'slug' => 'persons', 'with_front' => false ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null, // 14
			'supports'			 => array( 'title', 'editor', 'thumbnail',  ),	
			//'taxonomies' => array( '' ),			
		];
		
		register_post_type( AT_PERSON_POST_TYPE, $args );
	}
}