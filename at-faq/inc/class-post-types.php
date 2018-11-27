<?php
declare(strict_types=1);
namespace AT_Faq;

final class PostTypes {
	public static function init() {
		self::register_faq();
	}
	
	private static function register_faq() {
		$labels = [
			'name'                => 'FAQs',
			'singular_name'       => 'FAQ',
			'menu_name'           => 'FAQs',
			'name_admin_bar'      => 'FAQs', 			
			'add_new'             => 'Add New',
			'add_new_item'        => 'Add New FAQ',
			'edit'                => 'Edit',
			'edit_item'           => 'Edit FAQ',
			'new_item'            => 'New FAQ',
			'view'                => 'View',
			'view_item'           => 'View FAQ',
			'search_items'        => 'Search FAQs',
			'not_found'           => 'No FAQs found',
			'not_found_in_trash'  => 'No FAQs found in Trash',
			'parent'              => 'Parent FAQ',
			'exclude_from_search' => true,
		];
	
	
		$args = [
			'labels'             => $labels,
	        //'description'        => __( 'Description.', 'wp-travel-engine' ),
			'public'             => true,
			'menu_icon' 		 => 'dashicons-lightbulb',
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite' 			 => [ 'slug' => 'faqs', 'with_front' => false ],
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null, // 14
			'supports'			 => [ 'title', 'editor', /*'thumbnail',*/  ],	
			//'taxonomies'         => [ '' ],
		];
		
		register_post_type( AT_FAQ_POST_TYPE, $args );
	}
}