<?php
declare(strict_types=1);
namespace AT_Faq;


// ADMIN COLUMN - HEADERS
add_filter( 'manage_edit-' . AT_FAQ_POST_TYPE . '_columns', function ( $columns ) {
	unset( $columns['date'] );
	$columns['faq_sort_order'] = 'Sort Order';	
	return $columns;
} );


// ADMIN COLUMN - CONTENT
add_action( 'manage_' . AT_FAQ_POST_TYPE . '_posts_custom_column', function ( $column_name, $id ) {
	switch ( $column_name ) {
		
		case 'faq_sort_order':
			$data = new FaqData( get_post($id) );
			echo $data->get_sort_order();
			break;

		default:
			break;
	}
}, 10, 2 );



// ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
// https://gist.github.com/906872
add_filter( 'manage_edit-' . AT_FAQ_POST_TYPE . '_sortable_columns', function ( $columns ) {
	$custom = [
		'faq_sort_order' 	=> 'faq_sort_order',
	];
	return wp_parse_args( $custom, $columns );
	//return $columns;
} );