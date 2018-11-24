<?php 
declare(strict_types=1);
namespace AT_Trip;
/**
 * TripView class
 *
 * @package AT Trip
 */
final class TripView {
	private $tripData;
	
	function __construct( $post = null ) {
		$this->tripData = new TripData( $post );

		return $this->tripData->post;
	}

	
	function format_days(int $days) : string {
		return ( $days > 0 ) ? \AT_lib\num2form($days, 'день', 'дня', 'дней') : '';
	}
	
	function format_nights(int $nights): string  {
		return ( $nights > 0 ) ? \AT_lib\num2form($nights, 'ночь', 'ночи', 'ночей') : '';
	}
	
	
	private function print_all_prices_html( $title, $price, $sale_price, $currency ){
		$s = '<div ="trip-info-item">';
		$s .= '<span class="trip-info-title">' . $title . '</span>';
		
		$s .= '<span class="trip-info-value old-price">' . $price;
		$s .= '&nbsp;' . $currency;
		$s .= '</span>';

		$s .= '<span class="trip-info-value sale-price">' . $sale_price;
		$s .= '&nbsp;' . $currency;
		$s .= '</span>';
		
		$s .= '</div>';
		echo $s;
	}
	
	

	
	private function print_level_html( $title, $value ) {
		if ( $value > 0 ) {
			$s = '<div class="trip-info-item">';
			$s .= '<span class="trip-info-title">' . $title . '</span>';
			
			$max_value = 4;
			
			$s .= '<span class="trip-info-value">';
			for( $i = 1; $i <= $max_value; $i++ ) {
				$s .= $i <= $value ? '★' : '☆';
			}
			$s .= '</span>';
			
			$s .= '</div>';
			echo $s;
		}
	}
	
	
	function print_dates_duration() {
		$this->print_duration();
		$this->print_dates_info();
	}
	
	
	function print_all_info() {
		$this->print_duration();
		$this->print_dates_info();
		$this->print_other_info();
		$this->print_price();
	}
	
	
	function print_dates_info() {
		if( $this->tripData->get_show_dates() ) {
			$this->print_date_range();
		}		
	}
	
	
	function print_date_range() {
		$f = $this->tripData->getDateRangeField();
		
		$f->durationDays = $this->tripData->get_duration_days();
		
		$f->renderDisplay();
	}	
	
	
/*	
	function print_date_range() {
		$result = '';
		
		
		$trip_date_range = $this->tripData->get_date_range(); 
		if ( $trip_date_range ) {
			$result_start = '<table>';
			$result_end   = '</table>';
			$s_start = '<tr><td>';
			$s_end   = '</td></tr>';
			$sep = '';

			$duration_days = $this->tripData->get_duration_days();
			
			foreach ( $trip_date_range as $field ) {
				$start_date = $field['startdate'];
				if ( !empty($start_date) ) {
					$s = $this->format_date_duration_s($start_date, $duration_days);
					if ( !empty($s) ) {
						if ( empty($result) ) {
							$result = $result_start;
						} else {
							$result .= $sep;
						}
						$result .= $s_start . $s . $s_end;
					}
				}
			}
			$result .= $result_end;
		} 

		print_info_item( get_fa('calendar'), $result );		
	}
*/	

	function print_registration_form(string $title, string $id = '', string $class = '') {
		if ( !$this->tripData->get_registration_enabled() ) {
			return;
		}
		
		$end_date = $this->tripData->get_registration_end_date();
		if ( !empty($end_date) ) {
			if (strtotime($end_date) < time() ) {
				return;
			}
		}

		$s = $this->tripData->get_registration_form();
		if ( !empty($s) ) {
			if (!empty($id)) {
				$id = ' id="' . esc_attr($id) . '" ';
			}			
			if (!empty($class)) {
				$class = ' class="' . esc_attr($class) . '" ';
			}
			echo '<a ' . $id .  $class . ' target="_blank" rel="noopener nofollow" href="' . $s . '">' . esc_html($title) . '</a>';
		}
	}
	
	
	function print_duration() {
		$s = $this->format_days( $this->tripData->get_duration_days());
		
		$nights = $this->tripData->get_duration_nights();
		if ( $nights > 0 ) {
			if ( !empty( $s ) ) {
				$s .= ' / ';
			}
			$s .= $this->format_nights( $nights );
		}
		
		print_info_item( get_fa('clock-o'), $s );
	}	
	
	
	function print_other_info() {
		$highest_point = $this->tripData->get_highest_point();
		if ( $highest_point > 0 ) {
			print_info_item( get_fa('area-chart'), (string)$highest_point, '', 'м' );
		}
		
		$this->print_level_html( 'Сложность', $this->tripData->get_technical_difficulty() );
		$this->print_level_html( 'Фитнес', $this->tripData->get_fitness_level() );
		
		$group_size = $this->tripData->get_group_size();
		if ( $group_size > 0 ) {
			print_info_item( get_fa('group'), (string)$group_size, 'до', 'чел' );
		}
		$this->print_all_categories();
	}
	
	
	function print_all_categories() {
		$list = $this->tripData->get_trip_type_list();
		if ( !empty($list) ) {
			print_info_item( 'Тип', $list );
		}
		
		$list = $this->tripData->get_activity_list();
		if ( !empty($list) ) {
			print_info_item( 'Активность', $list );
		}
		
		$list = $this->tripData->get_destination_list();
		if ( !empty($list) ) {
			print_info_item( 'Направление', $list );
		}
		
		$list = $this->tripData->get_keyword_list();
		if ( !empty($list) ) {
			print_info_item( 'Тэг', $list );
		}
	}


	function print_price() {
		$price = $this->tripData->get_price();
		$currency = $this->tripData->get_currency();
		$enable_sale = $this->tripData->get_enable_sale();
	
		if (isset($currency)) {
			$currency = \AT_Lib\getCurrencySymbol($currency);
		}
		
		if ( $enable_sale ) {
			$sale_price = $this->tripData->get_sale_price();
			if ( $sale_price > 0 ) {
				
				if ($price > 0) {
					$this->print_all_prices_html( get_fa('money'), $price, $sale_price, $currency );
					return;
				}
				
				$price = $sale_price;
			} 
		} 
		
		if ($price > 0) {
			print_info_item( get_fa('money'), (string)$price, '', $currency );
		}
	}
	
	
	function get_tabs() {
		$description    = $this->tripData->get_description();
		
		$outline_title  = $this->tripData->get_outline_title();
		$outline        = $this->tripData->get_outline();
		
		$trip_include 	= $this->tripData->get_include();
		$trip_exclude 	= $this->tripData->get_exclude();
		
		$price_details  = $this->tripData->get_price_details();
		
		$show_price_list = $this->tripData->get_show_price_list();

		$price_list = '';
		if ($show_price_list) {
			$f = $this->tripData->getPriceListField();
			$f->currency      = $this->tripData->get_currency();
			$f->enable_sale  = $this->tripData->get_enable_sale();
			$price_list = $f->getHtml();
		}		
		
		$equipment      = $this->tripData->get_equipment();
		$additional_info= $this->tripData->get_additional_info();
		$gallery        = $this->tripData->get_gallery();
		
		$tabs = [];
		
		$this->tripData->getRelatedTripsField()->renderDisplay();

		
		if ( !empty($description) ) {
			$tabs[] = new Tab( 'tabone', 'Описание', $description );
		}

		if ( !empty($outline_title) || !empty($outline) || !empty($outline_days) ) {
			$tabs[] = new OutlineTab( 'tabtwo', 'Программа по дням', $outline_title, $outline, $this->tripData->getOutlineDaysField()->getHtml() );
		}

		if ( !empty($trip_include) || !empty($trip_exclude) ) {
			$tabs[] = new ServiceTab( 'tabthree', 'Услуги', $trip_include, $trip_exclude );
		}
		
		if ( !empty($price_details) || !empty($price_list) ) {
			$tabs[] = new Tab( 'tabfour', 'Стоимость', $price_list . $price_details );
		}

		if ( !empty($equipment) ) {			
			$tabs[] = new Tab( 'tabfive', 'Снаряжение', $equipment );
		}

		if ( !empty($additional_info) ) {			
			$tabs[] = new Tab( 'tabsix', 'Доп.информация', $additional_info );
		}

		if ( !empty($gallery) ) {
			$tabs[] = new Tab( 'tabseven', 'Галерея', $gallery );
		}	
		
		return  $tabs;
	}
}

function get_fa(string $icon) : string {
	return '<i class="fa fa-' . $icon . '"></i>';
}


function print_info_item( string $title, string $value, string $prefix = '', string $suffix = '' ) {
	if ( !empty ($value) ) {
		$s = '<div class="trip-info-item">';
		$s .= '<span class="trip-info-title">' . $title . '</span>';
		
		$s .= '<span class="trip-info-value">';
		if ( !empty ($prefix) ) {
			$s .=  $prefix . '&nbsp;';
		}			
		$s .= $value;
		if ( !empty ($suffix) ) {
			$s .= '&nbsp;' . $suffix;
		}
		$s .= '</span>';
		
		$s .= '</div>';
		echo $s;
	}
}