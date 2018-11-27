<?php
declare(strict_types=1);

final class At_Person_Taxonomies {

	public static function init() {
		self::register_person_types();
	}

	private static function register_person_types() {
		$labels = [
			'name'          => 'Статус',
			'add_new_item'  => 'Add New Person Type',
			'new_item_name' => 'New Person Type'
		];
          		
		$args = [
			'public'			=> true,
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'rewrite'			=> [ 'slug' => 'person-type' ], //, 'with_front' => false, 'hierarchical' => true ),
		];

		register_taxonomy( 'person_type', [AT_PERSON_POST_TYPE], $args );
	}
}