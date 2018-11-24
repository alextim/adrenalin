<?php
namespace AT_Trip;


/**
 * Admin Helper
 *
 * @package inc/admin/
 */


// ADMIN COLUMN - HEADERS
add_filter( 'manage_edit-' . AT_TRIP_POST_TYPE . '_columns', function ( $columns ) {
	unset( $columns['date'] );
	$columns['sticky'] = 'Sticky';
	$columns['price'] = 'Price';
	$columns['days'] = 'Days/Nights';
	return $columns;
} );


// ADMIN COLUMN - CONTENT
add_action( 'manage_' . AT_TRIP_POST_TYPE . '_posts_custom_column', 
	function ( $column_name, $id ) {
		$trip = new TripData( get_post( $id ) );
		
		switch ( $column_name ) {
			
			case 'sticky':
				if (1 === $trip->get_sticky()) {
					//$icon_class = ( 1 === $sticky ) ? 'dashicons-star-filled' : 'dashicons-star-empty';
					$icon_class = 'dashicons-star-filled';
					printf( '<span class="dashicons %s"></span>', $icon_class );
				}
				break;

			case 'price':
				$price = $trip->get_price();
				if ($price > 0) {
					$currency = $trip->get_currency();
					if (isset($currency)) {
						$currency = \AT_Lib\getCurrencySymbol($currency);
					}
					echo $price . '&nbsp;' . $currency;
				}
				break;

			case 'days':
				$days   = $trip->get_duration_days();
				$nights = $trip->get_duration_nights();
				if ( $days > 0 || $nights > 0 ) {
					printf( '%d / %d', $days, $nights );
				}
				break;
				
			default:
				break;
		}
	}, 10, 2 );


// ADMIN COLUMN - SORTING - MAKE HEADERS SORTABLE
// https://gist.github.com/906872
add_filter( 'manage_edit-' . AT_TRIP_POST_TYPE . '_sortable_columns', function ( $columns ) {
	$custom = [
		'sticky' 	=> 'trip_sticky',
		'price' 	=> 'trip_price',
	];
	return wp_parse_args( $custom, $columns );
	return $columns;
} );
