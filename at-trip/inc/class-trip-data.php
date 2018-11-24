<?php 
//declare(strict_types=1);
namespace AT_Trip;

final class TripData extends \AT_Lib\DataHelperBase {
	protected $fields = [];
	public $date_format_mask;
	
	public function __construct( $post = null ) {
		parent::__construct($post);

		$this->date_format_mask = \AT_Lib\getDateFormatMask();
		
		$id = $this->post->ID;
		
		
		$this->fields[] = new RelatedTripsField($id);
		$this->fields[] = new DateRangeField($id, $this->date_format_mask );
		$this->fields[] = new PriceListField($id);
		$this->fields[] = new OutlineDaysField($id);
	}
	
	public function getRelatedTripsField() : Field { return $this->fields[0]; }
	public function getDateRangeField()    : Field { return $this->fields[1]; }
	public function getPriceListField()    : Field { return $this->fields[2]; }
	public function getOutlineDaysField()  : Field { return $this->fields[3]; }
		
	public function get_sticky()              : int   { return parent::getInt( 'trip_sticky' ); }

	public function get_show_dates()          : int    { return parent::getInt( 'trip_show_dates' ); }
	
	public function get_duration_days()       : int    { return parent::getInt( 'trip_duration_days' ); }
	public function get_duration_nights()     : int    { return parent::getInt( 'trip_duration_nights' ); }
	
	public function get_price()               : int    { return parent::getInt( 'trip_price' ); }
	public function get_currency()            : string { return parent::getText_( 'trip_currency' ); }
	
	public function get_enable_sale()         : int    { return parent::getInt( 'trip_enable_sale' ); }
	public function get_sale_price()          : int    { return parent::getInt( 'trip_sale_price' ); }
	public function get_show_price_list()     : int    { return parent::getInt( 'trip_show_price_list' ); }

	public function get_highest_point()		  : int    { return parent::getInt( 'trip_highest_point' ); }
	public function get_technical_difficulty(): int    { return parent::getInt( 'trip_technical_difficulty' ); }
	public function get_fitness_level()		  : int    { return parent::getInt( 'trip_fitness_level' ); }
	public function get_group_size()		  : int    { return parent::getInt( 'trip_group_size' ); }

	public function get_description() : string {
		return ( isset( $this->post->post_content ) && '' !== $this->post->post_content ) ? $this->post->post_content : '';
	}

	public function get_outline_title()     : string { return parent::getRaw( 'trip_outline_title' ); }
	public function get_outline()	        : string { return parent::getRaw( 'trip_outline' ); }
	
	public function get_include()           : string { return parent::getRaw( 'trip_include' ); }
	public function get_exclude()           : string { return parent::getRaw( 'trip_exclude' ); }

	public function get_price_details()	    : string { return parent::getRaw( 'trip_price_details' ); }
	public function get_equipment()		    : string { return parent::getRaw( 'trip_equipment' ); }
	public function get_additional_info()   : string { return parent::getRaw( 'trip_additional_info' ); }
	public function get_gallery()		    : string { return parent::getRaw( 'trip_gallery' ); }

	public function get_registration_enabled()  : int    { return parent::getInt( 'trip_registration_enabled' ); }
	public function get_registration_end_date() : string { return parent::getDate_( 'trip_registration_end_date' ); }
	public function get_registration_form()     : string { return parent::getUrl( 'trip_registration_form' ); }
	

	public function get_trip_type_list( string $before = '', string $sep = ', ', string $after = '' )  : string {
		return parent::getTaxonomy( 'trip_type', $before, $sep, $after );
	}

	public function get_activity_list( string $before = '', string $sep = ', ', string $after = '' ) : string {
		return parent::getTaxonomy( 'activity', $before, $sep, $after );
	}
	
	public function get_destination_list( string $before = '', string $sep = ', ', string $after = '' ) : string {
		return parent::getTaxonomy( 'destination', $before, $sep, $after );
	}	
	
	public function get_keyword_list( string $before = '', string $sep = ', ', string $after = '' ) : string {
		return parent::getTaxonomy( 'trip_keyword', $before, $sep, $after );
	}
}