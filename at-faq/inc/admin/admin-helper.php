<?php
declare(strict_types=1);
/**
 * Admin Helper
 *
 * @package at-faq\inc\admin
 */


// ADMIN COLUMN - HEADERS
add_filter( 'manage_edit-' . AT_FAQ_POST_TYPE . '_columns', function ( $columns ) {
	unset( $columns['date'] );
	$columns['sort_order'] = 'Sort Order';	
	return $columns;
} );


// ADMIN COLUMN - CONTENT
add_action( 'manage_' . AT_FAQ_POST_TYPE . '_posts_custom_column', function ( $column_name, $id ) {
	$faq = new AT_Person_Person( get_post( $id ) );
	
	switch ( $column_name ) {
		
		case 'sort_order':
			echo $faq->get_sort_order();
			break;

		default:
			break;
	}
}, 10, 2 );



// ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
// https://gist.github.com/906872
add_filter( 'manage_edit-' . AT_FAQ_POST_TYPE . '_sortable_columns', function ( $columns ) {
	$custom = [
		'sort_order' 	=> 'faq_sort_order',
	];
	return wp_parse_args( $custom, $columns );
	//return $columns;
} );