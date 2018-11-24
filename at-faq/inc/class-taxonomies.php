<?php
declare(strict_types=1);

final class At_FAQ_Taxonomies {

	public static function init() {
		self::register_faq_types();
	}

	private static function register_faq_types() {
		$labels = [
			'name'          => 'FAQ Category',
			'add_new_item'  => 'Add New FAQ Category',
			'new_item_name' => 'New FAQ Category'
		];
          		
		$args = [
			'public'			=> true,
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => false,
			'query_var'         => true,
			'show_in_nav_menus' => true,
			'rewrite'			=> [ 'slug' => 'faq-category' ], //, 'with_front' => false, 'hierarchical' => true ),
		];

		register_taxonomy( 'faq_category', [AT_FAQ_POST_TYPE], $args );
	}
}