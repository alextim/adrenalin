<?php
declare(strict_types=1);
namespace AT_Gear;

final class Taxonomies {

	public static function init() {
		self::register_recommended_use_taxonomy();
		self::register_gear_type_taxonomy();
	}

	private static function register_recommended_use_taxonomy() {
		$labels = [
			'name'              => 'Рекомендуемое использование',
			'singular_name'     => 'Рекомендуемое использование',
			'search_items'      => __( 'Search Recommended use', 'at-gear' ),
			'all_items'         => __( 'All Recommended use', 'at-gear' ),
			'parent_item'       => __( 'Parent Recommended use', 'at-gear' ),
			'parent_item_colon' => __( 'Parent Recommended use:', 'at-gear' ),
			'edit_item'         => __( 'Edit Recommended use', 'at-gear' ),
			'update_item'       => __( 'Update Recommended use', 'at-gear' ),
			'add_new_item'      => __( 'Add New Recommended use', 'at-gear' ),
			'new_item_name'     => __( 'New Recommended use', 'at-gear' ),
			'menu_name'         => __( 'Recommended use', 'at-gear' ),
		];

		$args = [
			'public'			=> true,
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'rewrite'           => ['slug' => 'recommended-use'], //'with_front' => false, 'ep_mask' => EP_CATEGORIES ],
		];

		register_taxonomy( 'recommended_use', [ AT_GEAR_POST_TYPE ], $args );
	}	
	
		
	private static function register_gear_type_taxonomy() {
		$labels = [
			'name'              => 'Типы снаряжения',
			'singular_name'     => 'Тип снаряжения',
			'search_items'      => __( 'Search Gear Types', 'at-gear' ),
			'all_items'         => __( 'All Gear Types', 'at-gear' ),
			'parent_item'       => __( 'Parent Gear Type', 'at-gear' ),
			'parent_item_colon' => __( 'Parent Gear Type:', 'at-gear' ),
			'edit_item'         => __( 'Edit Gear Type', 'at-gear' ),
			'update_item'       => __( 'Update Gear Type', 'at-gear' ),
			'add_new_item'      => __( 'Add New Gear Type', 'at-gear' ),
			'new_item_name'     => __( 'New Tour Gear Name', 'at-gear' ),
			'menu_name'         => __( 'Gear Types', 'at-gear' ),
		];

		$args = [
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'gear-type' ],
		];

		register_taxonomy( 'gear_type', [ AT_GEAR_POST_TYPE ], $args );
	}	
}