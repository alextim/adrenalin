<?php
declare(strict_types=1);
namespace AT_Gear;

final class PostTypes {
	
	public static function init() {
		self::register_gear();
	}
	
	
	private static function register_gear() {
		//$permalink = at_gear_get_permalink_structure();
		$labels = [
			'name'               => 'Снаряжение',
			'singular_name'      => 'Снаряжение', 
			'menu_name'          => 'Снаряжение',
			'name_admin_bar'     => 'Снаряжение', 
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New Gear',
			'new_item'           => 'New Gear',
			'edit_item'          => 'Edit Gear',
			'view_item'          => 'View Gear',
			'all_items'          => 'All Gear',
			'search_items'       => 'Search Gear',
			'parent_item_colon'  => 'Parent Gear:',
			'not_found'          => 'No Gear found.',
			'not_found_in_trash' => 'No Gear found in Trash.',
		];

		$args = [
			'labels'             => $labels,
			'description'        => 'Description.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'gear', 'with_front' => false  ],
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [ 'title', 'excerpt', 'thumbnail' ],
			'menu_icon'          => 'dashicons-editor-ol',
		];
		
		register_post_type( AT_GEAR_POST_TYPE, $args );
	}

}